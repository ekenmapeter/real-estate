@extends('layouts.main')

@section('title', 'Invest in Projects | radiantdreamrealty')

@section('content')
<style>
    .project-card { transition: all 0.3s ease; }
    .project-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(15,23,42,0.12); }
    .project-action-btn { transition: all 0.2s ease; }
    .project-action-btn:hover { background:#eff6ff !important; color:#1d4ed8 !important; border-color:#93c5fd !important; }
    .save-btn.saved { background:#fdf2f8 !important; color:#db2777 !important; border-color:#f9a8d4 !important; }
</style>

<!-- Invest Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 100px 0 90px;">
  <div class="container relative z-2">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 col-md-12 text-start reveal-on-scroll delay-1">
        <h1 class="title-large text-white mb-3" style="font-size: clamp(2.4rem, 4.5vw, 3.6rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
          INVEST IN<br><span style="color: #fbbf24; text-shadow: 0 0 20px rgba(251,191,36,0.4);">PREMIUM PROJECTS</span>
        </h1>
        <h5 class="text-white-50 fw-bold mb-3" style="font-weight: 700;">High-Yield Real Estate Development Projects.</h5>
        <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.15rem; line-height: 1.65; font-weight: 500;">
          Browse curated real estate development projects. Download project documents, save projects to your list, share them with friends, and invest with flexible amounts — starting from each project's minimum.
        </p>
        <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
          <a href="#projectGrid" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 800;">View Projects</a>
          @auth
            <a href="{{ url('/dashboard') }}#saved_projects" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">My Saved Projects</a>
          @else
            <a href="{{ route('register') }}" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1.05rem; border-radius: 8px; font-weight: 700;">Start Investing</a>
          @endauth
        </div>
      </div>

      <div class="col-lg-6 col-md-12 text-center position-relative reveal-on-scroll delay-2">
        <div class="hero-image-wrapper position-relative d-inline-block">
          <img src="https://radiantdreamrealty.com/frontend/images/home/house-1.jpg" alt="Invest in Projects" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 460px; object-fit: cover; width: 100%;">
          <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 270px;">
            <div class="d-flex align-items-center gap-3 text-start">
              <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="bi bi-graph-up-arrow fs-5"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.95rem;">High Target Returns</h6>
                <small class="text-white-50" style="font-size: 0.8rem;">Earn projected ROI per project</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Search Filter Bar -->
<section class="py-4 border-bottom glass-panel">
  <div class="container">
    <form action="{{ url('/invest') }}" method="GET" class="row g-3 align-items-center">
      <div class="col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by project title, location or keyword...">
        </div>
      </div>
      <div class="col-md-4">
        <select name="category" class="form-select">
          <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
          <option value="Residential" {{ request('category') == 'Residential' ? 'selected' : '' }}>Residential</option>
          <option value="Commercial" {{ request('category') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
          <option value="Mixed-Use" {{ request('category') == 'Mixed-Use' ? 'selected' : '' }}>Mixed-Use</option>
          <option value="Infrastructure" {{ request('category') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-warning w-100 fw-bold py-2 text-dark" style="background-color: #fbbf24; border:none;">Filter Projects</button>
      </div>
    </form>
  </div>
</section>

<!-- Projects Grid -->
<section id="projectGrid" class="py-5" style="background-color: #f8fafc;">
  <div class="container">
    <div class="row g-4">
      @forelse($projects as $project)
        @php
          $raised = $project->raisedAmount();
          $funded = $project->fundedPercent();
          $isSaved = in_array($project->id, $savedProjectIds);
          $isActive = $project->isActiveWindow();
          $endsAtTs = $project->endsAt() ? $project->endsAt()->timestamp : 0;
          $statusBadge = [
              'active' => ['bg-success', 'Ongoing'],
              'completed' => ['bg-primary', 'Completed'],
              'closed' => ['bg-secondary', 'Closed'],
          ];
          $statusCls = $statusBadge[$project->status] ?? ['bg-secondary', ucfirst($project->status)];
          $rating = (float) $project->rating;
        @endphp
        <div class="col-lg-4 col-md-6 reveal-on-scroll delay-1">
          <div class="card h-100 border-0 glass-card rounded-4 overflow-hidden project-card">
            <div class="position-relative">
              <img src="{{ $project->image_url ?? 'https://radiantdreamrealty.com/frontend/images/home/house-1.jpg' }}" class="card-img-top" alt="{{ $project->title }}" style="height: 220px; object-fit: cover;">
              <div class="position-absolute top-0 start-0 m-3 d-flex gap-2 flex-wrap">
                <span class="badge {{ $statusCls[0] }} fw-bold">{{ $statusCls[1] }}</span>
                <span class="badge bg-warning text-dark fw-bold">{{ $project->category }}</span>
                <span class="badge bg-dark text-warning fw-bold">{{ $project->expected_return_percentage }}% Return</span>
              </div>
              @auth
                <form action="{{ route('project.save', $project) }}" method="POST" class="js-save-project position-absolute top-0 end-0 m-2">
                  @csrf
                  <button type="submit" class="btn btn-sm rounded-circle border-0 shadow-sm save-btn {{ $isSaved ? 'saved' : 'bg-white' }}" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;" title="{{ $isSaved ? 'Remove from saved' : 'Save project' }}">
                    <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}" style="font-size:1rem;"></i>
                  </button>
                </form>
              @else
                <a href="{{ route('login') }}" class="btn btn-sm bg-white rounded-circle border-0 shadow-sm position-absolute top-0 end-0 m-2" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;" title="Sign in to save">
                  <i class="bi bi-bookmark" style="font-size:1rem;"></i>
                </a>
              @endauth
              <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.75)); font-size: 0.85rem;">
                <i class="bi bi-geo-alt me-1"></i> {{ $project->location }}
              </div>
            </div>
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <h5 class="fw-bold mb-0" style="color: #1a3c5e;">{{ $project->title }}</h5>
              </div>

              <!-- Rating + Duration row -->
              <div class="d-flex align-items-center justify-content-between small text-muted mb-2">
                <span class="position-relative d-inline-flex align-items-center gap-1" style="white-space:nowrap;" title="{{ $rating }} / 5 rating">
                  <span class="d-inline-flex gap-1 text-muted">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </span>
                  <span class="position-absolute top-0 start-0 d-inline-flex gap-1 overflow-hidden" style="width:{{ $project->ratingWidth() }}%; color:#f59e0b;">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </span>
                  <b class="text-dark">{{ number_format($rating, 1) }}</b>
                </span>
                <span><i class="bi bi-clock-history me-1" style="color:#f59e0b;"></i> <b class="text-dark">{{ $project->investment_duration_months }} mos</b> duration</span>
              </div>

              <p class="text-muted small mb-3">{{ Str::limit($project->description, 90) }}</p>

              <div class="mb-3">
                <div class="d-flex justify-content-between small fw-bold mb-1">
                  <span>Funded: {{ $funded }}%</span>
                  <span class="text-primary">${{ number_format($raised, 0) }} / ${{ number_format($project->target_amount, 0) }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-warning" style="width: {{ $funded }}%;"></div>
                </div>
              </div>

              <!-- Countdown -->
              @if($isActive && $endsAtTs > 0)
                <div class="rounded-3 p-2 px-3 mb-3 d-flex align-items-center gap-2" style="background:#fffbeb; border:1px solid #fde68a;">
                  <i class="bi bi-hourglass-split" style="color:#b45309;"></i>
                  <small class="fw-bold text-muted">Funding ends in</small>
                  <span class="ms-auto fw-bold text-danger" data-countdown-ends="{{ $endsAtTs }}">--</span>
                </div>
              @endif

              <div class="d-flex justify-content-between text-muted small border-bottom pb-3 mb-3">
                <span><i class="bi bi-currency-dollar me-1"></i> Min: <b class="text-dark">${{ number_format($project->minimum_investment, 0) }}</b></span>
                <span><i class="bi bi-graph-up-arrow me-1"></i> Return: <b class="text-success">{{ $project->expected_return_percentage }}%</b></span>
                <span><i class="bi bi-calendar-check me-1"></i> {{ $project->investment_duration_months }} Mos</span>
              </div>

              <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('project.show', $project) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill project-action-btn">
                  <i class="bi bi-info-circle me-1"></i> More Info
                </a>
                @if($project->document_path)
                  <a href="{{ route('project.download', $project) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill project-action-btn">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Document
                  </a>
                @endif
                <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill project-action-btn" onclick="shareContent('{{ $project->title }}', '{{ route('project.show', $project) }}', 'Invest in this project')">
                  <i class="bi bi-share me-1"></i> Share
                </button>
              </div>

              @if($isActive)
                <a href="{{ route('project.show', $project) }}" class="btn btn-warning text-dark fw-bold w-100 py-2 rounded-3 shadow-sm">
                  <i class="bi bi-lightning-charge-fill me-1"></i> Invest Now
                </a>
              @else
                <button class="btn btn-secondary text-white fw-bold w-100 py-2 rounded-3" disabled>
                  <i class="bi bi-lock-fill me-1"></i> {{ $project->status === 'completed' ? 'Fully Completed' : 'Investments Closed' }}
                </button>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-4 bg-white rounded-4 shadow-sm d-inline-block" style="max-width:400px;">
            <i class="bi bi-rocket-takeoff fs-1 text-muted d-block mb-2"></i>
            <h5 class="fw-bold text-dark">No Active Projects</h5>
            <p class="text-muted small mb-3">Try adjusting your search keywords or category filters.</p>
            <a href="{{ url('/invest') }}" class="btn btn-outline-primary btn-sm fw-bold">Reset Filters</a>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<script>
    function shareProject(title, url) {
        shareContent(title, url, 'Invest in this project');
    }
</script>
@endsection
