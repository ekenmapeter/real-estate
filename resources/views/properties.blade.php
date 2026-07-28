@extends('layouts.main')

@section('title', 'Properties for Sale & Rent | Radiant Dream Realty')

@section('content')
<!-- Properties Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <!-- Left Column: Content -->
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          EXPLORE LUXURY<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">HOMES & ESTATES</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">Buy, Rent, or Co-Own Prime Global Real Estate.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Browse verified residential estates, beachfront villas, and high-yield commercial investment properties across 5 continents with full legal transparency.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#propertySearch" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">Browse Listings</a>
          <a href="{{ url('/list-property') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">List Property</a>
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
    <form class="row g-3 align-items-center">
      <div class="col-md-3">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control" placeholder="Search by location, city or keyword...">
        </div>
      </div>
      <div class="col-md-3">
        <select class="form-select">
          <option value="">All Categories</option>
          <option value="sale">For Sale</option>
          <option value="rent">For Rent</option>
          <option value="co-ownership">Co-Ownership</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select">
          <option value="">Price Range</option>
          <option value="1">$100k - $500k</option>
          <option value="2">$500k - $1M</option>
          <option value="3">$1M - $5M+</option>
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
      <!-- Property 1 -->
      <div class="col-lg-4 col-md-6 reveal-on-scroll delay-1">
        <div class="card h-100 border-0 glass-card rounded-4 overflow-hidden">
          <div class="position-relative">
            <img src="https://radiantdreamrealty.com/frontend/images/home/house-7.jpg" class="card-img-top" alt="Silverwood Estate" style="height: 230px; object-fit: cover;">
            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
              <span class="badge bg-primary">Featured</span>
              <span class="badge bg-success">For Sale</span>
            </div>
          </div>
          <div class="card-body p-4">
            <h5 class="fw-bold mb-2" style="color: #1a3c5e;">Silverwood Modern Estate</h5>
            <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> 145 Brooklyn Ave, California, USA</p>
            <div class="d-flex justify-content-between text-muted small border-bottom pb-3 mb-3">
              <span><i class="bi bi-door-closed me-1"></i> 4 Beds</span>
              <span><i class="bi bi-droplet me-1"></i> 3 Baths</span>
              <span><i class="bi bi-aspect-ratio me-1"></i> 3,200 Sqft</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="fw-bold text-primary mb-0">$1,250,000</h5>
              <a href="#" class="btn btn-sm btn-outline-primary fw-bold">View Details</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Property 2 -->
      <div class="col-lg-4 col-md-6 reveal-on-scroll delay-2">
        <div class="card h-100 border-0 glass-card rounded-4 overflow-hidden">
          <div class="position-relative">
            <img src="https://radiantdreamrealty.com/frontend/images/home/house-8.jpg" class="card-img-top" alt="Willowbrook Estate" style="height: 230px; object-fit: cover;">
            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
              <span class="badge bg-primary">Featured</span>
              <span class="badge bg-info text-white">For Rent</span>
            </div>
          </div>
          <div class="card-body p-4">
            <h5 class="fw-bold mb-2" style="color: #1a3c5e;">Willowbrook Family Estate</h5>
            <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> 298 Willow Creek Dr, Charlotte, NC</p>
            <div class="d-flex justify-content-between text-muted small border-bottom pb-3 mb-3">
              <span><i class="bi bi-door-closed me-1"></i> 4 Beds</span>
              <span><i class="bi bi-droplet me-1"></i> 3 Baths</span>
              <span><i class="bi bi-aspect-ratio me-1"></i> 2,450 Sqft</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="fw-bold text-primary mb-0">$3,450 / mo</h5>
              <a href="#" class="btn btn-sm btn-outline-primary fw-bold">View Details</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Property 3 -->
      <div class="col-lg-4 col-md-6 reveal-on-scroll delay-3">
        <div class="card h-100 border-0 glass-card rounded-4 overflow-hidden">
          <div class="position-relative">
            <img src="https://radiantdreamrealty.com/frontend/images/home/house-9.jpg" class="card-img-top" alt="Villa Azul del Mar" style="height: 230px; object-fit: cover;">
            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
              <span class="badge bg-primary">Featured</span>
              <span class="badge bg-success">For Sale</span>
            </div>
          </div>
          <div class="card-body p-4">
            <h5 class="fw-bold mb-2" style="color: #1a3c5e;">Villa Azul del Mar</h5>
            <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> 88 Palm Drive, Miami Beach, FL</p>
            <div class="d-flex justify-content-between text-muted small border-bottom pb-3 mb-3">
              <span><i class="bi bi-door-closed me-1"></i> 5 Beds</span>
              <span><i class="bi bi-droplet me-1"></i> 6 Baths</span>
              <span><i class="bi bi-aspect-ratio me-1"></i> 6,450 Sqft</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="fw-bold text-primary mb-0">$4,950,000</h5>
              <a href="#" class="btn btn-sm btn-outline-primary fw-bold">View Details</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
