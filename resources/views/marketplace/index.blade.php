@extends('layouts.main')

@section('title', 'Project Marketplace | ' . site_name())

@section('content')
<style>
    .project-card { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid transparent; }
    .project-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12); border-color: rgba(37, 99, 235, 0.25); }
    .project-card img { transition: transform 0.5s ease; }
    .project-card:hover img { transform: scale(1.04); }
    .countdown-badge { animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.85; } }
    .duration-pill { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
</style>

<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="row g-0">
        
        <!-- Left Sidebar Column (Matching Mockup Image 1) -->
        <div class="col-12 col-md-4 col-lg-3 d-none d-md-block">
            @include('partials.app-sidebar')
        </div>

        <!-- Right Main Workspace Content Area -->
        <div class="col-12 col-md-8 col-lg-9 p-3 p-md-4">
            
            <!-- Marketplace Top Row: Title, Subtitle & User Profile Badge -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Project Marketplace</h1>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">Browse active real estate projects and buy shares to start earning.</p>
                </div>
                
              
            </div>

            <!-- Marketplace Navigation Tabs Row (Matching Mockup Image 1) -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm" style="background: #2563eb; border: none;">
                    Marketplace
                </a>
                @auth
                <a href="{{ route('portfolio.index') }}" class="btn btn-light border fw-semibold px-3 py-2 rounded-3 text-secondary bg-white">
                    My Project Shares
                </a>
                <a href="{{ route('portfolio.index') }}#active" class="btn btn-light border fw-semibold px-3 py-2 rounded-3 text-secondary bg-white">
                    Active Cycles
                </a>
                <a href="{{ route('portfolio.index') }}#saved" class="btn btn-light border fw-semibold px-3 py-2 rounded-3 text-secondary bg-white">
                    Saved Projects
                </a>
                <a href="{{ route('portfolio.index') }}#completed" class="btn btn-light border fw-semibold px-3 py-2 rounded-3 text-secondary bg-white">
                    Completed Cycles
                </a>
                @endauth
            </div>

            <!-- How Project Shares Work Banner -->
            <div class="card border-0 rounded-4 text-white mb-4 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%);">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 text-white"><i class="bi bi-info-circle me-2" style="color: #60a5fa;"></i> How Project Shares Work</h5>
                        <button class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">Learn More</button>
                    </div>
                    
                    <div class="row text-center g-3 position-relative">
                        @php
                            $steps = [
                                ['icon' => 'bi-person-fill', 'title' => '1. Choose Project', 'desc' => 'Pick a project you like from the marketplace.', 'color' => '#60a5fa'],
                                ['icon' => 'bi-wallet2', 'title' => '2. Buy Shares', 'desc' => 'Buy shares using your AVC balance.', 'color' => '#34d399'],
                                ['icon' => 'bi-clipboard-check-fill', 'title' => '3. Activate Shares', 'desc' => 'Meet the required shares to activate earnings.', 'color' => '#fbbf24'],
                                ['icon' => 'bi-graph-up-arrow', 'title' => '4. Earn & Receive', 'desc' => 'Earn from your selected duration and receive payout.', 'color' => '#f472b6'],
                            ];
                        @endphp
                        @foreach($steps as $i => $step)
                        <div class="col-6 col-md-3">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-white mb-3 d-flex align-items-center justify-content-center shadow" style="width: 56px; height: 56px;">
                                    <i class="bi {{ $step['icon'] }} fs-4" style="color: {{ $step['color'] }};"></i>
                                </div>
                                <h6 class="fw-bold mb-1 text-white" style="font-size: 0.85rem;">{{ $step['title'] }}</h6>
                                <p class="text-white-50 small mb-0" style="font-size: 0.75rem;">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
                <div class="card-body p-3">
                    <form action="{{ route('marketplace.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search projects, location..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select text-secondary" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Funding</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="category" class="form-select text-secondary" onchange="this.form.submit()">
                                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                                <option value="Residential" {{ request('category') == 'Residential' ? 'selected' : '' }}>Residential</option>
                                <option value="Commercial" {{ request('category') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="Mixed-Use" {{ request('category') == 'Mixed-Use' ? 'selected' : '' }}>Mixed-Use</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="sort" class="form-select text-secondary" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort By: Newest</option>
                                <option value="return_high" {{ request('sort') == 'return_high' ? 'selected' : '' }}>Highest Return</option>
                                <option value="funding_high" {{ request('sort') == 'funding_high' ? 'selected' : '' }}>Highest Funding</option>
                                <option value="closing_soon" {{ request('sort') == 'closing_soon' ? 'selected' : '' }}>Closing Soon</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2 d-grid">
                            <button type="submit" class="btn btn-outline-secondary fw-semibold rounded-3">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Project Marketplace Cards Grid -->
            <div class="row g-4 mb-4">
                @forelse($projects as $project)
                    @php
                        $tiers = $project->getAvailableTiers();
                        $firstTier = $tiers->first() ?? $tiers[0] ?? null;
                        $sharePrice = (float) ($project->share_price ?: 100);
                        $minShares = $firstTier ? (int) $firstTier->required_shares : 10;
                        $minAVC = $sharePrice * $minShares;
                    @endphp
                    <div class="col-12 col-md-6 col-xl-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden project-card bg-white">
                            <!-- Project Image -->
                            <div class="position-relative" style="height: 220px; overflow: hidden; background-color: #0f172a;">
                                <img src="{{ $project->image_url ?: asset('images/property-placeholder.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $project->title }}">
                                
                                <!-- Target Earnings Tag -->
                                <div class="position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-size: 0.82rem;">
                                    {{ number_format($project->expected_return_percentage, 2) }}% Target Earnings
                                </div>

                                <!-- Save Bookmark Button -->
                                @auth
                                <button type="button" class="btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-3 shadow-sm d-flex align-items-center justify-content-center save-btn" style="width: 36px; height: 36px;" data-project-id="{{ $project->id }}" data-uuid="{{ $project->uuid }}">
                                    <i class="bi {{ in_array($project->id, $savedProjectIds) ? 'bi-bookmark-fill text-primary' : 'bi-bookmark text-secondary' }}"></i>
                                </button>
                                @endauth

                                <!-- Status & Category Badges -->
                                <div class="position-absolute bottom-0 start-0 m-3 d-flex gap-2">
                                    <span class="badge bg-success px-2 py-1 rounded-2 fw-semibold" style="font-size: 0.72rem;"><i class="bi bi-check-circle-fill me-1"></i> {{ $project->statusLabel() }}</span>
                                    <span class="badge bg-dark bg-opacity-75 px-2 py-1 rounded-2 fw-semibold" style="font-size: 0.72rem;">{{ $project->category }}</span>
                                </div>
                            </div>

                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Project Title & Rating -->
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div style="max-width: 70%;">
                                            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">{{ $project->title }}</h5>
                                            <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $project->location }}</p>
                                        </div>
                                        <div class="text-end flex-shrink-0">
                                            <div class="text-warning small mb-0">
                                                <i class="bi bi-star-fill"></i>
                                                <span class="fw-bold text-dark">{{ $project->averageRating() }}</span>
                                                <span class="text-muted">({{ $project->reviewCount() }})</span>
                                            </div>
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> {{ $project->investment_duration_months }} mos</span>
                                        </div>
                                    </div>

                                    <hr class="my-2 text-muted opacity-25">

                                    <!-- Share Info -->
                                    <div class="row g-2 mb-3 align-items-center">
                                        <div class="col-6">
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Share Price</span>
                                            <span class="fw-bold text-primary" style="font-size: 1.1rem;">${{ number_format($sharePrice, 2) }}</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Funding Target</span>
                                            <span class="fw-semibold text-dark small">${{ number_format($project->target_amount, 0) }}</span>
                                        </div>
                                    </div>

                                    <!-- Available Durations Pills -->
                                    <div class="d-flex gap-1 mb-3">
                                        @foreach($tiers as $tier)
                                            <span class="duration-pill">{{ $tier->duration_label }}</span>
                                        @endforeach
                                    </div>

                                    <!-- Funding Progress -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between text-muted small mb-1">
                                            <span>Funding</span>
                                            <span class="fw-bold text-dark">${{ number_format($project->raisedAmount(), 0) }} / ${{ number_format($project->target_amount, 0) }} ({{ $project->fundedPercent() }}%)</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height: 7px;">
                                            <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ min(100, $project->fundedPercent()) }}%; background: linear-gradient(90deg, #2563eb, #7c3aed);"></div>
                                        </div>
                                    </div>

                                    <!-- Countdown Timer -->
                                    <div class="rounded-3 p-2 text-center mb-3 countdown-badge" style="background-color: #fffbeb; border: 1px solid #fef3c7;">
                                        <span class="fw-medium small" style="color: #92400e;">
                                            <i class="bi bi-hourglass-split me-1 text-warning"></i> Funding closes in <strong>{{ $project->remainingDaysFormatted() }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 mt-1">
                                    <a href="{{ route('marketplace.show', $project) }}" class="btn btn-outline-primary fw-semibold flex-fill py-2 rounded-3" style="font-size: 0.85rem;">
                                        <i class="bi bi-info-circle me-1"></i> More Info
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary fw-semibold py-2 rounded-3" onclick="shareProject('{{ $project->title }}', '{{ route('marketplace.show', $project) }}')" style="font-size: 0.85rem;">
                                        <i class="bi bi-share"></i>
                                    </button>
                                    <a href="{{ route('marketplace.show', $project) }}#buy-panel" class="btn btn-primary fw-bold flex-fill py-2 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; font-size: 0.85rem;">
                                        <i class="bi bi-lightning-charge-fill me-1"></i> Buy Shares
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-building-exclamation text-muted display-4 mb-3"></i>
                            <h4 class="fw-bold text-dark">No Projects Available</h4>
                            <p class="text-muted">No property projects match your search criteria at this time. Please check back later.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Bottom Info Alert Banner -->
            <div class="card border-0 rounded-4 shadow-sm bg-white p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Important Information</h6>
                            <p class="text-muted small mb-0">Earnings start once your shares are activated. Choose 14 days, 1 month, or 3 months duration based on your preference. You can buy more shares anytime while the project is open.</p>
                        </div>
                    </div>
                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary fw-semibold px-4 rounded-3 text-nowrap shadow-sm" style="background: #2563eb; border: none;">View How It Works</a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Save/Unsave Project Toggle
document.querySelectorAll('.save-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const uuid = this.dataset.uuid;
        const icon = this.querySelector('i');
        fetch('/project-marketplace/' + uuid + '/save', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.saved) {
                icon.className = 'bi bi-bookmark-fill text-primary';
            } else {
                icon.className = 'bi bi-bookmark text-secondary';
            }
        })
        .catch(() => {
            window.location.href = '{{ route("login") }}';
        });
    });
});

function shareProject(title, url) {
    if (navigator.share) {
        navigator.share({ title: title, url: url });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Project link copied to clipboard!');
        });
    }
}
</script>
@endsection
