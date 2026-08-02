@extends('layouts.main')

@section('title', 'Properties for Sale | radiantdreamrealty')

@section('content')
<!-- Properties Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          BUY PREMIUM<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">HOMES & ESTATES</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Own Prime Global Real Estate Outright.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Browse verified residential estates, beachfront villas, and commercial properties across 5 continents and purchase them directly — one-time payment, full ownership.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#propertySearch" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Browse Properties</a>
          <a href="{{ route('invest.index') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Invest in Projects</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/home/house-7.jpg" alt="Properties Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 460px; object-fit: cover; width: 100%;">
          
          <!-- Floating Glassmorphism Overlay Card -->
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-building-check fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">Verified Properties</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">500+ Active Listings</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Search Filter Bar -->
<section id="propertySearch" class="py-4 border-bottom glass-panel">
  <div class="container">
    <form action="{{ url('/properties') }}" method="GET" class="row g-3 align-items-center">
      <div class="col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by property title, location or keyword...">
        </div>
      </div>
      <div class="col-md-4">
        <select name="category" class="form-select">
          <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
          <option value="Residential" {{ request('category') == 'Residential' ? 'selected' : '' }}>Residential</option>
          <option value="Luxury" {{ request('category') == 'Luxury' ? 'selected' : '' }}>Luxury Residential</option>
          <option value="Commercial" {{ request('category') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
          <option value="Beachfront" {{ request('category') == 'Beachfront' ? 'selected' : '' }}>Beachfront Villa</option>
          <option value="Apartments" {{ request('category') == 'Apartments' ? 'selected' : '' }}>Apartments</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="background-color: #2756fd;">Filter Properties</button>
      </div>
    </form>
  </div>
</section>

<!-- Properties Grid -->
<section class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-4">
      @forelse($properties as $prop)
        @php
          $price = $prop->purchasePrice();
          $sold = $prop->status === 'sold_out';
          $isSaved = in_array($prop->id, $savedPropertyIds);
        @endphp
        <div class="col-lg-4 col-md-6 reveal-on-scroll delay-1">
          <div class="card h-100 border-0 glass-card rounded-4 overflow-hidden">
            <div class="position-relative">
              <img src="{{ $prop->image_url ?? 'https://radiantdreamrealty.com/frontend/images/home/house-7.jpg' }}" class="card-img-top" alt="{{ $prop->title }}" style="height: 230px; object-fit: cover;">
              <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                <span class="badge bg-primary">{{ $prop->category }}</span>
                <span class="badge {{ $sold ? 'bg-secondary' : 'bg-success' }}">{{ $sold ? 'Sold' : 'For Sale' }}</span>
              </div>
              @auth
                <form action="{{ route('property.save', $prop) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                  @csrf
                  <button type="submit" class="btn btn-sm rounded-circle border-0 shadow-sm save-btn {{ $isSaved ? 'saved' : 'bg-white' }}" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;" title="{{ $isSaved ? 'Remove from saved' : 'Save property' }}">
                    <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}" style="font-size:1rem;"></i>
                  </button>
                </form>
              @else
                <a href="{{ route('login') }}" class="btn btn-sm bg-white rounded-circle border-0 shadow-sm position-absolute top-0 end-0 m-2" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;" title="Sign in to save">
                  <i class="bi bi-bookmark" style="font-size:1rem;"></i>
                </a>
              @endauth
              <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.75)); font-size: 0.85rem;">
                <i class="bi bi-geo-alt me-1"></i> {{ $prop->location }}
              </div>
            </div>
            <div class="card-body p-4">
              <h5 class="fw-bold mb-2" style="color: #1a3c5e;">{{ $prop->title }}</h5>
              <p class="text-muted small mb-3">{{ Str::limit($prop->description, 90) }}</p>

              <div class="d-flex justify-content-between text-muted small border-bottom pb-3 mb-3">
                <span><i class="bi bi-house-door me-1"></i> {{ $prop->category }}</span>
                <span><i class="bi bi-shield-check me-1 text-success"></i> Verified</span>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <small class="text-muted d-block" style="font-size:0.75rem;">Purchase Price</small>
                  <h5 class="fw-bold text-primary mb-0">${{ number_format($price, 2) }}</h5>
                </div>
                @if($sold)
                  <button class="btn btn-secondary btn-sm fw-bold px-3 py-2 rounded-3" disabled>Sold</button>
                @else
                  <a href="{{ route('property.show', $prop) }}" class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-3" style="background:#2756fd;">Buy Now</a>
                @endif
              </div>

              <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('property.show', $prop) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill project-action-btn">
                  <i class="bi bi-info-circle me-1"></i> More Info
                </a>
                <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill project-action-btn" onclick="shareContent('{{ $prop->title }}', '{{ route('property.show', $prop) }}', 'Buy this property')">
                  <i class="bi bi-share me-1"></i> Share
                </button>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-4 bg-white rounded-4 shadow-sm d-inline-block" style="max-width:400px;">
            <i class="bi bi-building-exclamation fs-1 text-muted d-block mb-2"></i>
            <h5 class="fw-bold text-dark">No Properties Found</h5>
            <p class="text-muted small mb-3">Try adjusting your search keywords or category filters.</p>
            <a href="{{ url('/properties') }}" class="btn btn-outline-primary btn-sm fw-bold">Reset Filters</a>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
