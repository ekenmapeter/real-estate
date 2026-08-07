@extends('layouts.main')

@section('title', ($property ?? null) ? 'Edit Listing | ' . site_name() : 'List Your Property | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
    .form-section-title { font-size: .8rem; font-weight: 800; letter-spacing: .06em; color: #475569; text-transform: uppercase; }
    .list-step { width: 34px; height: 34px; border-radius: 50%; background: #0B1F3A; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .9rem; }
</style>

<section class="py-4" style="background:#f8fafc; min-height:80vh;">
    <div class="container user-shell-content" style="max-width:900px;">
        <div class="mb-4">
            <h4 class="fw-bold mb-1" style="color:#0B1F3A;">{{ ($property ?? null) ? 'Edit Your Listing' : 'List Your Property' }}</h4>
            <p class="text-muted small mb-0">
                @if(($property ?? null))
                    Update your listing. If it was rejected or more info was requested, it will be resubmitted for admin review.
                @else
                    Every listing is reviewed and approved by our admin team before it becomes visible to buyers and renters. Reference numbers are assigned on submission.
                @endif
            </p>
        </div>

        <div class="alert small" style="background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af;">
            <i class="bi bi-shield-lock me-1"></i> No personal contact details (phone, email, WhatsApp or Telegram) are shown on your listing. All buyer communication is mediated by Aurevia Property Support.
        </div>

        <form action="{{ ($property ?? null) ? route('properties.update', $property) : route('properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="listing_type" id="listingType" value="{{ old('listing_type', ($property ?? null)?->listing_type ?? 'sale') }}">

            <!-- 1. Property Information -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="list-step">1</span>
                        <h5 class="fw-bold mb-0" style="color:#0B1F3A;">Property Information</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small">Property Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', ($property ?? null)?->title) }}" placeholder="e.g. Azure Coast Beachfront Villa" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Sale or Rent *</label>
                            <div class="d-flex gap-3">
                                <button type="button" class="btn flex-fill fw-bold rounded-3 js-type-toggle {{ old('listing_type', ($property ?? null)?->listing_type ?? 'sale') == 'sale' ? 'btn-primary text-white' : 'btn-light' }}" data-type="sale" style="{{ old('listing_type', ($property ?? null)?->listing_type ?? 'sale') == 'sale' ? 'background:#0B1F3A; border-color:#0B1F3A;' : '' }}">For Sale</button>
                                <button type="button" class="btn flex-fill fw-bold rounded-3 js-type-toggle {{ old('listing_type', ($property ?? null)?->listing_type ?? 'sale') == 'rent' ? 'btn-primary text-white' : 'btn-light' }}" data-type="rent" style="{{ old('listing_type', ($property ?? null)?->listing_type ?? 'sale') == 'rent' ? 'background:#0B1F3A; border-color:#0B1F3A;' : '' }}">For Rent</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Property Type *</label>
                            <select name="category" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" @selected(old('category', ($property ?? null)?->category) == $cat->name)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Description *</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Describe the property, condition, features and highlights..." required>{{ old('description', ($property ?? null)?->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Location -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="list-step">2</span>
                        <h5 class="fw-bold mb-0" style="color:#0B1F3A;">Location</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Country *</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', ($property ?? null)?->country) }}" placeholder="e.g. United Arab Emirates" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">State / Region</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', ($property ?? null)?->state) }}" placeholder="e.g. Dubai">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">City *</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', ($property ?? null)?->city) }}" placeholder="e.g. Dubai Marina" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', ($property ?? null)?->address) }}" placeholder="Street or building (optional)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Property Details -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="list-step">3</span>
                        <h5 class="fw-bold mb-0" style="color:#0B1F3A;">Property Details</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Bedrooms</label>
                            <input type="number" name="bedrooms" class="form-control" min="0" value="{{ old('bedrooms', ($property ?? null)?->bedrooms) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Bathrooms</label>
                            <input type="number" name="bathrooms" class="form-control" min="0" value="{{ old('bathrooms', ($property ?? null)?->bathrooms) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Parking</label>
                            <input type="text" name="parking" class="form-control" value="{{ old('parking', ($property ?? null)?->parking) }}" placeholder="e.g. 2 covered spaces">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Property Size (sqm)</label>
                            <input type="number" name="property_size" class="form-control" step="0.01" min="0" value="{{ old('property_size', ($property ?? null)?->property_size) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Land Size (sqm)</label>
                            <input type="number" name="land_size" class="form-control" step="0.01" min="0" value="{{ old('land_size', ($property ?? null)?->land_size) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Ownership Type</label>
                            <select name="ownership_type" class="form-select">
                                <option value="">Select...</option>
                                @foreach(['freehold' => 'Freehold', 'leasehold' => 'Leasehold', 'strata' => 'Strata', 'other' => 'Other'] as $k => $v)
                                    <option value="{{ $k }}" @selected(old('ownership_type', ($property ?? null)?->ownership_type) == $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Property Video URL</label>
                            <input type="url" name="video_url" class="form-control" value="{{ old('video_url', ($property ?? null)?->video_url) }}" placeholder="https://youtube.com/...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Amenities</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Pool','Gym','Parking','Garden','Balcony','Elevator','Security','Air Conditioning','Furnished','Sea View','Smart Home','Backup Power'] as $tag)
                                    @php $checked = in_array($tag, old('amenities', ($property ?? null)?->amenities() ?? [])); @endphp
                                    <label class="amenity-option form-check-inline mb-0">
                                        <input type="checkbox" name="amenities[]" value="{{ $tag }}" class="btn-check" @checked($checked)>
                                        <span class="btn btn-sm rounded-pill {{ $checked ? 'btn-primary text-white' : 'btn-light' }}" style="{{ $checked ? 'background:#0B1F3A; border-color:#0B1F3A;' : 'border:1px solid #dbe2ec;' }}">{{ $tag }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Pricing -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="list-step">4</span>
                        <h5 class="fw-bold mb-0" style="color:#0B1F3A;">Pricing <span class="text-muted fw-normal" style="font-size:.85rem;">(USD)</span></h5>
                    </div>
                    <div class="row g-3" id="salePricing">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Selling Price ($) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="1" value="{{ old('price', ($property ?? null)?->price) }}" placeholder="e.g. 750000">
                        </div>
                    </div>
                    <div class="row g-3" id="rentPricing" style="display:none;">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Monthly Rent ($) *</label>
                            <input type="number" name="monthly_rent" class="form-control" step="0.01" min="1" value="{{ old('monthly_rent', ($property ?? null)?->monthly_rent) }}" placeholder="e.g. 3500">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Security Deposit ($)</label>
                            <input type="number" name="security_deposit" class="form-control" step="0.01" min="0" value="{{ old('security_deposit', ($property ?? null)?->security_deposit) }}" placeholder="e.g. 3500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Media -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="list-step">5</span>
                        <h5 class="fw-bold mb-0" style="color:#0B1F3A;">Media</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Cover Image (JPG/PNG/WebP, max 10MB)</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                            @if(($property ?? null)?->image_url)
                                <div class="mt-2"><img src="{{ $property->image_url }}" style="height:70px; object-fit:cover; border-radius:8px;" alt="Cover"></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Additional Photos (multiple)</label>
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                            @if(($property ?? null)?->images->count())
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($property->images as $img)
                                        <img src="{{ $img->url() }}" style="height:55px; width:55px; object-fit:cover; border-radius:8px;" alt="Gallery">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Floor Plan (PDF/Image, optional)</label>
                            <input type="file" name="floor_plan" class="form-control" accept=".pdf,image/*">
                            @if(($property ?? null)?->documents()->where('document_type', 'floor_plan')->exists())
                                <div class="small text-muted mt-1"><i class="bi bi-check-circle text-success"></i> Floor plan uploaded</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. Ownership -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="list-step">6</span>
                        <h5 class="fw-bold mb-0" style="color:#0B1F3A;">Ownership & Verification</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">You are listing as *</label>
                            <select name="representative_role" class="form-select" required>
                                <option value="owner" @selected(old('representative_role', ($property ?? null)?->representative_role ?? auth()->user()->rep_type) == 'owner')>Owner</option>
                                <option value="agent" @selected(old('representative_role', ($property ?? null)?->representative_role ?? auth()->user()->rep_type) == 'agent')>Real Estate Agent</option>
                                <option value="developer" @selected(old('representative_role', ($property ?? null)?->representative_role ?? auth()->user()->rep_type) == 'developer')>Developer</option>
                                <option value="property_manager" @selected(old('representative_role', ($property ?? null)?->representative_role ?? auth()->user()->rep_type) == 'property_manager')>Property Manager</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Verification Documents (ownership proof, license, authorization)</label>
                            <input type="file" name="verification_documents[]" class="form-control" multiple accept=".pdf,image/*">
                            <div class="small text-muted mt-1">Required for agents, developers and property managers. Owners may upload proof of ownership.</div>
                        </div>
                        @if(auth()->user()->rep_type && ! auth()->user()->isRepresentativeVerified() && auth()->user()->rep_status !== 'pending')
                            <div class="col-12">
                                <div class="alert small mb-0 py-2" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e;">
                                    <i class="bi bi-info-circle me-1"></i> Your profile is not yet verified as {{ auth()->user()->repLabel() }}. You can still submit your listing — the admin will verify you during review.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end mb-5">
                <a href="{{ url('/properties') }}" class="btn btn-light fw-bold px-4 rounded-3">Cancel</a>
                <button type="submit" class="btn fw-bold px-5 py-2 text-white rounded-3" style="background:#0B1F3A;">
                    <i class="bi bi-send me-2"></i> {{ ($property ?? null) ? 'Save Changes' : 'Submit Listing for Review' }}
                </button>
            </div>
        </form>
    </div>
</section>

<script>
(function () {
    const saleEl = document.getElementById('salePricing');
    const rentEl = document.getElementById('rentPricing');
    const hidden = document.getElementById('listingType');

    function sync() {
        const isSale = hidden.value === 'sale';
        saleEl.style.display = isSale ? '' : 'none';
        rentEl.style.display = isSale ? 'none' : '';
        document.querySelectorAll('.js-type-toggle').forEach(btn => {
            const active = btn.dataset.type === hidden.value;
            btn.classList.toggle('btn-primary', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('btn-light', !active);
            btn.style.background = active ? '#0B1F3A' : '';
            btn.style.borderColor = active ? '#0B1F3A' : '';
        });
    }

    document.querySelectorAll('.js-type-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            hidden.value = btn.dataset.type;
            sync();
        });
    });

    document.querySelectorAll('.amenity-option input').forEach(input => {
        input.addEventListener('change', () => {
            const span = input.parentElement.querySelector('span');
            if (input.checked) {
                span.classList.add('btn-primary', 'text-white');
                span.classList.remove('btn-light');
                span.style.background = '#0B1F3A';
                span.style.borderColor = '#0B1F3A';
            } else {
                span.classList.remove('btn-primary', 'text-white');
                span.classList.add('btn-light');
                span.style.background = '';
                span.style.borderColor = '#dbe2ec';
            }
        });
    });

    sync();
})();
</script>
@endsection
