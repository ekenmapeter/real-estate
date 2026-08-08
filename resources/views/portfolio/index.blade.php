@extends('layouts.main')

@section('title', 'My Portfolio | ' . site_name())

@section('content')
<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="row g-0">
        
        <!-- Left Sidebar Column (Matching Mockup Image 3) -->
        <div class="col-12 col-md-4 col-lg-3 d-none d-md-block">
            <div class="sticky-top" style="top:70px; height:calc(100vh - 70px);">
    @include('partials.navy-sidebar')
</div>
@section('footer')<!-- suppressed -->@endsection
        </div>

        <!-- Right Main Workspace Content Area -->
        <div class="col-12 col-md-8 col-lg-9 p-3 p-md-4">
            
            <!-- Portfolio Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">My Portfolio</h2>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">Manage all your project shares, active cycles, pending activations and completed earnings.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('project-earnings.index') }}" class="btn btn-outline-primary fw-semibold px-4 py-2 rounded-3">
                        <i class="bi bi-cash-coin me-1"></i> Project Earnings
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary fw-semibold px-4 py-2 rounded-3 shadow-sm" style="background: #2563eb; border: none;">
                        <i class="bi bi-cart-plus me-1"></i> Browse Marketplace
                    </a>
                </div>
            </div>

            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Portfolio Header Tabs -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill gap-2" id="portfolioTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-2 rounded-3" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2 rounded-3" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-cycles" type="button" role="tab">Active Cycles ({{ $activeCycles->count() }})</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2 rounded-3" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-cycles" type="button" role="tab">Pending Activation ({{ $pendingCycles->count() }})</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2 rounded-3" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-cycles" type="button" role="tab">Completed Cycles ({{ $completedCycles->count() }})</button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 6 KPI Summary Cards Grid (Matching Mockup Image 3) -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Total Share Value</span>
                        <h5 class="fw-bold text-dark mb-0">{{ number_format($totalShareValue, 0) }} AVC</h5>
                        <span class="text-muted small" style="font-size: 0.72rem;">≈ ${{ number_format($totalShareValue, 2) }} USD</span>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success p-2 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Active Share Value</span>
                        <h5 class="fw-bold text-dark mb-0">{{ number_format($activeShareValue, 0) }} AVC</h5>
                        <span class="text-muted small" style="font-size: 0.72rem;">≈ ${{ number_format($activeShareValue, 2) }} USD</span>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-2 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Pending Activation</span>
                        <h5 class="fw-bold text-dark mb-0">{{ number_format($pendingShareValue, 0) }} AVC</h5>
                        <span class="text-muted small" style="font-size: 0.72rem;">≈ ${{ number_format($pendingShareValue, 2) }} USD</span>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                        <div class="rounded-3 p-2 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(147, 51, 234, 0.1); color: #9333ea;">
                            <i class="bi bi-lightning-charge fs-5"></i>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Projected Earnings</span>
                        <h5 class="fw-bold text-success mb-0">+{{ number_format($projectedEarnings, 0) }} AVC</h5>
                        <span class="text-muted small" style="font-size: 0.72rem;">≈ ${{ number_format($projectedEarnings, 2) }} USD</span>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                        <div class="rounded-3 bg-info bg-opacity-10 text-info p-2 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-piggy-bank fs-5"></i>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Earnings Received</span>
                        <h5 class="fw-bold text-dark mb-0">{{ number_format($earningsReceived, 0) }} AVC</h5>
                        <span class="text-muted small" style="font-size: 0.72rem;">≈ ${{ number_format($earningsReceived, 2) }} USD</span>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                        <div class="rounded-3 bg-secondary bg-opacity-10 text-secondary p-2 mb-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-buildings fs-5"></i>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Active Projects</span>
                        <h5 class="fw-bold text-dark mb-0">{{ $activeProjectsCount }}</h5>
                        <span class="text-muted small" style="font-size: 0.72rem;">Projects Funded</span>
                    </div>
                </div>
            </div>

            <!-- Information Banner Callout -->
            <div class="alert alert-primary border-0 rounded-4 shadow-sm bg-primary bg-opacity-10 text-primary mb-4 p-3 d-flex align-items-center gap-3">
                <i class="bi bi-info-circle-fill fs-4 flex-shrink-0"></i>
                <span class="small">Projects stay open for funding up to their set funding window. You can buy shares anytime while the project is open and start earning once you meet the required shares for your selected duration.</span>
            </div>

            <!-- Tab Content Area -->
            <div class="tab-content" id="portfolioTabsContent">
                
                <!-- OVERVIEW & ACTIVE CYCLES TAB -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    
                    <!-- SECTION 1: Active Cycles Cards List -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Active Cycles</h5>
                            <p class="text-muted small mb-0">Projects where your shares are activated and currently earning.</p>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        @forelse($activeCycles as $cycle)
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden p-3 p-md-4">
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-3">
                                            <div class="position-relative rounded-3 overflow-hidden" style="height: 160px;">
                                                <img src="{{ $cycle->project->image_url ?: asset('images/property-placeholder.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $cycle->project->title }}">
                                                <span class="badge bg-success position-absolute top-0 start-0 m-2 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Verified</span>
                                                <span class="badge bg-primary position-absolute bottom-0 start-0 m-2 px-2 py-1">Active Cycle</span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-9">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-2 gap-2">
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-0">{{ $cycle->project->title }}</h5>
                                                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $cycle->project->location }}</p>
                                                </div>
                                                <div class="badge bg-light text-dark border px-3 py-2 rounded-3 text-start">
                                                    <span class="text-muted small d-block">Selected Duration</span>
                                                    <strong class="text-primary"><i class="bi bi-clock me-1"></i> {{ $cycle->duration_label }}</strong>
                                                </div>
                                            </div>

                                            <!-- Cycle Metrics Bar -->
                                            <div class="row g-2 text-center bg-light p-2 rounded-3 mb-3">
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Shares Owned</span>
                                                    <span class="fw-bold text-dark">{{ $cycle->shares_owned }} Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Active Shares</span>
                                                    <span class="fw-bold text-success">{{ $cycle->shares_owned }} Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Pending Shares</span>
                                                    <span class="fw-bold text-muted">0 Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Share Value</span>
                                                    <span class="fw-bold text-primary">{{ number_format($cycle->total_purchase_amount, 0) }} AVC</span>
                                                </div>
                                            </div>

                                            <!-- Cycle Progress Bar -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between small text-muted mb-1">
                                                    <span>Cycle Progress: <strong>Day {{ $cycle->cycleDaysPassed() }} of {{ $cycle->duration_days }}</strong></span>
                                                    <span class="fw-bold text-primary">{{ $cycle->cycleProgressPercent() }}%</span>
                                                </div>
                                                <div class="progress rounded-pill mb-2" style="height: 10px;">
                                                    <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: {{ $cycle->cycleProgressPercent() }}%;"></div>
                                                </div>
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">Current Projected Earnings: <strong class="text-success">+{{ number_format($cycle->projected_earnings, 0) }} AVC</strong></span>
                                                    <span class="text-muted">Completion Value: <strong class="text-dark">{{ number_format($cycle->completion_value, 0) }} AVC</strong></span>
                                                </div>
                                            </div>

                                            <!-- Timeline & Actions -->
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 border-top gap-3">
                                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                                    <span><i class="bi bi-calendar-event me-1"></i> Purchased: <strong>{{ $cycle->purchased_at ? $cycle->purchased_at->format('M d, Y') : 'N/A' }}</strong></span>
                                                    <span><i class="bi bi-lightning-charge me-1"></i> Activated: <strong>{{ $cycle->activated_at ? $cycle->activated_at->format('M d, Y') : 'N/A' }}</strong></span>
                                                    <span><i class="bi bi-flag me-1"></i> Complete On: <strong>{{ $cycle->completion_date ? $cycle->completion_date->format('M d, Y') : 'N/A' }}</strong></span>
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> {{ $cycle->cycleDaysRemaining() }} Days Remaining</span>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('marketplace.show', $cycle->project) }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3">View Project</a>
                                                    <a href="{{ route('marketplace.show', $cycle->project) }}#buy-panel" class="btn btn-outline-primary btn-sm fw-semibold rounded-3">Buy More Shares</a>
                                                    <a href="{{ route('project-earnings.index', ['project' => $cycle->project->uuid]) }}" class="btn btn-outline-success btn-sm fw-semibold rounded-3"><i class="bi bi-cash-coin me-1"></i> View Earnings</a>
                                                    <a href="{{ route('portfolio.receipt', $cycle) }}" class="btn btn-primary btn-sm fw-semibold rounded-3" style="background: #2563eb;">Cycle Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center">
                                    <i class="bi bi-check-circle text-muted display-4 mb-3"></i>
                                    <h5 class="fw-bold text-dark">No Active Cycles</h5>
                                    <p class="text-muted">You do not have any active earning cycles right now.</p>
                                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary fw-semibold mx-auto px-4 rounded-3" style="background: #2563eb;">Browse Projects</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- SECTION 2: Pending Activation Cards List -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Pending Activation</h5>
                            <p class="text-muted small mb-0">Your shares are not yet activated. Buy more shares to meet the minimum requirement.</p>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        @forelse($pendingCycles as $cycle)
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden p-3 p-md-4 border-start border-warning border-4">
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-3">
                                            <div class="position-relative rounded-3 overflow-hidden" style="height: 140px;">
                                                <img src="{{ $cycle->project->image_url ?: asset('images/property-placeholder.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $cycle->project->title }}">
                                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-2 py-1 fw-bold">Pending Activation</span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-9">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-2 gap-2">
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-0">{{ $cycle->project->title }}</h5>
                                                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $cycle->project->location }}</p>
                                                </div>
                                                <span class="badge bg-light text-dark border px-3 py-2 rounded-3 text-start">
                                                    Selected Duration: <strong class="text-dark">{{ $cycle->duration_label }}</strong>
                                                </span>
                                            </div>

                                            <!-- Pending Share Numbers -->
                                            <div class="row g-2 text-center bg-light p-2 rounded-3 mb-3">
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Shares Owned</span>
                                                    <span class="fw-bold text-dark">{{ $cycle->shares_owned }} Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Required Shares</span>
                                                    <span class="fw-bold text-primary">{{ $cycle->required_shares }} Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Remaining</span>
                                                    <span class="fw-bold text-danger">{{ $cycle->remainingSharesNeeded() }} Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Share Value</span>
                                                    <span class="fw-bold text-dark">{{ number_format($cycle->total_purchase_amount, 0) }} AVC</span>
                                                </div>
                                            </div>

                                            <!-- Activation Progress Bar -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between small text-muted mb-1">
                                                    <span>Activation Progress</span>
                                                    <span class="fw-bold text-warning">{{ $cycle->shares_owned }} / {{ $cycle->required_shares }} Shares ({{ $cycle->activationProgressPercent() }}%)</span>
                                                </div>
                                                <div class="progress rounded-pill mb-2" style="height: 10px;">
                                                    <div class="progress-bar rounded-pill bg-warning" role="progressbar" style="width: {{ $cycle->activationProgressPercent() }}%;"></div>
                                                </div>
                                            </div>

                                            <!-- Action Alert Callout -->
                                            <div class="alert alert-warning border-0 p-2 px-3 rounded-3 mb-3 small d-flex align-items-center gap-2">
                                                <i class="bi bi-exclamation-circle-fill text-warning"></i>
                                                <span>You need <strong>{{ $cycle->remainingSharesNeeded() }} more shares</strong> to activate your {{ $cycle->duration_label }} earning cycle.</span>
                                            </div>

                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('marketplace.show', $cycle->project) }}#buy-panel" class="btn btn-warning fw-bold text-dark btn-sm rounded-3 px-3">
                                                    <i class="bi bi-cart-plus me-1"></i> Buy Remaining Shares
                                                </a>
                                                <a href="{{ route('marketplace.show', $cycle->project) }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3">Choose Another Duration</a>
                                                <a href="{{ route('marketplace.show', $cycle->project) }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-3">View Project</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 text-center">
                                    <p class="text-muted mb-0">No pending share activations.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- SECTION 3: Completed Cycles Cards List -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Completed Cycles</h5>
                            <p class="text-muted small mb-0">Your completed earning cycles and credited earnings.</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($completedCycles as $cycle)
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden p-3 p-md-4">
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-2">
                                            <div class="position-relative rounded-3 overflow-hidden" style="height: 100px;">
                                                <img src="{{ $cycle->project->image_url ?: asset('images/property-placeholder.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $cycle->project->title }}">
                                                <span class="badge bg-secondary position-absolute top-0 start-0 m-1">Completed</span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-10">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0">{{ $cycle->project->title }}</h6>
                                                    <span class="text-muted small"><i class="bi bi-clock me-1"></i> {{ $cycle->duration_label }} Cycle</span>
                                                </div>
                                                <a href="{{ route('portfolio.receipt', $cycle) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold">
                                                    <i class="bi bi-download me-1"></i> Download Receipt
                                                </a>
                                            </div>

                                            <div class="row g-2 text-center bg-light p-2 rounded-3">
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Shares</span>
                                                    <span class="fw-bold text-dark">{{ $cycle->shares_owned }} Shares</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Share Value</span>
                                                    <span class="fw-bold text-dark">{{ number_format($cycle->total_purchase_amount, 0) }} AVC</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Total Earnings</span>
                                                    <span class="fw-bold text-success">+{{ number_format($cycle->projected_earnings, 0) }} AVC</span>
                                                </div>
                                                <div class="col-3">
                                                    <span class="text-muted small d-block">Total Credited</span>
                                                    <span class="fw-bold text-primary">{{ number_format($cycle->completion_value, 0) }} AVC</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 text-center">
                                    <p class="text-muted mb-0">No completed earning cycles yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
