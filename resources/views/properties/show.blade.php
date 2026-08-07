@extends('layouts.main')

@section('title', $property->title . ' | ' . site_name())

@section('content')
<style>
    .detail-gallery { height: 480px; background: #e2e8f0; }
    .detail-gallery img { object-fit: cover; }
    .btn-whatsapp { background: #25D366; border-color: #25D366; color: #fff; }
    .btn-whatsapp:hover { background: #1eb857; border-color: #1eb857; color: #fff; }
    .btn-telegram { background: #229ED9; border-color: #229ED9; color: #fff; }
    .btn-telegram:hover { background: #1d8fc4; border-color: #1d8fc4; color: #fff; }
    .spec-tile { border: 1px solid #eef2f7; border-radius: 12px; padding: .9rem .5rem; text-align: center; background: #fbfdff; }
    .spec-tile .bi { font-size: 1.2rem; color: #2563eb; }
    .amenity-chip { border: 1px solid #dbe2ec; border-radius: 50rem; padding: .35rem .9rem; font-size: .8rem; font-weight: 600; color: #334155; background: #f8fafc; }
    .media-thumb { height: 90px; object-fit: cover; cursor: pointer; border-radius: 8px; }
</style>

<section class="py-4" style="background:#f8fafc; min-height:80vh;">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb bg-transparent p-0 mb-0 small">
                <li class="breadcrumb-item"><a href="{{ url('/properties') }}" class="text-decoration-none text-muted">Properties</a></li>
                <li class="breadcrumb-item active fw-bold text-dark text-truncate" style="max-width:300px;">{{ $property->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Left column -->
            <div class="col-lg-8">
                <!-- Gallery -->
                <div class="rounded-4 overflow-hidden shadow-sm bg-white position-relative mb-4" x-data='{ slide: 0, images: @json($galleryImages) }'>
                    <div class="detail-gallery position-relative">
                        <template x-for="(img, i) in images" :key="i">
                            <div x-show="slide === i" x-transition.opacity.duration.300ms class="position-absolute top-0 start-0 w-100 h-100">
                                <img :src="img" :alt='@json($property->title)' class="w-100 h-100">
                            </div>
                        </template>
                    </div>

                    <button x-show="images.length > 1" type="button" @click="slide = (slide - 1 + images.length) % images.length" class="btn btn-light rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; z-index:5;">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button x-show="images.length > 1" type="button" @click="slide = (slide + 1) % images.length" class="btn btn-light rounded-circle position-absolute top-50 end-0 translate-middle-y me-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; z-index:5;">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <div class="position-absolute top-0 start-0 m-3 d-flex gap-2" style="z-index:5;">
                        <span class="badge fw-bold px-3 py-2" style="background:{{ $property->isForSale() ? '#dc2626' : '#2563eb' }}; font-size:.8rem;">{{ $property->isForSale() ? 'For Sale' : 'For Rent' }}</span>
                        @if($property->is_verified)
                            <span class="badge fw-bold px-3 py-2" style="background:#16a34a; font-size:.8rem;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                        @endif
                        <span class="badge fw-bold px-3 py-2 bg-dark bg-opacity-75" style="font-size:.8rem;">{{ $property->category }}</span>
                    </div>

                    <div class="position-absolute bottom-0 end-0 m-3 bg-dark bg-opacity-75 text-white rounded-pill px-3 py-1 small fw-bold" style="z-index:5;" x-show="images.length > 1">
                        <span x-text="slide + 1"></span><span>/</span><span x-text="images.length"></span>
                    </div>
                </div>

                <!-- Title row -->
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div>
                            <h2 class="fw-bold mb-1" style="color:#0B1F3A; font-size:1.6rem;">{{ $property->title }}</h2>
                            <div class="d-flex align-items-center gap-3 text-muted flex-wrap small">
                                <span><i class="bi bi-geo-alt-fill me-1" style="color:#2563eb;"></i> {{ $property->fullLocation() }}</span>
                                <span class="d-flex align-items-center"><i class="bi bi-patch-check-fill me-1 text-success"></i> Verified Listing</span>
                                <span class="text-muted">{{ $property->ref() }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @auth
                                <form action="{{ route('property.save', $property) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $isSaved ? 'btn-primary' : 'btn-outline-primary' }} fw-bold px-3 py-2 rounded-3">
                                        <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }} me-1"></i> {{ $isSaved ? 'Saved' : 'Save' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3"><i class="bi bi-bookmark me-1"></i> Save</a>
                            @endauth
                            <button type="button" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3" onclick="shareContent('{{ $property->title }}', '{{ route('property.show', $property) }}', 'View this verified property')">
                                <i class="bi bi-share me-1"></i> Share
                            </button>
                        </div>
                    </div>

                    <!-- Specs -->
                    <div class="row g-2 mt-2">
                        @if($property->bedrooms !== null)
                            <div class="col-4 col-md-2"><div class="spec-tile"><i class="bi bi-door-closed"></i><div class="fw-bold small mt-1">{{ $property->bedrooms }}</div><div class="text-muted" style="font-size:.7rem;">Bedrooms</div></div></div>
                        @endif
                        @if($property->bathrooms !== null)
                            <div class="col-4 col-md-2"><div class="spec-tile"><i class="bi bi-droplet-half"></i><div class="fw-bold small mt-1">{{ $property->bathrooms }}</div><div class="text-muted" style="font-size:.7rem;">Bathrooms</div></div></div>
                        @endif
                        @if($property->property_size)
                            <div class="col-4 col-md-2"><div class="spec-tile"><i class="bi bi-rulers"></i><div class="fw-bold small mt-1">{{ number_format($property->property_size) }}</div><div class="text-muted" style="font-size:.7rem;">Sq Meters</div></div></div>
                        @endif
                        @if($property->land_size)
                            <div class="col-4 col-md-2"><div class="spec-tile"><i class="bi bi-bounding-box"></i><div class="fw-bold small mt-1">{{ number_format($property->land_size) }}</div><div class="text-muted" style="font-size:.7rem;">Lot Sqm</div></div></div>
                        @endif
                        @if($property->parking)
                            <div class="col-4 col-md-2"><div class="spec-tile"><i class="bi bi-p-circle"></i><div class="fw-bold small mt-1">{{ $property->parking }}</div><div class="text-muted" style="font-size:.7rem;">Parking</div></div></div>
                        @endif
                        @if($property->ownership_type)
                            <div class="col-4 col-md-2"><div class="spec-tile"><i class="bi bi-file-earmark-text"></i><div class="fw-bold small mt-1 text-capitalize">{{ $property->ownership_type }}</div><div class="text-muted" style="font-size:.7rem;">Ownership</div></div></div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-info-circle me-2" style="color:#2563eb;"></i>About This Property</h5>
                    <p class="mb-0" style="color:#475569; line-height:1.85;">{{ $property->description }}</p>
                </div>

                <!-- Amenities -->
                @if(count($property->amenities()))
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-star me-2" style="color:#2563eb;"></i>Amenities</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($property->amenities() as $a)
                                <span class="amenity-chip"><i class="bi bi-check2-circle me-1 text-success"></i>{{ $a }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Media: video + floor plan -->
                @if($property->video_url || $property->documents()->where('document_type', 'floor_plan')->exists())
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-play-circle me-2" style="color:#2563eb;"></i>Media & Floor Plans</h5>
                        <div class="d-flex flex-wrap gap-3">
                            @if($property->video_url)
                                <a href="{{ $property->video_url }}" target="_blank" class="btn btn-outline-primary fw-bold rounded-3"><i class="bi bi-play-btn me-1"></i> Watch Video</a>
                            @endif
                            @foreach($property->documents()->where('document_type', 'floor_plan')->get() as $doc)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($doc->file_path) }}" target="_blank" class="btn btn-outline-primary fw-bold rounded-3"><i class="bi bi-diagram-3 me-1"></i> Floor Plan</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right column -->
            <div class="col-lg-4">
                <!-- Price & actions -->
                <div class="card border-0 shadow rounded-4 sticky-top" style="top:90px; overflow:hidden;">
                    <div class="p-4 text-white" style="background:linear-gradient(135deg, #0B1F3A, #1e4a8a);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold" style="font-size:.78rem; letter-spacing:.08em;">{{ strtoupper($property->isForSale() ? 'For Sale' : 'For Rent') }}</span>
                            @if($property->isForSale())
                                <span class="badge bg-white bg-opacity-15 small">Negotiable</span>
                            @endif
                        </div>
                        @if($property->isForSale())
                            <h3 class="fw-bold mb-0">{{ format_usd($property->purchasePrice()) }}</h3>
                        @else
                            <h3 class="fw-bold mb-0">{{ format_usd($property->monthly_rent) }}<span class="fw-normal" style="font-size:1rem;">/month</span></h3>
                            @if($property->security_deposit)
                                <small class="text-white-50">Security deposit: {{ format_usd($property->security_deposit) }}</small>
                            @endif
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="p-3 rounded-3 mb-3" style="background:#f0f9ff; border:1px solid #bae6fd;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-shield-lock fs-5" style="color:#0284c7;"></i>
                                <div>
                                    <div class="fw-bold small" style="color:#0369a1;">Interested in this property?</div>
                                    <div class="small text-muted" style="font-size:.75rem; line-height:1.5;">
                                        All communication with the property representative is mediated by Aurevia Property Support. We verify both parties before connecting you.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ whatsapp_url(admin_contact_message($property)) }}" target="_blank" class="btn btn-whatsapp fw-bold py-2 rounded-3">
                                <i class="bi bi-whatsapp fs-5 me-2"></i> Contact via WhatsApp
                            </a>
                            <a href="{{ telegram_url(admin_contact_message($property)) }}" target="_blank" class="btn btn-telegram fw-bold py-2 rounded-3">
                                <i class="bi bi-telegram fs-5 me-2"></i> Contact via Telegram
                            </a>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn fw-bold py-2 rounded-3 text-white" style="background:#0B1F3A;" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                <i class="bi bi-calendar-check me-2"></i> Request Property Viewing
                            </button>
                            @if($property->isForSale())
                                <button type="button" class="btn btn-outline-primary fw-bold py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#purchaseModal">
                                    <i class="bi bi-hand-index-thumb me-2"></i> Purchase Interest
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-primary fw-bold py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#rentalModal">
                                    <i class="bi bi-file-earmark-text me-2"></i> Apply to Rent
                                </button>
                            @endif
                        </div>

                        <div class="mt-3 d-flex align-items-center justify-content-center gap-2 small text-muted" style="font-size:.72rem;">
                            <i class="bi bi-shield-check text-success"></i>
                            No direct contact. All parties verified by our admin team.
                        </div>
                    </div>
                </div>

                <!-- Listed by -->
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="p-4">
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-person-badge me-2" style="color:#2563eb;"></i>Listed By</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:52px; height:52px; background:#2563eb;">
                                {{ strtoupper(mb_substr(masked_name($property->owner?->name ?? 'Aurevia'), 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold" style="color:#0B1F3A;">{{ masked_name($property->owner?->name ?? 'Aurevia Admin') }}</div>
                                <div class="small text-muted">
                                    {{ $property->representativeLabel() }}
                                    @if($property->representative_verified)
                                        <span class="badge ms-1" style="background:#f0fdf4; color:#16a34a;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                                    @endif
                                </div>
                                @if($property->owner?->created_at)
                                    <div class="small text-muted">Member since {{ $property->owner->created_at->format('M Y') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="alert mt-3 mb-0 small py-2" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e;">
                            <i class="bi bi-info-circle me-1"></i> For your protection, representative contact details are never shown. Please use the WhatsApp or Telegram buttons above.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related -->
        @if($related->count())
            <div class="mt-5">
                <h5 class="fw-bold mb-3" style="color:#0B1F3A;">Similar Properties</h5>
                <div class="row g-4">
                    @foreach($related as $rel)
                        <div class="col-md-6 col-xl-4">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                <div class="position-relative" style="height:180px; background:#e2e8f0;">
                                    <a href="{{ route('property.show', $rel) }}">
                                        <img src="{{ $rel->image_url ?? asset('images/property-placeholder.jpg') }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $rel->title }}">
                                    </a>
                                    <span class="badge position-absolute top-0 start-0 m-3 fw-bold px-3 py-1" style="background:{{ $rel->isForSale() ? '#dc2626' : '#2563eb' }};">{{ $rel->isForSale() ? 'For Sale' : 'For Rent' }}</span>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-1 text-truncate" style="color:#0B1F3A;">{{ $rel->title }}</h6>
                                    <div class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $rel->fullLocation() }}</div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong style="color:#2563eb;">{{ $rel->isForRent() ? format_usd($rel->monthly_rent) . '/mo' : format_usd($rel->purchasePrice()) }}</strong>
                                        <a href="{{ route('property.show', $rel) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-3">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Inquiry modal (viewing / purchase / rental) -->
<div class="modal fade modal-dialog-centered" id="inquiryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('properties.inquiry', [$property, 'viewing']) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-calendar-check me-2" style="color:#2563eb;"></i>Request Property Viewing</h5>
                        <small class="text-muted">{{ $property->title }} · {{ $property->ref() }} — your request goes directly to Aurevia Property Support for review.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+1 555 000 1234">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Preferred Date *</label>
                            <input type="date" name="preferred_date" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Preferred Time *</label>
                            <input type="time" name="preferred_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Viewing Type *</label>
                            <select name="viewing_type" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="physical">Physical Viewing</option>
                                <option value="virtual">Virtual Viewing</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Number of Attendees</label>
                            <input type="number" name="attendees" class="form-control" min="1" max="20" value="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Additional Notes</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Anything the support team should know..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small d-block">Preferred Contact Channel *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="preferred_channel" id="chanWa" value="whatsapp" checked>
                                <label class="form-check-label" for="chanWa"><i class="bi bi-whatsapp text-success me-1"></i>WhatsApp</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="preferred_channel" id="chanTg" value="telegram">
                                <label class="form-check-label" for="chanTg"><i class="bi bi-telegram" style="color:#229ED9;"></i> Telegram</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    <small class="text-muted me-auto" style="font-size:.72rem;"><i class="bi bi-shield-check me-1 text-success"></i>Our team verifies both parties before confirming the viewing.</small>
                    <button type="button" class="btn btn-light fw-bold rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fw-bold px-4 rounded-3 text-white" style="background:#0B1F3A;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-dialog-centered" id="purchaseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('properties.inquiry', [$property, 'purchase']) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold mb-0" style="color:#0B1F3A;"><i class="bi bi-hand-index-thumb me-2" style="color:#2563eb;"></i>Purchase Interest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted">You are not purchasing now. This submits a purchase inquiry to Aurevia Property Support, who verifies both parties and coordinates the process.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Message</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Anything our support team should know about your purchase interest..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small d-block">Preferred Contact Channel *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="preferred_channel" id="pWa" value="whatsapp" checked>
                                <label class="form-check-label" for="pWa"><i class="bi bi-whatsapp text-success me-1"></i>WhatsApp</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="preferred_channel" id="pTg" value="telegram">
                                <label class="form-check-label" for="pTg"><i class="bi bi-telegram" style="color:#229ED9;"></i> Telegram</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-light fw-bold rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fw-bold px-4 rounded-3 text-white" style="background:#0B1F3A;">Submit Interest</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-dialog-centered" id="rentalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('properties.inquiry', [$property, 'rental']) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold mb-0" style="color:#0B1F3A;"><i class="bi bi-file-earmark-text me-2" style="color:#2563eb;"></i>Apply to Rent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted">Your rental application goes to Aurevia Property Support. The admin reviews it, verifies the representative, coordinates the viewing, and updates you with the decision.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+1 555 000 1234">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Preferred Move-in Date</label>
                            <input type="date" name="preferred_date" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Message</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Tell us about yourself and your rental needs..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-light fw-bold rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fw-bold px-4 rounded-3 text-white" style="background:#0B1F3A;">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
