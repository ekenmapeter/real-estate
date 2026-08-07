<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PropertyInquiryMail;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyDocument;
use App\Models\PropertyInquiry;
use App\Models\PropertyReport;
use App\Models\SavedProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query()->where('status', 'published');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($listing_type = $request->input('listing_type')) {
            if ($listing_type !== 'all') {
                $query->where('listing_type', $listing_type);
            }
        }

        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', 'like', "%{$category}%");
            }
        }

        if ($country = $request->input('country')) {
            $query->where('country', $country);
        }

        if ($city = $request->input('city')) {
            $query->where('city', 'like', "%{$city}%");
        }

        if ($min_price = $request->input('min_price')) {
            $query->where('price', '>=', (float) $min_price);
        }

        if ($max_price = $request->input('max_price')) {
            $query->where('price', '<=', (float) $max_price);
        }

        if ($bedrooms = $request->input('bedrooms')) {
            $query->where('bedrooms', '>=', (int) $bedrooms);
        }

        if ($bathrooms = $request->input('bathrooms')) {
            $query->where('bathrooms', '>=', (int) $bathrooms);
        }

        if ($min_size = $request->input('min_size')) {
            $query->where('property_size', '>=', (float) $min_size);
        }

        if ($amenity = $request->input('amenities')) {
            foreach ((array) $amenity as $tag) {
                $query->where('amenities_json', 'like', "%{$tag}%");
            }
        }

        if ($request->boolean('verified_only')) {
            $query->where('is_verified', true);
        }

        $properties = $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        $savedPropertyIds = Auth::check()
            ? SavedProperty::where('user_id', Auth::id())->pluck('property_id')->all()
            : [];

        $categories = PropertyCategory::where('is_active', true)->orderBy('sort_order')->get();
        $countries = Property::where('status', 'published')->whereNotNull('country')->distinct()->orderBy('country')->pluck('country');

        return view('properties.index', compact('properties', 'savedPropertyIds', 'categories', 'countries'));
    }

    public function show(Property $property)
    {
        if (! $property->isPublished() && ! (Auth::check() && (Auth::id() === $property->user_id || Auth::user()->isAdmin()))) {
            abort(404);
        }

        if ($property->isPublished()) {
            $property->increment('views_count');
        }

        $isSaved = Auth::check() && SavedProperty::where('user_id', Auth::id())->where('property_id', $property->id)->exists();
        $galleryImages = $property->galleryUrls();
        if (empty($galleryImages)) {
            $galleryImages = [asset('images/property-placeholder.jpg')];
        }

        $related = Property::where('status', 'published')
            ->where('id', '!=', $property->id)
            ->when($property->category, fn ($q) => $q->where('category', $property->category))
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'isSaved', 'galleryImages', 'related'));
    }

    public function toggleSave(Property $property)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $existing = SavedProperty::where('user_id', $user->id)->where('property_id', $property->id)->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            SavedProperty::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
            $saved = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['saved' => $saved]);
        }

        return redirect()->back()->with('success', $saved
            ? 'Property "' . $property->title . '" saved to your list!'
            : 'Property removed from your saved list.');
    }

    public function create()
    {
        $categories = PropertyCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('properties.list', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateListing($request);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $listing = $data['listing'];
        $listing['user_id'] = $user->id;
        $listing['status'] = 'submitted';
        $listing['listed_at'] = now();
        $listing['representative_role'] = $request->input('representative_role') ?: $user->rep_type;
        $listing['representative_verified'] = $user->isRepresentativeVerified();
        $listing['amenities_json'] = $data['amenities'];

        if ($request->hasFile('cover_image')) {
            $listing['image_url'] = $request->file('cover_image')->store('uploads/galleries/properties', 'public');
        }

        $property = Property::create($listing);
        $property->listing_number = 'AVP-' . str_pad((string) $property->id, 5, '0', STR_PAD_LEFT);
        $property->appendLog('Listing submitted by ' . $user->name . ', awaiting admin review.', $user->name);
        $property->save();

        $this->syncUserGallery($property, $request);
        $this->storeDocuments($property, $request);

        return redirect()->route('properties.mine')->with('success', 'Your listing has been submitted and is now awaiting admin review. Your reference is ' . $property->ref() . '.');
    }

    public function edit(Property $property)
    {
        $this->authorizeListing($property);

        $categories = PropertyCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('properties.edit', compact('property', 'categories'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeListing($property);

        $data = $this->validateListing($request, $property);

        $listing = $data['listing'];
        $listing['amenities_json'] = $data['amenities'];

        if ($request->hasFile('cover_image')) {
            if ($property->image_url && ! Str::startsWith($property->image_url, ['http://', 'https://'])) {
                Storage::disk('public')->delete($property->image_url);
            }
            $listing['image_url'] = $request->file('cover_image')->store('uploads/galleries/properties', 'public');
        }

        $property->appendLog('Listing updated by ' . (Auth::user()->name ?? 'User') . '.');
        if ($property->status === 'rejected' || $property->status === 'more_info_required') {
            $property->status = 'submitted';
            $property->appendLog('Listing resubmitted after revision, awaiting admin review again.');
        }

        $property->fill($listing);
        $property->save();

        $this->syncUserGallery($property, $request, true);
        $this->storeDocuments($property, $request);

        return redirect()->route('properties.mine')->with('success', 'Listing "' . $property->title . '" updated.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeListing($property);

        foreach ($property->documents as $doc) {
            if (! Str::startsWith($doc->file_path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($doc->file_path);
            }
        }

        $property->delete();

        return redirect()->route('properties.mine')->with('success', 'Listing deleted.');
    }

    public function togglePause(Property $property)
    {
        $this->authorizeListing($property);

        $property->appendLog(($property->status === 'published' ? 'Listing paused by' : 'Listing republished, awaiting admin review by') . ' ' . (Auth::user()->name ?? 'User') . '.');
        $property->status = $property->status === 'published' ? 'draft' : 'submitted';
        $property->save();

        return redirect()->back()->with('success', $property->status === 'draft' ? 'Listing paused.' : 'Listing resubmitted for review.');
    }

    public function markSold(Property $property)
    {
        $this->authorizeListing($property);
        $property->status = 'sold';
        $property->appendLog('Listing marked as sold by ' . (Auth::user()->name ?? 'User') . '.');
        $property->save();

        return redirect()->back()->with('success', 'Listing marked as sold.');
    }

    public function markRented(Property $property)
    {
        $this->authorizeListing($property);
        $property->status = 'rented';
        $property->appendLog('Listing marked as rented by ' . (Auth::user()->name ?? 'User') . '.');
        $property->save();

        return redirect()->back()->with('success', 'Listing marked as rented.');
    }

    public function storeInquiry(Request $request, Property $property, string $type)
    {
        if (! in_array($type, ['purchase', 'rental', 'viewing', 'general'], true)) {
            abort(404);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'date_format:H:i'],
            'viewing_type' => ['nullable', 'in:physical,virtual'],
            'attendees' => ['nullable', 'integer', 'min:1', 'max:20'],
            'message' => ['nullable', 'string', 'max:4000'],
            'preferred_channel' => ['nullable', 'in:whatsapp,telegram'],
        ]);

        $inquiry = PropertyInquiry::create([
            'property_id' => $property->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'viewing_type' => $validated['viewing_type'] ?? null,
            'attendees' => $validated['attendees'] ?? 1,
            'message' => $validated['message'] ?? null,
            'preferred_channel' => $validated['preferred_channel'] ?? 'whatsapp',
            'status' => 'awaiting_admin_review',
        ]);

        $inquiry->appendLog($inquiry->typeLabel() . ' submitted by ' . $inquiry->full_name . ', awaiting admin review.');

        if ($property->status === 'published') {
            $property->increment('views_count');
        }

        $inquiry->save();

        try {
            Mail::to($inquiry->email)->send(new PropertyInquiryMail($inquiry));
        } catch (\Throwable $e) {
            // Email delivery must never break the inquiry submission.
        }

        if ($type === 'viewing') {
            return redirect()->route('properties.viewing.confirmation', $inquiry)->with('success', 'Viewing request submitted.');
        }

        return redirect()->route('properties.show', $property)->with('success', 'Your ' . strtolower($inquiry->typeLabel()) . ' has been submitted. Reference: ' . $inquiry->inquiry_number . '. Our Property Support team will contact you shortly.');
    }

    public function viewingConfirmation(PropertyInquiry $inquiry)
    {
        return view('properties.viewing-confirmation', compact('inquiry'));
    }

    public function myListings()
    {
        $user = Auth::user();
        $listings = Property::where('user_id', $user->id)
            ->withCount(['inquiries'])
            ->orderBy('id', 'desc')
            ->get();

        return view('properties.mine', compact('listings'));
    }

    public function myInquiries()
    {
        $user = Auth::user();

        $inquiries = PropertyInquiry::where('user_id', $user->id)
            ->with('property')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('properties.inquiries', compact('inquiries'));
    }

    public function viewingRequests()
    {
        $user = Auth::user();

        $inquiries = PropertyInquiry::where('user_id', $user->id)
            ->where('type', 'viewing')
            ->with('property')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('properties.viewing-requests', compact('inquiries'));
    }

    public function savedProperties()
    {
        $user = Auth::user();

        $properties = Property::whereHas('savedBy', fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('properties.saved', compact('properties'));
    }

    public function report(Request $request, Property $property)
    {
        $validated = $request->validate([
            'report_type' => ['required', 'in:listing,fraud'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        PropertyReport::create([
            'property_id' => $property->id,
            'reporter_id' => Auth::id(),
            'report_type' => $validated['report_type'],
            'reason' => $validated['reason'],
        ]);

        return redirect()->back()->with('success', 'Thank you. Your report has been submitted for review.');
    }

    protected function validateListing(Request $request, ?Property $property = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'listing_type' => ['required', 'in:sale,rent'],
            'category' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:8000'],
            'country' => ['required', 'string', 'max:90'],
            'state' => ['nullable', 'string', 'max:90'],
            'city' => ['required', 'string', 'max:90'],
            'address' => ['nullable', 'string', 'max:190'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'property_size' => ['nullable', 'numeric', 'min:0'],
            'land_size' => ['nullable', 'numeric', 'min:0'],
            'parking' => ['nullable', 'string', 'max:60'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string', 'max:60'],
            'ownership_type' => ['nullable', 'string', 'max:40'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'representative_role' => ['nullable', 'in:owner,agent,developer,property_manager'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'verification_documents' => ['nullable', 'array'],
            'verification_documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
            'floor_plan' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $listing = $validated;
        unset($listing['amenities'], $listing['cover_image'], $listing['gallery'], $listing['verification_documents'], $listing['floor_plan']);

        $listing['location'] = implode(', ', array_filter([$listing['city'] ?? null, $listing['country'] ?? null]));
        $listing['location'] = $listing['location'] !== '' ? $listing['location'] : $listing['title'];

        if ($listing['listing_type'] === 'sale') {
            $listing['price'] = $request->validate(['price' => ['required', 'numeric', 'min:1']])['price'];
            $listing['monthly_rent'] = null;
            $listing['security_deposit'] = null;
        } else {
            $rent = $request->validate([
                'monthly_rent' => ['required', 'numeric', 'min:1'],
                'security_deposit' => ['nullable', 'numeric', 'min:0'],
            ]);
            $listing['monthly_rent'] = $rent['monthly_rent'];
            $listing['security_deposit'] = $rent['security_deposit'] ?? null;
            $listing['price'] = null;
        }

        return [
            'listing' => $listing,
            'amenities' => $validated['amenities'] ?? [],
        ];
    }

    protected function syncUserGallery(Property $property, Request $request, bool $append = false): void
    {
        if (! $append) {
            foreach ($property->images as $img) {
                if (! Str::startsWith($img->image_path, ['http://', 'https://'])) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
        }

        $order = $property->images->count();
        foreach ($request->file('gallery', []) as $file) {
            $property->images()->create([
                'image_path' => $file->store('uploads/galleries/properties', 'public'),
                'sort_order' => $order++,
            ]);
        }
    }

    protected function storeDocuments(Property $property, Request $request): void
    {
        foreach ($request->file('verification_documents', []) as $file) {
            $property->documents()->create([
                'title' => 'Verification Document',
                'document_type' => 'verification',
                'file_path' => $file->store('uploads/property-documents', 'public'),
                'is_restricted' => true,
            ]);
        }

        if ($request->hasFile('floor_plan')) {
            $property->documents()->create([
                'title' => 'Floor Plan',
                'document_type' => 'floor_plan',
                'file_path' => $request->file('floor_plan')->store('uploads/property-documents', 'public'),
                'is_restricted' => false,
            ]);
        }
    }

    protected function authorizeListing(Property $property): void
    {
        $user = Auth::user();
        if (! $user || ($property->user_id !== $user->id && ! $user->isAdmin())) {
            abort(403);
        }
    }
}
