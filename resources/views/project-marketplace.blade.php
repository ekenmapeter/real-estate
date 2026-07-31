@extends('layouts.main')

@section('title', 'Project Marketplace | Radiant Dream Realty')

@section('content')
<!-- Marketplace Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          CO-OWN LUXURY<br><span style="color: #fbbf24; text-shadow: 0 0 20px rgba(251,191,36,0.4);">HIGH-YIELD ASSETS</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Fractional Property Investment starting from $1,000.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Participate in curated real estate projects with automated quarterly rental yields and capital growth distributions across prime global destinations.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#marketplaceGrid" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">View Projects</a>
          <a href="{{ route('register') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Start Co-Owning</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/home/house-1.jpg" alt="Project Marketplace Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 460px; object-fit: cover; width: 100%;">
          
          <!-- Floating Glassmorphism Overlay Card -->
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-graph-up-arrow fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">High Target Yields</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">9.8% - 13.2% p.a. ROI</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Marketplace Grid -->
<section id="marketplaceGrid" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-4">
      @forelse($properties as $prop)
        @php
          $fundedPercent = $prop->total_shares > 0 ? round((($prop->total_shares - $prop->available_shares) / $prop->total_shares) * 100) : 0;
          $raisedAmount = ($prop->total_shares - $prop->available_shares) * $prop->price_per_share;
          $totalValuation = $prop->total_shares * $prop->price_per_share;
        @endphp
        <div class="col-lg-4 col-md-6 reveal-on-scroll delay-1">
          <div class="card h-100 border-0 glass-card rounded-4 overflow-hidden">
            <div class="position-relative">
              <img src="{{ $prop->image_url ?? 'https://radiantdreamrealty.com/frontend/images/home/house-1.jpg' }}" class="card-img-top" alt="{{ $prop->title }}" style="height: 220px; object-fit: cover;">
              <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                <span class="badge bg-warning text-dark fw-bold">{{ $prop->category }}</span>
                <span class="badge bg-success">{{ $prop->roi_percentage }}% ROI</span>
              </div>
            </div>
            <div class="card-body p-4">
              <h5 class="card-title fw-bold" style="color: #1a3c5e;">{{ $prop->title }}</h5>
              <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> {{ $prop->location }}</p>
              
              <!-- Progress Bar -->
              <div class="mb-3">
                <div class="d-flex justify-content-between small fw-bold mb-1">
                  <span>Funded: {{ $fundedPercent }}%</span>
                  <span class="text-primary">${{ number_format($raisedAmount, 2) }} / ${{ number_format($totalValuation, 2) }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-warning" style="width: {{ $fundedPercent }}%;"></div>
                </div>
              </div>

              <div class="d-flex justify-content-between border-top pt-3 text-muted small mb-3">
                <span>Share Price: <b class="text-dark">${{ number_format($prop->price_per_share, 2) }}</b></span>
                <span>Est. Yield: <b class="text-success">{{ $prop->roi_percentage }}% p.a.</b></span>
              </div>

              <a href="{{ route('property.show', $prop) }}" class="btn btn-warning text-dark fw-bold w-100 py-2 rounded-3 shadow-sm">
                <i class="bi bi-cart-plus me-1"></i> Buy Shares
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-4 bg-white rounded-4 shadow-sm d-inline-block" style="max-width:400px;">
            <i class="bi bi-building-exclamation fs-1 text-muted d-block mb-2"></i>
            <h5 class="fw-bold text-dark">No Active Marketplace Projects</h5>
            <p class="text-muted small mb-0">Check back soon for new property investment opportunities.</p>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
