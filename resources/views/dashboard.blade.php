@extends('layouts.main')

@section('title', 'User Dashboard | Radiant Dream Realty Investment Platform')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    /* High-contrast text colors for extreme legibility */
    body, h1, h2, h3, h4, h5, h6, label, table, th, td {
        color: #0f172a !important;
    }

    .text-muted {
        color: #334155 !important; /* Sharp slate color instead of pale grey */
    }

    .text-secondary {
        color: #475569 !important;
    }

    /* Mobile UX & Typography Adjustments */
    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .card-body {
            padding: 1.1rem !important;
        }
        .mobile-tab-scroll {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
            gap: 6px !important;
        }
        .mobile-tab-scroll .nav-item {
            flex: 0 0 auto !important;
        }
        .mobile-tab-scroll::-webkit-scrollbar {
            height: 4px;
        }
        .mobile-tab-scroll::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }
        .mobile-stack-btn {
            width: 100% !important;
        }
        .mobile-stack-btn button,
        .mobile-stack-btn a {
            width: 100% !important;
            margin-bottom: 6px;
            background-color: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #e2e8f0 !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
        }
        .mobile-stack-btn a.btn-warning {
            background-color: #ffc107 !important;
            color: #000000 !important;
        }
        .custom-modal-backdrop {
            padding: 0.5rem !important;
        }
        .custom-modal-card {
            border-radius: 18px !important;
            max-width: 95vw !important;
        }
        h2.h3 {
            font-size: 1.35rem !important;
        }
        h3 {
            font-size: 1.45rem !important;
        }
    }

    /* Full-screen Glassmorphism Modal Backdrop */
    .custom-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.82) !important;
        backdrop-filter: blur(14px) !important;
        -webkit-backdrop-filter: blur(14px) !important;
        z-index: 99999 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow-y: auto;
    }

    .custom-modal-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4);
        max-width: 580px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.8);
        overflow: hidden;
        animation: popupScaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes popupScaleIn {
        from {
            opacity: 0;
            transform: scale(0.92) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>

<div class="py-4 bg-light" style="min-height: calc(100vh - 68px);" x-data="userDashboardEngine()">
    <div class="container-fluid px-lg-5">

        <!-- Flash Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong class="d-block">Success!</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                <div>
                    <strong class="d-block">Error</strong>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- User Profile Banner -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white overflow-hidden">
            <div class="card-body p-4 p-lg-5" style="background: linear-gradient(135deg, #1e3a8a 0%, #2756fd 100%);">
                <div class="row align-items-center text-white">
                    <div class="col-lg-7 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 shadow flex-shrink-0" style="width: 56px; height: 56px;">
                                {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="h3 fw-bold mb-1 text-white">Welcome back, {{ $user->name ?? 'Investor' }}! 👋</h2>
                                <div class="d-flex flex-wrap align-items-center gap-2" style="font-size: 0.88rem;">
                                    <span class="badge bg-white text-primary fw-bold px-3 py-1 rounded-pill shadow-sm">
                                        <i class="bi bi-person-badge me-1"></i> Account ID: {{ $user->account_id ?? 'RDR-884920' }}
                                    </span>
                                    <span class="badge bg-success text-white fw-bold px-3 py-1 rounded-pill shadow-sm">
                                        <i class="bi bi-shield-check me-1"></i> Verified Investor
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill shadow-sm">
                                        <i class="bi bi-share me-1"></i> Referral Code: {{ $user->affiliate_code ?? 'RAD8849' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end mobile-stack-btn">
                            <button @click="openWelcomeModal = true" class="btn bg-white text-dark fw-bold px-3 py-2 rounded-3 shadow-sm border">
                                <i class="bi bi-window-stack text-primary me-1"></i> Quick View Popup
                            </button>
                            <button @click="activeTab = 'deposit'" class="btn bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm border">
                                <i class="bi bi-plus-circle me-1"></i> Deposit Funds
                            </button>
                            <button @click="activeTab = 'withdraw'" class="btn bg-white text-dark fw-bold px-3 py-2 rounded-3 shadow-sm border">
                                <i class="bi bi-arrow-up-right-circle text-success me-1"></i> Withdraw
                            </button>
                            <button @click="openReceiveModal = true" class="btn bg-white text-dark fw-bold px-3 py-2 rounded-3 shadow-sm border">
                                <i class="bi bi-qr-code text-info me-1"></i> Receive Funds
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Key Balance Cards -->
        <div class="row g-4 mb-4">
            <!-- 1. Wallet Balance -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white h-100 p-3 p-xl-4 hover-lift">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary fw-semibold" style="font-size: 0.85rem;">WALLET BALANCE</span>
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">${{ number_format($walletBalance, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                        <span class="text-muted" style="font-size: 0.8rem;"><i class="bi bi-lock-fill text-success"></i> Instant Withdrawal</span>
                        <button @click="activeTab = 'deposit'" class="btn btn-sm btn-link p-0 text-primary fw-bold text-decoration-none">Add Funds &rarr;</button>
                    </div>
                </div>
            </div>

            <!-- 2. Active Projects Invested -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white h-100 p-3 p-xl-4 hover-lift">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary fw-semibold" style="font-size: 0.85rem;">ACTIVE PROJECTS</span>
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $activeProjectsCount }} <span class="fs-6 fw-normal text-muted">Properties</span></h3>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                        <span class="text-muted" style="font-size: 0.8rem;">Invested: <strong>${{ number_format($totalInvested, 2) }}</strong></span>
                        <button @click="activeTab = 'my-investments'" class="btn btn-sm btn-link p-0 text-info fw-bold text-decoration-none">View Matrix &rarr;</button>
                    </div>
                </div>
            </div>

            <!-- 3. Total ROI Earned -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white h-100 p-3 p-xl-4 hover-lift">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary fw-semibold" style="font-size: 0.85rem;">TOTAL ROI EARNED</span>
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1">+${{ number_format($totalRoiEarned, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold"><i class="bi bi-lightning-charge-fill me-1"></i> High Yield Returns</span>
                        <span class="text-muted" style="font-size: 0.8rem;">Auto-Compounded</span>
                    </div>
                </div>
            </div>

            <!-- 4. Affiliate Earnings -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white h-100 p-3 p-xl-4 hover-lift">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary fw-semibold" style="font-size: 0.85rem;">AFFILIATE EARNINGS</span>
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">${{ number_format($affiliateEarnings, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                        <span class="text-muted" style="font-size: 0.8rem;">Code: <strong>{{ $user->affiliate_code ?? 'RAD8849' }}</strong></span>
                        <button @click="copyReferralCode()" class="btn btn-sm btn-link p-0 text-warning fw-bold text-decoration-none">Copy Link &rarr;</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card border-0 rounded-4 shadow-sm bg-white mb-4">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill flex-column flex-sm-row gap-1 mobile-tab-scroll" id="dashboardTabs">
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'overview' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'overview'">
                            <i class="bi bi-grid-fill me-2"></i>Overview
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'deposit' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'deposit'">
                            <i class="bi bi-wallet-fill me-2"></i>Deposit Funds
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'withdraw' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'withdraw'">
                            <i class="bi bi-arrow-up-right-square-fill me-2"></i>Withdraw Funds
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'send-receive' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'send-receive'">
                            <i class="bi bi-send-fill me-2"></i>Send & Receive
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'marketplace' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'marketplace'">
                            <i class="bi bi-shop me-2"></i>Property Listings
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'my-investments' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'my-investments'">
                            <i class="bi bi-pie-chart-fill me-2"></i>Investment Matrix
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeTab === 'transactions' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeTab = 'transactions'">
                            <i class="bi bi-receipt me-2"></i>Transactions
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- TAB CONTENT CONTAINER -->

        <!-- 1. OVERVIEW TAB -->
        <div x-show="activeTab === 'overview'" x-transition>
            <div class="row g-4 mb-4">
                <!-- Investment Performance Matrix -->
                <div class="col-lg-8">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-bar-chart-line text-primary me-2"></i>Investment Matrix Overview</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Track your real estate portfolio performance and quarterly yield distributions.</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">Active Portfolio</span>
                        </div>

                        <!-- Progress Summary -->
                        <div class="p-3 bg-light rounded-4 mb-4 border">
                            <div class="row text-center g-3">
                                <div class="col-4 border-end">
                                    <span class="text-muted d-block" style="font-size: 0.8rem;">TOTAL CAPITAL INVESTED</span>
                                    <strong class="text-dark fs-5">${{ number_format($totalInvested, 2) }}</strong>
                                </div>
                                <div class="col-4 border-end">
                                    <span class="text-muted d-block" style="font-size: 0.8rem;">ESTIMATED ANNUAL ROI</span>
                                    <strong class="text-primary fs-5">21.8% Avg</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted d-block" style="font-size: 0.8rem;">TOTAL EARNED TO DATE</span>
                                    <strong class="text-success fs-5">${{ number_format($totalRoiEarned, 2) }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Active Properties List -->
                        <h6 class="fw-bold text-dark mb-3">Your Portfolio Holdings</h6>
                        @forelse($userInvestments as $inv)
                            <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded-3 border hover-shadow bg-white">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $inv->property->image_url ?? 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop' }}" class="rounded-3 object-fit-cover" width="60" height="50" alt="property">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $inv->property->title ?? 'Property' }}</h6>
                                        <small class="text-muted"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $inv->property->location ?? 'USA' }} &bull; {{ $inv->shares_bought }} Share(s)</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">${{ number_format($inv->total_amount, 2) }}</div>
                                    <small class="text-success fw-bold">+${{ number_format($inv->roi_earned, 2) }} ROI</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted bg-light rounded-4">
                                <i class="bi bi-building fs-1 d-block mb-2 text-secondary"></i>
                                You haven't invested in any property shares yet.
                                <button @click="activeTab = 'marketplace'" class="btn btn-primary btn-sm mt-2 fw-semibold">Browse Listings</button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Action Sidebar & Receive Modal Launcher -->
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Quick Operations</h5>
                        <div class="d-grid gap-2 mb-4">
                            <button @click="activeTab = 'deposit'" class="btn btn-outline-primary fw-bold text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-bank text-primary me-2"></i> Deposit Funds</span>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                            <button @click="activeTab = 'withdraw'" class="btn btn-outline-success fw-bold text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-cash-stack text-success me-2"></i> Withdraw to Bank / Crypto</span>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                            <button @click="activeTab = 'send-receive'" class="btn btn-outline-info fw-bold text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-send text-info me-2"></i> Send Funds to User</span>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                            <button @click="openReceiveModal = true" class="btn btn-outline-dark fw-bold text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-qr-code-scan text-dark me-2"></i> Receive Funds (Account ID)</span>
                                <i class="bi bi-share text-primary"></i>
                            </button>
                        </div>

                        <div class="p-3 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-headset me-2"></i>Need Financial Assistance?</h6>
                            <p class="text-secondary mb-3" style="font-size: 0.82rem;">Book a 1-on-1 session with our dedicated real estate portfolio advisor.</p>
                            <button @click="activeTab = 'deposit'; depositMethod = 'financial_assistant'" class="btn btn-primary btn-sm w-100 fw-bold">Book Advisor Session</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. DEPOSIT FUNDS TAB -->
        <div x-show="activeTab === 'deposit'" x-transition>
            <div class="row g-4">
                <!-- Deposit Form -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-wallet2 text-primary me-2"></i>Deposit Funds to Wallet</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Select your preferred payment channel to fund your investment wallet.</p>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">0% Platform Fee</span>
                        </div>

                        <form action="{{ route('deposit.store') }}" method="POST">
                            @csrf
                            <!-- Method Selector -->
                            <label class="form-label fw-bold text-dark">1. Select Payment Method</label>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-sm-3">
                                    <input type="radio" class="btn-check" name="payment_method" value="bank_transfer" id="dep_bank" x-model="depositMethod">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 text-center fw-semibold" for="dep_bank">
                                        <i class="bi bi-bank fs-4 d-block mb-1"></i><span style="font-size:0.8rem;">Bank Transfer</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <input type="radio" class="btn-check" name="payment_method" value="credit_card" id="dep_card" x-model="depositMethod">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 text-center fw-semibold" for="dep_card">
                                        <i class="bi bi-credit-card fs-4 d-block mb-1"></i><span style="font-size:0.8rem;">Credit/Debit Card</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <input type="radio" class="btn-check" name="payment_method" value="wire_transfer" id="dep_wire" x-model="depositMethod">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 text-center fw-semibold" for="dep_wire">
                                        <i class="bi bi-globe fs-4 d-block mb-1"></i><span style="font-size:0.8rem;">Wire Transfer</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <input type="radio" class="btn-check" name="payment_method" value="crypto" id="dep_crypto" x-model="depositMethod">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 text-center fw-semibold" for="dep_crypto">
                                        <i class="bi bi-currency-bitcoin fs-4 d-block mb-1"></i><span style="font-size:0.8rem;">Cryptocurrency</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">2. Enter Deposit Amount (USD)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light text-dark fw-bold">$</span>
                                    <input type="number" step="0.01" min="10" class="form-control fw-bold" name="amount" placeholder="e.g. 5000.00" required>
                                </div>
                                <div class="form-text">Minimum deposit: $10.00</div>
                            </div>

                            <!-- Bank Transfer Instructions -->
                            <div class="p-3 rounded-3 mb-4 border" style="background:#f0f7ff;" x-show="depositMethod === 'bank_transfer'" x-cloak>
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-bank me-1"></i>Bank Transfer Instructions</h6>
                                <div class="row g-2">
                                    <div class="col-sm-6"><p class="small text-secondary mb-1"><strong class="text-dark">Bank Name:</strong> Radiant Capital Bank NA</p></div>
                                    <div class="col-sm-6"><p class="small text-secondary mb-1"><strong class="text-dark">Account No:</strong> 8849-2091-4412</p></div>
                                    <div class="col-sm-6"><p class="small text-secondary mb-1"><strong class="text-dark">Routing No:</strong> 021000089</p></div>
                                    <div class="col-sm-6"><p class="small text-secondary mb-0"><strong class="text-dark">SWIFT/BIC:</strong> RDRUS33XXX</p></div>
                                </div>
                                <p class="small text-muted mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>Use your deposit code as the payment reference. Allow 1–3 business days for confirmation.</p>
                            </div>

                            <!-- Wire Transfer Instructions -->
                            <div class="p-3 rounded-3 mb-4 border" style="background:#f5f5ff;" x-show="depositMethod === 'wire_transfer'" x-cloak>
                                <h6 class="fw-bold text-dark mb-2" style="color:#4f46e5!important;"><i class="bi bi-globe me-1"></i>International Wire Transfer</h6>
                                <div class="row g-2">
                                    <div class="col-sm-6"><p class="small text-secondary mb-1"><strong class="text-dark">Beneficiary:</strong> Radiant Dream Realty LLC</p></div>
                                    <div class="col-sm-6"><p class="small text-secondary mb-1"><strong class="text-dark">IBAN:</strong> US89 3704 0044 0532 0130 00</p></div>
                                    <div class="col-sm-6"><p class="small text-secondary mb-1"><strong class="text-dark">SWIFT/BIC:</strong> RDRUS33XXX</p></div>
                                    <div class="col-sm-6"><p class="small text-secondary mb-0"><strong class="text-dark">Bank Address:</strong> 250 Park Ave, New York, NY 10177</p></div>
                                </div>
                                <p class="small text-muted mt-2 mb-0"><i class="bi bi-clock me-1"></i>Wire transfers typically settle within 24–48 hours. Include your Account ID in the wire memo.</p>
                            </div>

                            <!-- Credit/Debit Card Fields -->
                            <div class="mb-4" x-show="depositMethod === 'credit_card'" x-cloak>
                                <div class="p-3 rounded-3 border mb-3" style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <p class="text-white-50 small mb-0">Card Number</p>
                                            <p class="text-white fw-bold mb-0 fs-6" x-text="cardNumber || '•••• •••• •••• ••••'"></p>
                                        </div>
                                        <i class="bi bi-credit-card-2-front-fill text-white fs-2 opacity-75"></i>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="text-white-50" style="font-size:0.7rem;margin-bottom:2px;">CARDHOLDER NAME</p>
                                            <p class="text-white fw-bold small mb-0" x-text="cardName || 'FULL NAME'"></p>
                                        </div>
                                        <div class="text-end">
                                            <p class="text-white-50" style="font-size:0.7rem;margin-bottom:2px;">EXPIRES</p>
                                            <p class="text-white fw-bold small mb-0" x-text="cardExpiry || 'MM/YY'"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-dark small">Card Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="bi bi-credit-card text-primary"></i></span>
                                            <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456"
                                                maxlength="19"
                                                x-model="cardNumber"
                                                @input="cardNumber = $event.target.value.replace(/\D/g,'').replace(/(\d{4})(?=\d)/g,'$1 ').trim()"
                                                :required="depositMethod === 'credit_card'">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-dark small">Cardholder Name</label>
                                        <input type="text" name="card_name" class="form-control" placeholder="As it appears on the card"
                                            x-model="cardName"
                                            @input="cardName = $event.target.value.toUpperCase()"
                                            :required="depositMethod === 'credit_card'">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-dark small">Expiry Date</label>
                                        <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY"
                                            maxlength="5"
                                            x-model="cardExpiry"
                                            @input="let v = $event.target.value.replace(/\D/g,''); cardExpiry = v.length >= 2 ? v.slice(0,2)+'/'+v.slice(2,4) : v"
                                            :required="depositMethod === 'credit_card'">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-dark small">CVV / CVC</label>
                                        <div class="input-group">
                                            <input type="password" name="card_cvv" class="form-control" placeholder="•••"
                                                maxlength="4"
                                                :required="depositMethod === 'credit_card'">
                                            <span class="input-group-text bg-white"><i class="bi bi-shield-lock text-primary"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 p-2 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                    <p class="small text-dark mb-0"><i class="bi bi-lock-fill text-warning me-1"></i><strong>Secure Entry.</strong> Your card details are encrypted and processed securely.</p>
                                </div>
                            </div>

                            <!-- Cryptocurrency Options -->
                            <div x-show="depositMethod === 'crypto'" x-cloak class="mb-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-currency-bitcoin text-warning me-1"></i>Select Cryptocurrency Network</h6>
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="crypto_network" value="usdt_trc20" id="crypto_usdt_trc" x-model="cryptoNetwork">
                                        <label class="btn btn-outline-warning w-100 py-2 rounded-3 text-center fw-semibold" for="crypto_usdt_trc" style="font-size:0.75rem;">
                                            <img src="https://cryptologos.cc/logos/tether-usdt-logo.png" width="20" class="d-block mx-auto mb-1" alt="USDT" onerror="this.style.display='none'">
                                            USDT TRC20
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="crypto_network" value="usdt_erc20" id="crypto_usdt_erc" x-model="cryptoNetwork">
                                        <label class="btn btn-outline-warning w-100 py-2 rounded-3 text-center fw-semibold" for="crypto_usdt_erc" style="font-size:0.75rem;">
                                            <i class="bi bi-currency-exchange d-block fs-5 mb-1"></i>USDT ERC20
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="crypto_network" value="bitcoin" id="crypto_btc" x-model="cryptoNetwork">
                                        <label class="btn btn-outline-warning w-100 py-2 rounded-3 text-center fw-semibold" for="crypto_btc" style="font-size:0.75rem;">
                                            <i class="bi bi-currency-bitcoin d-block fs-5 mb-1"></i>Bitcoin (BTC)
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="crypto_network" value="ethereum" id="crypto_eth" x-model="cryptoNetwork">
                                        <label class="btn btn-outline-warning w-100 py-2 rounded-3 text-center fw-semibold" for="crypto_eth" style="font-size:0.75rem;">
                                            <i class="bi bi-gem d-block fs-5 mb-1"></i>Ethereum (ETH)
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="crypto_network" value="bnb" id="crypto_bnb" x-model="cryptoNetwork">
                                        <label class="btn btn-outline-warning w-100 py-2 rounded-3 text-center fw-semibold" for="crypto_bnb" style="font-size:0.75rem;">
                                            <i class="bi bi-hexagon d-block fs-5 mb-1"></i>BNB (BSC)
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="crypto_network" value="solana" id="crypto_sol" x-model="cryptoNetwork">
                                        <label class="btn btn-outline-warning w-100 py-2 rounded-3 text-center fw-semibold" for="crypto_sol" style="font-size:0.75rem;">
                                            <i class="bi bi-lightning d-block fs-5 mb-1"></i>Solana (SOL)
                                        </label>
                                    </div>
                                </div>
                                <input type="hidden" name="crypto_network_value" :value="cryptoNetwork">
                                <div class="p-3 rounded-3 border" style="background:#fffbeb;">
                                    <template x-if="cryptoNetwork === 'usdt_trc20'">
                                        <div>
                                            <p class="small fw-bold text-dark mb-1"><i class="bi bi-wallet2 me-1 text-warning"></i>USDT (TRC20) Wallet Address:</p>
                                            <code class="small text-dark d-block p-2 bg-white rounded border" style="word-break:break-all;">TEvXn9942a10sK9921RadiantRealtyUSDT</code>
                                        </div>
                                    </template>
                                    <template x-if="cryptoNetwork === 'usdt_erc20'">
                                        <div>
                                            <p class="small fw-bold text-dark mb-1"><i class="bi bi-wallet2 me-1 text-warning"></i>USDT (ERC20) Wallet Address:</p>
                                            <code class="small text-dark d-block p-2 bg-white rounded border" style="word-break:break-all;">0xA1B2C3D4E5RadiantRealtyERC20USDT</code>
                                        </div>
                                    </template>
                                    <template x-if="cryptoNetwork === 'bitcoin'">
                                        <div>
                                            <p class="small fw-bold text-dark mb-1"><i class="bi bi-currency-bitcoin me-1 text-warning"></i>Bitcoin (BTC) Wallet Address:</p>
                                            <code class="small text-dark d-block p-2 bg-white rounded border" style="word-break:break-all;">1RDR9942a10sK9921RadiantRealtyBTC</code>
                                        </div>
                                    </template>
                                    <template x-if="cryptoNetwork === 'ethereum'">
                                        <div>
                                            <p class="small fw-bold text-dark mb-1"><i class="bi bi-gem me-1 text-warning"></i>Ethereum (ETH) Wallet Address:</p>
                                            <code class="small text-dark d-block p-2 bg-white rounded border" style="word-break:break-all;">0xD9E8F7A6B5RadiantRealtyETH2024</code>
                                        </div>
                                    </template>
                                    <template x-if="cryptoNetwork === 'bnb'">
                                        <div>
                                            <p class="small fw-bold text-dark mb-1"><i class="bi bi-hexagon me-1 text-warning"></i>BNB (BSC) Wallet Address:</p>
                                            <code class="small text-dark d-block p-2 bg-white rounded border" style="word-break:break-all;">0xBNB7F6E5D4RadiantRealtyBSC2024</code>
                                        </div>
                                    </template>
                                    <template x-if="cryptoNetwork === 'solana'">
                                        <div>
                                            <p class="small fw-bold text-dark mb-1"><i class="bi bi-lightning me-1 text-warning"></i>Solana (SOL) Wallet Address:</p>
                                            <code class="small text-dark d-block p-2 bg-white rounded border" style="word-break:break-all;">SoLRADRealtyWalletAddress2024XYZ</code>
                                        </div>
                                    </template>
                                    <template x-if="!cryptoNetwork">
                                        <p class="small text-muted mb-0"><i class="bi bi-arrow-up me-1"></i>Select a network above to view the wallet address.</p>
                                    </template>
                                    <p class="small text-muted mt-2 mb-0"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>Send only the selected currency to this address. Wrong network = lost funds.</p>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label fw-semibold text-dark small">Your Sending Wallet Address (optional)</label>
                                    <input type="text" name="crypto_from_wallet" class="form-control" placeholder="Paste your wallet address for verification">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3">
                                <i class="bi bi-check-circle me-2"></i>Submit Deposit Request
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Deposit History -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Deposit History</h5>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deposits as $dep)
                                        <tr>
                                            <td class="fw-semibold text-primary" style="font-size: 0.85rem;">{{ $dep->deposit_code }}</td>
                                            <td class="text-capitalize" style="font-size: 0.85rem;">
                                                <i class="bi bi-credit-card me-1 text-secondary"></i>{{ str_replace('_', ' ', $dep->payment_method) }}
                                            </td>
                                            <td class="fw-bold text-dark">${{ number_format($dep->amount, 2) }}</td>
                                            <td>
                                                @if($dep->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1 rounded-pill">Approved</span>
                                                @elseif($dep->status === 'pending')
                                                    <span class="badge bg-warning bg-opacity-15 text-warning-dark fw-bold px-2 py-1 rounded-pill">Pending</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1 rounded-pill">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="text-muted" style="font-size: 0.8rem;">{{ $dep->created_at ? $dep->created_at->format('M d, Y') : 'Recent' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No deposit history found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. WITHDRAW FUNDS TAB -->
        <div x-show="activeTab === 'withdraw'" x-transition>
            <div class="row g-4">
                <!-- Withdrawal Form -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-arrow-up-right-circle text-success me-2"></i>Withdraw Funds</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Transfer funds from your wallet to your external bank account or crypto wallet.</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">Available: ${{ number_format($walletBalance, 2) }}</span>
                        </div>

                        <form action="{{ route('withdraw.store') }}" method="POST">
                            @csrf
                            <label class="form-label fw-bold text-dark">1. Select Withdrawal Channel</label>
                            <div class="row g-2 mb-4">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="withdrawal_method" value="bank_transfer" id="wth_bank" x-model="withdrawMethod">
                                    <label class="btn btn-outline-success w-100 py-3 rounded-3 text-center fw-semibold" for="wth_bank">
                                        <i class="bi bi-bank fs-4 d-block mb-1"></i> Bank Transfer
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="withdrawal_method" value="paypal" id="wth_paypal" x-model="withdrawMethod">
                                    <label class="btn btn-outline-success w-100 py-3 rounded-3 text-center fw-semibold" for="wth_paypal">
                                        <i class="bi bi-paypal fs-4 d-block mb-1"></i> PayPal
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="withdrawal_method" value="crypto" id="wth_crypto" x-model="withdrawMethod">
                                    <label class="btn btn-outline-success w-100 py-3 rounded-3 text-center fw-semibold" for="wth_crypto">
                                        <i class="bi bi-currency-bitcoin fs-4 d-block mb-1"></i> Crypto
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">2. Withdrawal Amount (USD)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light text-dark fw-bold">$</span>
                                    <input type="number" step="0.01" min="10" max="{{ $walletBalance }}" class="form-control fw-bold" name="amount" placeholder="e.g. 1500.00" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">3. Destination Account Details</label>
                                <textarea class="form-control" name="account_details" rows="3" placeholder="Enter Bank Account Number / Routing, PayPal Email, or Crypto Wallet Address" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-3">
                                <i class="bi bi-arrow-up-right-circle me-2"></i>Confirm Withdrawal Request
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Withdrawal History -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-success me-2"></i>Withdrawal History</h5>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Channel</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawals as $wth)
                                        <tr>
                                            <td class="fw-semibold text-success" style="font-size: 0.85rem;">{{ $wth->withdrawal_code }}</td>
                                            <td class="text-capitalize" style="font-size: 0.85rem;">
                                                <i class="bi bi-bank me-1 text-secondary"></i>{{ str_replace('_', ' ', $wth->withdrawal_method) }}
                                            </td>
                                            <td class="fw-bold text-dark">${{ number_format($wth->amount, 2) }}</td>
                                            <td>
                                                @if($wth->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1 rounded-pill">Approved</span>
                                                @elseif($wth->status === 'pending')
                                                    <span class="badge bg-warning bg-opacity-15 text-warning-dark fw-bold px-2 py-1 rounded-pill">Pending</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1 rounded-pill">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="text-muted" style="font-size: 0.8rem;">{{ $wth->created_at ? $wth->created_at->format('M d, Y') : 'Recent' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No withdrawal history found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. SEND & RECEIVE FUNDS TAB -->
        <div x-show="activeTab === 'send-receive'" x-transition>
            <div class="row g-4">
                <!-- Send Funds Form -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-send-fill text-info me-2"></i>Send Funds to Investor</h5>
                        <p class="text-muted mb-4" style="font-size: 0.85rem;">Transfer funds instantly to another platform member using their Email Address or Account ID.</p>

                        <form action="{{ route('send-funds.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Recipient Email or Account ID</label>
                                <input type="text" class="form-control form-control-lg" name="recipient" placeholder="e.g. investor@radiantrealty.com or RDR-884920" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Transfer Amount (USD)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light text-dark fw-bold">$</span>
                                    <input type="number" step="0.01" min="1" max="{{ $walletBalance }}" class="form-control fw-bold" name="amount" placeholder="100.00" required>
                                </div>
                                <div class="form-text">Available Wallet Balance: ${{ number_format($walletBalance, 2) }}</div>
                            </div>

                            <button type="submit" class="btn btn-info text-white btn-lg w-100 fw-bold rounded-3">
                                <i class="bi bi-send me-2"></i>Send Funds Instantly
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Receive Funds Showcase -->
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 text-center">
                        <div class="p-3 bg-light rounded-circle d-inline-flex mx-auto mb-3 text-primary">
                            <i class="bi bi-qr-code-scan fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Receive Funds Into Your Wallet</h5>
                        <p class="text-muted mb-4" style="font-size: 0.85rem;">Share your unique Account ID or registered Email Address to receive direct platform deposits.</p>

                        <div class="bg-light p-4 rounded-4 border mb-4 text-start">
                            <div class="mb-3">
                                <span class="text-muted d-block small">YOUR UNIQUE ACCOUNT ID</span>
                                <div class="d-flex align-items-center justify-content-between bg-white p-2 px-3 rounded border">
                                    <span class="fw-bold text-dark fs-5">{{ $user->account_id ?? 'RDR-884920' }}</span>
                                    <button @click="copyText('{{ $user->account_id ?? 'RDR-884920' }}')" class="btn btn-sm btn-outline-primary fw-semibold">
                                        <i class="bi bi-clipboard me-1"></i>Copy ID
                                    </button>
                                </div>
                            </div>

                            <div>
                                <span class="text-muted d-block small">REGISTERED EMAIL ADDRESS</span>
                                <div class="d-flex align-items-center justify-content-between bg-white p-2 px-3 rounded border">
                                    <span class="fw-semibold text-dark">{{ $user->email ?? 'investor@radiantrealty.com' }}</span>
                                    <button @click="copyText('{{ $user->email ?? 'investor@radiantrealty.com' }}')" class="btn btn-sm btn-outline-primary fw-semibold">
                                        <i class="bi bi-clipboard me-1"></i>Copy Email
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button @click="openReceiveModal = true" class="btn btn-outline-primary fw-bold w-100 rounded-3">
                            <i class="bi bi-share me-2"></i>Open Shareable Receiving Card
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. PROPERTY LISTINGS MARKETPLACE TAB -->
        <div x-show="activeTab === 'marketplace'" x-transition>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-building text-primary me-2"></i>Property Investment Marketplace</h4>
                    <p class="text-muted mb-0" style="font-size: 0.88rem;">Buy fractional shares in premium real estate properties using your wallet balance.</p>
                </div>
                <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill">Wallet Balance: ${{ number_format($walletBalance, 2) }}</span>
            </div>

            <div class="row g-4">
                @foreach($properties as $prop)
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white h-100 overflow-hidden hover-lift">
                            <div class="position-relative">
                                <img src="{{ $prop->image_url }}" class="card-img-top object-fit-cover" style="height: 200px;" alt="{{ $prop->title }}">
                                <span class="position-absolute top-0 start-0 m-3 badge bg-dark bg-opacity-75 text-white fw-semibold px-3 py-1 rounded-pill">
                                    {{ $prop->category }}
                                </span>
                                <span class="position-absolute top-0 end-0 m-3 badge bg-success text-white fw-bold px-3 py-1 rounded-pill">
                                    {{ $prop->roi_percentage }}% ROI
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $prop->title }}</h5>
                                    <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $prop->location }}</p>
                                    
                                    <div class="row text-center g-2 bg-light p-2 rounded-3 mb-3 border">
                                        <div class="col-6 border-end">
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">PRICE / SHARE</small>
                                            <strong class="text-primary">${{ number_format($prop->price_per_share, 2) }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">DURATION</small>
                                            <strong class="text-dark">{{ $prop->investment_duration_months }} Mos</strong>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Available Shares</span>
                                            <span class="fw-bold text-dark">{{ $prop->available_shares }} / {{ $prop->total_shares }}</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ (($prop->total_shares - $prop->available_shares) / $prop->total_shares) * 100 }}%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <button @click="openBuyModal({{ json_encode($prop) }})" class="btn btn-primary w-100 fw-bold rounded-3">
                                    <i class="bi bi-cart-plus me-1"></i>Buy Shares Now
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 6. MY INVESTMENT MATRIX TAB -->
        <div x-show="activeTab === 'my-investments'" x-transition>
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-pie-chart-fill text-primary me-2"></i>My Real Estate Investment Matrix</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Detailed list of your property share ownership, expected returns, and quarterly payout matrix.</p>
                    </div>
                    <button @click="activeTab = 'marketplace'" class="btn btn-outline-primary btn-sm fw-bold rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i>Invest in More Properties
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Property Details</th>
                                <th>Category</th>
                                <th>Shares Owned</th>
                                <th>Capital Invested</th>
                                <th>Expected ROI</th>
                                <th>Earned ROI</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userInvestments as $inv)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $inv->property->image_url ?? 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop' }}" class="rounded-3 object-fit-cover" width="50" height="40" alt="prop">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">{{ $inv->property->title ?? 'Property' }}</h6>
                                                <small class="text-muted">{{ $inv->property->location ?? 'USA' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark fw-semibold border">{{ $inv->property->category ?? 'Real Estate' }}</span></td>
                                    <td class="fw-bold text-dark">{{ $inv->shares_bought }} Shares</td>
                                    <td class="fw-bold text-dark">${{ number_format($inv->total_amount, 2) }}</td>
                                    <td class="text-primary fw-bold">+${{ number_format($inv->expected_roi_amount, 2) }}</td>
                                    <td class="text-success fw-bold">+${{ number_format($inv->roi_earned, 2) }}</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill">Active Yielding</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No active investments found. Start by investing in property shares!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 7. TRANSACTIONS TAB -->
        <div x-show="activeTab === 'transactions'" x-transition>
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-receipt text-primary me-2"></i>Full Transaction Ledger</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ref ID</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                <tr>
                                    <td class="fw-semibold text-primary" style="font-size: 0.85rem;">{{ $txn->reference ?? 'TXN-0001' }}</td>
                                    <td class="text-capitalize">
                                        <span class="badge bg-light text-dark border fw-semibold">{{ str_replace('_', ' ', $txn->type) }}</span>
                                    </td>
                                    <td class="text-dark" style="font-size: 0.88rem;">{{ $txn->description }}</td>
                                    <td class="fw-bold {{ in_array($txn->type, ['deposit', 'roi_payout', 'receive_funds', 'affiliate_earning']) ? 'text-success' : 'text-danger' }}">
                                        {{ in_array($txn->type, ['deposit', 'roi_payout', 'receive_funds', 'affiliate_earning']) ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                                    </td>
                                    <td>
                                        @if($txn->status === 'completed')
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1 rounded-pill">Completed</span>
                                        @elseif($txn->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-15 text-warning-dark fw-bold px-2 py-1 rounded-pill">Pending</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1 rounded-pill">Failed</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 0.8rem;">{{ $txn->created_at ? $txn->created_at->format('M d, Y H:i') : 'Recent' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- POPUP MODAL 1: PAGE-LOAD WELCOME & PORTFOLIO OVERVIEW MODAL COVERING DASHBOARD -->
    <div class="custom-modal-backdrop" x-show="openWelcomeModal" x-cloak x-transition @click.self="openWelcomeModal = false">
        <div class="custom-modal-card p-4 p-md-5 text-center position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-4" @click="openWelcomeModal = false" aria-label="Close"></button>
            
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                <i class="bi bi-building-check fs-1"></i>
            </div>
            
            <h3 class="fw-bold text-dark mb-1">Investor Portfolio Dashboard</h3>
            <p class="text-muted small mb-4">Welcome back! Here is your instantaneous account & investment status overview.</p>

            <div class="p-3 bg-light rounded-4 border mb-4 text-start">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Account ID:</span>
                    <strong class="text-primary">{{ $user->account_id ?? 'RDR-884920' }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Wallet Balance:</span>
                    <strong class="text-success fs-5">${{ number_format($walletBalance, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Active Property Investments:</span>
                    <strong class="text-dark">{{ $activeProjectsCount }} Holdings (${{ number_format($totalInvested, 2) }})</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Total ROI Earned:</span>
                    <strong class="text-success">+${{ number_format($totalRoiEarned, 2) }}</strong>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button @click="openWelcomeModal = false; activeTab = 'marketplace'" class="btn btn-primary btn-lg fw-bold rounded-3">
                    <i class="bi bi-cart-plus me-2"></i>Explore Property Marketplace
                </button>
                <button @click="openWelcomeModal = false; openReceiveModal = true" class="btn btn-outline-secondary fw-semibold rounded-3">
                    <i class="bi bi-qr-code me-2"></i>Share Receiving Account Details
                </button>
            </div>
        </div>
    </div>

    <!-- POPUP MODAL 2: RECEIVE FUNDS SHARE MODAL (FULL OVERLAY COVERING DASHBOARD) -->
    <div class="custom-modal-backdrop" x-show="openReceiveModal" x-cloak x-transition @click.self="openReceiveModal = false">
        <div class="custom-modal-card">
            <div class="bg-primary text-white p-4 d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-qr-code me-2"></i>Receive Funds Account Card</h5>
                <button type="button" class="btn-close btn-close-white" @click="openReceiveModal = false"></button>
            </div>
            <div class="p-4 text-center">
                <div class="p-3 bg-light rounded-4 d-inline-block mb-3 border">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($user->account_id ?? 'RDR-884920') }}" alt="QR Code" class="img-fluid rounded-3">
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name ?? 'Investor' }}</h5>
                <p class="text-muted small mb-4">Share these credentials to receive funds instantly from other platform members.</p>

                <div class="p-3 bg-light rounded-3 text-start mb-4 border">
                    <div class="mb-3">
                        <small class="text-muted d-block">YOUR UNIQUE ACCOUNT ID:</small>
                        <div class="d-flex justify-content-between align-items-center bg-white p-2 px-3 rounded border">
                            <strong class="text-dark fs-5">{{ $user->account_id ?? 'RDR-884920' }}</strong>
                            <button @click="copyText('{{ $user->account_id ?? 'RDR-884920' }}')" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-clipboard me-1"></i>Copy ID
                            </button>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block">REGISTERED USER EMAIL:</small>
                        <div class="d-flex justify-content-between align-items-center bg-white p-2 px-3 rounded border">
                            <strong class="text-dark">{{ $user->email ?? 'investor@radiantrealty.com' }}</strong>
                            <button @click="copyText('{{ $user->email ?? 'investor@radiantrealty.com' }}')" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-clipboard me-1"></i>Copy Email
                            </button>
                        </div>
                    </div>
                </div>

                <button @click="shareAccountDetails()" class="btn btn-primary w-100 fw-bold rounded-3 py-3">
                    <i class="bi bi-share me-2"></i>Share Receiving Credentials
                </button>
            </div>
        </div>
    </div>

    <!-- POPUP MODAL 3: BUY PROPERTY SHARES MODAL (FULL OVERLAY COVERING DASHBOARD) -->
    <div class="custom-modal-backdrop" x-show="selectedProperty" x-cloak x-transition @click.self="selectedProperty = null">
        <div class="custom-modal-card" x-show="selectedProperty">
            <div class="bg-primary text-white p-4 d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-cart-plus me-2"></i>Buy Property Shares</h5>
                <button type="button" class="btn-close btn-close-white" @click="selectedProperty = null"></button>
            </div>
            <div class="p-4" x-show="selectedProperty">
                <form action="{{ route('buy-shares.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_id" :value="selectedProperty.id">

                    <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3 border">
                        <img :src="selectedProperty.image_url" class="rounded-3 object-fit-cover" width="70" height="60">
                        <div>
                            <h6 class="fw-bold text-dark mb-0" x-text="selectedProperty.title"></h6>
                            <small class="text-muted"><i class="bi bi-geo-alt text-danger me-1"></i><span x-text="selectedProperty.location"></span></small>
                            <div class="text-success fw-bold small"><span x-text="selectedProperty.roi_percentage"></span>% Expected Annual ROI</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small mb-1">Price Per Share</label>
                            <div class="fw-bold text-dark fs-5">$<span x-text="selectedProperty.price_per_share"></span></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small mb-1">Available Shares</label>
                            <div class="fw-bold text-primary fs-5" x-text="selectedProperty.available_shares"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Number of Shares to Buy</label>
                        <input type="number" min="1" :max="selectedProperty.available_shares" class="form-control form-control-lg fw-bold" name="shares" x-model.number="buySharesCount" required>
                    </div>

                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 border border-primary mb-4 text-dark">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Investment Cost:</span>
                            <strong class="text-primary fs-5">$<span x-text="(buySharesCount * (selectedProperty.price_per_share || 0)).toFixed(2)"></span></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-muted small">
                            <span>Expected Annual ROI Return:</span>
                            <strong class="text-success">+$<span x-text="((buySharesCount * (selectedProperty.price_per_share || 0) * (selectedProperty.roi_percentage || 0)) / 100).toFixed(2)"></span></strong>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Available Wallet Balance:</span>
                            <span class="fw-bold">${{ number_format($walletBalance, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3">
                        <i class="bi bi-check-circle me-2"></i>Confirm Share Purchase
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Alpine JS Engine Script -->
<script>
    function userDashboardEngine() {
        return {
            activeTab: 'overview',
            depositMethod: 'bank_transfer',
            withdrawMethod: 'bank_transfer',
            openWelcomeModal: false, // Hidden by default on page load
            openReceiveModal: false,
            selectedProperty: null,
            buySharesCount: 1,
            // Card details live preview
            cardNumber: '',
            cardName: '',
            cardExpiry: '',
            // Crypto network selector
            cryptoNetwork: '',

            openBuyModal(prop) {
                this.selectedProperty = prop;
                this.buySharesCount = 1;
            },

            copyText(text) {
                navigator.clipboard.writeText(text);
                alert('Copied to clipboard: ' + text);
            },

            copyReferralCode() {
                const code = '{{ $user->affiliate_code ?? "RAD8849" }}';
                navigator.clipboard.writeText(window.location.origin + '?ref=' + code);
                alert('Referral link copied to clipboard!');
            },

            shareAccountDetails() {
                const text = `Radiant Dream Realty Receiving Credentials:\nAccount ID: {{ $user->account_id ?? "RDR-884920" }}\nEmail: {{ $user->email ?? "investor@radiantrealty.com" }}`;
                if (navigator.share) {
                    navigator.share({
                        title: 'Radiant Realty Receiving Account',
                        text: text,
                    }).catch(() => {});
                } else {
                    navigator.clipboard.writeText(text);
                    alert('Account receiving details copied to clipboard!');
                }
            }
        }
    }
</script>
@endsection
