<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PropertyListingStatusMail;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyConversation;
use App\Models\PropertyDocument;
use App\Models\PropertyInquiry;
use App\Models\PropertyReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');

        $query = Property::query();

        switch ($tab) {
            case 'pending':
                $query->whereIn('status', ['submitted', 'under_review', 'more_info_required']);
                break;
            case 'approved':
                $query->whereIn('status', ['approved', 'published']);
                break;
            case 'rejected':
                $query->where('status', 'rejected');
                break;
            case 'suspended':
                $query->where('status', 'suspended');
                break;
            default:
                $tab = 'all';
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('listing_number', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $properties = $query->with('user')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $counts = [
            'all' => Property::count(),
            'pending' => Property::whereIn('status', ['submitted', 'under_review', 'more_info_required'])->count(),
            'approved' => Property::whereIn('status', ['approved', 'published'])->count(),
            'rejected' => Property::where('status', 'rejected')->count(),
            'suspended' => Property::where('status', 'suspended')->count(),
        ];

        return view('admin.properties.index', compact('properties', 'tab', 'counts'));
    }

    public function review(Property $property)
    {
        $property->load('images', 'documents', 'user', 'inquiries');

        return view('admin.properties.review', compact('property'));
    }

    public function approve(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $property->status = 'published';
        $property->is_verified = true;
        $property->representative_verified = $property->representative_verified || $property->representative_role !== 'owner';
        $property->appendLog('Listing approved and published by admin.', Auth::user()->name);
        $property->save();

        $this->notifyOwner($property, 'Congratulations! Your listing "' . $property->title . '" has been approved and is now live in the Properties Marketplace.');

        return redirect()->route('admin.properties.review', $property)->with('success', 'Listing "' . $property->title . '" approved and published.');
    }

    public function reject(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $validated = $request->validate(['reason' => ['required', 'string', 'max:2000']]);
        $property->admin_note = $validated['reason'];
        $property->status = 'rejected';
        $property->appendLog('Listing rejected by admin: ' . $validated['reason'], Auth::user()->name);
        $property->save();

        $this->notifyOwner($property, 'Unfortunately your listing "' . $property->title . '" was rejected. Reason: ' . $validated['reason']);

        return redirect()->route('admin.properties.review', $property)->with('success', 'Listing rejected.');
    }

    public function requestInfo(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $validated = $request->validate(['reason' => ['required', 'string', 'max:2000']]);
        $property->admin_note = $validated['reason'];
        $property->status = 'more_info_required';
        $property->appendLog('Admin requested more information: ' . $validated['reason'], Auth::user()->name);
        $property->save();

        $this->notifyOwner($property, 'More information is required for your listing "' . $property->title . '". ' . $validated['reason']);

        return redirect()->route('admin.properties.review', $property)->with('success', 'More information requested from the listing owner.');
    }

    public function suspend(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $property->status = 'suspended';
        $property->appendLog('Listing suspended by admin.', Auth::user()->name);
        $property->save();

        return redirect()->back()->with('success', 'Listing suspended.');
    }

    public function restore(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $property->status = 'published';
        $property->appendLog('Listing restored to published by admin.', Auth::user()->name);
        $property->save();

        return redirect()->back()->with('success', 'Listing restored.');
    }

    public function toggleFeatured(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $property->is_featured = ! $property->is_featured;
        $property->appendLog(($property->is_featured ? 'Listing featured' : 'Listing unfeatured') . ' by admin.', Auth::user()->name);
        $property->save();

        return redirect()->back()->with('success', $property->is_featured ? 'Listing featured.' : 'Listing unfeatured.');
    }

    public function destroy(Request $request, Property $property)
    {
        $this->ensureAdmin();

        foreach ($property->documents as $doc) {
            if (! Str::startsWith($doc->file_path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($doc->file_path);
            }
        }

        $property->delete();

        return redirect()->route('admin.properties.index', ['tab' => 'all'])->with('success', 'Listing removed.');
    }

    public function update(Request $request, Property $property)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'category' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:8000'],
            'country' => ['nullable', 'string', 'max:90'],
            'city' => ['nullable', 'string', 'max:90'],
            'address' => ['nullable', 'string', 'max:190'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'monthly_rent' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'property_size' => ['nullable', 'numeric', 'min:0'],
            'is_verified' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $property->fill($validated);
        $property->appendLog('Listing edited by admin.', Auth::user()->name);
        $property->save();

        return redirect()->route('admin.properties.review', $property)->with('success', 'Listing updated.');
    }

    public function downloadDocument(Property $property, PropertyDocument $document)
    {
        $this->ensureAdmin();

        if ($document->property_id !== $property->id) {
            abort(404);
        }

        $path = storage_path('app/public/' . $document->file_path);
        if (! is_file($path)) {
            return redirect()->back()->with('error', 'Document file not found.');
        }

        return response()->download($path, ($document->title ?: 'document') . '.pdf');
    }

    public function categories()
    {
        $this->ensureAdmin();

        $categories = PropertyCategory::orderBy('sort_order')->get();

        return view('admin.properties.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'alpha_dash', 'max:80'],
            'icon' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        PropertyCategory::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? 'bi-house',
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.properties.categories')->with('success', 'Category added.');
    }

    public function updateCategory(Request $request, PropertyCategory $category)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'icon' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->fill($validated);
        $category->is_active = $request->boolean('is_active');
        $category->save();

        return redirect()->route('admin.properties.categories')->with('success', 'Category updated.');
    }

    public function destroyCategory(PropertyCategory $category)
    {
        $this->ensureAdmin();

        $category->delete();

        return redirect()->route('admin.properties.categories')->with('success', 'Category deleted.');
    }

    public function inquiries(Request $request)
    {
        $this->ensureAdmin();

        $query = PropertyInquiry::with('property', 'user');

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $inquiries = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.properties.inquiries', compact('inquiries'));
    }

    public function inquiryShow(PropertyInquiry $inquiry)
    {
        $this->ensureAdmin();

        $inquiry->load('property', 'user', 'conversations');

        return view('admin.properties.inquiry-show', compact('inquiry'));
    }

    public function inquiryUpdate(Request $request, PropertyInquiry $inquiry)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'status' => ['required', 'in:awaiting_admin_review,representative_verification,viewing_scheduled,purchase_discussion,rental_review,completed,cancelled'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $old = $inquiry->status;
        $inquiry->status = $validated['status'];
        if (isset($validated['admin_note']) && $validated['admin_note'] !== '') {
            $inquiry->admin_note = $validated['admin_note'];
        }
        $inquiry->appendLog('Status changed from ' . ($old) . ' to ' . $validated['status'] . ' by admin.', Auth::user()->name);
        $inquiry->save();

        if ($validated['status'] === 'completed' && $inquiry->type === 'rental' && $inquiry->user) {
            try {
                app(\App\Services\DocumentService::class)->generate('rental_agreement', $inquiry, $inquiry->user, [
                    'metadata' => ['related_label' => $inquiry->property->title . ' (' . $inquiry->property->ref() . ')'],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('admin.inquiries.show', $inquiry)->with('success', 'Inquiry updated to "' . $inquiry->statusLabel() . '".');
    }

    public function inquiryConnect(Request $request, PropertyInquiry $inquiry)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'channel' => ['required', 'in:whatsapp_group,telegram_group,call,meeting'],
            'external_link' => ['nullable', 'url', 'max:500'],
            'participants' => ['nullable', 'array'],
            'participants.*' => ['string', 'max:120'],
        ]);

        $conversation = PropertyConversation::create([
            'inquiry_id' => $inquiry->id,
            'property_id' => $inquiry->property_id,
            'channel' => $validated['channel'],
            'external_link' => $validated['external_link'] ?? null,
            'participants' => $validated['participants'] ?? [],
            'status' => 'active',
        ]);

        $inquiry->appendLog('Admin connected parties via ' . $conversation->channelLabel() . '.', Auth::user()->name);
        $inquiry->save();

        return redirect()->route('admin.inquiries.show', $inquiry)->with('success', 'Conversation created and both parties connected.');
    }

    public function conversations(Request $request)
    {
        $this->ensureAdmin();

        $query = PropertyConversation::with('inquiry.property');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $conversations = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.properties.conversations', compact('conversations'));
    }

    public function conversationClose(Request $request, PropertyConversation $conversation)
    {
        $this->ensureAdmin();

        $conversation->status = 'closed';
        $conversation->save();

        if ($conversation->inquiry) {
            $conversation->inquiry->appendLog('Conversation closed by admin.', Auth::user()->name);
            $conversation->inquiry->save();
        }

        return redirect()->back()->with('success', 'Conversation closed.');
    }

    public function representatives()
    {
        $this->ensureAdmin();

        $users = User::whereIn('rep_type', ['owner', 'agent', 'developer', 'property_manager'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.properties.representatives', compact('users'));
    }

    public function verifyRepresentative(Request $request, User $user)
    {
        $this->ensureAdmin();

        $user->rep_status = 'verified';
        $user->rep_verified_at = now();
        $user->save();

        foreach ($user->propertyListings()->where('status', 'submitted')->get() as $property) {
            $property->representative_verified = true;
            $property->save();
        }

        return redirect()->back()->with('success', $user->name . ' verified as ' . $user->repLabel() . '.');
    }

    public function rejectRepresentative(Request $request, User $user)
    {
        $this->ensureAdmin();

        $user->rep_status = 'rejected';
        $user->save();

        return redirect()->back()->with('success', $user->name . '\'s representative verification rejected.');
    }

    public function reports()
    {
        $this->ensureAdmin();

        $reports = PropertyReport::with('property', 'reporter')->orderBy('id', 'desc')->paginate(15);

        return view('admin.properties.reports', compact('reports'));
    }

    public function reportResolve(Request $request, PropertyReport $report)
    {
        $this->ensureAdmin();

        $report->status = 'resolved';
        $report->save();

        return redirect()->back()->with('success', 'Report marked as resolved.');
    }

    public function reportDismiss(Request $request, PropertyReport $report)
    {
        $this->ensureAdmin();

        $report->status = 'dismissed';
        $report->save();

        return redirect()->back()->with('success', 'Report dismissed.');
    }

    protected function ensureAdmin(): void
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            abort(403);
        }
    }

    protected function notifyOwner(Property $property, string $message): void
    {
        if (! $property->user?->email) {
            return;
        }

        try {
            Mail::to($property->user->email)->send(new PropertyListingStatusMail($property, $message));
        } catch (\Throwable $e) {
            // Email delivery must never break admin actions.
        }
    }
}
