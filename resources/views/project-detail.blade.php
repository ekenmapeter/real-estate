@extends('layouts.main')

@section('title', $project->title . ' | Radiant Dream Realty')

@section('content')
<style>
    .loading-spinner {
        display: inline-block;
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .project-stat-card { transition: all 0.3s ease; }
    .project-stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
</style>

<section class="py-5" style="background: #f8fafc; min-height: 80vh;">
    <div class="container">
 

        <div class="row g-5">
            <!-- Left: Image & Details -->
            <div class="col-lg-7">
                @php
                    $isActive = $project->isActiveWindow();
                    $endsAtTs = $project->endsAt() ? $project->endsAt()->timestamp : 0;
                    $rating = (float) $project->rating;
                    $statusBadge = [
                        'active' => ['bg-success', 'Ongoing'],
                        'completed' => ['bg-primary', 'Completed'],
                        'closed' => ['bg-secondary', 'Closed'],
                    ];
                    $statusCls = $statusBadge[$project->status] ?? ['bg-secondary', ucfirst($project->status)];
                @endphp
                @php
                    $galleryImages = $project->galleryUrls();
                    if (empty($galleryImages)) {
                        $galleryImages = ['https://radiantdreamrealty.com/frontend/images/home/house-1.jpg'];
                    }
                @endphp
                <div class="rounded-4 overflow-hidden shadow-lg mb-4 position-relative" x-data="{ slide: 0, images: @json($galleryImages) }">
                    <div class="position-relative overflow-hidden" style="height:460px; background:#e2e8f0;">
                        <template x-for="(img, i) in images" :key="i">
                            <div x-show="slide === i" x-transition.opacity.duration.300ms class="position-absolute top-0 start-0 w-100 h-100">
                                <img :src="img" :alt="@json($project->title)" class="w-100 h-100" style="object-fit:cover;">
                            </div>
                        </template>
                    </div>

                    <button x-show="images.length > 1" type="button" @click="slide = (slide - 1 + images.length) % images.length" class="btn btn-light rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; opacity:0.85; z-index:5;">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button x-show="images.length > 1" type="button" @click="slide = (slide + 1) % images.length" class="btn btn-light rounded-circle position-absolute top-50 end-0 translate-middle-y me-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; opacity:0.85; z-index:5;">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <div x-show="images.length > 1" class="position-absolute bottom-0 end-0 m-3 d-flex gap-1" style="z-index:5;">
                        <template x-for="(img, i) in images" :key="i">
                            <button type="button" @click="slide = i" class="rounded-circle border-0" :class="slide === i ? 'bg-white' : 'bg-white bg-opacity-50'" style="width:8px; height:8px; padding:0;"></button>
                        </template>
                    </div>

                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge {{ $statusCls[0] }} fs-6 px-3 py-2">{{ $statusCls[1] }}</span>
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">{{ $project->category }}</span>
                            <span class="badge bg-success fs-6 px-3 py-2">{{ $project->expected_return_percentage }}% Target Return</span>
                            <span class="badge bg-primary fs-6 px-3 py-2"><i class="bi bi-clock-history me-1"></i>{{ $project->investment_duration_months }} Months</span>
                            <span class="badge bg-dark fs-6 px-3 py-2 text-warning" title="{{ $rating }} / 5">
                                <i class="bi bi-star-fill me-1"></i>{{ number_format($rating, 1) }} Rating
                            </span>
                        </div>
                    </div>
                </div>

                @if($isActive && $endsAtTs > 0)
                    <div class="bg-white rounded-4 p-3 shadow-sm mb-4 d-flex align-items-center gap-3" style="border:1px solid #fde68a;">
                        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width:44px; height:44px; background:#fef3c7;">
                            <i class="bi bi-hourglass-split fs-5" style="color:#b45309;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted fw-bold d-block">FUNDING WINDOW CLOSES IN</small>
                            <span class="fw-bold fs-4" style="color:#b45309;" data-countdown-ends="{{ $endsAtTs }}">--</span>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-dark fw-bold px-3 py-2 rounded-pill">{{ $project->investment_duration_months }} mo duration</span>
                    </div>
                @endif

                <!-- Funding Progress -->
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0" style="color: #0f172a;"><i class="bi bi-bar-chart-fill me-2" style="color:#f59e0b;"></i>Funding Progress</h5>
                        <span class="badge bg-warning bg-opacity-10 text-dark fw-bold px-3 py-2 rounded-pill">{{ $fundedPercent }}% Funded</span>
                    </div>
                    <div class="progress mb-3" style="height: 14px; background:#e2e8f0; border-radius: 10px;">
                        <div class="progress-bar rounded-pill bg-warning" style="width: {{ $fundedPercent }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span style="color:#b45309;">${{ number_format($raisedAmount, 2) }} raised</span>
                        <span class="text-muted">${{ number_format($project->target_amount, 2) }} target</span>
                    </div>
                </div>

                <!-- Title & Location -->
                <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold mb-2" style="color: #0f172a; font-size: 2rem;">{{ $project->title }}</h2>
                        <div class="d-flex align-items-center gap-3 text-muted flex-wrap">
                            <span><i class="bi bi-geo-alt me-1" style="color:#f59e0b;"></i> {{ $project->location }}</span>
                            <span class="d-flex align-items-center"><i class="bi bi-shield-check me-1 text-success"></i> Verified Project</span>
                            <span class="position-relative d-inline-flex align-items-center gap-1" style="white-space:nowrap;" title="{{ $rating }} / 5 rating">
                                <span class="d-inline-flex gap-1 text-muted">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                </span>
                                <span class="position-absolute top-0 start-0 d-inline-flex gap-1 overflow-hidden" style="width:{{ $project->ratingWidth() }}%; color:#f59e0b;">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                </span>
                                <b class="text-dark">{{ number_format($rating, 1) }}</b>
                                <span class="text-muted">({{ $project->investments()->count() }} investors)</span>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @auth
                            <form action="{{ route('project.save', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $isSaved ? 'btn-danger' : 'btn-outline-primary' }} fw-bold px-3 py-2 rounded-3">
                                    <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }} me-1"></i> {{ $isSaved ? 'Saved' : 'Save' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3">
                                <i class="bi bi-bookmark me-1"></i> Save
                            </a>
                        @endauth
                        @if($project->document_path)
                            <a href="{{ route('project.download', $project) }}" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Download Document
                            </a>
                        @endif
                        <button type="button" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3" onclick="shareProject('{{ $project->title }}', '{{ route('project.show', $project) }}')">
                            <i class="bi bi-share me-1"></i> Share
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3" style="color: #0f172a;"><i class="bi bi-info-circle me-2" style="color:#f59e0b;"></i>About This Project</h5>
                    <p class="mb-0" style="color: #475569; line-height: 1.8;">{{ $project->description }}</p>
                </div>

                <!-- Project Details (More Info) -->
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3" style="color: #0f172a;"><i class="bi bi-list-ul me-2" style="color:#f59e0b;"></i>Project Details</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-diagram-3 fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Project Status</small>
                                    <span class="fw-bold text-dark">{{ $project->statusLabel() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-clock-history fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Project Duration</small>
                                    <span class="fw-bold text-dark">{{ $project->investment_duration_months }} months</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-star fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Project Rating</small>
                                    <span class="fw-bold text-dark">{{ number_format($rating, 1) }} / 5</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-people fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Investors</small>
                                    <span class="fw-bold text-dark">{{ $project->investments()->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-bullseye fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Target Amount</small>
                                    <span class="fw-bold text-dark">${{ number_format($project->target_amount, 0) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-currency-dollar fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Minimum Investment</small>
                                    <span class="fw-bold text-dark">${{ number_format($project->minimum_investment, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-graph-up-arrow fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Expected Return</small>
                                    <span class="fw-bold text-success">{{ $project->expected_return_percentage }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <i class="bi bi-tag fs-4" style="color:#f59e0b;"></i>
                                <div>
                                    <small class="text-muted d-block">Category</small>
                                    <span class="fw-bold text-dark">{{ $project->category }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards Row -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="project-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#fef3c7;">
                                <i class="bi bi-bullseye text-warning fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Target Amount</small>
                            <h5 class="fw-bold mb-0 text-dark">${{ number_format($project->target_amount, 0) }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="project-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#f0fdf4;">
                                <i class="bi bi-currency-dollar text-success fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Minimum Invest</small>
                            <h5 class="fw-bold mb-0 text-success">${{ number_format($project->minimum_investment, 0) }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="project-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#eff6ff;">
                                <i class="bi bi-graph-up-arrow text-primary fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Expected Return</small>
                            <h5 class="fw-bold mb-0" style="color:#9333ea;">{{ $project->expected_return_percentage }}%</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="project-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#faf5ff;">
                                <i class="bi bi-calendar-check fs-5" style="color:#9333ea;"></i>
                            </div>
                            <small class="text-muted d-block">Duration</small>
                            <h5 class="fw-bold mb-0" style="color:#9333ea;">{{ $project->investment_duration_months }} Months</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Invest Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 90px; overflow: hidden;">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold" style="font-size:0.85rem; letter-spacing:0.05em;">INVESTMENT OPPORTUNITY</span>
                            <span class="badge {{ $statusCls[0] }} ms-auto">{{ $statusCls[1] }}</span>
                        </div>
                        <h4 class="fw-bold mb-0 mt-1 text-white font-weight-bold">{{ $project->title }}</h4>
                    </div>

                    <div class="p-4">
                        <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-semibold">Minimum Investment</span>
                                <strong class="fs-4 fw-bold" style="color:#0f172a;">${{ number_format($project->minimum_investment, 2) }}</strong>
                            </div>
                            <hr class="my-2" style="border-color:#e2e8f0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Est. Target Return</span>
                                <span class="fw-bold text-success">{{ $project->expected_return_percentage }}%</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Investment Duration</span>
                                <span class="fw-bold text-dark">{{ $project->investment_duration_months }} months</span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="text-muted small">Rating</span>
                                <span class="fw-bold" style="color:#f59e0b;"><i class="bi bi-star-fill me-1"></i>{{ number_format($rating, 1) }} / 5</span>
                            </div>
                        </div>

                        @if(!$isActive)
                            <div class="alert alert-warning rounded-3 d-flex align-items-center gap-2 mb-3" role="alert">
                                <i class="bi bi-lock-fill"></i>
                                <span class="small fw-bold">This project is {{ strtolower($project->statusLabel()) }} — new investments are closed.</span>
                            </div>
                        @endif

                        <div x-data="investForm()">
                            <form action="{{ route('project.invest', $project) }}" method="POST" @submit="handleSubmit">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Investment Amount ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold">$</span>
                                        <input type="number" name="amount" x-model.number="amount" min="{{ $project->minimum_investment }}" step="0.01"
                                               class="form-control text-center fw-bold" placeholder="{{ number_format($project->minimum_investment, 2) }}" required>
                                    </div>
                                    <small class="text-muted">Minimum ${{ number_format($project->minimum_investment, 2) }} · Up to ${{ number_format(max(0, $project->target_amount - $raisedAmount), 2) }} remaining</small>
                                </div>

                                <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fde68a;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color:#b45309;">Your Investment</span>
                                        <span class="fw-bold" style="color:#b45309; font-size:1.6rem;" x-text="'$' + amount.toFixed(2)">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1 small">
                                        <span class="text-muted">Est. Return ({{ $project->expected_return_percentage }}%)</span>
                                        <span class="fw-bold text-success" x-text="'$' + (amount * {{ $project->expected_return_percentage }} / 100).toFixed(2)">$0.00</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 mt-2 small">
                                        <i class="bi bi-wallet2" style="color:#b45309;"></i>
                                        @auth
                                            <span style="color:#b45309;">Balance: <strong>${{ number_format(auth()->user()->wallet_balance, 2) }}</strong></span>
                                        @else
                                            <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in</a> to see your balance
                                        @endauth
                                    </div>
                                </div>

                                @auth
                                    @if($isActive)
                                        <button type="submit" class="btn btn-warning fw-bold w-100 py-3 rounded-3 shadow-sm text-dark"
                                                style="background:#fbbf24; border:none; font-size:1.05rem;"
                                                :disabled="loading">
                                            <span x-show="!loading"><i class="bi bi-lightning-charge-fill me-1"></i> Confirm Investment</span>
                                            <span x-show="loading"><span class="loading-spinner me-2"></span> Processing...</span>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary fw-bold w-100 py-3 rounded-3 text-white" style="font-size:1.05rem;" disabled>
                                            <i class="bi bi-lock-fill me-1"></i> Investments Closed
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-warning fw-bold w-100 py-3 rounded-3 shadow-sm text-dark" style="background:#fbbf24; border:none; font-size:1.05rem;">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Invest
                                    </a>
                                @endauth
                            </form>

                            @auth
                                <script>
                                    function investForm() {
                                        return {
                                            amount: {{ $project->minimum_investment }},
                                            minAmount: {{ $project->minimum_investment }},
                                            loading: false,
                                            handleSubmit(e) {
                                                if (!this.amount || this.amount < this.minAmount) {
                                                    e.preventDefault();
                                                    alert('Minimum investment is ${{ number_format($project->minimum_investment, 2) }}.');
                                                    return;
                                                }
                                                this.loading = true;
                                            }
                                        }
                                    }
                                </script>
                            @endauth
                        </div>

                        @if($project->document_path)
                            <a href="{{ route('project.download', $project) }}" class="btn btn-outline-secondary fw-bold w-100 py-2 rounded-3 mb-2 mt-1">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Download Project Document
                            </a>
                        @endif
                        <button type="button" class="btn btn-outline-secondary fw-bold w-100 py-2 rounded-3" onclick="shareProject('{{ $project->title }}', '{{ route('project.show', $project) }}')">
                            <i class="bi bi-share me-1"></i> Share This Project
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function shareProject(title, url) {
        shareContent(title, url, 'Invest in this project');
    }
</script>
@endsection
