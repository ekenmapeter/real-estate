@extends('layouts.main')

@section('title', 'My Property Listings | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
    .status-badge { font-size: .7rem; font-weight: 700; padding: .35rem .8rem; border-radius: 50rem; }
</style>
<section class="py-4" style="background:#f8fafc; min-height:80vh;">
    <div class="container user-shell-content" style="max-width:1100px;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;">My Property Listings</h4>
                <p class="text-muted small mb-0">Manage your listings. Only approved listings are visible to the public.</p>
            </div>
        </div>


        @if(session('success'))
            <div class="alert alert-success small fw-bold">{{ session('success') }}</div>
        @endif

        @forelse($listings as $listing)
            @php
                $badge = match ($listing->status) {
                    'published' => ['#f0fdf4', '#16a34a'],
                    'submitted', 'under_review' => ['#eff6ff', '#2563eb'],
                    'more_info_required' => ['#fffbeb', '#d97706'],
                    'approved' => ['#f0fdf4', '#16a34a'],
                    'rejected' => ['#fef2f2', '#dc2626'],
                    'suspended' => ['#fff7ed', '#ea580c'],
                    'sold', 'rented' => ['#f1f5f9', '#64748b'],
                    default => ['#f8fafc', '#64748b'],
                };
            @endphp
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-2 col-4">
                            <img src="{{ $listing->image_url ?? asset('images/property-placeholder.jpg') }}" class="rounded-3 w-100" style="height:70px; object-fit:cover;" alt="{{ $listing->title }}">
                        </div>
                        <div class="col-md-4 col-8">
                            <div class="fw-bold text-truncate" style="color:#0B1F3A;">{{ $listing->title }}</div>
                            <div class="small text-muted">{{ $listing->ref() }} · {{ $listing->fullLocation() }}</div>
                            <div class="small fw-bold mt-1" style="color:#2563eb;">
                                {{ $listing->isForRent() ? format_usd($listing->monthly_rent) . '/mo' : format_usd($listing->purchasePrice()) }}
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">
                                @if($listing->status === 'more_info_required')
                                    <i class="bi bi-info-circle me-1"></i>
                                @elseif($listing->status === 'rejected')
                                    <i class="bi bi-x-circle me-1"></i>
                                @elseif($listing->status === 'published')
                                    <i class="bi bi-check-circle me-1"></i>
                                @endif
                                {{ $listing->statusLabel() }}
                            </span>
                            @if($listing->status === 'more_info_required' && $listing->admin_note)
                                <div class="small text-muted mt-1" style="font-size:.7rem;" title="{{ $listing->admin_note }}">
                                    <i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($listing->admin_note, 60) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3 col-6 text-md-end">
                            <div class="small text-muted mb-2">
                                <i class="bi bi-eye me-1"></i>{{ number_format($listing->views_count) }} views
                                <span class="mx-1">·</span>
                                <i class="bi bi-chat-dots me-1"></i>{{ $listing->inquiries_count }} inquiries
                            </div>
                            <div class="d-flex flex-wrap gap-1 justify-content-md-end">
                                <a href="{{ route('property.show', $listing) }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">View</a>
                                @if(in_array($listing->status, ['draft', 'submitted', 'under_review', 'more_info_required', 'rejected', 'approved', 'published']))
                                    <a href="{{ route('properties.edit', $listing) }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">Edit</a>
                                    @if($listing->status === 'published')
                                        <form action="{{ route('properties.pause', $listing) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">Pause</button>
                                        </form>
                                        <form action="{{ route($listing->isForSale() ? 'properties.sold' : 'properties.rented', $listing) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this listing as {{ $listing->isForSale() ? 'sold' : 'rented' }}?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm fw-bold text-white rounded-3" style="background:#0B1F3A;">Mark {{ $listing->isForSale() ? 'Sold' : 'Rented' }}</button>
                                        </form>
                                    @elseif($listing->status === 'draft')
                                        <form action="{{ route('properties.pause', $listing) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary fw-bold rounded-3">Resubmit</button>
                                        </form>
                                    @endif
                                @endif
                                <form action="{{ route('properties.destroy', $listing) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this listing permanently?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold rounded-3"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-house-add fs-1 text-muted d-block mb-2"></i>
                    <h5 class="fw-bold" style="color:#0B1F3A;">No listings yet</h5>
                    <p class="text-muted small mb-3">List your property and reach verified buyers and renters.</p>
                    <a href="{{ route('properties.create') }}" class="btn fw-bold text-white px-4 rounded-3" style="background:#0B1F3A;">List Your Property</a>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
