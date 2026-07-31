@extends('layouts.main')

@section('title', $property->title . ' | Radiant Dream Realty')

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
    .property-stat-card {
        transition: all 0.3s ease;
    }
    .property-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }
</style>

<section class="py-5" style="background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none fw-semibold text-primary">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/properties') }}" class="text-decoration-none fw-semibold text-primary">Properties</a></li>
                <li class="breadcrumb-item active fw-bold text-dark text-truncate" style="max-width:280px;" aria-current="page">{{ $property->title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Left: Image & Details -->
            <div class="col-lg-7">
                <!-- Hero Image -->
                <div class="rounded-4 overflow-hidden shadow-lg mb-4 position-relative">
                    <img src="{{ $property->image_url ?? 'https://radiantdreamrealty.com/frontend/images/home/house-7.jpg' }}"
                         alt="{{ $property->title }}"
                         class="w-100"
                         style="height: 460px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $property->category }}</span>
                            <span class="badge bg-success fs-6 px-3 py-2">{{ $property->roi_percentage }}% Target ROI</span>
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">{{ $property->investment_duration_months }} Months</span>
                        </div>
                    </div>
                </div>

                  <!-- Funding Progress -->
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0" style="color: #0f172a;"><i class="bi bi-bar-chart-fill me-2" style="color:#2563eb;"></i>Funding Progress</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">{{ $fundedPercent }}% Funded</span>
                    </div>
                    <div class="progress mb-3" style="height: 14px; background:#e2e8f0; border-radius: 10px;">
                        <div class="progress-bar rounded-pill" style="width: {{ $fundedPercent }}%; background: linear-gradient(90deg, #2563eb, #3b82f6);"></div>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span style="color:#2563eb;">${{ number_format($raisedAmount, 2) }} raised</span>
                        <span class="text-muted">${{ number_format($totalValuation, 2) }} target</span>
                    </div>
                </div>

                <!-- Title & Location -->
                <div class="mb-4">
                    <h2 class="fw-bold mb-2" style="color: #0f172a; font-size: 2rem;">{{ $property->title }}</h2>
                    <div class="d-flex align-items-center gap-3 text-muted">
                        <span><i class="bi bi-geo-alt me-1" style="color:#2563eb;"></i> {{ $property->location }}</span>
                        <span class="d-flex align-items-center"><i class="bi bi-shield-check me-1 text-success"></i> Verified Property</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3" style="color: #0f172a;"><i class="bi bi-info-circle me-2" style="color:#2563eb;"></i>Description</h5>
                    <p class="mb-0" style="color: #475569; line-height: 1.8;">{{ $property->description }}</p>
                </div>

                <!-- Stats Cards Row -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="property-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#eff6ff;">
                                <i class="bi bi-pie-chart text-primary fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Total Shares</small>
                            <h5 class="fw-bold mb-0 text-dark">{{ number_format($property->total_shares) }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="property-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#f0fdf4;">
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            </div>
                            <small class="text-muted d-block">Available</small>
                            <h5 class="fw-bold mb-0 text-success">{{ number_format($property->available_shares) }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="property-stat-card p-3 rounded-4 bg-white border-0 shadow-sm text-center h-100">
                            <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; background:#faf5ff;">
                                <i class="bi bi-graph-up-arrow text-purple fs-5" style="color:#9333ea;"></i>
                            </div>
                            <small class="text-muted d-block">Sold</small>
                            <h5 class="fw-bold mb-0" style="color:#9333ea;">{{ number_format($property->total_shares - $property->available_shares) }}</h5>
                        </div>
                    </div>
                </div>

              
            </div>

            <!-- Right: Invest Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 90px; overflow: hidden;">
                    <!-- Card Header Gradient -->
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold" style="font-size:0.85rem; letter-spacing:0.05em;">INVESTMENT OPPORTUNITY</span>
                        </div>
                        <h4 class="fw-bold mb-0 mt-1 text-white font-weight-bold">{{ $property->title }}</h4>
                    </div>

                    <div class="p-4">
                        <!-- Price Summary -->
                        <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-semibold">Price Per Share</span>
                                <strong class="fs-4 fw-bold" style="color:#0f172a;">${{ number_format($property->price_per_share, 2) }}</strong>
                            </div>
                            <hr class="my-2" style="border-color:#e2e8f0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Est. Annual ROI</span>
                                <span class="fw-bold text-success">{{ $property->roi_percentage }}%</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Available Shares</span>
                                <span class="fw-bold text-dark">{{ number_format($property->available_shares) }}</span>
                            </div>
                        </div>

                        <!-- Invest Form -->
                        <div x-data="investForm()">
                            <form action="{{ route('buy-shares.store') }}" method="POST" @submit="handleSubmit">
                                @csrf
                                <input type="hidden" name="property_id" value="{{ $property->id }}">

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Number of Shares</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary fw-bold" @click="qty = Math.max(1, qty - 1)">-</button>
                                        <input type="number" name="shares" x-model.number="qty" min="1"
                                               :max="{{ $property->available_shares }}"
                                               class="form-control text-center fw-bold" required>
                                        <button type="button" class="btn btn-outline-secondary fw-bold" @click="qty = Math.min({{ $property->available_shares }}, qty + 1)">+</button>
                                    </div>
                                    <small class="text-muted">Min 1 · Max {{ number_format($property->available_shares) }}</small>
                                </div>

                                <!-- Total Cost Display -->
                                <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color:#1e40af;">Total Cost</span>
                                        <span class="fw-bold" style="color:#1e40af; font-size:1.6rem;" x-text="'$' + total.toFixed(2)">$0.00</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 mt-1 small">
                                        <i class="bi bi-wallet2" style="color:#1e40af;"></i>
                                        @auth
                                            <span style="color:#1e40af;">Balance: <strong>${{ number_format(auth()->user()->wallet_balance, 2) }}</strong></span>
                                        @else
                                            <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in</a> to see your balance
                                        @endauth
                                    </div>
                                </div>

                                @auth
                                    <button type="submit" class="btn btn-primary fw-bold w-100 py-3 rounded-3 shadow-sm"
                                            style="background:#2563eb; font-size:1.05rem;"
                                            :disabled="loading">
                                        <span x-show="!loading"><i class="bi bi-lightning-charge me-1"></i> Confirm Investment</span>
                                        <span x-show="loading"><span class="loading-spinner me-2"></span> Processing...</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary fw-bold w-100 py-3 rounded-3 shadow-sm" style="background:#2563eb; font-size:1.05rem;">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Invest
                                    </a>
                                @endauth
                            </form>

                            @auth
                                <script>
                                    function investForm() {
                                        return {
                                            qty: 1,
                                            price: {{ $property->price_per_share }},
                                            loading: false,
                                            get total() {
                                                return this.qty * this.price;
                                            },
                                            handleSubmit(e) {
                                                if (this.qty < 1 || this.qty > {{ $property->available_shares }}) {
                                                    e.preventDefault();
                                                    alert('Invalid number of shares.');
                                                    return;
                                                }
                                                this.loading = true;
                                            }
                                        }
                                    }
                                </script>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection