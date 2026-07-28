@extends('layouts.main')

@section('title', 'List Your Property | Radiant Dream Realty')

@section('content')
<!-- List Property Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          LIST YOUR PROPERTY<br><span style="color: #34d399; text-shadow: 0 0 20px rgba(52,211,153,0.4);">REACH 35,000+ BUYERS</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Market Your Real Estate to Global Investors Worldwide.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          List luxury homes, villas, or commercial properties with Radiant Dream Realty. Connect directly to verified buyers and co-ownership syndicates.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#submitForm" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Submit Listing</a>
          <a href="{{ url('/agent') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Become Agent</a>
        </div>
      </div>

      <!-- Right Column: Hero Image Showcase -->
      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/home/house-8.jpg" alt="List Property Showcase" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 460px; object-fit: cover; width: 100%;">
          
          <!-- Floating Glassmorphism Overlay Card -->
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-rocket-takeoff-fill fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">Fast Listing Approval</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">Reviewed within 24 hours</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Form Section -->
<section id="submitForm" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-5 justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 glass-card rounded-4 p-4 p-md-5">
          <h4 class="fw-bold mb-4" style="color: #1a3c5e;">Property Submission Form</h4>

          <form action="#" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Property Title</label>
                <input type="text" class="form-control" placeholder="e.g. Oceanfront Luxury Villa" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Property Type</label>
                <select class="form-select" required>
                  <option value="">Select Type</option>
                  <option value="villa">Luxury Villa</option>
                  <option value="apartment">Apartment / Condo</option>
                  <option value="commercial">Commercial Space</option>
                  <option value="land">Land Development</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Listing Price ($)</label>
                <input type="number" class="form-control" placeholder="e.g. 750000" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Location (City, Country)</label>
                <input type="text" class="form-control" placeholder="e.g. Costa del Sol, Spain" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Description</label>
                <textarea class="form-control" rows="4" placeholder="Detail the property features, bedrooms, amenities, and unique highlights..."></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary fw-bold px-4 py-3 w-100" style="background-color: #2756fd;">Submit Property Listing</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
