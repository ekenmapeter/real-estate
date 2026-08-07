@extends('layouts.main')

@section('title', 'Verified Properties Marketplace | ' . site_name())

@section('content')
<style>
    .property-card { transition: all .25s ease; }
    .property-card:hover { transform: translateY(-5px); box-shadow: 0 18px 40px rgba(11,31,58,.14) !important; }
    .property-card .img-wrap { height: 225px; overflow: hidden; background: #e2e8f0; }
    .property-card .img-wrap img { transition: transform .4s ease; }
    .property-card:hover .img-wrap img { transform: scale(1.06); }
    .badge-sale { background: #dc2626; }
    .badge-rent { background: #2563eb; }
    .badge-verified { background: #16a34a; }
    .filter-card { border: 1px solid #e8edf4; border-radius: 12px; }
    .filter-card .form-label { font-size: .8rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: .03em; }
    .tab-pill { padding: .5rem 1.4rem; border-radius: 50rem; font-weight: 700; font-size: .9rem; color: #475569; background: #fff; border: 1.5px solid #dbe2ec; }
    .tab-pill.active { background: #0B1F3A; color: #fff; border-color: #0B1F3A; }
    .tab-pill:hover:not(.active) { border-color: #2563eb; color: #2563eb; }
    .trust-icon { width: 46px; height: 46px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; }
</style>

<!-- Hero -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #0B1F3A 0%, #12325f 60%, #1e4a8a 100%); padding: 90px 0 80px;">
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 rounded-pill px-3 py-1 mb-3" style="border:1px solid rgba(255,255,255,.18);">
                    <i class="bi bi-patch-check-fill" style="color:#34d399;"></i>
                    <span class="text-white small fw-bold" style="letter-spacing:.06em;">VERIFIED REAL ESTATE LISTINGS</span>
                </div>
                <h1 class="text-white fw-bold mb-3" style="font-size: clamp(2.2rem, 4.5vw, 3.4rem); line-height:1.15; letter-spacing:-.5px;">
                    Explore Properties<br>For Sale or Rent
                </h1>
                <p class="text-white-50 mb-4" style="font-size:1.1rem; max-width:620px; margin:0 auto; line-height:1.7;">
                    Every listing is reviewed and verified by our team. All communication with owners and agents is
                    securely mediated by {{ site_name() }} Property Support — no direct contact, no fraud.
                </p>
                <form action="{{ url('/properties') }}" method="GET" class="mx-auto mt-4" style="max-width:640px;">
                    <div class="input-group input-group-lg shadow-lg" style="border-radius:14px; overflow:hidden;">
                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0" value="{{ request('search') }}" placeholder="Search by location, keyword, property name...">
                        <button class="btn fw-bold px-4 border-0 text-white" type="submit" style="background:#2563eb;">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .filter-label { font-size: .72rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem; display: block; }
    .filter-field { border: 1.5px solid #e2e8f0; border-radius: 10px; padding: .45rem .8rem; font-size: .9rem; transition: border-color .15s, box-shadow .15s; }
    .filter-field:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    .filter-icon-field { background: #fff; border: 1.5px solid #e2e8f0; border-radius: 10px; }
    .filter-icon-field .input-group-text { background: transparent; border: none; color: #94a3b8; }
    .filter-icon-field input { border: none; box-shadow: none !important; font-size: .9rem; padding: .45rem .8rem .45rem 0; }
    .filter-icon-field:focus-within { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    .filter-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .55rem 1.1rem; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #fff; font-size: .85rem; font-weight: 700; color: #475569; transition: all .15s; }
    .filter-btn:hover { border-color: #2563eb; color: #2563eb; background: #f0f6ff; }
    .filter-btn.active { border-color: #0B1F3A; background: #0B1F3A; color: #fff; }
    .amenity-chip { display: inline-flex; align-items: center; gap: .4rem; border: 1.5px solid #e2e8f0; border-radius: 50rem; padding: .42rem 1rem; font-size: .82rem; font-weight: 600; color: #475569; cursor: pointer; user-select: none; background: #fff; transition: all .15s; }
    .amenity-chip:hover { border-color: #2563eb; color: #2563eb; background: #f0f6ff; }
    .amenity-chip.checked { border-color: #0B1F3A; background: #0B1F3A; color: #fff; }
    .amenity-chip input { display: none; }
    .filter-divider { border: 0; border-top: 1px dashed #e2e8f0; margin: 1rem 0; }
    [x-cloak] { display: none !important; }
</style>

<!-- Filter tabs + toolbar -->
<section class="py-4" style="background:#fff; border-bottom:1px solid #eef2f7;">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted fw-bold small me-1"><i class="bi bi-grid-3x3-gap me-1"></i>Showing:</span>
                <a href="{{ url('/properties') }}" class="filter-btn {{ ! request('listing_type') || request('listing_type') == 'all' ? 'active' : '' }}">All</a>
                <a href="{{ url('/properties', ['listing_type' => 'sale']) }}" class="filter-btn {{ request('listing_type') == 'sale' ? 'active' : '' }}"><span style="width:8px; height:8px; border-radius:50%; background:#dc2626; display:inline-block;"></span> For Sale</a>
                <a href="{{ url('/properties', ['listing_type' => 'rent']) }}" class="filter-btn {{ request('listing_type') == 'rent' ? 'active' : '' }}"><span style="width:8px; height:8px; border-radius:50%; background:#2563eb; display:inline-block;"></span> For Rent</a>
            </div>
            <div class="small text-muted fw-bold" x-data="filterState()" x-init="init()" x-cloak>
                <template x-if="activeCount() > 0">
                    <span class="badge rounded-pill px-3 py-2" style="background:#eff6ff; color:#2563eb;">
                        <i class="bi bi-funnel me-1"></i><span x-text="activeCount()"></span> active filter<span x-text="activeCount() > 1 ? 's' : ''"></span>
                    </span>
                </template>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <form action="{{ url('/properties') }}" method="GET" x-data="filterState()" x-init="init()">
                <input type="hidden" name="listing_type" value="{{ request('listing_type', 'all') }}">
                <div class="card-body p-4">
                    <!-- Primary row: search + main selects -->
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label class="filter-label"><i class="bi bi-search me-1"></i>Search</label>
                            <div class="input-group filter-icon-field">
                                <span class="input-group-text ps-3"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Location, keyword, property name...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="filter-label"><i class="bi bi-tags me-1"></i>Category</label>
                            <select name="category" class="form-select filter-field">
                                <option value="all">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" @selected(request('category') == $cat->name)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="filter-label"><i class="bi bi-globe2 me-1"></i>Country</label>
                            <select name="country" class="form-select filter-field">
                                <option value="">All Countries</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c }}" @selected(request('country') == $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="filter-label"><i class="bi bi-geo-alt me-1"></i>City</label>
                            <input type="text" name="city" class="form-control filter-field" value="{{ request('city') }}" placeholder="Any city">
                        </div>
                    </div>

                    <hr class="filter-divider">

                    <!-- More filters (collapsible) -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-sm fw-bold rounded-3 px-3" style="background:#f8fafc; color:#334155; border:1.5px solid #e2e8f0;"
                                @click="more = ! more" x-bind:aria-expanded="more">
                            <i class="bi bi-sliders me-1"></i>
                            <span x-text="more ? 'Hide Advanced Filters' : 'Advanced Filters'"></span>
                            <i class="bi bi-chevron-down ms-1" style="transition:transform .2s;" :style="more ? 'transform: rotate(180deg);' : ''"></i>
                        </button>
                        <button type="button" class="btn btn-sm fw-bold rounded-3 px-3" style="background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca;"
                                @click="resetAll()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All
                        </button>
                    </div>

                    <div x-show="more" x-transition.opacity.duration.250ms>
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-3 col-md-6">
                                <label class="filter-label"><i class="bi bi-cash-stack me-1"></i>Price Range ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text filter-field border-end-0 rounded-end-0" style="background:#f8fafc;">$</span>
                                    <input type="number" name="min_price" class="form-control filter-field border-start-0 rounded-start-0" value="{{ request('min_price') }}" placeholder="Min" x-model="minPrice">
                                    <span class="input-group-text filter-field border-start-0 rounded-end-0" style="background:#f8fafc; border-left:0;">–</span>
                                    <input type="number" name="max_price" class="form-control filter-field border-start-0 rounded-start-0" value="{{ request('max_price') }}" placeholder="Max" x-model="maxPrice">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <label class="filter-label"><i class="bi bi-door-closed me-1"></i>Bedrooms</label>
                                <select name="bedrooms" class="form-select filter-field">
                                    <option value="">Any</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" @selected(request('bedrooms') == $i)>{{ $i }}+</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <label class="filter-label"><i class="bi bi-droplet-half me-1"></i>Bathrooms</label>
                                <select name="bathrooms" class="form-select filter-field">
                                    <option value="">Any</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" @selected(request('bathrooms') == $i)>{{ $i }}+</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="filter-label"><i class="bi bi-rulers me-1"></i>Min Size (sqm)</label>
                                <input type="number" name="min_size" class="form-control filter-field" value="{{ request('min_size') }}" placeholder="0+" x-model="minSize">
                            </div>
                            <div class="col-lg-3 col-md-4">
                                <label class="filter-label"><i class="bi bi-patch-check me-1"></i>Listing Quality</label>
                                <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border-0" style="background:#f8fafc; border:1.5px solid #e2e8f0; cursor:pointer; width:100%;">
                                    <input class="form-check-input m-0" type="checkbox" name="verified_only" value="1" id="verifiedOnly" @checked(request('verified_only')) style="width:1.1rem; height:1.1rem; cursor:pointer;">
                                    <span class="fw-bold small" style="color:#334155;"><i class="bi bi-patch-check-fill text-success me-1"></i>Verified listings only</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="filter-label mb-2"><i class="bi bi-star me-1"></i>Amenities</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Pool','Gym','Parking','Garden','Balcony','Elevator','Security','Air Conditioning','Furnished','Sea View','Smart Home','Backup Power'] as $tag)
                                    @php $checked = in_array($tag, (array) request('amenities')); @endphp
                                    <label class="amenity-chip {{ $checked ? 'checked' : '' }}">
                                        <input type="checkbox" name="amenities[]" value="{{ $tag }}" @checked($checked)>
                                        <i class="bi {{ $checked ? 'bi-check-circle-fill' : 'bi-check-circle' }}"></i> {{ $tag }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer actions -->
                <div class="card-footer bg-white border-0 px-4 pb-4 pt-0 d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ url('/properties') }}" class="btn btn-light fw-bold px-4 rounded-3" style="border:1.5px solid #e2e8f0;">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                    <button type="submit" class="btn fw-bold px-4 rounded-3 text-white shadow-sm" style="background:#0B1F3A;">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function filterState() {
    return {
        more: false,
        minPrice: {{ request('min_price') ?: "''" }},
        maxPrice: {{ request('max_price') ?: "''" }},
        minSize: {{ request('min_size') ?: "''" }},
        init() {
            const hasAdvanced = !!document.querySelector('[name="min_price"]').value
                || !!document.querySelector('[name="max_price"]').value
                || !!document.querySelector('[name="bedrooms"]').value
                || !!document.querySelector('[name="bathrooms"]').value
                || !!document.querySelector('[name="min_size"]').value
                || !!document.querySelector('[name="verified_only"]').checked
                || document.querySelectorAll('[name="amenities[]"]:checked').length > 0;
            this.more = hasAdvanced;
        },
        activeCount() {
            let count = 0;
            const url = new URLSearchParams(window.location.search);
            ['search', 'category', 'country', 'city', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'min_size', 'verified_only'].forEach(k => {
                if (url.get(k) && (k !== 'category' || url.get(k) !== 'all')) count++;
            });
            if (url.getAll('amenities[]').length) count++;
            return count;
        },
        resetAll() {
            window.location.href = '{{ url('/properties') }}';
        }
    };
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.amenity-chip input').forEach(input => {
        input.addEventListener('change', () => {
            input.closest('.amenity-chip').classList.toggle('checked', input.checked);
        });
    });
});
</script>


<!-- Results -->
<section class="py-5" style="background:#f8fafc; min-height:50vh;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0" style="color:#0B1F3A;">{{ $properties->total() }} verified propert{{ $properties->total() == 1 ? 'y' : 'ies' }} found</h5>
                <small class="text-muted">All listings are admin-reviewed and verified.</small>
            </div>
            <a href="{{ route('properties.create') }}" class="btn btn-sm fw-bold text-white px-3" style="background:#16a34a;">
                <i class="bi bi-plus-lg me-1"></i> List Your Property
            </a>
        </div>

        <div class="row g-4">
            @forelse($properties as $prop)
                @php
                    $isSaved = in_array($prop->id, $savedPropertyIds);
                    $img = $prop->image_url ?? ($prop->galleryUrls[0] ?? asset('images/property-placeholder.jpg'));
                @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="card property-card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="img-wrap position-relative">
                            <a href="{{ route('property.show', $prop) }}">
                                <img src="{{ $img }}" alt="{{ $prop->title }}" class="w-100 h-100" style="object-fit:cover;">
                            </a>
                            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                                <span class="badge fw-bold px-3 py-1 {{ $prop->isForSale() ? 'badge-sale' : 'badge-rent' }}" style="font-size:.75rem;">
                                    {{ $prop->isForSale() ? 'For Sale' : 'For Rent' }}
                                </span>
                                @if($prop->is_verified)
                                    <span class="badge badge-verified fw-bold px-3 py-1" style="font-size:.75rem;">
                                        <i class="bi bi-patch-check-fill me-1"></i>Verified
                                    </span>
                                @endif
                            </div>
                            <button type="button" class="btn bg-white rounded-circle shadow-sm position-absolute top-0 end-0 m-3 js-save-property d-flex align-items-center justify-content-center"
                                    style="width:38px; height:38px;"
                                    data-uuid="{{ $prop->uuid }}" data-saved="{{ $isSaved ? 1 : 0 }}"
                                    title="{{ $isSaved ? 'Remove from saved' : 'Save property' }}">
                                <i class="bi {{ $isSaved ? 'bi-bookmark-fill text-primary' : 'bi-bookmark' }}"></i>
                            </button>
                            <div class="position-absolute bottom-0 start-0 w-100 px-3 py-2 text-white d-flex align-items-center" style="background:linear-gradient(transparent, rgba(11,31,58,.85)); font-size:.85rem;">
                                <i class="bi bi-geo-alt-fill me-1"></i> {{ $prop->fullLocation() }}
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-2 d-flex justify-content-between align-items-start gap-2">
                                <a href="{{ route('property.show', $prop) }}" class="text-decoration-none">
                                    <h6 class="fw-bold mb-1" style="color:#0B1F3A;">{{ $prop->title }}</h6>
                                </a>
                                <span class="text-muted small text-nowrap" style="font-size:.72rem; color:#64748b !important;">{{ $prop->ref() }}</span>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mb-3 small" style="color:#475569;">
                                @if($prop->bedrooms !== null)
                                    <span><i class="bi bi-door-closed me-1 text-primary"></i>{{ $prop->bedrooms }} Beds</span>
                                @endif
                                @if($prop->bathrooms !== null)
                                    <span><i class="bi bi-droplet-half me-1 text-primary"></i>{{ $prop->bathrooms }} Baths</span>
                                @endif
                                @if($prop->property_size)
                                    <span><i class="bi bi-rulers me-1 text-primary"></i>{{ number_format($prop->property_size) }} sqm</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3" style="border-top:1px solid #eef2f7;">
                                <div>
                                    @if($prop->isForRent())
                                        <small class="text-muted d-block" style="font-size:.7rem;">Monthly Rent</small>
                                        <h6 class="fw-bold mb-0" style="color:#2563eb;">{{ format_usd($prop->monthly_rent) }}<span class="text-muted fw-normal" style="font-size:.75rem;">/mo</span></h6>
                                    @else
                                        <small class="text-muted d-block" style="font-size:.7rem;">Purchase Price</small>
                                        <h6 class="fw-bold mb-0" style="color:#2563eb;">{{ format_usd($prop->purchasePrice()) }}</h6>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-3" onclick="shareContent('{{ $prop->title }}', '{{ route('property.show', $prop) }}', 'View this verified property')">
                                        <i class="bi bi-share"></i>
                                    </button>
                                    <a href="{{ route('property.show', $prop) }}" class="btn btn-sm fw-bold text-white rounded-3 px-3" style="background:#0B1F3A;">
                                        View Property
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-4 bg-white rounded-4 shadow-sm d-inline-block" style="max-width:420px;">
                        <i class="bi bi-house-x fs-1 text-muted d-block mb-2"></i>
                        <h5 class="fw-bold" style="color:#0B1F3A;">No Properties Found</h5>
                        <p class="text-muted small mb-3">Try adjusting your filters or search for another location.</p>
                        <a href="{{ url('/properties') }}" class="btn btn-outline-primary btn-sm fw-bold">Reset Filters</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($properties->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Admin-mediated process -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 mb-3" style="background:#eff6ff; color:#2563eb; font-size:.8rem;">HOW IT WORKS</span>
            <h3 class="fw-bold" style="color:#0B1F3A;">Every Transaction Is Admin-Mediated</h3>
            <p class="text-muted mx-auto" style="max-width:600px;">Buyers and renters never deal directly with sellers. Our Property Support team verifies both parties and supervises the entire process.</p>
        </div>
        <div class="row g-3">
            @php
                $steps = [
                    ['bi-chat-dots', 'User Inquiry', 'You contact Aurevia Property Support via WhatsApp or Telegram with the property reference.'],
                    ['bi-shield-check', 'Admin Verifies Both Parties', 'We verify your identity and the listing representative, ownership, and availability.'],
                    ['bi-people', 'Admin Connects Both Parties', 'We create a WhatsApp or Telegram group or arrange a scheduled call.'],
                    ['bi-calendar-check', 'Viewing & Negotiation', 'The viewing is scheduled and negotiation is guided by the admin.'],
                    ['bi-patch-check', 'Transaction Completed', 'Documents and payment instructions are confirmed. The admin stays involved until completion.'],
                ];
            @endphp
            @foreach($steps as $i => [$icon, $title, $text])
                <div class="col-md-6 col-lg">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="position:relative;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="trust-icon text-white" style="background:{{ $i == 4 ? '#16a34a' : '#2563eb' }};">
                                <i class="bi {{ $icon }} fs-5"></i>
                            </div>
                            <span class="fw-bold text-muted" style="font-size:1.4rem;">{{ $i + 1 }}</span>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#0B1F3A;">{{ $title }}</h6>
                        <p class="small text-muted mb-0" style="line-height:1.6;">{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Trust bar -->
<section class="py-4" style="background:#0B1F3A;">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="text-white fw-bold" style="font-size:.95rem;">
                <i class="bi bi-shield-fill-check me-2" style="color:#34d399;"></i>
                All property transactions are 100% secured and supervised by {{ site_name() }} Property Support
            </div>
            <div class="d-flex flex-wrap gap-4">
                <div class="d-flex align-items-center gap-2 text-white-50 small"><i class="bi bi-slash-circle fs-5" style="color:#fbbf24;"></i> No direct contact</div>
                <div class="d-flex align-items-center gap-2 text-white-50 small"><i class="bi bi-person-check fs-5" style="color:#34d399;"></i> Admin verified</div>
                <div class="d-flex align-items-center gap-2 text-white-50 small"><i class="bi bi-lock fs-5" style="color:#60a5fa;"></i> Secure communication</div>
                <div class="d-flex align-items-center gap-2 text-white-50 small"><i class="bi bi-safe fs-5" style="color:#fbbf24;"></i> Safe transactions</div>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.js-save-property').forEach(btn => {
    btn.addEventListener('click', function () {
        const uuid = this.dataset.uuid;
        fetch('{{ url("/property") }}/' + uuid + '/save', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        }).then(res => {
            if (res.status === 401) { window.location = '{{ route("login") }}'; throw new Error('redirect'); }
            if (!res.ok) throw new Error('error');
            return res.json();
        }).then(data => {
            const saved = data.saved;
            this.dataset.saved = saved ? '1' : '0';
            const icon = this.querySelector('i');
            icon.className = saved ? 'bi bi-bookmark-fill text-primary' : 'bi bi-bookmark';
            if (typeof showToast === 'function') {
                showToast(saved ? 'Property saved to your list!' : 'Property removed from your saved list.', saved ? 'success' : 'info');
            }
        }).catch(err => {
            if (err.message !== 'redirect' && typeof showToast === 'function') {
                showToast('Something went wrong. Please try again.', 'error');
            }
        });
    });
});
</script>
@endsection
