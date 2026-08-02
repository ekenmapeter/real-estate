@extends('layouts.main')

@section('title', 'Resources & Market Insights | radiantdreamrealty')

@section('content')
<!-- Resources Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          RESOURCES & MARKET<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">INSIGHTS GUIDE</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Free Property Investment Guides, Reports & Strategies.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Access comprehensive research reports, legal escrow documentation guides, and market yield forecasts to make informed real estate decisions.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#resourcesHub" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Explore Hub</a>
          <a href="{{ url('/project-marketplace') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">View Marketplace</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/home/house-9.jpg" alt="Resources Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 440px; object-fit: cover; width: 100%;">
          
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-book-fill fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">2025 Market Report</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">Free Downloadable PDF</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Resources Grid -->
<section id="resourcesHub" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 reveal-on-scroll delay-1">
        <div class="card border-0 glass-card rounded-4 p-4 h-100">
          <div class="mb-3 text-primary"><i class="bi bi-book display-5"></i></div>
          <h5 class="fw-bold text-dark">Real Estate Co-Ownership Guide</h5>
          <p class="text-muted small">Everything you need to know about fractional property investment and quarterly dividend payouts.</p>
          <a href="#" class="btn btn-sm btn-outline-primary fw-bold mt-auto">Read Guide <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 reveal-on-scroll delay-2">
        <div class="card border-0 glass-card rounded-4 p-4 h-100">
          <div class="mb-3 text-primary"><i class="bi bi-graph-up-arrow display-5"></i></div>
          <h5 class="fw-bold text-dark">2025 Global Market Report</h5>
          <p class="text-muted small">Comprehensive analysis on luxury real estate yields in Spain, Dubai, Bahamas, and US vacation hubs.</p>
          <a href="#" class="btn btn-sm btn-outline-primary fw-bold mt-auto">Download PDF <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 reveal-on-scroll delay-3">
        <div class="card border-0 glass-card rounded-4 p-4 h-100">
          <div class="mb-3 text-primary"><i class="bi bi-shield-check display-5"></i></div>
          <h5 class="fw-bold text-dark">Escrow & Legal Protection 101</h5>
          <p class="text-muted small">How our multi-currency finance team secures your funds and handles cross-border ownership deeds.</p>
          <a href="#" class="btn btn-sm btn-outline-primary fw-bold mt-auto">Read Article <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
