@extends('layouts.main')

@section('title', 'Become a Verified Agent | ' . site_name())

@section('content')
<!-- Agent Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          BECOME A VERIFIED<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">PROPERTY AGENT</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Market & Sell Luxury International Real Estate.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Join {{ site_name() }} as a verified broker or agent. Access thousands of active global buyers and list premium inventory worldwide.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#agentApp" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Apply as Agent</a>
          <a href="{{ url('/affiliate') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Affiliate Program</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-4.jpg" alt="Agent Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 440px; object-fit: cover; width: 100%;">
          
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-briefcase-fill fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">High Split Commissions</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">Industry-leading payouts</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Agent Content -->
<section id="agentApp" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <div class="card border-0 glass-card rounded-4 p-4 p-md-5">
          <h4 class="fw-bold mb-4" style="color: #1a3c5e;">Agent Application</h4>
          <form action="#" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Full Name</label>
              <input type="text" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Email Address</label>
              <input type="email" class="form-control" placeholder="john@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">License Number / Region</label>
              <input type="text" class="form-control" placeholder="RE-109283 / California, USA" required>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Years of Experience</label>
              <select class="form-select" required>
                <option value="1-3">1 - 3 Years</option>
                <option value="3-5">3 - 5 Years</option>
                <option value="5+">5+ Years</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary fw-bold w-100 py-3" style="background-color: #2756fd;">Submit Application</button>
          </form>
        </div>
      </div>
      <div class="col-lg-6">
        <h3 class="fw-bold mb-3" style="color: #1a3c5e;">Why Join {{ site_name() }}?</h3>
        <ul class="list-unstyled space-y-3">
          <li class="d-flex align-items-start gap-3 mb-3">
            <i class="bi bi-check-circle-fill text-primary fs-4"></i>
            <div>
              <h6 class="fw-bold mb-0">Global Buyer Matchmaking</h6>
              <small class="text-muted">Connect your local listings to over 35,000 active international buyers.</small>
            </div>
          </li>
          <li class="d-flex align-items-start gap-3 mb-3">
            <i class="bi bi-check-circle-fill text-primary fs-4"></i>
            <div>
              <h6 class="fw-bold mb-0">Competitive Split Commissions</h6>
              <small class="text-muted">Enjoy industry-leading commission splits with instant dashboard payouts.</small>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
@endsection
