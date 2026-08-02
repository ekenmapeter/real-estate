@extends('layouts.main')

@section('title', 'Home | ' . site_name() . ' - Global Real Estate Co-Ownership')

@section('content')
<!-- 1. Hero Slider Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 110px 0 100px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Dynamic Text Slider -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <div class="hero-text-slider position-relative" style="min-height: 340px;">
          <!-- Slide 1 -->
          <div class="hero-slide active-slide" id="heroSlide1" style="transition: opacity 0.6s ease, transform 0.6s ease;">
            <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
              BUY. SELL.<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">CO-OWN. EARN.</span>
            </h1>
            <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Your All-in-One Real Estate Wealth Platform.</h5>
            <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
              Step into an international marketplace where you can co-own premium properties, buy or sell luxury homes, and build generational wealth with full transparency.
            </p>
          </div>

          <!-- Slide 2 -->
          <div class="hero-slide d-none" id="heroSlide2" style="transition: opacity 0.6s ease, transform 0.6s ease;">
            <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
              INVEST IN<br><span style="color: #34d399; text-shadow: 0 0 20px rgba(52,211,153,0.4);">PRIME REAL ESTATE</span>
            </h1>
            <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Luxury Villas & Commercial Hubs From $1,250.</h5>
            <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
              Access institutional-grade real estate opportunities in Spain, Dubai, Bahamas, Italy, and US vacation markets with automated quarterly payouts.
            </p>
          </div>

          <!-- Slide 3 -->
          <div class="hero-slide d-none" id="heroSlide3" style="transition: opacity 0.6s ease, transform 0.6s ease;">
            <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
              EARN UP TO<br><span style="color: #fbbf24; text-shadow: 0 0 20px rgba(251,191,36,0.4);">30% COMMISSIONS</span>
            </h1>
            <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Work Remotely as an Affiliate or Agent.</h5>
            <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
              Partner with {{ site_name() }} from anywhere. Broker international property deals, refer investors, and earn high-tier commissions paid directly to your balance.
            </p>
          </div>

          <!-- CTA Buttons -->
          <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
            <a href="{{ route('register') }}" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Get Started</a>
            <a href="{{ url('/properties') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Explore Properties</a>
          </div>

          <!-- Slide Controls & Indicator Dots -->
          <div class="d-flex align-items-center gap-3 mt-4 pt-2">
            <button type="button" class="btn btn-sm btn-outline-light rounded-circle p-0 d-flex align-items-center justify-content-center" id="prevHeroSlide" style="width: 34px; height: 34px;"><i class="bi bi-chevron-left"></i></button>
            <div class="d-flex gap-2">
              <span class="hero-dot active-dot rounded-pill" onclick="switchHeroSlide(0)" style="width: 24px; height: 8px; background-color: #60a5fa; cursor: pointer; transition: all 0.3s ease;"></span>
              <span class="hero-dot rounded-circle" onclick="switchHeroSlide(1)" style="width: 8px; height: 8px; background-color: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s ease;"></span>
              <span class="hero-dot rounded-circle" onclick="switchHeroSlide(2)" style="width: 8px; height: 8px; background-color: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s ease;"></span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-light rounded-circle p-0 d-flex align-items-center justify-content-center" id="nextHeroSlide" style="width: 34px; height: 34px;"><i class="bi bi-chevron-right"></i></button>
          </div>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="{{ asset('frontend/images/hero.png') }}" alt="Luxury Villa Real Estate" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 500px; object-fit: cover; width: 100%;">
          
          <!-- Floating Glassmorphism Overlay Card -->
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-house-check-fill fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">Luxury Co-Ownership</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">From $1,250 min. entry</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Hero Slider Javascript Controller -->
<script>
  let currentHeroSlide = 0;
  const totalHeroSlides = 3;

  function switchHeroSlide(index) {
    for (let i = 1; i <= totalHeroSlides; i++) {
      const slide = document.getElementById('heroSlide' + i);
      if (slide) {
        slide.classList.add('d-none');
        slide.classList.remove('active-slide');
      }
    }

    currentHeroSlide = index;
    const activeSlide = document.getElementById('heroSlide' + (currentHeroSlide + 1));
    if (activeSlide) {
      activeSlide.classList.remove('d-none');
      activeSlide.classList.add('active-slide');
    }

    // Update dots
    const dots = document.querySelectorAll('.hero-dot');
    dots.forEach((dot, idx) => {
      if (idx === currentHeroSlide) {
        dot.style.width = '24px';
        dot.style.borderRadius = '50px';
        dot.style.backgroundColor = '#60a5fa';
      } else {
        dot.style.width = '8px';
        dot.style.borderRadius = '50%';
        dot.style.backgroundColor = 'rgba(255,255,255,0.4)';
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('prevHeroSlide')?.addEventListener('click', function() {
      switchHeroSlide((currentHeroSlide - 1 + totalHeroSlides) % totalHeroSlides);
    });

    document.getElementById('nextHeroSlide')?.addEventListener('click', function() {
      switchHeroSlide((currentHeroSlide + 1) % totalHeroSlides);
    });

    // Auto rotate every 5 seconds
    setInterval(function() {
      switchHeroSlide((currentHeroSlide + 1) % totalHeroSlides);
    }, 5000);
  });
</script>

<!-- 2. Funding Milestone Section -->
<section class="funding-milestone-section reveal-on-scroll" style="background: #ffffff; padding: 60px 0; border-bottom: 1px solid #e5e7eb;">
  <div class="funding-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
    <div class="funding-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; align-items: start;">
      <!-- Left Column: Progress & Stats -->
      <div class="funding-progress glass-card p-4 rounded-4">
        <p class="fw-bold mb-3" style="color: #1a3c5e; font-size: 1.1rem;">
          <i class="bi bi-trophy-fill text-warning me-2"></i> Funding Milestone
        </p>
        <div class="progress-container mb-4">
          <div class="progress-bar-container" style="height: 50px; background: #e2e8f0; border-radius: 10px; position: relative; overflow: hidden;">
            <div class="progress-bar" style="width: 97.8%; height: 100%; background: linear-gradient(90deg, #2756fd, #3b82f6); display: flex; align-items: center; padding-left: 16px;">
              <span class="progress-amount text-white fw-bold" style="font-size: 1.1rem;">$48,920,118</span>
            </div>
            <span class="progress-goal position-absolute end-0 top-50 translate-middle-y me-3 fw-bold text-secondary" style="font-size: 0.95rem;">Goal: $50M</span>
          </div>
        </div>

        <div class="stats-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="stat-box text-center p-3 glass-card rounded-3">
            <i class="bi bi-people-fill text-primary display-6 mb-1 d-block"></i>
            <span class="d-block fw-bold text-primary" style="font-size: 1.25rem;">35,000+</span>
            <small class="text-muted" style="font-size: 0.85rem;">Active investors across 5 continents</small>
          </div>
          <div class="stat-box text-center p-3 glass-card rounded-3">
            <i class="bi bi-graph-up-arrow text-primary display-6 mb-1 d-block"></i>
            <span class="d-block fw-bold text-primary" style="font-size: 1.25rem;">$3.21B+</span>
            <small class="text-muted" style="font-size: 0.85rem;">Total investor contributions to date</small>
          </div>
        </div>
      </div>

      <!-- Right Column: Details & Info -->
      <div class="funding-details glass-card p-4 rounded-4">
        <div class="details-text mb-4" style="font-size: 1.05rem; color: #475569; line-height: 1.7;">
          2024 has been our strongest year yet, delivering over <strong>$1.95B</strong> in gross profits with <strong>61% YoY growth</strong>. RadiantDream Realty now leads in luxury and vacation property co-ownership, connecting investors to premium markets worldwide.
        </div>

        <div class="cta-container mb-4">
            <a href="{{ route('register') }}" class="btn btn-primary w-100 py-3 fw-bold mb-3 shadow" style="background: #2756fd; border: none; font-size: 1rem; border-radius: 8px;">
            Join Our Global Investor Network
          </a>

          <div class="investment-info d-flex justify-content-around glass-panel p-3 rounded-3 border">
            <div class="info-item text-center">
              <span class="d-block fw-bold fs-5 text-dark">$1,250</span>
              <small class="text-muted">Min. investment</small>
            </div>
            <div class="border-end"></div>
            <div class="info-item text-center">
              <span class="d-block fw-bold fs-5 text-dark">$152.15</span>
              <small class="text-muted">Current share price</small>
            </div>
          </div>
        </div>

        <div class="funding-quick-links pt-3 border-top d-flex gap-3 flex-wrap text-muted" style="font-size: 0.9rem;">
          <a href="#" class="text-primary text-decoration-none fw-medium">Investor Education</a> •
          <a href="#" class="text-primary text-decoration-none fw-medium">Raise Capital</a> •
          <a href="#" class="text-primary text-decoration-none fw-medium">Market Performance</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3. Market Expansion Announcement -->
<section class="flat-section py-5" style="background: #f8fafc;">
  <div class="container">
    <div class="box-title text-center mb-4">
      <span class="text-primary fw-bold text-uppercase tracking-wider" style="font-size: 0.9rem;">Just Announced</span>
      <h2 class="title mt-2 fw-bold" style="color: #1a3c5e;">New Market Expansions</h2>
      <p class="desc text-secondary mt-2" style="font-size: 1.05rem;">
        We’re scaling in <b>Spain, Italy, The Bahamas, Dubai, Brazil, The Caribbean, and Mexico</b>
      </p>
    </div>

    <div class="row justify-content-center mb-4">
      <div class="col-lg-10">
        <blockquote class="bg-white p-4 rounded-4 shadow-sm border-start border-primary border-4" style="font-size: 1.05rem; color: #334155;">
          “Our mission is to make high-yield real estate ownership accessible to anyone, anywhere.
          By adding markets like Spain’s Costa del Sol, Italy, The Bahamas, Dubai’s luxury waterfront, Brazil’s Rio coastline, The Caribbean and Mexico’s vacation hubs, we’re creating global opportunities without the complexity of cross-border ownership.”
          <footer class="blockquote-footer mt-2 fw-bold text-primary">– RDR CEO</footer>
        </blockquote>
      </div>
    </div>

    <!-- Expansion Cards Row -->
    <div class="d-flex gap-4 overflow-auto pb-3 px-2" style="scrollbar-width: thin;">
      <!-- Spain -->
      <div class="card border-0 rounded-4 shadow-sm flex-shrink-0" style="width: 320px; background: #ffffff;">
        <img src="https://radiantdreamrealty.com/frontend/images/market/coast.jpg" class="card-img-top rounded-top-4" alt="Spain" style="height: 180px; object-fit: cover;">
        <div class="card-body p-3">
          <span class="badge bg-primary mb-2">Spain</span>
          <h5 class="card-title fw-bold fs-6">Costa del Sol Villas</h5>
          <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">Luxury seaside villas in Spain’s most glamorous coastline. Known for sunshine, golf resorts, and high rental yields.</p>
        </div>
      </div>
      <!-- Italy -->
      <div class="card border-0 rounded-4 shadow-sm flex-shrink-0" style="width: 320px; background: #ffffff;">
        <img src="https://radiantdreamrealty.com/frontend/images/market/lake.jpg" class="card-img-top rounded-top-4" alt="Italy" style="height: 180px; object-fit: cover;">
        <div class="card-body p-3">
          <span class="badge bg-success mb-2">Italy</span>
          <h5 class="card-title fw-bold fs-6">Lakeside Homes</h5>
          <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">Elegant lakeside homes surrounded by mountains and timeless charm, ensuring high capital appreciation.</p>
        </div>
      </div>
      <!-- Bahamas -->
      <div class="card border-0 rounded-4 shadow-sm flex-shrink-0" style="width: 320px; background: #ffffff;">
        <img src="https://radiantdreamrealty.com/frontend/images/market/paradise.jpg" class="card-img-top rounded-top-4" alt="Bahamas" style="height: 180px; object-fit: cover;">
        <div class="card-body p-3">
          <span class="badge bg-info mb-2">Bahamas</span>
          <h5 class="card-title fw-bold fs-6">Paradise Island Villas</h5>
          <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">Beachfront retreats with year-round holiday demand and stable US-dollar linked economy.</p>
        </div>
      </div>
      <!-- Dubai -->
      <div class="card border-0 rounded-4 shadow-sm flex-shrink-0" style="width: 320px; background: #ffffff;">
        <img src="https://radiantdreamrealty.com/frontend/images/market/palm.jpg" class="card-img-top rounded-top-4" alt="Dubai" style="height: 180px; object-fit: cover;">
        <div class="card-body p-3">
          <span class="badge bg-warning text-dark mb-2">Dubai</span>
          <h5 class="card-title fw-bold fs-6">Palm Jumeirah Apartments</h5>
          <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">Iconic waterfront apartments in one of the world’s most prestigious tax-friendly luxury destinations.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 4. Services Section -->
<section class="flat-section py-5" style="background: #ffffff;">
  <div class="container">
    <div class="box-title text-center mb-5">
      <span class="text-primary fw-bold text-uppercase" style="font-size: 0.9rem;">Benefits that Grow with You</span>
      <h3 class="mt-2 title fw-bold" style="color: #1a3c5e;">Unlock Exclusive Perks as Your Portfolio Expands</h3>
    </div>

    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="service-box p-4 rounded-4 border text-center h-100 shadow-sm" style="background: #fafafa;">
          <div class="icon-wrap mb-3 text-primary">
            <i class="bi bi-shield-check display-4"></i>
          </div>
          <h5 class="fw-bold mb-3" style="color: #1a3c5e;">Exclusive Investments</h5>
          <p class="text-muted" style="font-size: 0.95rem;">Access private, high-yield real estate opportunities worldwide — from luxury villas to commercial developments — before they hit the public market.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="service-box p-4 rounded-4 border text-center h-100 shadow-sm" style="background: #fafafa;">
          <div class="icon-wrap mb-3 text-primary">
            <i class="bi bi-headset display-4"></i>
          </div>
          <h5 class="fw-bold mb-3" style="color: #1a3c5e;">White-Glove Support</h5>
          <p class="text-muted" style="font-size: 0.95rem;">Enjoy 24/7 access to tools, services, and live support through your Investor Dashboard to manage deposits, earnings, and property performance.</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="service-box p-4 rounded-4 border text-center h-100 shadow-sm" style="background: #fafafa;">
          <div class="icon-wrap mb-3 text-primary">
            <i class="bi bi-stars display-4"></i>
          </div>
          <h5 class="fw-bold mb-3" style="color: #1a3c5e;">Early Project Access</h5>
          <p class="text-muted" style="font-size: 0.95rem;">Get priority entry into high-demand projects as a VIP investor, ensuring you secure the best properties at early-stage valuations.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 5. How Investing Works -->
<section class="py-5" style="background: #f1f5f9;">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="fw-bold" style="color: #1a3c5e;">How Investing Works</h3>
      <p class="text-muted">Start building your real estate portfolio in 4 simple steps</p>
    </div>

    <div class="row g-4 text-center">
      <div class="col-lg-3 col-sm-6">
        <div class="step-card p-3">
          <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-key-fill text-primary fs-2"></i>
          </div>
          <h5 class="fw-bold fs-6">1. Sign Up</h5>
          <p class="text-muted small">Create a free account in minutes.</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="step-card p-3">
          <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-wallet-fill text-primary fs-2"></i>
          </div>
          <h5 class="fw-bold fs-6">2. Fund Your Balance</h5>
          <p class="text-muted small">Local & international payment options.</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="step-card p-3">
          <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-house-fill text-primary fs-2"></i>
          </div>
          <h5 class="fw-bold fs-6">3. Choose Projects</h5>
          <p class="text-muted small">Browse global properties to co-own.</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="step-card p-3">
          <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-graph-up-arrow text-primary fs-2"></i>
          </div>
          <h5 class="fw-bold fs-6">4. Earn ROI</h5>
          <p class="text-muted small">Receive rental yields and capital gains.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 6. Investment Projects Preview -->
<section class="py-5" style="background: #ffffff;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <span class="text-primary fw-bold text-uppercase" style="font-size: 0.85rem;">Investment Projects</span>
        <h3 class="fw-bold mb-0" style="color: #1a3c5e;">Explore Our Latest Projects</h3>
      </div>
      <a href="{{ url('/invest') }}" class="btn btn-outline-primary fw-bold btn-sm">View All Projects <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">
      @forelse($projects->take(3) as $proj)
        @php
          $fundedPercent = $proj->fundedPercent();
          $raisedAmount = $proj->raisedAmount();
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="position-relative">
              <img src="{{ $proj->image_url ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000&auto=format&fit=crop' }}" class="card-img-top" alt="{{ $proj->title }}" style="height: 220px; object-fit: cover;">
              <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                <span class="badge bg-primary">{{ $proj->category ?? 'Development' }}</span>
                <span class="badge bg-success">{{ $proj->expected_return_percentage }}% Return</span>
              </div>
            </div>
            <div class="card-body p-4">
              <h5 class="card-title fw-bold" style="color: #1a3c5e;">{{ $proj->title }}</h5>
              <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> {{ $proj->location }}</p>

              <!-- Funding Progress -->
              <div class="mb-3">
                <div class="d-flex justify-content-between small fw-bold mb-1">
                  <span>Funded: {{ $fundedPercent }}%</span>
                  <span class="text-primary">${{ number_format($raisedAmount, 0) }} / ${{ number_format($proj->target_amount, 0) }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-primary" style="width: {{ $fundedPercent }}%;"></div>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <div>
                  <small class="text-muted d-block">Min. Investment</small>
                  <span class="fw-bold text-primary fs-5">${{ number_format($proj->minimum_investment, 2) }}</span>
                </div>
                <a href="{{ route('project.show', $proj) }}" class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-3">Invest Now</a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-4">
          <p class="text-muted">No active investment projects available at the moment.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- 7. Start Building a Better Portfolio Banner -->
<section class="py-5" style="background: linear-gradient(135deg, #1a3c5e 0%, #2756fd 100%);">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7 text-white">
        <h2 class="fw-bold mb-3 display-6 text-white">Start Building a Better Portfolio</h2>
        <ul class="list-unstyled d-flex gap-4 mb-4 flex-wrap">
          <li class="d-flex align-items-center fs-5"><i class="bi bi-check2-circle me-2 text-warning fs-4"></i> Flexible Minimums</li>
          <li class="d-flex align-items-center fs-5"><i class="bi bi-check2-circle me-2 text-warning fs-4"></i> Quarterly Liquidity</li>
        </ul>
        <a href="{{ route('register') }}" class="btn btn-light text-white fw-bold px-4 py-3 rounded-3 shadow">Start Investing</a>
      </div>
      <div class="col-lg-5 text-center">
        <img src="https://radiantdreamrealty.com/frontend/images/banner/banner.png" alt="Portfolio Banner" class="img-fluid rounded-4 shadow-lg" style="max-height: 260px; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- 8. Top Properties (Buy | Sell | Rent) -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="text-primary fw-bold text-uppercase" style="font-size: 0.85rem;">Verified Real Estate Listings</span>
      <h3 class="fw-bold mt-1" style="color: #1a3c5e;">Explore Live Property Listings</h3>
    </div>

    <div class="row g-4 mb-4">
      @forelse($properties as $prop)
        @php
          $propPrice = $prop->purchasePrice();
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden bg-white">
            <div class="position-relative">
              <img src="{{ $prop->image_url ?? 'https://radiantdreamrealty.com/frontend/images/home/house-7.jpg' }}" class="card-img-top" alt="{{ $prop->title }}" style="height: 230px; object-fit: cover;">
              <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                <span class="badge bg-primary">{{ $prop->category }}</span>
                @if($prop->status === 'sold_out')
                  <span class="badge bg-secondary">Sold</span>
                @else
                  <span class="badge bg-success">Verified</span>
                @endif
              </div>
              <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); font-size: 0.85rem;">
                <i class="bi bi-geo-alt me-1"></i> {{ $prop->location }}
              </div>
            </div>
            <div class="card-body p-4">
              <h5 class="fw-bold mb-2" style="color: #1a3c5e;">{{ $prop->title }}</h5>
              <div class="d-flex justify-content-between text-muted small border-bottom pb-3 mb-3">
                <span><i class="bi bi-tag me-1"></i> Price: <b>${{ number_format($propPrice, 2) }}</b></span>
                <span><i class="bi bi-graph-up me-1"></i> ROI: <b class="text-success">{{ $prop->roi_percentage }}%</b></span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Full Purchase</small>
                  <h5 class="fw-bold text-primary mb-0">${{ number_format($propPrice, 2) }}</h5>
                </div>
                <a href="{{ route('property.show', $prop) }}" class="btn btn-sm btn-outline-primary fw-bold px-3 py-2 rounded-3">
                  Buy Now <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-4">
          <p class="text-muted">No properties found in database.</p>
        </div>
      @endforelse
    </div>

    <div class="text-center">
      <a href="{{ url('/properties') }}" class="btn btn-primary fw-bold px-4 py-2" style="background: #2756fd;">Browse Listings <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- 9. Become an Affiliate or Agent & Commission CTA -->
<section class="py-5" style="background: #ffffff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="text-primary fw-bold text-uppercase" style="font-size: 0.85rem;">Our Teams</span>
      <h3 class="fw-bold mt-1" style="color: #1a3c5e;">Become an Affiliate or Agent</h3>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 bg-light">
          <img src="https://radiantdreamrealty.com/frontend/images/affil.jpg" class="card-img-top" alt="Affiliate Program" style="height: 220px; object-fit: cover;">
          <div class="card-body p-4">
            <h5 class="fw-bold" style="color: #1a3c5e;">Global Affiliate</h5>
            <p class="text-muted small">Work from home as a global affiliate, earn 10% - 30% commissions on investment volume.</p>
            <a href="{{ url('/affiliate') }}" class="btn btn-primary btn-sm fw-bold">Become an Affiliate</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 bg-light">
          <img src="https://radiantdreamrealty.com/frontend/images/home/house-7.jpg" class="card-img-top" alt="Agent Program" style="height: 220px; object-fit: cover;">
          <div class="card-body p-4">
            <h5 class="fw-bold" style="color: #1a3c5e;">Verified Agent</h5>
            <p class="text-muted small">Join as a Verified Agent to Sell, Rent and Manage Properties across key global markets.</p>
            <a href="{{ url('/agent') }}" class="btn btn-primary btn-sm fw-bold">Apply as an Agent</a>
          </div>
        </div>
      </div>
    </div>

    <div class="p-4 rounded-4 text-center text-white d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: linear-gradient(90deg, #1a3c5e, #2756fd);">
      <span class="fs-5 fw-bold mb-0">Become an affiliate and get the commission you deserve.</span>
      <a href="{{ url('/affiliate') }}" class="btn btn-light text-primary fw-bold px-4">Get Started</a>
    </div>
  </div>
</section>

<!-- 10. Global Finance Team -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill mb-2">FINANCE & ESCROW</span>
        <h2 class="fw-bold mb-3" style="color: #1a3c5e;">Finance Team – Secure Global Payments</h2>
        <p class="text-muted mb-4" style="font-size: 1.05rem;">
          Our Global Finance Team ensures your deposits, withdrawals, and project earnings are safely processed worldwide via all payment methods.
        </p>

        <ul class="list-unstyled mb-4">
          <li class="mb-2 fs-6 text-dark"><i class="bi bi-shield-check text-primary me-2 fs-5"></i> Escrow-protected transactions</li>
          <li class="mb-2 fs-6 text-dark"><i class="bi bi-currency-exchange text-primary me-2 fs-5"></i> Multi-currency support</li>
          <li class="mb-2 fs-6 text-dark"><i class="bi bi-chat-dots text-primary me-2 fs-5"></i> 24/7 finance chat assistance</li>
        </ul>

        <div class="d-flex gap-3 mb-4">
          <span class="badge bg-white text-dark border p-2 px-3 shadow-sm"><i class="bi bi-bank text-primary me-1"></i> Funds</span>
          <span class="badge bg-white text-dark border p-2 px-3 shadow-sm"><i class="bi bi-lock-fill text-primary me-1"></i> Escrow</span>
          <span class="badge bg-white text-dark border p-2 px-3 shadow-sm"><i class="bi bi-cash-stack text-primary me-1"></i> Payout</span>
        </div>

        <a href="#" class="btn btn-primary fw-bold px-4 py-2" style="background: #2756fd;">Meet the Finance Team</a>
      </div>

      <div class="col-lg-6 text-center">
        <div class="p-4 bg-white rounded-4 shadow-sm border">
          <i class="bi bi-shield-lock-fill text-primary display-1 mb-3"></i>
          <h4 class="fw-bold" style="color: #1a3c5e;">Bank-Grade Escrow Protection</h4>
          <p class="text-muted small">Every investment transaction is audited, verified, and backed by top-tier banking partners globally.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 11. Our Growth Story (Optimized with Graph & Metrics) -->
<section class="py-5" style="background: #ffffff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill mb-2" style="font-size: 0.85rem; letter-spacing: 0.5px;">TRACK RECORD & ANALYTICS</span>
      <h2 class="fw-bold display-6 mb-2" style="color: #1a3c5e; font-weight: 800;">Our Growth Story</h2>
      <p class="text-secondary fs-5 mx-auto" style="max-width: 600px;">Trusted by thousands of investors worldwide with proven, market-leading returns.</p>
    </div>

    <!-- Key Performance Metrics Grid -->
    <div class="row g-3 mb-5">
      <div class="col-lg-3 col-sm-6">
        <div class="p-4 rounded-4 border bg-light text-center h-100 shadow-sm">
          <span class="text-muted fw-bold text-uppercase small d-block mb-1">Total Transacted</span>
          <h3 class="fw-bold text-primary mb-1 display-6">$1.05B</h3>
          <span class="badge bg-success-subtle text-success fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>+18.7% YoY</span>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="p-4 rounded-4 border bg-light text-center h-100 shadow-sm">
          <span class="text-muted fw-bold text-uppercase small d-block mb-1">5-Year CAGR</span>
          <h3 class="fw-bold text-primary mb-1 display-6">49.3%</h3>
          <span class="badge bg-primary-subtle text-primary fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i>Compound Growth</span>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="p-4 rounded-4 border bg-light text-center h-100 shadow-sm">
          <span class="text-muted fw-bold text-uppercase small d-block mb-1">Active Portfolio</span>
          <h3 class="fw-bold text-primary mb-1 display-6">500+</h3>
          <span class="badge bg-info-subtle text-info fw-semibold"><i class="bi bi-building me-1"></i>Global Properties</span>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="p-4 rounded-4 border bg-light text-center h-100 shadow-sm">
          <span class="text-muted fw-bold text-uppercase small d-block mb-1">Investor Payouts</span>
          <h3 class="fw-bold text-primary mb-1 display-6">$240M+</h3>
          <span class="badge bg-warning-subtle text-warning fw-semibold"><i class="bi bi-cash-stack me-1"></i>Distributed ROI</span>
        </div>
      </div>
    </div>

    <!-- Visual Bar Graph Card -->
    <div class="p-4 p-md-5 rounded-4 border shadow-sm mb-4" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 border-bottom pb-3">
        <div>
          <h5 class="fw-bold mb-1" style="color: #1a3c5e;">Annual Transaction Volume ($ Millions)</h5>
          <small class="text-muted">Global real estate transaction growth from 2019 to 2024</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex align-items-center small text-secondary"><span class="rounded-circle bg-primary me-2" style="width: 10px; height: 10px;"></span> Annual Volume</span>
          <span class="d-flex align-items-center small text-secondary"><span class="rounded-circle bg-success me-2" style="width: 10px; height: 10px;"></span> Record Milestone</span>
        </div>
      </div>

      <!-- CSS Visual Chart Container -->
      <div class="chart-container pt-4 pb-2" style="height: 320px; position: relative;">
        <!-- Gridlines Background -->
        <div class="position-absolute w-100 h-100 top-0 start-0 d-flex flex-column justify-content-between pointer-events-none" style="opacity: 0.15;">
          <div class="border-top border-dark w-100"></div>
          <div class="border-top border-dark w-100"></div>
          <div class="border-top border-dark w-100"></div>
          <div class="border-top border-dark w-100"></div>
          <div class="border-top border-dark w-100"></div>
        </div>

        <!-- Columns Row -->
        <div class="d-flex align-items-end justify-content-around h-100 position-relative z-2">
          <!-- 2019 -->
          <div class="text-center flex-fill mx-1 mx-md-3 d-flex flex-column align-items-center h-100 justify-content-end">
            <span class="fw-bold text-secondary mb-2 small">$141M</span>
            <div class="w-100 rounded-top-3 shadow-sm" style="height: 14%; background: linear-gradient(180deg, #93c5fd 0%, #3b82f6 100%); min-height: 25px; transition: all 0.3s ease;"></div>
            <span class="mt-3 fw-bold text-secondary small">2019</span>
          </div>

          <!-- 2020 -->
          <div class="text-center flex-fill mx-1 mx-md-3 d-flex flex-column align-items-center h-100 justify-content-end">
            <span class="fw-bold text-secondary mb-2 small">$93M</span>
            <div class="w-100 rounded-top-3 shadow-sm" style="height: 9%; background: linear-gradient(180deg, #93c5fd 0%, #3b82f6 100%); min-height: 20px; transition: all 0.3s ease;"></div>
            <span class="mt-3 fw-bold text-secondary small">2020</span>
          </div>

          <!-- 2021 -->
          <div class="text-center flex-fill mx-1 mx-md-3 d-flex flex-column align-items-center h-100 justify-content-end">
            <span class="fw-bold text-secondary mb-2 small">$279M</span>
            <div class="w-100 rounded-top-3 shadow-sm" style="height: 27%; background: linear-gradient(180deg, #93c5fd 0%, #3b82f6 100%); transition: all 0.3s ease;"></div>
            <span class="mt-3 fw-bold text-secondary small">2021</span>
          </div>

          <!-- 2022 -->
          <div class="text-center flex-fill mx-1 mx-md-3 d-flex flex-column align-items-center h-100 justify-content-end">
            <span class="fw-bold text-secondary mb-2 small">$765M</span>
            <div class="w-100 rounded-top-3 shadow-sm" style="height: 73%; background: linear-gradient(180deg, #60a5fa 0%, #2563eb 100%); transition: all 0.3s ease;"></div>
            <span class="mt-3 fw-bold text-secondary small">2022</span>
          </div>

          <!-- 2023 -->
          <div class="text-center flex-fill mx-1 mx-md-3 d-flex flex-column align-items-center h-100 justify-content-end">
            <span class="fw-bold text-secondary mb-2 small">$884M</span>
            <div class="w-100 rounded-top-3 shadow-sm" style="height: 84%; background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%); transition: all 0.3s ease;"></div>
            <span class="mt-3 fw-bold text-secondary small">2023</span>
          </div>

          <!-- 2024 Highlighted Bar -->
          <div class="text-center flex-fill mx-1 mx-md-3 d-flex flex-column align-items-center h-100 justify-content-end">
            <span class="badge bg-success mb-2 shadow-sm" style="font-size: 0.85rem;">$1.05B ★</span>
            <div class="w-100 rounded-top-3 shadow-lg" style="height: 100%; background: linear-gradient(180deg, #10b981 0%, #2756fd 100%); border-top: 3px solid #34d399; transition: all 0.3s ease;"></div>
            <span class="mt-3 fw-bold text-primary small">2024</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Highlighted Milestone Summary Box -->
    <div class="p-3 px-4 rounded-pill d-inline-flex align-items-center gap-3 border bg-white shadow-sm">
      <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
      <span class="fw-bold text-dark" style="font-size: 0.95rem;">Over $1B real estate value transacted globally in 2024.</span>
      <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3">Join Network</a>
    </div>
  </div>
</section>

<!-- 12. Success Stories & Verified Member Reviews (Google Integrated) -->
<section class="py-5" style="background: #ffffff;">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold display-5 mb-2" style="color: #111827; font-weight: 800;">Discover What’s Possible</h2>
      <p class="text-secondary fs-5 mb-3">Real investors. Real properties. Real success.</p>
      
      <!-- Google Brand Badge -->
      <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border shadow-sm">
        <span class="fw-bold text-dark me-1">EXCELLENT</span>
        <div class="text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
        <span class="text-muted small">Based on <b>4,368 reviews</b> on</span>
        <span class="fw-bold ms-1" style="font-size: 1.05rem;">
          <span style="color:#4285F4">G</span><span style="color:#EA4335">o</span><span style="color:#FBBC05">o</span><span style="color:#4285F4">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span>
        </span>
      </div>
    </div>

    <!-- Rating Breakdown & Community Stats Row -->
    <div class="row g-4 mb-5 align-items-center justify-content-center">
      <!-- Left: 5-Star Breakdown Chart -->
      <div class="col-lg-5 col-md-6">
        <div class="p-4 rounded-4 border bg-light shadow-sm">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark">4,368 reviews</h6>
            <div class="text-warning small"><i class="bi bi-star-fill me-1"></i><b>4.9 / 5.0</b></div>
          </div>
          <!-- Bars -->
          <div class="d-flex align-items-center gap-2 mb-2 small">
            <span class="text-muted" style="width: 45px;">5 stars</span>
            <div class="progress flex-grow-1" style="height: 8px;">
              <div class="progress-bar bg-success" style="width: 88%;"></div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 mb-2 small">
            <span class="text-muted" style="width: 45px;">4 stars</span>
            <div class="progress flex-grow-1" style="height: 8px;">
              <div class="progress-bar bg-success" style="width: 12%;"></div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 mb-2 small">
            <span class="text-muted" style="width: 45px;">3 stars</span>
            <div class="progress flex-grow-1" style="height: 8px;">
              <div class="progress-bar bg-secondary-subtle" style="width: 0%;"></div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 mb-2 small">
            <span class="text-muted" style="width: 45px;">2 stars</span>
            <div class="progress flex-grow-1" style="height: 8px;">
              <div class="progress-bar bg-secondary-subtle" style="width: 0%;"></div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 small">
            <span class="text-muted" style="width: 45px;">1 star</span>
            <div class="progress flex-grow-1" style="height: 8px;">
              <div class="progress-bar bg-danger" style="width: 1.5%;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: 3 Key Community Stats -->
      <div class="col-lg-7 col-md-6">
        <div class="row g-3">
          <div class="col-sm-4 text-center">
            <div class="p-3 rounded-4 glass-card h-100">
              <i class="bi bi-people-fill text-primary fs-2 mb-2 d-block"></i>
              <h4 class="fw-bold text-dark mb-1">35,000+</h4>
              <small class="text-muted d-block">Active members worldwide</small>
            </div>
          </div>
          <div class="col-sm-4 text-center">
            <div class="p-3 rounded-4 glass-card h-100">
              <i class="bi bi-house-door-fill text-primary fs-2 mb-2 d-block"></i>
              <h4 class="fw-bold text-dark mb-1">850+</h4>
              <small class="text-muted d-block">Properties co-owned</small>
            </div>
          </div>
          <div class="col-sm-4 text-center">
            <div class="p-3 rounded-4 glass-card h-100">
              <i class="bi bi-currency-dollar text-primary fs-2 mb-2 d-block"></i>
              <h4 class="fw-bold text-dark mb-1">$1.95B+</h4>
              <small class="text-muted d-block">Member returns generated</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Verified Members Alert Banner -->
    <div class="p-3 px-4 rounded-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-shield-check text-success fs-4"></i>
        <span class="fw-bold text-success" style="font-size: 1rem;">All reviews are verified members</span>
      </div>
      <div class="d-flex align-items-center gap-1">
        <span class="fw-bold small text-muted me-1">Verified on</span>
        <span class="fw-bold" style="font-size: 1.1rem;">
          <span style="color:#4285F4">G</span><span style="color:#EA4335">o</span><span style="color:#FBBC05">o</span><span style="color:#4285F4">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span>
        </span>
      </div>
    </div>

    <!-- Testimonial Cards Grid -->
    <div class="row g-4">
      <!-- Card 1: Steve Lindberg -->
      <div class="col-lg-4 col-md-6">
        <div class="card border-0 glass-card rounded-4 p-4 h-100 bg-white position-relative">
          <!-- Google Icon Top Right -->
          <div class="position-absolute top-0 end-0 m-3">
            <span class="fw-bold" style="font-size: 1.1rem;">
              <span style="color:#4285F4">G</span><span style="color:#EA4335">o</span><span style="color:#FBBC05">o</span><span style="color:#4285F4">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span>
            </span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #059669; font-size: 1rem;">SL</div>
            <div>
              <h6 class="mb-0 fw-bold text-dark">Steve Lindberg</h6>
              <small class="text-muted" style="font-size: 0.82rem;">United States • Apr 26, 2025 • <span class="text-success fw-semibold"><i class="bi bi-patch-check-fill me-1"></i>Verified</span></small>
            </div>
          </div>

          <!-- Green Rating Stars -->
          <div class="d-flex gap-1 mb-3">
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
          </div>

          <h6 class="fw-bold text-dark mb-2">House is beautiful and ready to go</h6>
          <p class="text-secondary small mb-4" style="line-height: 1.6;">"House is beautiful and ready to go. People are responsive and professional when needed."</p>

          <div class="mt-auto pt-2 text-secondary small">
            <button class="btn btn-sm btn-light border rounded-3 px-3"><i class="bi bi-share me-1"></i> Share</button>
          </div>
        </div>
      </div>

      <!-- Card 2: Serah Sadique -->
      <div class="col-lg-4 col-md-6">
        <div class="card border-0 glass-card rounded-4 p-4 h-100 bg-white position-relative">
          <div class="position-absolute top-0 end-0 m-3">
            <span class="fw-bold" style="font-size: 1.1rem;">
              <span style="color:#4285F4">G</span><span style="color:#EA4335">o</span><span style="color:#FBBC05">o</span><span style="color:#4285F4">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span>
            </span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #15803d; font-size: 1rem;">S</div>
            <div>
              <h6 class="mb-0 fw-bold text-dark">Serah Sadique</h6>
              <small class="text-muted" style="font-size: 0.82rem;">2021-10-29 • <span class="text-success fw-semibold"><i class="bi bi-patch-check-fill me-1"></i>Verified</span></small>
            </div>
          </div>

          <!-- Gold Rating Stars -->
          <div class="d-flex gap-1 mb-3 text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-patch-check-fill text-primary ms-1"></i>
          </div>

          <h6 class="fw-bold text-dark mb-2">Excellent customer service & team</h6>
          <p class="text-secondary small mb-4" style="line-height: 1.6;">"Excellent customer service, friendly team is very well organised, efficient and meets all deadlines; also very resourceful, a wide range of services and advice offered."</p>

          <div class="mt-auto pt-2 text-secondary small">
            <button class="btn btn-sm btn-light border rounded-3 px-3"><i class="bi bi-share me-1"></i> Share</button>
          </div>
        </div>
      </div>

      <!-- Card 3: Jennifer Davies -->
      <div class="col-lg-4 col-md-6">
        <div class="card border-0 glass-card rounded-4 p-4 h-100 bg-white position-relative">
          <div class="position-absolute top-0 end-0 m-3">
            <span class="fw-bold" style="font-size: 1.1rem;">
              <span style="color:#4285F4">G</span><span style="color:#EA4335">o</span><span style="color:#FBBC05">o</span><span style="color:#4285F4">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span>
            </span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #2563eb; font-size: 1rem;">JD</div>
            <div>
              <h6 class="mb-0 fw-bold text-dark">Jennifer Davies</h6>
              <small class="text-muted" style="font-size: 0.82rem;">Canada • Apr 25, 2025 • <span class="text-success fw-semibold"><i class="bi bi-patch-check-fill me-1"></i>Verified</span></small>
            </div>
          </div>

          <!-- Green Star Rating -->
          <div class="d-flex gap-1 mb-3">
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
            <span class="badge p-1 px-2" style="background-color: #10b981;"><i class="bi bi-star-fill text-white"></i></span>
          </div>

          <h6 class="fw-bold text-dark mb-2">Excellent investment opportunity</h6>
          <p class="text-secondary small mb-4" style="line-height: 1.6;">"The team guided me through every step of the investment process. Very professional and transparent with all information."</p>

          <div class="mt-auto pt-2 text-secondary small">
            <button class="btn btn-sm btn-light border rounded-3 px-3"><i class="bi bi-share me-1"></i> Share</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 13. Our Benefits / Why Choose {{ site_name() }} -->
<section class="py-5" style="background: #ffffff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="text-primary fw-bold text-uppercase" style="font-size: 0.85rem;">Our Benefits</span>
      <h2 class="fw-bold mt-1" style="color: #1a3c5e;">Why Choose {{ site_name() }}</h2>
      <p class="text-muted mx-auto" style="max-width: 650px;">Our seasoned team excels in real estate with years of successful market navigation, offering informed decisions and optimal results.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <i class="bi bi-award-fill text-primary display-5 mb-3 d-block"></i>
          <h5 class="fw-bold" style="color: #1a3c5e;">Proven Expertise</h5>
          <p class="text-muted small">Our seasoned team excels in real estate with years of successful market navigation, offering informed decisions and optimal results.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <i class="bi bi-sliders text-primary display-5 mb-3 d-block"></i>
          <h5 class="fw-bold" style="color: #1a3c5e;">Customized Solutions</h5>
          <p class="text-muted small">We pride ourselves on crafting personalized strategies to match your unique goals, ensuring a seamless real estate journey.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <i class="bi bi-handshake-fill text-primary display-5 mb-3 d-block"></i>
          <h5 class="fw-bold" style="color: #1a3c5e;">Transparent Partnerships</h5>
          <p class="text-muted small">Transparency is key in our client relationships. We prioritize clear communication and ethical practices, fostering trust throughout.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 14. Meet Our Agents -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="text-primary fw-bold text-uppercase" style="font-size: 0.85rem;">Our Teams</span>
      <h2 class="fw-bold mt-1" style="color: #1a3c5e;">Meet Our Agents</h2>
    </div>

    <div class="row g-4">
      <!-- Agent 1 -->
      <div class="col-lg-3 col-sm-6">
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm text-center bg-white">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-1.jpg" class="card-img-top" alt="Chris Patt" style="height: 220px; object-fit: cover;">
          <div class="card-body p-3">
            <h6 class="fw-bold mb-1" style="color: #1a3c5e;">Chris Patt</h6>
            <small class="text-muted">Administrative Staff</small>
          </div>
        </div>
      </div>
      <!-- Agent 2 -->
      <div class="col-lg-3 col-sm-6">
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm text-center bg-white">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-2.jpg" class="card-img-top" alt="Esther Howard" style="height: 220px; object-fit: cover;">
          <div class="card-body p-3">
            <h6 class="fw-bold mb-1" style="color: #1a3c5e;">Esther Howard</h6>
            <small class="text-muted">Administrative Staff</small>
          </div>
        </div>
      </div>
      <!-- Agent 3 -->
      <div class="col-lg-3 col-sm-6">
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm text-center bg-white">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-3.jpg" class="card-img-top" alt="Darrell Steward" style="height: 220px; object-fit: cover;">
          <div class="card-body p-3">
            <h6 class="fw-bold mb-1" style="color: #1a3c5e;">Darrell Steward</h6>
            <small class="text-muted">Administrative Staff</small>
          </div>
        </div>
      </div>
      <!-- Agent 4 -->
      <div class="col-lg-3 col-sm-6">
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm text-center bg-white">
          <img src="https://radiantdreamrealty.com/frontend/images/agents/agent-4.jpg" class="card-img-top" alt="Robert Fox" style="height: 220px; object-fit: cover;">
          <div class="card-body p-3">
            <h6 class="fw-bold mb-1" style="color: #1a3c5e;">Robert Fox</h6>
            <small class="text-muted">Administrative Staff</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 15. World-Class Partners Banner -->
<section class="py-4 text-center text-white" style="background: #1a3c5e;">
  <div class="container">
    <h4 class="fw-bold mb-0"><i class="bi bi-shield-check text-warning me-2"></i> $350M+ Backed by World-Class Partners</h4>
  </div>
</section>

<!-- 16. Frequently Asked Questions (FAQ Accordion) -->
<section class="py-5" style="background: #ffffff;">
  <div class="container" style="max-width: 900px;">
    <div class="text-center mb-5">
      <span class="text-primary fw-bold text-uppercase" style="font-size: 0.85rem;">Frequently Asked Questions</span>
      <h2 class="fw-bold mt-1" style="color: #1a3c5e;">Your Questions Answered</h2>
    </div>

    <div class="accordion" id="faqAccordion">
      <!-- FAQ 1 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            What types of properties can I access on Radiant Property Hub?
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            You can access luxury residential villas, vacation homes, commercial office complexes, and pre-vetted co-ownership projects across top global destinations like Spain, Italy, Dubai, US, and the Caribbean.
          </div>
        </div>
      </div>

      <!-- FAQ 2 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Who can join and use the platform?
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            Anyone worldwide can sign up as an investor, buyer, seller, affiliate, or licensed agent, provided they satisfy standard identity verification checks.
          </div>
        </div>
      </div>

      <!-- FAQ 3 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            How are property values determined?
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            Property valuations are established through certified independent appraisals, local market comparability data, and rigorous financial analysis.
          </div>
        </div>
      </div>

      <!-- FAQ 4 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            How do I earn returns from my property activities?
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            Returns are earned through rental yield payouts deposited to your dashboard balance and long-term capital appreciation upon share or property resale.
          </div>
        </div>
      </div>

      <!-- FAQ 5 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingFive">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
            How does Radiant Property Hub make money?
          </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            We charge small, transparent platform management fees and listing/transaction fees.
          </div>
        </div>
      </div>

      <!-- FAQ 6 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingSix">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
            What’s new with Radiant Property Hub?
          </button>
        </h2>
        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            We have recently expanded into 7 new luxury vacation markets including Costa del Sol, Palm Jumeirah, and St. Lucia.
          </div>
        </div>
      </div>

      <!-- FAQ 7 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingSeven">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
            How are funds used when I buy, invest, or co-own?
          </button>
        </h2>
        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            Invested funds are held in secure escrow accounts specifically dedicated to property acquisition, legal registration, and asset management.
          </div>
        </div>
      </div>

      <!-- FAQ 8 -->
      <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header" id="headingEight">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
            How does the Finance Team assist me?
          </button>
        </h2>
        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            Our 24/7 Finance Team manages multi-currency conversions, processes swift payouts, and provides live chat assistance for all your financial queries.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
