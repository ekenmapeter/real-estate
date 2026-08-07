@extends('layouts.main')

@section('title', 'Saved Properties | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<section class="py-4" style="background:#f8fafc; min-height:80vh;">
    <div class="container user-shell-content" style="max-width:1100px;">
        <h4 class="fw-bold mb-1" style="color:#0B1F3A;">Saved Properties</h4>
        <p class="text-muted small mb-3">Properties you have saved. They stay here until you remove them.</p>


        <div class="row g-4">
            @forelse($properties as $prop)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="position-relative" style="height:190px; background:#e2e8f0;">
                            <a href="{{ route('property.show', $prop) }}">
                                <img src="{{ $prop->image_url ?? asset('images/property-placeholder.jpg') }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $prop->title }}">
                            </a>
                            <span class="badge position-absolute top-0 start-0 m-3 fw-bold px-3 py-1" style="background:{{ $prop->isForSale() ? '#dc2626' : '#2563eb' }};">{{ $prop->isForSale() ? 'For Sale' : 'For Rent' }}</span>
                            @if($prop->is_verified)
                                <span class="badge position-absolute top-0 start-0 m-3 fw-bold px-3 py-1" style="background:#16a34a; margin-left:110px;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <h6 class="fw-bold mb-1" style="color:#0B1F3A;">{{ $prop->title }}</h6>
                                    <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $prop->fullLocation() }}</div>
                                </div>
                                <strong class="text-nowrap" style="color:#2563eb;">{{ $prop->isForRent() ? format_usd($prop->monthly_rent) . '/mo' : format_usd($prop->purchasePrice()) }}</strong>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('property.show', $prop) }}" class="btn btn-sm fw-bold text-white rounded-3 px-3" style="background:#0B1F3A;">View Property</a>
                                <form action="{{ route('property.save', $prop) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold rounded-3"><i class="bi bi-bookmark-x me-1"></i> Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-bookmark-heart fs-1 text-muted d-block mb-2"></i>
                            <h5 class="fw-bold" style="color:#0B1F3A;">No saved properties</h5>
                            <p class="text-muted small mb-3">Tap the bookmark icon on any property to save it here.</p>
                            <a href="{{ url('/properties') }}" class="btn fw-bold text-white px-4 rounded-3" style="background:#0B1F3A;">Browse Properties</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($properties->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $properties->links() }}</div>
        @endif
    </div>
</section>
@endsection
