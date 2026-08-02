@extends('layouts.main')

@section('title', 'Careers | radiantdreamrealty')

@section('content')
<!-- Career Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          BUILD THE FUTURE OF<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">REAL ESTATE TECH</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Explore Global & Remote Opportunities.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Join a fast-growing international team of investment analysts, software engineers, and real estate brokers transforming global property wealth.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#openPositions" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Open Positions</a>
          <a href="{{ url('/team') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Meet The Team</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-3.jpg" alt="Careers Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 440px; object-fit: cover; width: 100%;">
          
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-laptop fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">Remote-First Culture</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">Global Career Opportunities</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Open Positions Section -->
<section id="openPositions" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <h3 class="fw-bold mb-4 text-center" style="color: #1a3c5e;">Open Positions</h3>
    <div class="row g-4 justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 glass-card p-4 rounded-4 mb-3 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-1">Senior Real Estate Investment Analyst</h5>
            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> New York, USA (Hybrid)</small>
          </div>
          <a href="#" class="btn btn-outline-primary btn-sm fw-bold">Apply Position</a>
        </div>

        <div class="card border-0 glass-card p-4 rounded-4 mb-3 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-1">Full Stack Laravel Developer</h5>
            <small class="text-muted"><i class="bi bi-globe me-1"></i> Remote</small>
          </div>
          <a href="#" class="btn btn-outline-primary btn-sm fw-bold">Apply Position</a>
        </div>

        <div class="card border-0 glass-card p-4 rounded-4 mb-3 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-1">International Property Broker</h5>
            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> Dubai, UAE</small>
          </div>
          <a href="#" class="btn btn-outline-primary btn-sm fw-bold">Apply Position</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
