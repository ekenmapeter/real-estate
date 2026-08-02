@extends('layouts.main')

@section('title', $property->title . ' | radiantdreamrealty')

@section('content')
<style>
    .loading-spinner {
        display: inline-block;
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .property-stat-card {
        transition: all 0.3s ease;
    }
    .property-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }
</style>

<section class="py-5" style="background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none fw-semibold text-primary">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/properties') }}" class="text-decoration-none fw-semibold text-primary">Properties</a></li>
                <li class="breadcrumb-item active fw-bold text-dark text-truncate" style="max-width:280px;" aria-current="page">{{ $property->title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Left: Image & Details -->
            <div class="col-lg-7">
                <!-- Hero Image Carousel -->
                @php
                    $galleryImages = $property->galleryUrls();
                    if (empty($galleryImages)) {
                        $galleryImages = ['https://radiantdreamrealty.com/frontend/images/home/house-7.jpg'];
                    }
                @endphp
                <div class="rounded-4 overflow-hidden shadow-lg mb-4 position-relative" x-data="{ slide: 0, images: @json($galleryImages) }">
                    <div class="position-relative overflow-hidden" style="height:460px; background:#e2e8f0;">
                        <template x-for="(img, i) in images" :key="i">
                            <div x-show="slide === i" x-transition.opacity.duration.300ms class="position-absolute top-0 start-0 w-100 h-100">
                                <img :src="img" :alt="@json($property->title)" class="w-100 h-100" style="object-fit:cover;">
                            </div>
                        </template>
                    </div>

                    <button x-show="images.length > 1" type="button" @click="slide = (slide - 1 + images.length) % images.length" class="btn btn-light rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; opacity:0.85; z-index:5;">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button x-show="images.length > 1" type="button" @click="slide = (slide + 1) % images.length" class="btn btn-light rounded-circle position-absolute top-50 end-0 translate-middle-y me-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; opacity:0.85; z-index:5;">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <div x-show="images.length > 1" class="position-absolute bottom-0 end-0 m-3 d-flex gap-1" style="z-index:5;">
                        <template x-for="(img, i) in images" :key="i">
                            <button type="button" @click="slide = i" class="rounded-circle border-0" :class="slide === i ? 'bg-white' : 'bg-white bg-opacity-50'" style="width:8px; height:8px; padding:0;"></button>
                        </template>
                    </div>

                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $property->category }}</span>
                            <span class="badge {{ $property->status === 'sold_out' ? 'bg-secondary' : 'bg-success' }} fs-6 px-3 py-2">{{ $property->status === 'sold_out' ? 'Sold' : 'For Sale' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Title & Location -->
                <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold mb-2" style="color: #0f172a; font-size: 2rem;">{{ $property->title }}</h2>
                        <div class="d-flex align-items-center gap-3 text-muted flex-wrap">
                            <span><i class="bi bi-geo-alt me-1" style="color:#2563eb;"></i> {{ $property->location }}</span>
                            <span class="d-flex align-items-center"><i class="bi bi-shield-check me-1 text-success"></i> Verified Property</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @auth
                            <form action="{{ route('property.save', $property) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $isSaved ? 'btn-danger' : 'btn-outline-primary' }} fw-bold px-3 py-2 rounded-3">
                                    <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }} me-1"></i> {{ $isSaved ? 'Saved' : 'Save' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3">
                                <i class="bi bi-bookmark me-1"></i> Save
                            </a>
                        @endauth
                        <button type="button" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3" onclick="shareContent('{{ $property->title }}', '{{ route('property.show', $property) }}', 'Buy this property')">
                            <i class="bi bi-share me-1"></i> Share
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3" style="color: #0f172a;"><i class="bi bi-info-circle me-2" style="color:#2563eb;"></i>Description</h5>
                    <p class="mb-0" style="color: #475569; line-height: 1.8;">{{ $property->description }}</p>
                </div>

                <!-- Stats Cards Row -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="property-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#eff6ff;">
                                <i class="bi bi-tag text-primary fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Purchase Price</small>
                            <h5 class="fw-bold mb-0 text-dark">${{ number_format($price, 2) }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="property-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#f0fdf4;">
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Ownership</small>
                            <h5 class="fw-bold mb-0 text-success">Full Title</h5>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Purchase Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 90px; overflow: hidden;">
                    <!-- Card Header Gradient -->
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold" style="font-size:0.85rem; letter-spacing:0.05em;">PROPERTY FOR SALE</span>
                        </div>
                        <h4 class="fw-bold mb-0 mt-1 text-white font-weight-bold">{{ $property->title }}</h4>
                    </div>

                    <div class="p-4">
                        <!-- Price Summary -->
                        <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-semibold">Purchase Price</span>
                                <strong class="fs-4 fw-bold" style="color:#0f172a;">${{ number_format($price, 2) }}</strong>
                            </div>
                            <hr class="my-2" style="border-color:#e2e8f0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Property Type</span>
                                <span class="fw-bold text-dark">{{ $property->category }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Ownership</span>
                                <span class="fw-bold text-success">Direct Purchase</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="color:#1e40af;">Total Payable</span>
                                <span class="fw-bold" style="color:#1e40af; font-size:1.6rem;">${{ number_format($price, 2) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-1 small">
                                <i class="bi bi-wallet2" style="color:#1e40af;"></i>
                                @auth
                                    <span style="color:#1e40af;">Balance: <strong>${{ number_format(auth()->user()->wallet_balance, 2) }}</strong></span>
                                @else
                                    <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in</a> to see your balance
                                @endauth
                            </div>
                        </div>

                        @if($property->status === 'sold_out')
                            <div class="alert alert-secondary text-center fw-bold mb-0">This property has been sold.</div>
                        @else
                            <form action="{{ route('property.purchase', $property) }}" method="POST" onsubmit="return confirm('Confirm purchase of {{ $property->title }} for ${{ number_format($price, 2) }}? This amount will be deducted from your wallet.');">
                                @csrf
                                @auth
                                    <button type="submit" class="btn btn-primary fw-bold w-100 py-3 rounded-3 shadow-sm"
                                            style="background:#2563eb; font-size:1.05rem;">
                                        <i class="bi bi-house-check-fill me-1"></i> Buy Property Now
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary fw-bold w-100 py-3 rounded-3 shadow-sm" style="background:#2563eb; font-size:1.05rem;">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Purchase
                                    </a>
                                @endauth
                            </form>
                            <small class="text-muted d-block text-center mt-2" style="font-size:0.72rem;">
                                <i class="bi bi-shield-check me-1 text-success"></i>One-time full payment from your wallet. Full ownership transferred on purchase.
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection