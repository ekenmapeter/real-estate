@extends('layouts.main')

@section('title', 'Meet The Team | ' . site_name())

@section('content')
<!-- Team Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          MEET THE EXPERTS<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">BEHIND OUR GROWTH</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Real Estate Veterans, Financial Analysts & Technologists.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Our seasoned executive team brings decades of combined experience navigating international property acquisitions, legal escrow, and asset management.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#teamGrid" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Meet Executives</a>
          <a href="{{ url('/career') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Join Our Team</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-1.jpg" alt="Team Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 440px; object-fit: cover; width: 100%;">
          
          <!-- Floating Glassmorphism Overlay Card -->
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-person-badge-fill fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">Proven Leadership</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">20+ Years Market Nav.</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Team Grid -->
<section id="teamGrid" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 col-sm-6 reveal-on-scroll delay-1">
        <div class="card border-0 glass-card rounded-4 text-center overflow-hidden">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-1.jpg" class="card-img-top" alt="Chris Patt" style="height: 250px; object-fit: cover;">
          <div class="card-body p-3">
            <h5 class="fw-bold mb-1" style="color: #1a3c5e;">Chris Patt</h5>
            <small class="text-muted">Chief Operations Officer</small>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 reveal-on-scroll delay-2">
        <div class="card border-0 glass-card rounded-4 text-center overflow-hidden">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-2.jpg" class="card-img-top" alt="Esther Howard" style="height: 250px; object-fit: cover;">
          <div class="card-body p-3">
            <h5 class="fw-bold mb-1" style="color: #1a3c5e;">Esther Howard</h5>
            <small class="text-muted">Head of Global Finance</small>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 reveal-on-scroll delay-3">
        <div class="card border-0 glass-card rounded-4 text-center overflow-hidden">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-3.jpg" class="card-img-top" alt="Darrell Steward" style="height: 250px; object-fit: cover;">
          <div class="card-body p-3">
            <h5 class="fw-bold mb-1" style="color: #1a3c5e;">Darrell Steward</h5>
            <small class="text-muted">Head of Asset Management</small>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 reveal-on-scroll delay-4">
        <div class="card border-0 glass-card rounded-4 text-center overflow-hidden">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-4.jpg" class="card-img-top" alt="Robert Fox" style="height: 250px; object-fit: cover;">
          <div class="card-body p-3">
            <h5 class="fw-bold mb-1" style="color: #1a3c5e;">Robert Fox</h5>
            <small class="text-muted">Senior Broker Specialist</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
