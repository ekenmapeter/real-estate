@extends('layouts.main')

@section('title', 'Become an Affiliate | Radiant Dream Realty')

@section('content')
<!-- Affiliate Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          EARN 10% - 30%<br><span style="color: #34d399; text-shadow: 0 0 20px rgba(52,211,153,0.4);">RECURRING PAYOUTS</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Work From Home as a Global Real Estate Affiliate.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Refer investors, buyers, or co-owners to Radiant Dream Realty and earn generous commission payouts directly to your balance with real-time dashboard analytics.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="{{ route('register') }}" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Get Started</a>
          <a href="#affiliateTiers" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Commission Tiers</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/affil.jpg" alt="Affiliate Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 440px; object-fit: cover; width: 100%;">
          
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-cash-coin fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">Automated Payouts</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">Multi-Currency & Instant</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Affiliate Tiers Section -->
<section id="affiliateTiers" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-4 mb-5 text-center">
      <div class="col-md-4">
        <div class="card border-0 glass-card p-4 rounded-4 h-100">
          <h3 class="fw-bold text-primary mb-2">10% - 30%</h3>
          <h6 class="fw-bold text-dark">Commission Rate</h6>
          <p class="text-muted small">Earn competitive commission payouts on every referred property investment.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 glass-card p-4 rounded-4 h-100">
          <h3 class="fw-bold text-primary mb-2">24/7</h3>
          <h6 class="fw-bold text-dark">Dashboard Tracking</h6>
          <p class="text-muted small">Monitor clicks, leads, funded investments, and payouts in real-time.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 glass-card p-4 rounded-4 h-100">
          <h3 class="fw-bold text-primary mb-2">Instant</h3>
          <h6 class="fw-bold text-dark">Multi-Currency Payouts</h6>
          <p class="text-muted small">Withdraw your referral earnings directly via bank transfer, crypto, or PayPal.</p>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 glass-card p-4 p-md-5 rounded-4 text-center">
          <h3 class="fw-bold mb-3" style="color: #1a3c5e;">Ready to Start Earning?</h3>
          <p class="text-muted mb-4">Create your free affiliate account today and get instant access to your unique referral link and promotional banners.</p>
          <a href="{{ route('register') }}" class="btn btn-primary fw-bold py-3 px-5 d-inline-block" style="background-color: #2756fd; border-radius: 8px;">Register as Affiliate</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
