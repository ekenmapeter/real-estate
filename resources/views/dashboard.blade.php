@extends('layouts.main')

@section('title', 'User Dashboard | ' . site_name() . ' Investment Platform')

@section('content')

@if($showTour)
<div x-data="siteTourGuard()" x-init="initTour" x-cloak>
    <template x-if="tourStep !== null">
        <div class="tour-backdrop" style="position:fixed;inset:0;z-index:999999;background:rgba(11,19,41,0.8);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;" @click="skipTour">
            <div class="tour-card bg-white rounded-4 shadow-lg p-4 text-center" style="max-width:440px;width:90%;" @click.stop>
                <template x-if="tourStep === 0">
                    <div>
                        <div class="mb-3" style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-compass-fill text-white" style="font-size:2.2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#0f172a;">Welcome to Your Dashboard!</h4>
                        <p class="text-muted small mb-3" style="font-size:0.85rem;">Let's take a quick tour of the key features to get you started.</p>
                        <button class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);" @click="tourStep = 1">Start Tour</button>
                        <button class="btn btn-link text-muted small d-block mx-auto mt-2" style="text-decoration:none;font-size:0.8rem;" @click="skipTour">Skip</button>
                    </div>
                </template>
                <template x-if="tourStep === 1">
                    <div>
                        <div class="mb-3" style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-list-ul text-white" style="font-size:2.2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#0f172a;">Sidebar Navigation</h4>
                        <p class="text-muted small mb-3" style="font-size:0.85rem;">Use the sidebar on the left to navigate between sections — Dashboard overview, your portfolio, wallet, and account settings.</p>
                        <button class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#f59e0b,#d97706);" @click="tourStep = 2">Next</button>
                    </div>
                </template>
                <template x-if="tourStep === 2">
                    <div>
                        <div class="mb-3" style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-wallet2 text-white" style="font-size:2.2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#0f172a;">Fund Your AVC Balance</h4>
                        <p class="text-muted small mb-3" style="font-size:0.85rem;">Go to the Deposit tab to add funds. Once your AVC balance is funded, you can invest in projects or purchase premium properties directly.</p>
                        <button class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#10b981,#059669);" @click="tourStep = 3">Next</button>
                    </div>
                </template>
                <template x-if="tourStep === 3">
                    <div>
                        <div class="mb-3" style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-building text-white" style="font-size:2.2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#0f172a;">Browse & Invest</h4>
                        <p class="text-muted small mb-3" style="font-size:0.85rem;">Browse projects in the Invest tab with flexible minimums, or purchase properties outright from Browse Properties — all from your AVC balance.</p>
                        <button class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);" @click="tourStep = 4">Next</button>
                    </div>
                </template>
                <template x-if="tourStep === 4">
                    <div>
                        <div class="mb-3" style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#ef4444,#dc2626);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-shield-check text-white" style="font-size:2.2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#0f172a;">Verify Your Account</h4>
                        <p class="text-muted small mb-3" style="font-size:0.85rem;">Complete your KYC verification in the Profile tab to unlock all features and higher investment limits.</p>
                        <button class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#ef4444,#dc2626);" @click="tourStep = 5">Next</button>
                    </div>
                </template>
                <template x-if="tourStep === 5">
                    <div>
                        <div class="mb-3" style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#2563eb,#1d4ed8);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-check-circle-fill text-white" style="font-size:2.2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#0f172a;">You're All Set!</h4>
                        <p class="text-muted small mb-3" style="font-size:0.85rem;">You're ready to start your real estate investment journey. If you have questions, contact our support team anytime.</p>
                        <button class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);" @click="skipTour">Get Started</button>
                    </div>
                </template>
                <div class="d-flex justify-content-center gap-1 mt-2">
                    <template x-for="(_, i) in [0,1,2,3,4,5]" :key="i">
                        <span :class="tourStep === i ? 'bg-primary' : 'bg-secondary'" style="width:8px;height:8px;border-radius:50%;display:inline-block;opacity:0.4;"></span>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>
@endif
<style>
    [x-cloak] { display: none !important; }

    /* Dashboard Layout Styles */
    body {
        background-color: #f8fafc !important;
    }

    .sidebar-dark {
        background-color: #0b1329 !important;
        color: #ffffff;
        min-height: calc(100vh - 70px);
        overflow-y: auto;
    }

    .sidebar-dark .nav-link-sidebar {
        color: #94a3b8;
        padding: 9px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        cursor: pointer;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
    }

    .sidebar-dark .nav-link-sidebar:hover,
    .sidebar-dark .nav-link-sidebar.active {
        color: #ffffff !important;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }

    .sidebar-dark .nav-link-sidebar i {
        font-size: 1.05rem;
        width: 18px;
        flex-shrink: 0;
    }

    /* Sidebar section group labels */
    .sidebar-group-label {
        color: #475569;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        padding: 0 14px;
        margin-top: 14px;
        margin-bottom: 4px;
        display: block;
    }

    /* Sub-items indented slightly */
    .sidebar-dark .nav-link-sub {
        padding-left: 20px;
        font-size: 0.83rem;
    }

    .drag-drop-zone {
        border: 2px dashed #cbd5e1;
        background: #f8fafc;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }

    .drag-drop-zone:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }

    /* Modal Glass Backdrop */
    .custom-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(11, 19, 41, 0.75) !important;
        backdrop-filter: blur(10px) !important;
        z-index: 99999 !important;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 1rem;
        overflow-y: auto;
    }

    .custom-modal-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 540px;
        width: 100%;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin: auto;
    }

    /* ── 3D Flip Card (Crypto Card Widget) ── */
    .card3d-scene {
        perspective: 1200px;
    }

    .card3d-flip {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.7s cubic-bezier(0.4, 0.2, 0.2, 1);
    }

    .card3d-flip.flipped {
        transform: rotateY(180deg);
    }

    .card3d-face {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        border-radius: 20px;
        overflow: hidden;
    }

    .card3d-back {
        transform: rotateY(180deg);
    }

    @media (max-width: 767.98px) {
        .card3d-flip {
            height: 210px !important;
        }
    }
</style>

<div class="container-fluid px-0" x-data="userDashboardEngine()">
    <div class="row g-0">

        <!-- Left Dark Navy Sidebar -->
        <div class="col-lg-3 col-md-4 sidebar-dark p-3 d-none d-md-block">
            <!-- User Profile Widget at top of sidebar -->
            <div class="p-3 mb-4 rounded-3 text-white" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;">
                        {{ strtoupper(substr($user->name ?? 'JS', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.95rem;">{{ $user->name ?? 'John Smith' }}</h6>
                        <div class="d-flex gap-1 mt-1">
                            <small class="badge bg-primary bg-opacity-20 text-blue-300 fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; color: #93c5fd;">Investor</small>
                            @if($user->kyc_verified)
                                <small class="badge fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; background:rgba(34,197,94,0.15); color:#22c55e;"><i class="bi bi-patch-check-fill me-1"></i>Verified</small>
                            @else
                                <small class="badge fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; background:rgba(248,113,113,0.15); color:#f87171;"><i class="bi bi-shield-exclamation me-1"></i>Unverified</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="nav flex-column pb-3">

                <!-- GROUP: OVERVIEW -->
                <span class="sidebar-group-label">Overview</span>
                <a href="#" class="nav-link-sidebar" :class="{ 'active': activeTab === 'overview' }" @click.prevent="activeTab = 'overview'">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>

                <!-- GROUP: MY PORTFOLIO -->
                <span class="sidebar-group-label">My Portfolio</span>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'invest' }" @click.prevent="activeTab = 'invest'">
                    <i class="bi bi-shop"></i> Project Marketplace
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'saved_projects' }" @click.prevent="activeTab = 'saved_projects'">
                    <i class="bi bi-bookmark-star-fill"></i> Saved Projects
                    @if($savedProjects->count() > 0)
                        <span id="savedProjectsBadge" class="badge ms-auto rounded-pill" style="background:#f59e0b; color:#1a1a1a; font-size:0.7rem;">{{ $savedProjects->count() }}</span>
                    @endif
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'marketplace' }" @click.prevent="activeTab = 'marketplace'">
                    <i class="bi bi-building"></i> Browse Properties
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'my_investments' }" @click.prevent="activeTab = 'my_investments'">
                    <i class="bi bi-pie-chart-fill"></i> My Portfolio
                </a>

                <!-- GROUP: WALLET -->
                <span class="sidebar-group-label">Wallet</span>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'deposit' }" @click.prevent="activeTab = 'deposit'">
                    <i class="bi bi-arrow-down-circle-fill"></i> Deposit
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'withdraw' }" @click.prevent="activeTab = 'withdraw'">
                    <i class="bi bi-arrow-up-circle-fill"></i> Withdraw
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'transfer' }" @click.prevent="activeTab = 'transfer'">
                    <i class="bi bi-send-fill"></i> Transfer
                </a>
                <a href="{{ route('marketplace') }}" class="nav-link-sidebar nav-link-sub">
                    <i class="bi bi-arrow-repeat text-warning"></i> AVC Marketplace
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'transactions' }" @click.prevent="activeTab = 'transactions'">
                    <i class="bi bi-arrow-down-up"></i> Transactions
                </a>

                <!-- GROUP: ACCOUNT -->
                <span class="sidebar-group-label">Account</span>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'notifications' }" @click.prevent="activeTab = 'notifications'">
                    <i class="bi bi-bell-fill"></i> Notifications
                    @if($deposits->where('status', 'awaiting_payment')->count() > 0)
                        <span class="badge ms-auto rounded-pill" style="background:#f59e0b; color:#1a1a1a; font-size:0.7rem;">{{ $deposits->where('status', 'awaiting_payment')->count() }}</span>
                    @endif
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'referrals' }" @click.prevent="activeTab = 'referrals'">
                    <i class="bi bi-people-fill"></i> Referrals
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'profile_kyc' }" @click.prevent="activeTab = 'profile_kyc'">
                    <i class="bi bi-person-badge-fill"></i> Profile & KYC
                </a>

                <!-- DIVIDER -->
                <hr class="border-secondary opacity-20 my-3">

                <!-- GROW YOUR WEALTH CTA -->
                <div class="mx-1 mb-3 rounded-3 p-3" style="background:linear-gradient(135deg,#1e3a8a,#2563eb); border:1px solid rgba(255,255,255,0.1);">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-graph-up-arrow text-warning" style="font-size:1rem;"></i>
                        <span class="fw-bold text-white small">Grow Your Wealth</span>
                    </div>
                    <p class="text-white mb-2" style="font-size:0.72rem; opacity:0.8; line-height:1.4;">Start investing today and watch your portfolio grow.</p>
                    <button class="btn btn-warning btn-sm fw-bold w-100 rounded-3" style="font-size:0.75rem;" @click="activeTab = 'invest'">
                        <i class="bi bi-lightning-fill me-1"></i> Invest Now
                    </button>
                </div>

                <!-- LANGUAGE & APPEARANCE -->
                <div class="px-1 mb-3" x-data="{ lang: 'en', darkMode: false }">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-semibold" style="color:#94a3b8; letter-spacing:0.05em; font-size:0.7rem;">LANGUAGE</span>
                        <select x-model="lang" class="form-select form-select-sm border-0 rounded-2 fw-semibold" style="background:#1e293b; color:#e2e8f0; font-size:0.72rem; width:auto; padding:2px 6px; cursor:pointer;">
                            <option value="en">🇺🇸 English</option>
                            <option value="es">🇪🇸 Español</option>
                            <option value="zh">🇨🇳 中文</option>
                            <option value="ar">🇸🇦 Arabic</option>
                            <option value="ph">🇵🇭 Filipino</option>
                        </select>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small fw-semibold" style="color:#94a3b8; letter-spacing:0.05em; font-size:0.7rem;">APPEARANCE</span>
                        <button @click="darkMode = !darkMode; document.documentElement.setAttribute('data-bs-theme', darkMode ? 'dark' : 'light')" class="btn btn-sm rounded-pill d-flex align-items-center gap-1 fw-bold" :style="darkMode ? 'background:#334155; color:#f8fafc;' : 'background:#334155; color:#94a3b8;'" style="font-size:0.72rem; padding:3px 10px; border:none;">
                            <i class="bi" :class="darkMode ? 'bi-sun-fill text-warning' : 'bi-moon-fill'"></i>
                            <span x-text="darkMode ? 'Light' : 'Dark'"></span>
                        </button>
                    </div>
                </div>

                <!-- VIEW SITE & LOGOUT -->
                <a href="{{ url('/') }}" class="nav-link-sidebar" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> View Site
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="nav-link-sidebar" style="color: #f87171;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>

            </nav>
        </div>

        <!-- Main Dashboard Body Area -->
        <div class="col-lg-9 col-md-8 p-3 p-lg-4" style="min-height: calc(100vh - 70px); background: #f8fafc;">

            @if(session('impersonating'))
                <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center justify-content-between" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-workspace fs-4 me-3" style="color:#d97706;"></i>
                        <div>
                            <strong class="d-block">Admin Preview Mode</strong>
                            <span class="small">You are viewing this dashboard as <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }}). All actions are performed on this user's account.</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.impersonate.stop') }}" class="ms-3 flex-shrink-0">
                        @csrf
                        <button type="submit" class="btn btn-sm fw-bold px-3" style="background:#d97706; color:#fff; border:none;">
                            <i class="bi bi-arrow-left me-1"></i> Return to Admin
                        </button>
                    </form>
                </div>
            @endif

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
                        <strong class="d-block">Notification</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Mobile Navigation Tab Strip (Visible on mobile/tablets) -->
            <div class="d-md-none mb-3 overflow-x-auto pb-2">
                <div class="d-flex gap-1" style="white-space: nowrap;">
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'overview' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'overview'">Dashboard</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'invest' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'invest'">Project Marketplace</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'saved_projects' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'saved_projects'">Saved</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'marketplace' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'marketplace'">Browse</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'my_investments' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'my_investments'">My Portfolio</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'deposit' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'deposit'">Deposit</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'withdraw' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'withdraw'">Withdraw</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'transfer' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'transfer'">Transfer</button>
                    <a href="{{ route('marketplace') }}" class="btn btn-sm btn-warning fw-semibold text-dark text-decoration-none">AVC Marketplace</a>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'transactions' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'transactions'">Transactions</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'notifications' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'notifications'">Notifications</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'referrals' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'referrals'">Referrals</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'profile_kyc' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'profile_kyc'">Profile & KYC</button>
                </div>
            </div>

            <!-- STEP 1: DASHBOARD OVERVIEW -->
            <div x-show="activeTab === 'overview'" x-transition>
                <!-- Top Header Card -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="d-flex flex-wrap align-items-stretch justify-content-between gap-3">
                        <div class="rounded-4 p-4 text-white position-relative overflow-hidden flex-fill d-flex flex-column justify-content-center" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#334155 100%); box-shadow:0 8px 32px rgba(15,23,42,0.25); min-height:140px;">
                            <div class="position-absolute top-0 start-0 opacity-5" style="font-size:7rem; line-height:1; transform:rotate(-10deg) translate(-15px,-10px);"><i class="bi bi-person-badge-fill"></i></div>
                            <div class="d-flex align-items-center gap-2 mb-1 position-relative">
                                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:42px; height:42px; font-size:0.95rem;">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-0 text-white" style="font-size:1.7rem;">Dashboard</h3>
                                    <span class="fw-bold" style="font-size:0.7rem; color:#fff;">{{ $user->name ? $user->name : 'New User' }}</span>
                                        @if($user->kyc_verified)
                                            <span class="badge fw-semibold ms-2 rounded-pill px-2 py-0.5" style="font-size:0.65rem; background:rgba(34,197,94,0.2); color:#22c55e;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                                        @else
                                            <span class="badge fw-semibold ms-2 rounded-pill px-2 py-0.5" style="font-size:0.65rem; background:rgba(248,113,113,0.2); color:#f87171;"><i class="bi bi-shield-exclamation me-1"></i>Unverified</span>
                                        @endif
                                </div>
                            </div>
                            <p class="mb-0 position-relative mt-1 fw-semibold" style="font-size:0.9rem; color:#fff; max-width:340px;">Overview of your real estate investment portfolio and account activity.</p>
                        </div>
                        <div class="rounded-4 p-4 text-white position-relative overflow-hidden flex-fill" style="min-width:320px; background:linear-gradient(135deg,#1a3c5e 0%,#2563eb 50%,#1d4ed8 100%); box-shadow:0 8px 32px rgba(37,99,235,0.3);">
                            <div class="position-absolute top-0 end-0 opacity-10" style="font-size:6rem; line-height:1; transform:rotate(15deg) translate(10px,-10px);"><i class="bi bi-credit-card-fill"></i></div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold" style="letter-spacing:0.1em; color:rgba(255,255,255,0.75);">AVC BALANCE</span>
                                <i class="bi bi-wallet2 fs-5"></i>
                            </div>
                            <h2 class="fw-bold mb-1 text-white" style="font-size:2.4rem; letter-spacing:-0.5px;">{{ format_avc($walletBalance) }}</h2>
                            <div class="small mb-3" style="color:rgba(255,255,255,0.85); font-size:0.8rem;">&asymp; {{ avc_equivalent($walletBalance, $preferredCurrency) }} <span class="opacity-75">({{ $preferredCurrency }})</span> &middot; <span class="opacity-75">1 AVC = 1 USD</span></div>
                            <div class="small mb-3" style="color:rgba(255,255,255,0.55); font-size:0.75rem;">Available to deposit, withdraw &amp; invest</div>
                            <hr class="mb-3" style="border-color:rgba(255,255,255,0.15);">
                            <div class="d-flex gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2 flex-fill">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px; background:rgba(255,255,255,0.12);">
                                        <i class="bi bi-arrow-down-circle fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white" style="font-size:0.8rem; line-height:1.2;">{{ format_avc($totalDeposits) }}</div>
                                        <small style="font-size:0.65rem; opacity:0.7;">Total Deposited</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-fill">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px; background:rgba(255,255,255,0.12);">
                                        <i class="bi bi-arrow-up-circle fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white" style="font-size:0.8rem; line-height:1.2;">{{ format_avc($totalWithdrawals) }}</div>
                                        <small style="font-size:0.65rem; opacity:0.7;">Total Withdrawn</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-fill">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px; background:rgba(255,255,255,0.12);">
                                        <i class="bi bi-building fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white" style="font-size:0.8rem; line-height:1.2;">{{ format_avc($totalInvested) }}</div>
                                        <small style="font-size:0.65rem; opacity:0.7;">Total Invested</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Quick Action Buttons Bar -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <span class="fw-bold text-dark small"><i class="bi bi-lightning-fill text-warning me-1"></i> Quick Actions:</span>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm" style="background:#2563eb;" @click="activeTab = 'deposit'">
                                <i class="bi bi-plus-circle me-1.5"></i> Deposit Funds
                            </button>
                            <button class="btn btn-outline-primary fw-bold px-4 py-2 rounded-3" @click="activeTab = 'marketplace'">
                                <i class="bi bi-building me-1.5"></i> Browse Properties
                            </button>
                            <button class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3" @click="activeTab = 'withdraw'">
                                <i class="bi bi-arrow-up-circle me-1.5"></i> Withdraw
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CRYPTO CARD WIDGET -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <h6 class="fw-bold text-dark mb-0" style="font-size:0.95rem;"><i class="bi bi-credit-card-2-front text-primary me-2"></i>My Crypto Card</h6>
                                @if($userCard && $userCard->status === 'approved')
                                    <span class="badge fw-bold rounded-pill px-3 py-1" style="background:#f0fdf4; color:#16a34a;"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                                @elseif($userCard && $userCard->status === 'pending')
                                    <span class="badge fw-bold rounded-pill px-3 py-1" style="background:#fffbeb; color:#b45309;"><i class="bi bi-clock-fill me-1"></i>Pending Review</span>
                                @elseif($userCard && $userCard->status === 'rejected')
                                    <span class="badge fw-bold rounded-pill px-3 py-1" style="background:#fef2f2; color:#dc2626;"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                                @else
                                    <span class="badge fw-bold rounded-pill px-3 py-1" style="background:#f1f5f9; color:#64748b;">Not Issued</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-center justify-content-lg-start">
                                <div class="card3d-scene w-100" style="max-width:420px;">
                                    <div :class="cardFlipped ? 'card3d-flip flipped' : 'card3d-flip'" style="width:100%; height:230px;">
                                        @if($userCard && $userCard->status === 'approved')
                                            <!-- FRONT FACE -->
                                            <div class="card3d-face text-white p-4 d-flex flex-column justify-content-between" style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 55%,#2563eb 100%); box-shadow:0 18px 40px rgba(30,58,138,0.35); cursor:pointer;" @click="cardFlipped = !cardFlipped">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="fw-bold" style="font-size:0.72rem; letter-spacing:0.12em; opacity:0.9;">{{ $userCard->card_brand ?? 'RADIANT' }} CRYPTO CARD</div>
                                                        <div class="text-white-50" style="font-size:0.6rem; letter-spacing:0.14em;">PREMIUM &middot; DIGITAL</div>
                                                    </div>
                                                    <i class="bi bi-credit-card-2-front" style="font-size:1.5rem; opacity:0.9;"></i>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        <div class="rounded-2" style="width:40px; height:28px; background:linear-gradient(135deg,#f59e0b,#d97706);"></div>
                                                        <i class="bi bi-wifi text-white-50" style="font-size:1.1rem; transform:rotate(90deg);"></i>
                                                    </div>
                                                    <div class="fw-bold mb-3" style="font-size:1.25rem; letter-spacing:0.14em; font-variant-numeric:tabular-nums;">{{ $userCard->maskedNumber() }}</div>
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div class="overflow-hidden" style="max-width:190px;">
                                                            <div class="text-white-50" style="font-size:0.58rem; letter-spacing:0.1em;">CARDHOLDER</div>
                                                            <div class="fw-bold text-truncate" style="font-size:0.85rem;">{{ $userCard->cardholder_name }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="text-white-50" style="font-size:0.58rem; letter-spacing:0.1em;">EXPIRES</div>
                                                            <div class="fw-bold" style="font-size:0.85rem;">{{ $userCard->expiryLabel() }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- BACK FACE -->
                                            <div class="card3d-face card3d-back d-flex flex-column" style="background:linear-gradient(160deg,#1e293b,#0f172a); box-shadow:0 18px 40px rgba(15,23,42,0.35); cursor:pointer;" @click="cardFlipped = !cardFlipped">
                                                <div style="height:44px; background:#0a0f1c; margin-top:28px;"></div>
                                                <div class="p-4 d-flex flex-column" style="flex:1; gap:12px;">
                                                    <div class="d-flex align-items-center justify-content-between rounded-3 px-3 py-2 bg-white">
                                                        <span class="fw-bold" style="font-family:monospace; letter-spacing:0.22em; color:#0f172a; font-size:1rem;">{{ $userCard->cvv }}</span>
                                                        <span class="text-muted small fw-bold" style="font-size:0.62rem; letter-spacing:0.08em;">CVV</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between rounded-3 px-3 py-2 bg-white">
                                                        <span class="text-dark fw-bold" style="font-family:'Brush Script MT', cursive; font-size:1.2rem;">{{ $userCard->cardholder_name }}</span>
                                                        <span class="text-muted small fw-bold" style="font-size:0.62rem; letter-spacing:0.08em;">SIGNATURE</span>
                                                    </div>
                                                    <div class="text-white-50 small mt-auto" style="font-size:0.62rem; line-height:1.5;">
                                                        <i class="bi bi-shield-lock me-1"></i> Digital crypto card. Never share your CVV. Contact support immediately if your card is lost or stolen.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- PLACEHOLDER FACE -->
                                            <div class="card3d-face d-flex flex-column align-items-center justify-content-center text-center p-4" style="background:linear-gradient(135deg,#f8fafc,#eef2f7); border:2px dashed #cbd5e1;">
                                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width:64px; height:64px;">
                                                    <i class="bi bi-credit-card-2-front text-muted" style="font-size:1.8rem;"></i>
                                                </div>
                                                <div class="fw-bold text-dark mb-1">{{ $userCard && $userCard->status === 'pending' ? 'Application Under Review' : 'No Crypto Card Yet' }}</div>
                                                <p class="text-muted small mb-0" style="max-width:280px;">
                                                    @if($userCard && $userCard->status === 'pending')
                                                        Your application is being reviewed by our team. Card details will be generated and emailed to you once approved.
                                                    @elseif($userCard && $userCard->status === 'rejected')
                                                        Your last application was declined. You may apply again anytime.
                                                    @else
                                                        Apply for your branded crypto card. Once approved, your card is generated instantly and delivered by email.
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    @if($userCard && $userCard->status === 'approved')
                                        <div class="text-center text-muted small mt-2" style="font-size:0.72rem;">
                                            <i class="bi bi-mouse me-1"></i><span x-text="cardFlipped ? 'Click to flip back' : 'Click the card to flip it'"></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="h-100 d-flex flex-column justify-content-center">
                                @if($userCard && $userCard->status === 'approved')
                                    <h5 class="fw-bold text-dark mb-2" style="font-size:1.15rem;">Your card is ready!</h5>
                                    <p class="text-muted small mb-3" style="line-height:1.6;">Your Crypto Card details have been generated and sent to <strong class="text-dark">{{ $user->email }}</strong>. Tap the card to flip it and reveal your CVV whenever needed.</p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-outline-primary fw-bold px-4 py-2 rounded-3" style="font-size:0.85rem;" @click="cardFlipped = !cardFlipped">
                                            <i class="bi bi-arrow-repeat me-1"></i> Flip Card
                                        </button>
                                    </div>
                                @elseif($userCard && $userCard->status === 'pending')
                                    <h5 class="fw-bold text-dark mb-2" style="font-size:1.15rem;">Application under review</h5>
                                    <p class="text-muted small mb-0" style="line-height:1.6;">Our team is verifying your application. Once approved, your card details will be generated and emailed to you automatically. This usually takes 1&ndash;2 business days.</p>
                                @elseif($userCard && $userCard->status === 'rejected')
                                    <h5 class="fw-bold text-dark mb-2" style="font-size:1.15rem;">Application declined</h5>
                                    <p class="text-muted small mb-3" style="line-height:1.6;">
                                        Reason: <strong class="text-danger">{{ $userCard->rejection_reason ?? 'Not provided' }}</strong>. You can submit a new application whenever you are ready.
                                    </p>
                                    <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3" style="background:#2563eb; font-size:0.85rem;" @click="openCardApplyModal = true">
                                        <i class="bi bi-credit-card me-1"></i> Apply Again
                                    </button>
                                @else
                                    <h5 class="fw-bold text-dark mb-2" style="font-size:1.15rem;">Get your Crypto Card</h5>
                                    <p class="text-muted small mb-3" style="line-height:1.6;">Apply for a branded digital crypto card. Our team reviews your application, then your card is generated with a unique number, expiry and CVV &mdash; delivered straight to your email.</p>
                                    <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm" style="background:#2563eb; font-size:0.85rem;" @click="openCardApplyModal = true">
                                        <i class="bi bi-credit-card me-1"></i> Apply for Crypto Card
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Investment vs Returns & Referral Program Section -->
                <div class="row g-4 mb-4">
                    <!-- Investment vs Returns Visual Progress Card -->
                    <div class="col-lg-7">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Investment vs Returns</h6>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2.5 py-1 rounded-pill">Active Portfolio</span>
                            </div>
                            <p class="text-muted small mb-4">Comparison of capital invested against total returns generated.</p>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span class="text-muted"><i class="bi bi-circle-fill text-primary me-1" style="font-size:0.6rem;"></i> Total Invested Capital</span>
                                    <span class="text-dark">{{ format_avc($totalInvested) }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height:10px;">
                                    <div class="progress-bar" style="width: {{ $totalInvested > 0 ? min(100, round(($totalInvested / max(1, $totalInvested + $totalRoiEarned)) * 100)) : 0 }}%; background: linear-gradient(90deg, #2563eb, #3b82f6);"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span class="text-muted"><i class="bi bi-circle-fill text-success me-1" style="font-size:0.6rem;"></i> Total Returns Earned</span>
                                    <span class="text-success">{{ format_avc($totalRoiEarned) }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height:10px;">
                                    <div class="progress-bar bg-success" style="width: {{ $totalRoiEarned > 0 ? min(100, round(($totalRoiEarned / max(1, $totalInvested + $totalRoiEarned)) * 100)) : 0 }}%;"></div>
                                </div>
                            </div>

                            <div class="pt-3 border-top d-flex justify-content-between align-items-center text-muted small mt-auto">
                                <span>Portfolio Net Return:</span>
                                <strong class="text-success fs-6">+{{ $totalInvested > 0 ? number_format(($totalRoiEarned / $totalInvested) * 100, 1) : '0.0' }}%</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Program Card -->
                    <div class="col-lg-5">
                        <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%); color:#fff;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-people-fill fs-4 text-warning"></i>
                                <h6 class="fw-bold text-white mb-0">Referral Program</h6>
                            </div>
                            <p class="small mb-3" style="color:#93c5fd;">Share your referral link and earn 5% bonus on referral investments.</p>
                            
                            <!-- Referral Link Box -->
                            <div class="mb-3">
                                <label class="form-label small text-white-50 mb-1">Referral Link</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control bg-dark bg-opacity-50 text-white border-secondary small" value="{{ url('/register?ref=' . ($user->affiliate_code ?? '32C8A530')) }}" readonly>
                                    <button class="btn btn-primary fw-bold" style="background:#2563eb;" onclick="navigator.clipboard.writeText('{{ url('/register?ref=' . ($user->affiliate_code ?? '32C8A530')) }}'); alert('Referral link copied!')">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Referral Code Box -->
                            <div>
                                <label class="form-label small text-white-50 mb-1">Referral Code</label>
                                <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                    <code class="fs-6 fw-bold text-white">Code: {{ $user->affiliate_code ?? '32C8A530' }}</code>
                                    <button class="btn btn-sm btn-outline-light py-0.5 px-2 fw-bold" onclick="navigator.clipboard.writeText('{{ $user->affiliate_code ?? '32C8A530' }}'); alert('Code copied!')">
                                        Copy Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Summary Lists (Notifications, Active Investments, Recent Transactions) -->
                <div class="row g-4">
                    <!-- Recent Notifications / Activity Log -->
                    <div class="col-12 col-md-4">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-activity me-1 text-primary"></i>Recent Activity</h6>
                                <button class="btn btn-link text-primary p-0 fw-bold small text-decoration-none" @click="activeTab = 'transactions'">View All</button>
                            </div>
                            <div class="list-group list-group-flush">
                                @forelse($transactions->take(6) as $txn)
                                    @php
                                        $iconMap = [
                                            'deposit' => ['bi bi-arrow-down-circle-fill', '#2563eb', '#eff6ff'],
                                            'withdrawal' => ['bi bi-arrow-up-circle-fill', '#f59e0b', '#fffbeb'],
                                            'property_investment' => ['bi bi-building', '#16a34a', '#f0fdf4'],
                                            'send_funds' => ['bi bi-send', '#dc2626', '#fef2f2'],
                                            'receive_funds' => ['bi bi-inbox', '#16a34a', '#f0fdf4'],
                                        ];
                                        $ico = $iconMap[$txn->type] ?? ['bi bi-circle', '#64748b', '#f8fafc'];
                                    @endphp
                                    <div class="list-group-item px-0 py-2 border-bottom d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px; background:{{ $ico[2] }};">
                                            <i class="{{ $ico[0] }}" style="color:{{ $ico[1] }}; font-size:0.85rem;"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold small text-dark text-truncate">{{ $txn->description }}</div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="text-muted" style="font-size:0.7rem;">${{ number_format($txn->amount, 2) }}</span>
                                                <span class="badge fw-semibold px-1.5 py-0.5 rounded-pill" style="font-size:0.62rem; {{ $txn->status === 'completed' ? 'background:#f0fdf4; color:#16a34a;' : ($txn->status === 'pending' ? 'background:#fffbeb; color:#d97706;' : 'background:#fef2f2; color:#dc2626;') }}">
                                                    {{ ucfirst($txn->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <small class="text-muted flex-shrink-0" style="font-size:0.65rem;">{{ $txn->created_at?->diffForHumans() }}</small>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-muted small">No activity yet. Start investing to see your log here.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Active Investments -->
                    <div class="col-12 col-md-4">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pie-chart me-1 text-success"></i>Active Investments</h6>
                                <button class="btn btn-link text-primary p-0 fw-bold small text-decoration-none" @click="activeTab = 'my_investments'">View All</button>
                            </div>
                            <div class="list-group list-group-flush">
                                @forelse($userInvestments->where('status', 'active')->take(3) as $inv)
                                    <div class="list-group-item px-0 py-2 border-bottom d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark small text-truncate" style="max-width: 140px;">{{ $inv->property->title ?? 'Property' }}</div>
                                            <small class="text-muted">{{ $inv->shares_bought }} Shares</small>
                                        </div>
                                        <span class="fw-bold text-success small">${{ number_format($inv->total_amount, 2) }}</span>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-muted small">No active investments yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="col-12 col-md-4">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-arrow-down-up me-1 text-info"></i>Recent Transactions</h6>
                                <button class="btn btn-link text-primary p-0 fw-bold small text-decoration-none" @click="activeTab = 'transactions'">View All</button>
                            </div>
                            <div class="list-group list-group-flush">
                                @forelse($transactions->take(3) as $txn)
                                    <div class="list-group-item px-0 py-2 border-bottom d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark small">{{ ucwords(str_replace('_', ' ', $txn->type)) }}</div>
                                            <small class="text-muted">{{ $txn->created_at ? $txn->created_at->format('M d, Y') : 'Recent' }}</small>
                                        </div>
                                        <span class="fw-bold {{ in_array($txn->type, ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']) ? 'text-success' : 'text-danger' }} small">
                                            {{ in_array($txn->type, ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']) ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-muted small">No transactions yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: FINANCE REQUESTS LIST (From Image) -->
            <div x-show="activeTab === 'finance_requests'" x-transition>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Finance Requests</h4>
                        <p class="text-muted mb-0 small">Track all your deposit & withdrawal requests, payment instructions, and verification evidence.</p>
                    </div>
                    <button class="btn btn-primary fw-bold px-3 py-2 rounded-3" style="background:#2563eb;" @click="openFinanceForm('deposit')">
                        <i class="bi bi-plus-lg me-1"></i> New Request
                    </button>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <!-- Filter Tabs: All, Deposit, Withdrawal -->
                    <div class="d-flex gap-2 border-bottom pb-3 mb-4">
                        <button class="btn btn-sm rounded-pill px-3 fw-bold" :class="requestFilter === 'all' ? 'btn-primary' : 'btn-light text-secondary'" @click="requestFilter = 'all'">All</button>
                        <button class="btn btn-sm rounded-pill px-3 fw-bold" :class="requestFilter === 'deposit' ? 'btn-primary' : 'btn-light text-secondary'" @click="requestFilter = 'deposit'">Deposit</button>
                        <button class="btn btn-sm rounded-pill px-3 fw-bold" :class="requestFilter === 'withdrawal' ? 'btn-primary' : 'btn-light text-secondary'" @click="requestFilter = 'withdrawal'">Withdrawal</button>
                    </div>

                    <div class="row g-3">
                        @forelse($deposits as $dep)
                            <div class="col-lg-6" x-show="requestFilter === 'all' || requestFilter === 'deposit'">
                                <div class="p-3 rounded-4 border bg-light h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">Deposit Request</span>
                                            @if($dep->status === 'completed')
                                                <span class="badge bg-success bg-opacity-15 text-success fw-bold px-3 py-1 rounded-pill">Completed</span>
                                            @elseif($dep->status === 'awaiting_payment')
                                                <span class="badge bg-primary text-white fw-bold px-3 py-1 rounded-pill">Approved (Pay Now)</span>
                                            @elseif($dep->status === 'evidence_submitted')
                                                <span class="badge bg-info text-white fw-bold px-3 py-1 rounded-pill">Under Review</span>
                                            @elseif($dep->status === 'rejected')
                                                <span class="badge bg-danger bg-opacity-15 text-danger fw-bold px-3 py-1 rounded-pill">Rejected</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-20 text-warning-dark fw-bold px-3 py-1 rounded-pill">Pending</span>
                                            @endif
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1">{{ $dep->currency ?? 'PHP' }} {{ number_format($dep->amount, 2) }}</h4>
                                        <div class="small text-muted mb-2">Request ID: <code class="text-dark fw-bold">{{ $dep->deposit_code }}</code></div>
                                        <div class="small text-secondary mb-3"><i class="bi bi-credit-card me-1"></i> Method: {{ ucwords(str_replace('_', ' ', $dep->payment_method)) }}</div>
                                    </div>

                                    <div class="pt-2 border-top">
                                        @if($dep->status === 'awaiting_payment')
                                            <button class="btn btn-primary btn-sm w-100 fw-bold rounded-3 mb-1" style="background:#2563eb;" @click="showInstructionsModal({{ json_encode($dep) }})">
                                                <i class="bi bi-wallet2 me-1"></i> View Instructions & Pay
                                            </button>
                                        @elseif($dep->status === 'evidence_submitted')
                                            <div class="small text-info fw-bold"><i class="bi bi-clock me-1"></i> Proof submitted. Admin verifying...</div>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm w-100 fw-bold rounded-3" @click="showInstructionsModal({{ json_encode($dep) }})">
                                                View Request Details
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted">No deposit finance requests found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- INVEST TAB (Projects) -->
            <div x-show="activeTab === 'invest'" x-transition>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Invest in Projects</h4>
                        <p class="text-muted mb-0 small">Browse available development projects. View details, download documents, save and share projects, then invest with a flexible amount.</p>
                    </div>
                    <a href="{{ route('invest.index') }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-shrink-0">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Browse All Projects
                    </a>
                </div>

                <!-- PROJECT WORKFLOW & SEND/RECEIVE CALLOUT BANNER -->
                <div class="card border-0 rounded-4 shadow-sm mb-4" style="background:linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color:#fff;">
                    <div class="card-body p-4">
                        <div class="row align-items-center g-3">
                            <div class="col-lg-8">
                                <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1.5 mb-2 fs-6"><i class="bi bi-info-circle me-1"></i> How Investment Projects Work</span>
                                <h5 class="fw-bold text-white mb-2">Fund AVC Balance &rarr; Buy Shares &rarr; Project Ends &amp; Returns to AVC Balance</h5>
                                <p class="text-white-50 small mb-0" style="line-height:1.6;">
                                    Deposit funds to your AVC balance to buy shares in admin-listed development projects. Once a project duration matures, your full investment capital plus accumulated ROI returns straight to your <strong>AVC Balance</strong>. You can then withdraw, reinvest, or send to peers via internal P2P transfers.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                    <button class="btn btn-sm btn-light fw-bold rounded-3 text-primary" @click="openReceiveModal = true">
                                        <i class="bi bi-box-arrow-in-down me-1"></i> Receive Funds
                                    </button>
                                    <a href="{{ route('marketplace') }}" class="btn btn-sm btn-warning fw-bold rounded-3 text-dark">
                                        <i class="bi bi-arrow-repeat me-1"></i> AVC Marketplace
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse($projects as $proj)
                        @php
                            $projRaised = $proj->raisedAmount();
                            $projFunded = $proj->fundedPercent();
                            $projSaved = in_array($proj->id, $savedProjectIds);
                            $projActive = $proj->isActiveWindow();
                            $projEndsAt = $proj->endsAt() ? $proj->endsAt()->timestamp : 0;
                            $projStatus = [
                                'active' => ['bg-success', 'Ongoing'],
                                'completed' => ['bg-primary', 'Completed'],
                                'closed' => ['bg-secondary', 'Closed'],
                            ];
                            $projStatusCls = $projStatus[$proj->status] ?? ['bg-secondary', ucfirst($proj->status)];
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden bg-white">
                                <div style="height:180px; overflow:hidden; position:relative;">
                                    <img src="{{ $proj->image_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $proj->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    <span class="badge position-absolute top-0 start-0 m-2 rounded-pill fw-bold {{ $projStatusCls[0] }}" style="font-size:0.75rem;">{{ $projStatusCls[1] }}</span>
                                    <span class="badge position-absolute top-0 start-0 m-2 mt-4 rounded-pill fw-bold" style="background:#f59e0b; font-size:0.75rem; color:#1a1a1a;">{{ $proj->expected_return_percentage }}% Return</span>
                                    @auth
                                        <form action="{{ route('project.save', $proj) }}" method="POST" class="js-save-project position-absolute top-0 end-0 m-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm rounded-circle border-0 shadow-sm {{ $projSaved ? 'text-danger' : 'bg-white' }}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="{{ $projSaved ? 'Remove from saved' : 'Save project' }}">
                                                <i class="bi {{ $projSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark mb-1">{{ $proj->title }}</h6>
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $proj->location }}</p>
                                    <div class="d-flex align-items-center justify-content-between small mb-2">
                                        <span class="position-relative d-inline-flex align-items-center gap-1" style="white-space:nowrap;" title="{{ $proj->rating }} / 5 rating">
                                            <span class="d-inline-flex gap-1 text-muted">
                                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                            </span>
                                            <span class="position-absolute top-0 start-0 d-inline-flex gap-1 overflow-hidden" style="width:{{ $proj->ratingWidth() }}%; color:#f59e0b;">
                                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                            </span>
                                            <b class="text-dark">{{ number_format((float) $proj->rating, 1) }}</b>
                                        </span>
                                        <span class="text-muted"><i class="bi bi-clock-history me-1" style="color:#f59e0b;"></i>{{ $proj->investment_duration_months }} mos</span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Min Invest</span>
                                        <strong class="text-dark">${{ number_format($proj->minimum_investment, 2) }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Funding</span>
                                            <span>${{ number_format($projRaised, 0) }} / ${{ number_format($proj->target_amount, 0) }} ({{ $projFunded }}%)</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height:6px;">
                                            <div class="progress-bar" style="width:{{ $projFunded }}%; background:#f59e0b;"></div>
                                        </div>
                                    </div>
                                    @if($projActive && $projEndsAt > 0)
                                        <div class="rounded-3 p-1 px-2 mb-2 d-flex align-items-center gap-2" style="background:#fffbeb; border:1px solid #fde68a;">
                                            <i class="bi bi-hourglass-split small" style="color:#b45309;"></i>
                                            <small class="fw-bold text-muted">Ends in</small>
                                            <span class="ms-auto fw-bold text-danger" style="font-size:0.8rem;" data-countdown-ends="{{ $projEndsAt }}">--</span>
                                        </div>
                                    @endif
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('project.show', $proj) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill">
                                            <i class="bi bi-info-circle me-1"></i> More Info
                                        </a>
                                        @if($proj->document_path)
                                            <a href="{{ route('project.download', $proj) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill">
                                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Doc
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill" onclick="shareContent('{{ $proj->title }}', '{{ route('project.show', $proj) }}', 'Invest in this project')">
                                            <i class="bi bi-share me-1"></i> Share
                                        </button>
                                    </div>
                                    @if($projActive)
                                        <a href="{{ route('project.show', $proj) }}" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 mt-2" style="background:#2563eb;">
                                            <i class="bi bi-lightning-charge me-1"></i> Invest Now
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100 fw-bold rounded-3 mt-2" disabled>
                                            <i class="bi bi-lock-fill me-1"></i> {{ $proj->status === 'completed' ? 'Completed' : 'Closed' }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-rocket-takeoff fs-1 d-block mb-2 opacity-25"></i>
                            No active projects available at this time.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SAVED PROJECTS TAB -->
            <div x-show="activeTab === 'saved_projects'" x-transition>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Saved Projects</h4>
                        <p class="text-muted mb-0 small">Projects you have saved to invest in later.</p>
                    </div>
                    @if($savedProjects->count() > 0)
                        <span id="savedCountPill" class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">{{ $savedProjects->count() }} Saved</span>
                    @endif
                </div>
                <div id="savedProjectsGrid" class="row g-4">
                    @forelse($savedProjects as $proj)
                        @php
                            $projRaised = $proj->raisedAmount();
                            $projFunded = $proj->fundedPercent();
                            $projActive = $proj->isActiveWindow();
                            $projEndsAt = $proj->endsAt() ? $proj->endsAt()->timestamp : 0;
                            $projStatus = [
                                'active' => ['bg-success', 'Ongoing'],
                                'completed' => ['bg-primary', 'Completed'],
                                'closed' => ['bg-secondary', 'Closed'],
                            ];
                            $projStatusCls = $projStatus[$proj->status] ?? ['bg-secondary', ucfirst($proj->status)];
                        @endphp
                        <div class="col-md-6 col-lg-4" data-saved-card>
                            <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden bg-white">
                                <div style="height:180px; overflow:hidden; position:relative;">
                                    <img src="{{ $proj->image_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $proj->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    <span class="badge position-absolute top-0 start-0 m-2 rounded-pill fw-bold {{ $projStatusCls[0] }}" style="font-size:0.75rem;">{{ $projStatusCls[1] }}</span>
                                    <span class="badge position-absolute top-0 start-0 m-2 mt-4 rounded-pill fw-bold" style="background:#f59e0b; font-size:0.75rem; color:#1a1a1a;">{{ $proj->expected_return_percentage }}% Return</span>
                                    <form action="{{ route('project.save', $proj) }}" method="POST" class="js-save-project position-absolute top-0 end-0 m-2" data-remove-card>
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-circle border-0 shadow-sm bg-white" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="Remove from saved">
                                            <i class="bi bi-bookmark-fill text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark mb-1">{{ $proj->title }}</h6>
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $proj->location }}</p>
                                    <div class="d-flex align-items-center justify-content-between small mb-2">
                                        <span class="position-relative d-inline-flex align-items-center gap-1" style="white-space:nowrap;" title="{{ $proj->rating }} / 5 rating">
                                            <span class="d-inline-flex gap-1 text-muted">
                                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                            </span>
                                            <span class="position-absolute top-0 start-0 d-inline-flex gap-1 overflow-hidden" style="width:{{ $proj->ratingWidth() }}%; color:#f59e0b;">
                                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                            </span>
                                            <b class="text-dark">{{ number_format((float) $proj->rating, 1) }}</b>
                                        </span>
                                        <span class="text-muted"><i class="bi bi-clock-history me-1" style="color:#f59e0b;"></i>{{ $proj->investment_duration_months }} mos</span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Min Invest</span>
                                        <strong class="text-dark">${{ number_format($proj->minimum_investment, 2) }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Funding</span>
                                            <span>${{ number_format($projRaised, 0) }} / ${{ number_format($proj->target_amount, 0) }} ({{ $projFunded }}%)</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height:6px;">
                                            <div class="progress-bar" style="width:{{ $projFunded }}%; background:#f59e0b;"></div>
                                        </div>
                                    </div>
                                    @if($projActive && $projEndsAt > 0)
                                        <div class="rounded-3 p-1 px-2 mb-2 d-flex align-items-center gap-2" style="background:#fffbeb; border:1px solid #fde68a;">
                                            <i class="bi bi-hourglass-split small" style="color:#b45309;"></i>
                                            <small class="fw-bold text-muted">Ends in</small>
                                            <span class="ms-auto fw-bold text-danger" style="font-size:0.8rem;" data-countdown-ends="{{ $projEndsAt }}">--</span>
                                        </div>
                                    @endif
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('project.show', $proj) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill">
                                            <i class="bi bi-info-circle me-1"></i> More Info
                                        </a>
                                        @if($proj->document_path)
                                            <a href="{{ route('project.download', $proj) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill">
                                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Doc
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill" onclick="shareContent('{{ $proj->title }}', '{{ route('project.show', $proj) }}', 'Invest in this project')">
                                            <i class="bi bi-share me-1"></i> Share
                                        </button>
                                    </div>
                                    @if($projActive)
                                        <a href="{{ route('project.show', $proj) }}" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 mt-2" style="background:#2563eb;">
                                            <i class="bi bi-lightning-charge me-1"></i> Invest Now
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100 fw-bold rounded-3 mt-2" disabled>
                                            <i class="bi bi-lock-fill me-1"></i> {{ $proj->status === 'completed' ? 'Completed' : 'Closed' }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px; height:64px; background:#f1f5f9;">
                                    <i class="bi bi-bookmark-star fs-2 text-muted"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">No Saved Projects</h5>
                                <p class="text-muted small mb-4">Save projects you are interested in and they will appear here for quick access.</p>
                                <button class="btn btn-primary fw-bold px-4 py-2 rounded-3 mx-auto" style="background:#2563eb; max-width:200px;" @click="activeTab = 'invest'">
                                    <i class="bi bi-rocket-takeoff me-1"></i> Browse Projects
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- MY PORTFOLIO TAB -->
            <div x-show="activeTab === 'my_investments'" x-transition>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1" style="font-size:1.6rem;">My Portfolio</h2>
                        <p class="text-muted mb-0" style="font-size:0.95rem; font-weight:500;">Track all your active and completed project investments and purchased properties.</p>
                    </div>
                    @if($projectInvestments->count() > 0)
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">{{ $projectInvestments->count() }} Project Investment(s)</span>
                    @endif
                </div>

                @if($projectInvestments->count() > 0 || $purchases->count() > 0 || $userInvestments->count() > 0)
                    <h6 class="fw-bold text-dark mb-3" style="font-size:0.95rem;"><i class="bi bi-rocket-takeoff me-2" style="color:#f59e0b;"></i>Project Investments</h6>
                @endif
                <div class="row g-3 mb-4">
                    @forelse($projectInvestments as $inv)
                        @php
                            $progressPct = $inv->expected_roi_amount > 0 ? min(100, round(($inv->roi_earned / $inv->expected_roi_amount) * 100)) : 0;
                        @endphp
                        <div class="col-lg-6">
                            <div class="card border-0 rounded-4 overflow-hidden h-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.04);">
                                <div style="height:4px; background:linear-gradient(90deg, {{ $inv->status === 'active' ? '#f59e0b,#fbbf24' : '#94a3b8,#cbd5e1' }});"></div>
                                <div class="p-3 bg-white">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="flex-shrink-0 overflow-hidden rounded-3 border" style="width:56px; height:56px; border-color:#e2e8f0 !important; background:#f8fafc;">
                                            @if($inv->project->image_url)
                                                <img src="{{ $inv->project->image_url }}" alt="{{ $inv->project->title }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100" style="background:#fffbeb;">
                                                    <i class="bi bi-rocket-takeoff fs-5" style="color:#d97706;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h6 class="fw-bold mb-0 text-truncate" style="color:#0f172a;">{{ $inv->project->title ?? 'Project Investment' }}</h6>
                                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $inv->project->location ?? 'Location' }}</small>
                                        </div>
                                        <span class="badge rounded-pill fw-bold px-3 py-1 {{ $inv->status === 'active' ? 'bg-warning text-dark' : 'bg-secondary text-white' }}" style="font-size:0.75rem;">
                                            {{ ucfirst($inv->status) }}
                                        </span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                    <i class="bi bi-currency-dollar fs-6" style="color:#d97706;"></i>
                                                </div>
                                                <div class="fw-bold" style="color:#0f172a; font-size:0.95rem;">${{ number_format($inv->amount, 2) }}</div>
                                                <small class="text-muted" style="font-size:0.68rem;">Invested</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                    <i class="bi bi-graph-up-arrow fs-6" style="color:#16a34a;"></i>
                                                </div>
                                                <div class="fw-bold text-success" style="font-size:0.95rem;">${{ number_format($inv->roi_earned, 2) }}</div>
                                                <small class="text-muted" style="font-size:0.68rem;">ROI Earned</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                    <i class="bi bi-bullseye fs-6" style="color:#9333ea;"></i>
                                                </div>
                                                <div class="fw-bold" style="color:#0f172a; font-size:0.95rem;">${{ number_format($inv->expected_roi_amount, 2) }}</div>
                                                <small class="text-muted" style="font-size:0.68rem;">Expected</small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($inv->status === 'active')
                                        <div class="mt-2">
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted" style="font-size:0.7rem;">ROI Progress</span>
                                                <span class="fw-bold" style="font-size:0.7rem; color:#d97706;">{{ $progressPct }}%</span>
                                            </div>
                                            <div class="progress" style="height:4px; background:#e2e8f0;">
                                                <div class="progress-bar rounded-pill" style="width:{{ $progressPct }}%; background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @if($purchases->count() === 0 && $userInvestments->count() === 0)
                            <div class="col-12">
                                <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px; height:64px; background:#f1f5f9;">
                                        <i class="bi bi-pie-chart fs-2 text-muted"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">No Investments Yet</h5>
                                    <p class="text-muted small mb-4">Browse available projects and invest to start earning returns.</p>
                                    <button class="btn btn-primary fw-bold px-4 py-2 rounded-3 mx-auto" style="background:#2563eb; max-width:200px;" @click="activeTab = 'invest'">
                                        <i class="bi bi-lightning-charge me-1"></i> Start Investing
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforelse
                </div>

                @if($purchases->count() > 0)
                    <h6 class="fw-bold text-dark mb-3" style="font-size:0.95rem;"><i class="bi bi-house-check me-2" style="color:#2563eb;"></i>Purchased Properties</h6>
                    <div class="row g-3 mb-4">
                        @foreach($purchases as $purchase)
                            <div class="col-lg-6">
                                <div class="card border-0 rounded-4 overflow-hidden h-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.04);">
                                    <div style="height:4px; background:linear-gradient(90deg, #2563eb,#3b82f6);"></div>
                                    <div class="p-3 bg-white">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="flex-shrink-0 overflow-hidden rounded-3 border" style="width:56px; height:56px; border-color:#e2e8f0 !important; background:#f8fafc;">
                                                @if($purchase->property->image_url)
                                                    <img src="{{ $purchase->property->image_url }}" alt="{{ $purchase->property->title }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100" style="background:#eff6ff;">
                                                        <i class="bi bi-building fs-5" style="color:#2563eb;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h6 class="fw-bold mb-0 text-truncate" style="color:#0f172a;">{{ $purchase->property->title ?? 'Property' }}</h6>
                                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $purchase->property->location ?? 'Location' }}</small>
                                            </div>
                                            <span class="badge rounded-pill fw-bold px-3 py-1 bg-success text-white" style="font-size:0.75rem;">
                                                Owned
                                            </span>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                        <i class="bi bi-currency-dollar fs-6" style="color:#2563eb;"></i>
                                                    </div>
                                                    <div class="fw-bold" style="color:#0f172a; font-size:0.95rem;">${{ number_format($purchase->amount, 2) }}</div>
                                                    <small class="text-muted" style="font-size:0.68rem;">Purchase Price</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                        <i class="bi bi-calendar-check fs-6" style="color:#16a34a;"></i>
                                                    </div>
                                                    <div class="fw-bold" style="color:#0f172a; font-size:0.95rem;">{{ $purchase->created_at?->format('M d, Y') }}</div>
                                                    <small class="text-muted" style="font-size:0.68rem;">Purchased On</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($userInvestments->count() > 0)
                    <h6 class="fw-bold text-dark mb-3" style="font-size:0.95rem;"><i class="bi bi-building me-2" style="color:#64748b;"></i>Legacy Property Share Investments</h6>
                    <div class="row g-3">
                        @foreach($userInvestments as $inv)
                            @php
                                $progressPct = $inv->total_amount > 0 ? min(100, round(($inv->roi_earned / $inv->total_amount) * 100)) : 0;
                            @endphp
                            <div class="col-lg-6">
                                <div class="card border-0 rounded-4 overflow-hidden h-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.04);">
                                    <div style="height:4px; background:linear-gradient(90deg, {{ $inv->status === 'active' ? '#2563eb,#3b82f6' : '#94a3b8,#cbd5e1' }});"></div>
                                    <div class="p-3 bg-white">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="flex-shrink-0 overflow-hidden rounded-3 border" style="width:56px; height:56px; border-color:#e2e8f0 !important; background:#f8fafc;">
                                                @if($inv->property->image_url)
                                                    <img src="{{ $inv->property->image_url }}" alt="{{ $inv->property->title }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100" style="background:{{ $inv->status === 'active' ? '#eff6ff' : '#f1f5f9' }};">
                                                        <i class="bi bi-building fs-5" style="color:{{ $inv->status === 'active' ? '#2563eb' : '#64748b' }};"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h6 class="fw-bold mb-0 text-truncate" style="color:#0f172a;">{{ $inv->property->title ?? 'Property Investment' }}</h6>
                                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $inv->property->location ?? 'Location' }}</small>
                                            </div>
                                            <span class="badge rounded-pill fw-bold px-3 py-1 {{ $inv->status === 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="font-size:0.75rem;">
                                                {{ ucfirst($inv->status) }}
                                            </span>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                        <i class="bi bi-pie-chart fs-6" style="color:#2563eb;"></i>
                                                    </div>
                                                    <div class="fw-bold" style="color:#0f172a; font-size:0.95rem;">{{ $inv->shares_bought }}</div>
                                                    <small class="text-muted" style="font-size:0.68rem;">Shares</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                        <i class="bi bi-currency-dollar fs-6" style="color:#2563eb;"></i>
                                                    </div>
                                                    <div class="fw-bold" style="color:#0f172a; font-size:0.95rem;">${{ number_format($inv->total_amount, 2) }}</div>
                                                    <small class="text-muted" style="font-size:0.68rem;">Invested</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 text-center" style="background:#f8fafc;">
                                                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                                        <i class="bi bi-graph-up-arrow fs-6" style="color:#16a34a;"></i>
                                                    </div>
                                                    <div class="fw-bold text-success" style="font-size:0.95rem;">${{ number_format($inv->roi_earned, 2) }}</div>
                                                    <small class="text-muted" style="font-size:0.68rem;">ROI Earned</small>
                                                </div>
                                            </div>
                                        </div>
                                        @if($inv->status === 'active')
                                            <div class="mt-2">
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted" style="font-size:0.7rem;">ROI Progress</span>
                                                    <span class="fw-bold" style="font-size:0.7rem; color:#16a34a;">{{ $progressPct }}%</span>
                                                </div>
                                                <div class="progress" style="height:4px; background:#e2e8f0;">
                                                    <div class="progress-bar rounded-pill" style="width:{{ $progressPct }}%; background:linear-gradient(90deg,#16a34a,#22c55e);"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- DEPOSIT TAB -->
            <div x-show="activeTab === 'deposit'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold text-dark mb-1" style="font-size:1.6rem;">Deposit Funds</h2>
                    <p class="text-muted mb-0" style="font-size:0.95rem; font-weight:500;">Submit a finance request to deposit funds into your AVC balance.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-5 d-flex flex-column gap-4">
                        <div class="rounded-4 p-4 text-white position-relative overflow-hidden" style="background:linear-gradient(135deg,#0f172a,#1e3a5f); box-shadow:0 6px 24px rgba(15,23,42,0.2);">
                            <div class="position-absolute top-0 end-0 opacity-10" style="font-size:5rem; line-height:1; transform:rotate(10deg);"><i class="bi bi-wallet2"></i></div>
                            <div class="d-flex align-items-center gap-3 position-relative">
                                <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px; height:44px; background:rgba(255,255,255,0.12);">
                                    <i class="bi bi-wallet2 text-white fs-5"></i>
                                </div>
                                <div>
                                    <div class="small" style="color:#94a3b8;">Available Balance (AVC)</div>
                                    <h3 class="fw-bold text-white mb-0" style="font-size:1.5rem;">{{ format_avc($walletBalance) }}</h3>
                                    <div class="small" style="color:#94a3b8;">&asymp; {{ avc_equivalent($walletBalance, $preferredCurrency) }} {{ $preferredCurrency }}</div>
                                </div>
                            </div>
                            <hr class="my-3" style="border-color:rgba(255,255,255,0.1);">
                            <button class="btn btn-primary fw-bold w-100 py-2 rounded-3 position-relative" style="background:#2563eb;" @click="showFinanceModal = true">
                                <i class="bi bi-plus-circle me-1"></i> Quick Deposit Modal
                            </button>
                        </div>
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold mb-2" style="color:#0f172a; font-size:0.85rem;"><i class="bi bi-shield-check text-primary me-1.5"></i>Official Admin Payment Accounts</h6>
                            <p class="text-muted small mb-3" style="font-size:0.75rem;">Transfer funds directly to our official company accounts below to fund your AVC balance.</p>
                            <div class="d-flex flex-column gap-2.5">
                                <div class="p-2.5 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark small"><i class="bi bi-bank2 text-primary me-1"></i> Bank Transfer / GCash</span>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold" style="font-size:0.65rem;">Active</span>
                                    </div>
                                    <div class="small text-muted mb-1">Account Name: <strong class="text-dark">RINNY P.</strong></div>
                                    <div class="d-flex align-items-center justify-content-between bg-white p-1.5 rounded border">
                                        <code class="fw-bold text-primary small">09658726718</code>
                                        <button class="btn btn-sm btn-link p-0 text-primary fw-bold small text-decoration-none" @click="copyText('09658726718')">
                                            <i class="bi bi-copy me-1"></i>Copy
                                        </button>
                                    </div>
                                </div>

                                <div class="p-2.5 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark small"><i class="bi bi-globe2 text-primary me-1"></i> International Wire Transfer</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size:0.65rem;">SWIFT</span>
                                    </div>
                                    <div class="small text-muted mb-1">Account Name: <strong class="text-dark">{{ site_name() }} Corp.</strong></div>
                                    <div class="d-flex align-items-center justify-content-between bg-white p-1.5 rounded border">
                                        <span class="small text-muted">SWIFT: <code class="fw-bold text-dark">RDRPHMM1XXXX</code></span>
                                        <button class="btn btn-sm btn-link p-0 text-primary fw-bold small text-decoration-none" @click="copyText('RDRPHMM1XXXX')">
                                            <i class="bi bi-copy me-1"></i>Copy
                                        </button>
                                    </div>
                                </div>

                                <div class="p-2.5 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark small"><i class="bi bi-currency-bitcoin text-warning me-1"></i> USDT (TRC-20) Wallet</span>
                                        <span class="badge bg-warning bg-opacity-20 text-warning-dark fw-bold" style="font-size:0.65rem;">Crypto</span>
                                    </div>
                                    <div class="small text-muted mb-1">Network: <strong class="text-dark">TRC-20</strong></div>
                                    <div class="d-flex align-items-center justify-content-between bg-white p-1.5 rounded border">
                                        <code class="fw-bold text-dark small text-truncate" style="max-width:180px;">TYd1kL9m8X7P2q4W3n5V6b7Z8x9C0v1B</code>
                                        <button class="btn btn-sm btn-link p-0 text-primary fw-bold small text-decoration-none" @click="copyText('TYd1kL9m8X7P2q4W3n5V6b7Z8x9C0v1B')">
                                            <i class="bi bi-copy me-1"></i>Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 d-flex flex-column gap-4">
                        <!-- Deposit Details Form Card -->
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold mb-4" style="color:#0f172a;"><i class="bi bi-plus-circle-fill me-2" style="color:#2563eb;"></i>Submit Deposit Request</h6>
                            <form action="{{ route('deposit.store') }}" method="POST" x-data="{ depMethod: 'bank_transfer' }">
                                @csrf
                                <!-- Payment Method Select -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold small" style="color:#1e293b;">Payment Method <span style="color:#ef4444;">*</span></label>
                                    <select class="form-select rounded-3 border-secondary-subtle" name="payment_method" x-model="depMethod" required>
                                        <option value="bank_transfer">Bank Transfer / GCash</option>
                                        <option value="credit_card">Credit / Debit Card</option>
                                        <option value="wire_transfer">Wire Transfer</option>
                                        <option value="crypto">Cryptocurrency (USDT / BTC)</option>
                                    </select>
                                </div>

                                <!-- Admin Payment Account Destination Box inside Form -->
                                <template x-if="depMethod === 'bank_transfer'">
                                    <div class="p-3 mb-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold text-primary mb-0" style="font-size:0.82rem;"><i class="bi bi-bank2 me-1"></i> Admin Payment Account (Bank / GCash)</h6>
                                            <span class="badge bg-primary text-white" style="font-size:0.65rem;">Admin Settings</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5 small">
                                            <span class="text-muted">Account Name:</span>
                                            <strong class="text-dark">RINNY P.</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5 small">
                                            <span class="text-muted">Account Number:</span>
                                            <div class="d-flex align-items-center gap-1">
                                                <code class="fw-bold text-primary">09658726718</code>
                                                <button type="button" class="btn btn-sm btn-link p-0 text-primary" @click="copyText('09658726718')"><i class="bi bi-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="depMethod === 'wire_transfer'">
                                    <div class="p-3 mb-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold text-primary mb-0" style="font-size:0.82rem;"><i class="bi bi-globe2 me-1"></i> Admin Wire Transfer (SWIFT) Account</h6>
                                            <span class="badge bg-primary text-white" style="font-size:0.65rem;">Admin Settings</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5 small">
                                            <span class="text-muted">Account Name:</span>
                                            <strong class="text-dark">{{ site_name() }} Corp.</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5 small">
                                            <span class="text-muted">SWIFT Code:</span>
                                            <div class="d-flex align-items-center gap-1">
                                                <code class="fw-bold text-primary">RDRPHMM1XXXX</code>
                                                <button type="button" class="btn btn-sm btn-link p-0 text-primary" @click="copyText('RDRPHMM1XXXX')"><i class="bi bi-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="depMethod === 'crypto'">
                                    <div class="p-3 mb-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0" style="font-size:0.82rem; color:#b45309;"><i class="bi bi-currency-bitcoin me-1"></i> Admin USDT (TRC-20) Wallet Address</h6>
                                            <span class="badge bg-warning text-dark" style="font-size:0.65rem;">TRC20</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <span class="text-muted">Deposit Address:</span>
                                            <div class="d-flex align-items-center gap-1">
                                                <code class="fw-bold text-dark text-truncate" style="max-width:180px;">TYd1kL9m8X7P2q4W3n5V6b7Z8x9C0v1B</code>
                                                <button type="button" class="btn btn-sm btn-link p-0 text-primary" @click="copyText('TYd1kL9m8X7P2q4W3n5V6b7Z8x9C0v1B')"><i class="bi bi-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Amount Field -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold small" style="color:#1e293b;">Amount ($ USD) <span style="color:#ef4444;">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-secondary-subtle fw-bold">$</span>
                                        <input type="number" step="0.01" min="10" name="amount" class="form-control rounded-end-3 border-secondary-subtle" placeholder="e.g. 500.00" required>
                                    </div>
                                    <small class="text-muted" style="font-size:0.72rem;">Minimum deposit amount is $10.00</small>
                                </div>

                                <!-- Dynamic fields based on method -->
                                <template x-if="depMethod === 'credit_card'">
                                    <div class="p-3 mb-3 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary mb-1">Cardholder Name</label>
                                            <input type="text" name="card_name" class="form-control form-control-sm rounded-3" placeholder="John Doe">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary mb-1">Card Number</label>
                                            <input type="text" name="card_number" class="form-control form-control-sm rounded-3" placeholder="4532 &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 8921">
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-secondary mb-1">Expiry Date</label>
                                                <input type="text" name="card_expiry" class="form-control form-control-sm rounded-3" placeholder="MM/YY">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-secondary mb-1">CVV / CVC</label>
                                                <input type="password" maxlength="4" name="card_cvv" class="form-control form-control-sm rounded-3" placeholder="123">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="depMethod === 'crypto'">
                                    <div class="p-3 mb-3 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary mb-1">Crypto Network</label>
                                            <select name="crypto_network_value" class="form-select form-select-sm rounded-3">
                                                <option value="USDT-TRC20">USDT (TRC-20)</option>
                                                <option value="USDT-ERC20">USDT (ERC-20)</option>
                                                <option value="BTC">Bitcoin (BTC)</option>
                                                <option value="ETH">Ethereum (ETH)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label small fw-semibold text-secondary mb-1">Your Wallet Address (Sender)</label>
                                            <input type="text" name="crypto_from_wallet" class="form-control form-control-sm rounded-3" placeholder="0x... or T...">
                                        </div>
                                    </div>
                                </template>

                                <template x-if="depMethod === 'bank_transfer' || depMethod === 'wire_transfer'">
                                    <div class="p-3 mb-3 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary mb-1">Sender Account Name</label>
                                            <input type="text" name="sender_account_name" class="form-control form-control-sm rounded-3" value="{{ $user->name ?? '' }}" placeholder="Account Holder Name">
                                        </div>
                                        <div>
                                            <label class="form-label small fw-semibold text-secondary mb-1">Bank / Account Number</label>
                                            <input type="text" name="sender_account_number" class="form-control form-control-sm rounded-3" placeholder="Account or IBAN Number">
                                        </div>
                                    </div>
                                </template>

                                <!-- Notes field -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold small" style="color:#1e293b;">Additional Notes / Reference (Optional)</label>
                                    <textarea name="notes" class="form-control rounded-3 border-secondary-subtle" rows="2" placeholder="Add any details for the finance admin team..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary fw-bold w-100 py-2.5 rounded-3 shadow-sm" style="background:#2563eb;">
                                    <i class="bi bi-send-fill me-1.5"></i> Submit Deposit Request
                                </button>
                            </form>
                        </div>

                        <!-- Recent Deposit Requests History -->
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0" style="color:#0f172a; font-size:0.9rem;"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Deposit Requests</h6>
                                @if($deposits->count() > 0)
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold rounded-pill px-2" style="font-size:0.7rem;">{{ $deposits->count() }}</span>
                                @endif
                            </div>
                            @forelse($deposits->take(5) as $dep)
                                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px; height:36px; background:{{ $dep->status === 'completed' ? '#f0fdf4' : ($dep->status === 'awaiting_payment' ? '#fffbeb' : '#f1f5f9') }};">
                                            <i class="bi {{ $dep->status === 'completed' ? 'bi-check-circle text-success' : ($dep->status === 'awaiting_payment' ? 'bi-clock text-warning' : 'bi-arrow-right text-secondary') }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color:#0f172a; font-size:0.85rem;">{{ $dep->currency ?? '$' }} {{ number_format($dep->amount, 2) }}</div>
                                            <div class="text-muted" style="font-size:0.72rem;">{{ $dep->deposit_code }} &middot; {{ ucwords(str_replace('_', ' ', $dep->payment_method)) }} &middot; {{ $dep->created_at?->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                    @if($dep->status === 'completed')
                                        <span class="badge bg-success text-white fw-bold rounded-pill px-3 py-1" style="font-size:0.7rem;">Completed</span>
                                    @elseif($dep->status === 'awaiting_payment')
                                        <button class="btn btn-primary btn-sm fw-bold rounded-pill px-3 py-1" style="background:#2563eb; font-size:0.72rem;" @click="showInstructionsModal({{ json_encode($dep) }})">Pay Now</button>
                                    @elseif($dep->status === 'evidence_submitted')
                                        <span class="badge bg-info text-white fw-bold rounded-pill px-3 py-1" style="font-size:0.7rem;">Under Review</span>
                                    @else
                                        <span class="badge bg-warning text-white fw-bold rounded-pill px-3 py-1" style="font-size:0.7rem;">Pending</span>
                                    @endif
                                </div>
                            @empty
                                <div class="py-5 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px; height:48px; background:#f1f5f9;">
                                        <i class="bi bi-inbox fs-5 text-muted"></i>
                                    </div>
                                    <p class="text-muted small mb-0">No deposit requests yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- WITHDRAW TAB -->
            <div x-show="activeTab === 'withdraw'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;">Withdraw Funds</h2>
                    <p class="mb-0" style="font-size:0.95rem; font-weight:500; color:#475569;">Request a withdrawal from your available AVC balance.</p>
                </div>
                <div class="row g-4">
                    
                    <div class="col-lg-5">
                        <div class="rounded-4 p-4 text-white position-relative overflow-hidden mb-4" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#334155 100%); box-shadow:0 8px 32px rgba(15,23,42,0.25);">
                            <div class="position-absolute top-0 end-0 opacity-10" style="font-size:5rem; line-height:1; transform:rotate(15deg) translate(10px,-10px);"><i class="bi bi-wallet2"></i></div>
                            <div class="position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold" style="letter-spacing:0.1em; color:#fff;">AVAILABLE BALANCE (AVC)</span>
                                    <i class="bi bi-credit-card-2-front fs-5" style="color:#3b82f6;"></i>
                                </div>
                                <h2 class="fw-bold mb-0" style="font-size:2rem; letter-spacing:-0.02em; color:#fff;">{{ format_avc($walletBalance) }}</h2>
                                <div class="small fw-bold mt-1" style="color:rgba(255,255,255,0.7);">&asymp; {{ avc_equivalent($walletBalance, $preferredCurrency) }} {{ $preferredCurrency }}</div>
                                <div class="d-flex align-items-center gap-2 mt-3">
                                    <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:rgba(34,197,94,0.15); color:#22c55e; font-size:0.7rem;"><i class="bi bi-arrow-up-short me-1"></i>Available</span>
                                    <span class="small fw-bold" style="color:#fff;">withdrawal limit: $10,000</span>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold mb-3" style="color:#0f172a; font-size:0.85rem;"><i class="bi bi-info-circle me-2" style="color:#2563eb;"></i>Withdrawal Info</h6>
                            <ul class="list-unstyled mb-0 small" style="color:#475569;">
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-clock text-primary flex-shrink-0 mt-1"></i><span>Processing takes 1–3 business days</span></li>
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-shield-check text-primary flex-shrink-0 mt-1"></i><span>KYC verification required for amounts over $1,000</span></li>
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-percent text-primary flex-shrink-0 mt-1"></i><span>No withdrawal fees for GCash &amp; Maya</span></li>
                                <li class="d-flex gap-2"><i class="bi bi-currency-dollar text-primary flex-shrink-0 mt-1"></i><span>Bank transfers may incur a $2.50 fee</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                            <h6 class="fw-bold mb-4" style="color:#0f172a;"><i class="bi bi-pencil-square me-2" style="color:#2563eb;"></i>Withdrawal Details</h6>
                            <form action="{{ route('withdraw.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small" style="color:#1e293b;">Amount <span style="color:#ef4444;">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold border-0" style="background:#f1f5f9; color:#2563eb;">$</span>
                                        <input type="number" step="0.01" min="10" name="amount" class="form-control fw-bold border-0" placeholder="0.00" required style="background:#f8fafc; font-size:1.1rem; box-shadow:none !important;">
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="small text-muted">Min: $10.00</span>
                                        <button type="button" class="btn btn-sm fw-bold py-0 px-2 border-0 rounded-pill" style="background:#dbeafe; color:#2563eb; font-size:0.72rem;" onclick="event.target.closest('.input-group').querySelector('input').value = '{{ $walletBalance }}'">Max: {{ format_avc($walletBalance) }}</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small" style="color:#1e293b;">Withdrawal Method <span style="color:#ef4444;">*</span></label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border cursor-pointer flex-fill" style="border-color:#e2e8f0 !important; transition:all 0.15s; min-width:110px;" x-data @mouseenter="$el.style.borderColor='#2563eb';$el.style.background='#eff6ff'" @mouseleave="$el.style.borderColor='#e2e8f0';$el.style.background=''">
                                            <input type="radio" name="withdrawal_method" value="bank_transfer" class="form-check-input m-0" checked style="accent-color:#2563eb;">
                                            <div><div class="fw-bold small" style="color:#0f172a;">Bank</div><span class="text-muted" style="font-size:0.65rem;">Wire Transfer</span></div>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border cursor-pointer flex-fill" style="border-color:#e2e8f0 !important; transition:all 0.15s; min-width:110px;" x-data @mouseenter="$el.style.borderColor='#2563eb';$el.style.background='#eff6ff'" @mouseleave="$el.style.borderColor='#e2e8f0';$el.style.background=''">
                                            <input type="radio" name="withdrawal_method" value="GCash" class="form-check-input m-0" style="accent-color:#2563eb;">
                                            <div><div class="fw-bold small" style="color:#0f172a;">GCash</div><span class="text-muted" style="font-size:0.65rem;">Mobile Wallet</span></div>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border cursor-pointer flex-fill" style="border-color:#e2e8f0 !important; transition:all 0.15s; min-width:110px;" x-data @mouseenter="$el.style.borderColor='#2563eb';$el.style.background='#eff6ff'" @mouseleave="$el.style.borderColor='#e2e8f0';$el.style.background=''">
                                            <input type="radio" name="withdrawal_method" value="Maya" class="form-check-input m-0" style="accent-color:#2563eb;">
                                            <div><div class="fw-bold small" style="color:#0f172a;">Maya</div><span class="text-muted" style="font-size:0.65rem;">Mobile Wallet</span></div>
                                        </label>
                                        <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border cursor-pointer flex-fill" style="border-color:#e2e8f0 !important; transition:all 0.15s; min-width:110px;" x-data @mouseenter="$el.style.borderColor='#2563eb';$el.style.background='#eff6ff'" @mouseleave="$el.style.borderColor='#e2e8f0';$el.style.background=''">
                                            <input type="radio" name="withdrawal_method" value="crypto" class="form-check-input m-0" style="accent-color:#2563eb;">
                                            <div><div class="fw-bold small" style="color:#0f172a;">Crypto</div><span class="text-muted" style="font-size:0.65rem;">USDT/BTC</span></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small" style="color:#1e293b;">Account / Wallet Details <span style="color:#ef4444;">*</span></label>
                                    <textarea name="account_details" class="form-control border-0" rows="2" placeholder="Bank name, account number, account name..." required style="background:#f8fafc; box-shadow:none !important;"></textarea>
                                </div>
                                <button type="submit" class="btn fw-bold w-100 py-2 rounded-3 border-0 d-flex align-items-center justify-content-center gap-2" style="background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:0.95rem;">
                                    <i class="bi bi-send-fill"></i> Submit Withdrawal Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BROWSE PROPERTIES TAB (Buy Directly) -->
            <div x-show="activeTab === 'marketplace'" x-transition>
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Browse Properties</h4>
                        <p class="text-muted mb-0 small">Explore available properties. Save them to your list, share them, or purchase directly with a one-time payment.</p>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn fw-bold rounded-3 me-1" :class="propFilter === 'all' ? 'btn-primary' : 'btn-light border'" @click="propFilter = 'all'">
                            <i class="bi bi-grid me-1"></i> All
                        </button>
                        <button type="button" class="btn fw-bold rounded-3" :class="propFilter === 'saved' ? 'btn-primary' : 'btn-light border'" @click="propFilter = 'saved'">
                            <i class="bi bi-bookmark-fill me-1"></i> Saved
                            @if(count($savedPropertyIds) > 0)
                                <span class="badge bg-warning text-dark ms-1 rounded-pill">{{ count($savedPropertyIds) }}</span>
                            @endif
                        </button>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($properties as $prop)
                        @php
                            $propPrice = $prop->purchasePrice();
                            $propSold = $prop->status === 'sold_out';
                            $propSaved = in_array($prop->id, $savedPropertyIds);
                        @endphp
                        <div class="col-md-6 col-lg-4" x-show="propFilter === 'all' || {{ $propSaved ? 'true' : 'false' }}">
                            <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden">
                                <div style="height:180px; overflow:hidden; position:relative;">
                                    <a href="{{ route('property.show', $prop) }}">
                                        <img src="{{ $prop->image_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $prop->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    </a>
                                    <span class="badge position-absolute top-0 start-0 m-2 rounded-pill fw-bold {{ $propSold ? 'bg-secondary' : 'bg-success' }}" style="font-size:0.75rem;">{{ $propSold ? 'Sold' : 'For Sale' }}</span>
                                    @auth
                                        <form action="{{ route('property.save', $prop) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm rounded-circle border-0 shadow-sm {{ $propSaved ? 'text-danger' : 'bg-white' }}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;" title="{{ $propSaved ? 'Remove from saved' : 'Save property' }}">
                                                <i class="bi {{ $propSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark mb-1"><a href="{{ route('property.show', $prop) }}" class="text-decoration-none text-dark">{{ $prop->title }}</a></h6>
                                    <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $prop->location }}</p>
                                    <div class="d-flex justify-content-between small fw-bold mb-3">
                                        <span>Price: <span class="text-primary">${{ number_format($propPrice, 2) }}</span></span>
                                        <span class="text-success">{{ $prop->category }}</span>
                                    </div>
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ route('property.show', $prop) }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill">
                                            <i class="bi bi-info-circle me-1"></i> More Info
                                        </a>
                                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-3 flex-fill" onclick="shareContent('{{ $prop->title }}', '{{ route('property.show', $prop) }}', 'Buy this property')">
                                            <i class="bi bi-share me-1"></i> Share
                                        </button>
                                    </div>
                                    @if($propSold)
                                        <button class="btn btn-secondary btn-sm w-100 fw-bold rounded-3 mt-1" disabled>
                                            <i class="bi bi-check-circle me-1"></i> Sold
                                        </button>
                                    @else
                                        <a href="{{ route('property.show', $prop) }}" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 mt-1" style="background:#2563eb;">
                                            <i class="bi bi-house-check me-1"></i> Buy Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TRANSACTIONS TAB -->
            <div x-show="activeTab === 'transactions'" x-transition>
                @php
                    $txnCredits = $transactions->whereIn('type', ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']);
                    $txnDebits = $transactions->whereIn('type', ['withdrawal', 'property_investment', 'project_investment', 'property_purchase', 'send_funds']);
                    $totalCredits = $txnCredits->sum('amount');
                    $totalDebits = $txnDebits->sum('amount');
                    $txnCount = $transactions->count();
                    $pendingCount = $transactions->where('status', 'pending')->count();
                @endphp
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;">Transaction History</h2>
                    <p class="mb-0" style="font-size:0.95rem; font-weight:500; color:#475569;">Complete ledger of all your account activity.</p>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="rounded-4 p-3 h-100 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#0f172a,#1e293b); box-shadow:0 4px 20px rgba(15,23,42,0.15);">
                            <div class="d-none d-lg-flex rounded-3 align-items-center justify-content-center flex-shrink-0" style="width:46px; height:46px; background:rgba(255,255,255,0.08);">
                                <i class="bi bi-receipt text-white" style="font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white" style="font-size:1.4rem; line-height:1.2;">{{ $txnCount }}</div>
                                <div class="small" style="font-size:0.65rem; color:#94a3b8; letter-spacing:0.06em; text-transform:uppercase;">Transactions</div>
                                <span class="badge fw-semibold rounded-pill mt-1" style="font-size:0.6rem; background:rgba(251,191,36,0.15); color:#fbbf24; padding:1px 6px;">{{ $pendingCount }} pending</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="rounded-4 p-3 h-100 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#064e3b,#065f46); box-shadow:0 4px 20px rgba(5,150,105,0.15);">
                            <div class="d-none d-lg-flex rounded-3 align-items-center justify-content-center flex-shrink-0" style="width:46px; height:46px; background:rgba(255,255,255,0.08);">
                                <i class="bi bi-arrow-down-circle text-white" style="font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white" style="font-size:1.4rem; line-height:1.2;">+{{ format_avc($totalCredits) }}</div>
                                <div class="small" style="font-size:0.65rem; color:#6ee7b7; letter-spacing:0.06em; text-transform:uppercase;">Total Credits</div>
                                <span class="badge fw-semibold rounded-pill mt-1" style="font-size:0.6rem; background:rgba(110,231,183,0.12); color:#6ee7b7; padding:1px 6px;">{{ $txnCredits->count() }} txns</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="rounded-4 p-3 h-100 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#7f1d1d,#991b1b); box-shadow:0 4px 20px rgba(220,38,38,0.15);">
                            <div class="d-none d-lg-flex rounded-3 align-items-center justify-content-center flex-shrink-0" style="width:46px; height:46px; background:rgba(255,255,255,0.08);">
                                <i class="bi bi-arrow-up-circle text-white" style="font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white" style="font-size:1.4rem; line-height:1.2;">-{{ format_avc($totalDebits) }}</div>
                                <div class="small" style="font-size:0.65rem; color:#fca5a5; letter-spacing:0.06em; text-transform:uppercase;">Total Debits</div>
                                <span class="badge fw-semibold rounded-pill mt-1" style="font-size:0.6rem; background:rgba(252,165,165,0.12); color:#fca5a5; padding:1px 6px;">{{ $txnDebits->count() }} txns</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="rounded-4 p-3 h-100 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8); box-shadow:0 4px 20px rgba(37,99,235,0.18);">
                            <div class="d-none d-lg-flex rounded-3 align-items-center justify-content-center flex-shrink-0" style="width:46px; height:46px; background:rgba(255,255,255,0.08);">
                                <i class="bi bi-graph-up-arrow text-white" style="font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white" style="font-size:1.4rem; line-height:1.2;">{{ format_avc($totalCredits - $totalDebits) }}</div>
                                <div class="small" style="font-size:0.65rem; color:#93c5fd; letter-spacing:0.06em; text-transform:uppercase;">Net Flow</div>
                                <span class="badge fw-semibold rounded-pill mt-1" style="font-size:0.6rem; background:rgba(147,197,253,0.12); color:#93c5fd; padding:1px 6px;">credits − debits</span>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $typeStyles = [
                        'deposit' => ['label' => 'Deposit', 'icon' => 'bi bi-arrow-down-circle-fill', 'color' => '#16a34a', 'bg' => '#f0fdf4'],
                        'withdrawal' => ['label' => 'Withdrawal', 'icon' => 'bi bi-arrow-up-circle-fill', 'color' => '#d97706', 'bg' => '#fffbeb'],
                        'property_investment' => ['label' => 'Investment', 'icon' => 'bi bi-building', 'color' => '#2563eb', 'bg' => '#eff6ff'],
                        'send_funds' => ['label' => 'Sent', 'icon' => 'bi bi-send', 'color' => '#dc2626', 'bg' => '#fef2f2'],
                        'receive_funds' => ['label' => 'Received', 'icon' => 'bi bi-inbox', 'color' => '#16a34a', 'bg' => '#f0fdf4'],
                        'affiliate_earning' => ['label' => 'Affiliate', 'icon' => 'bi bi-people', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
                        'roi_payout' => ['label' => 'ROI', 'icon' => 'bi bi-graph-up', 'color' => '#0891b2', 'bg' => '#ecfeff'],
                    ];
                @endphp
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                    <div class="px-4 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0" style="color:#0f172a; font-size:0.9rem;">
                            <i class="bi bi-list-ul me-2" style="color:#2563eb;"></i>All Transactions
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem;">{{ $txnCount }} entries</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th class="px-4 py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">REFERENCE</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">TYPE</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">AMOUNT</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">STATUS</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">DATE</th>
                                    <th class="px-4 py-3 small fw-bold text-muted text-end" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">RECEIPT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $i => $txn)
                                    @php $ts = $typeStyles[$txn->type] ?? ['label' => ucwords(str_replace('_',' ',$txn->type)), 'icon' => 'bi bi-circle', 'color' => '#64748b', 'bg' => '#f8fafc']; @endphp
                                    <tr style="transition:background 0.12s; border-bottom:1px solid #f1f5f9; cursor:pointer;" onmouseenter="this.style.background='#f8fafc'" onmouseleave="this.style.background=''" @click="openTxnPreview({{ $i }})">
                                        <td class="px-4 py-3">
                                            <span class="fw-bold small" style="color:#2563eb; font-family:monospace; font-size:0.8rem;">{{ $txn->reference }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="d-inline-flex align-items-center gap-1.5 fw-semibold small rounded-pill px-2.5 py-1" style="font-size:0.7rem; background:{{ $ts['bg'] }}; color:{{ $ts['color'] }}; white-space:nowrap;">
                                                <i class="{{ $ts['icon'] }}" style="font-size:0.7rem;"></i> {{ $ts['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 fw-bold" style="font-size:0.9rem; font-variant-numeric:tabular-nums;">${{ number_format($txn->amount, 2) }}</td>
                                        <td class="py-3">
                                            <span class="badge fw-semibold rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1" style="font-size:0.66rem; {{ $txn->status === 'completed' ? 'background:#f0fdf4; color:#16a34a;' : ($txn->status === 'pending' ? 'background:#fffbeb; color:#d97706;' : 'background:#fef2f2; color:#dc2626;') }}">
                                                <i class="bi {{ $txn->status === 'completed' ? 'bi-check-circle-fill' : ($txn->status === 'pending' ? 'bi-clock-fill' : 'bi-x-circle-fill') }}" style="font-size:0.6rem;"></i>
                                                {{ ucfirst($txn->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-muted small" style="font-size:0.78rem; white-space:nowrap;">{{ $txn->created_at ? $txn->created_at->format('M d, Y') : '' }}</td>
                                        <td class="px-4 py-3 text-end" @click.stop>
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold px-2.5 py-1 rounded-3" style="font-size:0.72rem;" @click="openTxnPreview({{ $i }}); setTimeout(() => printTransactionReceipt(), 150);">
                                                <i class="bi bi-printer me-1"></i> Receipt
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5" style="color:#94a3b8;"><i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i><span style="font-size:0.9rem;">No transactions yet.</span></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- NOTIFICATIONS TAB -->
            <div x-show="activeTab === 'notifications'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.7rem; color:#0f172a;">Notifications</h2>
                    <p class="mb-0" style="font-size:0.95rem; color:#475569;">Important updates about your finance requests and investments.</p>
                </div>
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
                    <div class="px-4 pt-4 pb-2 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="bi bi-bell-fill me-2" style="color:#2563eb;"></i>Activity Feed</h6>
                        <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem;">{{ $notifications->count() }} updates</span>
                    </div>
                    <div class="px-4 pb-4">
                        @forelse($notifications as $notif)
                            <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-2" style="background:{{ $notif->bg }}; border:1px solid transparent;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:36px; height:36px; background:{{ $notif->color }}20;">
                                    <i class="bi {{ $notif->icon }}" style="color:{{ $notif->color }}; font-size:0.95rem;"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold text-dark mb-1" style="font-size:0.85rem;">{{ $notif->title }}</div>
                                    <div class="text-muted mb-1" style="font-size:0.8rem; line-height:1.4;">{{ $notif->description }}</div>
                                    @if($notif->action)
                                        <button class="btn btn-sm btn-primary fw-bold mt-1 rounded-pill px-3" style="background:#2563eb; font-size:0.78rem;" @click="showInstructionsModal({{ json_encode($notif->action) }})">
                                            <i class="bi bi-wallet2 me-1"></i> View &amp; Pay
                                        </button>
                                    @endif
                                </div>
                                <small class="text-muted flex-shrink-0" style="font-size:0.7rem;">{{ $notif->date->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="text-center py-5" style="color:#94a3b8;">
                                <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-25"></i>
                                <div class="fw-semibold" style="font-size:0.95rem;">No notifications yet</div>
                                <small style="font-size:0.8rem;">Activity from deposits, withdrawals, investments, and marketplace offers will appear here.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- REFERRALS TAB -->
            <div x-show="activeTab === 'referrals'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.7rem; color:#0f172a;">Referrals</h2>
                    <p class="mb-0" style="font-size:0.95rem; color:#475569;">Invite friends to invest and earn affiliate commissions.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card border-0 rounded-4 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%); color:#fff;">
                            <h5 class="fw-bold text-white mb-1"><i class="bi bi-people-fill me-2"></i>Your Referral Code</h5>
                            <p class="small mb-3" style="color:#93c5fd;">Share this code with friends. Earn a commission for every investor you refer.</p>
                            <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background: rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1);">
                                <code class="fs-5 fw-bold text-white flex-grow-1">{{ $user->affiliate_code ?? 'RAD0000' }}</code>
                                <button class="btn btn-sm fw-bold px-3 rounded-pill" style="background:#2563eb; color:#fff;" onclick="navigator.clipboard.writeText('{{ $user->affiliate_code ?? '' }}'); alert('Referral code copied!')">
                                    <i class="bi bi-copy me-1"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3">Share Your Referral Link</h6>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{ url('/') }}?ref={{ $user->affiliate_code ?? 'RAD0000' }}" readonly>
                                <button class="btn btn-outline-primary fw-bold" onclick="navigator.clipboard.writeText('{{ url('/') }}?ref={{ $user->affiliate_code ?? '' }}'); alert('Link copied!')"><i class="bi bi-copy"></i></button>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="https://wa.me/?text=Join+Radiant+Dream+Realty%21+Use+my+code+{{ $user->affiliate_code ?? '' }}" target="_blank" class="btn btn-success btn-sm fw-bold rounded-pill px-3"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
                                <a href="https://twitter.com/intent/tweet?text=Invest+in+real+estate%21+{{ url('/') }}?ref={{ $user->affiliate_code ?? '' }}" target="_blank" class="btn btn-dark btn-sm fw-bold rounded-pill px-3"><i class="bi bi-twitter-x me-1"></i>Twitter/X</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card border-0 rounded-4 shadow-sm p-4 text-center" style="background:linear-gradient(135deg,#0b1329,#1e3a8a);">
                            <i class="bi bi-cash-coin fs-1 mb-2" style="color:#93c5fd;"></i>
                            <div class="small mb-1" style="color:#93c5fd; font-weight:500;">Total Affiliate Earnings</div>
                            <h2 class="fw-bold mb-0 text-white">${{ number_format($affiliateEarnings, 2) }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Referral History -->
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mt-4">
                    <div class="px-4 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="bi bi-clock-history me-2" style="color:#7c3aed;"></i>Referral History</h6>
                        <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem;">{{ $referrals->count() }} referrals</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th class="px-4 py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">MEMBER</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">EMAIL</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">JOINED</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">BONUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $ref)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:34px; height:34px; background:linear-gradient(135deg,#2563eb,#1d4ed8); font-size:0.7rem;">
                                                    {{ strtoupper(substr($ref->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div class="fw-bold small text-dark">{{ $ref->name }}</div>
                                            </div>
                                        </td>
                                        <td class="py-3"><span class="small" style="color:#475569;">{{ $ref->email }}</span></td>
                                        <td class="py-3"><span class="small text-muted">{{ $ref->created_at->format('M d, Y') }}</span></td>
                                        <td class="py-3"><span class="fw-bold text-success small">$10.00</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-5" style="color:#94a3b8;"><i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i><span style="font-size:0.9rem;">No referrals yet. Share your code to get started!</span></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PROFILE & KYC TAB -->
            <div x-show="activeTab === 'profile_kyc'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.7rem; color:#0f172a;">Profile &amp; KYC</h2>
                    <p class="mb-0" style="font-size:0.95rem; color:#475569;">Manage your personal information and identity verification.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4" x-data="{ editing: false }">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-person-circle text-primary me-2"></i>Personal Information</h6>
                                <button class="btn btn-sm fw-bold rounded-3 px-3"
                                    :class="editing ? 'btn-outline-secondary' : 'btn-outline-primary'"
                                    @click="editing = !editing"
                                    style="font-size:0.8rem;">
                                    <i class="bi" :class="editing ? 'bi-x-lg' : 'bi-pencil-square'"></i>
                                    <span x-text="editing ? ' Cancel' : ' Edit Profile'"></span>
                                </button>
                            </div>

                            {{-- VIEW MODE --}}
                            <div x-show="!editing" x-transition>
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 text-white flex-shrink-0" style="width:64px; height:64px; background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                                        {{ strtoupper(substr($user->name ?? 'IN', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $user->name ?? 'Investor Name' }}</div>
                                        <div class="text-muted small">{{ $user->email ?? 'investor@email.com' }}</div>
                                        <span class="badge rounded-pill mt-1" style="background:#eff6ff; color:#2563eb; font-size:0.72rem;">Account ID: {{ $user->account_id ?? 'RDR-000000' }}</span>
                                    </div>
                                </div>
                                <div class="row g-2 small">
                                    <div class="col-6"><div class="p-2 rounded-3" style="background:#f8fafc;"><div class="text-muted">AVC Balance</div><div class="fw-bold text-success">{{ format_avc($walletBalance) }}</div><div class="text-muted" style="font-size:0.7rem;">&asymp; {{ avc_equivalent($walletBalance, $preferredCurrency) }} {{ $preferredCurrency }}</div></div></div>
                                    <div class="col-6"><div class="p-2 rounded-3" style="background:#f8fafc;"><div class="text-muted">Active Investments</div><div class="fw-bold text-dark">{{ $activeProjectsCount }}</div></div></div>
                                </div>
                                <div class="mt-3 pt-3 border-top small text-muted">
                                    <div class="d-flex justify-content-between"><span>Member Since</span><span class="fw-semibold text-dark">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</span></div>
                                    <div class="d-flex justify-content-between mt-1"><span>KYC Status</span>
                                        <span class="fw-semibold {{ $user->kyc_verified ? 'text-success' : 'text-warning' }}">
                                            {{ $user->kyc_verified ? 'Verified' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- EDIT MODE --}}
                            <div x-show="editing" x-transition>
                                @if(session('success') && str_contains(session('success'), 'Profile'))
                                    <div class="alert alert-success border-0 rounded-3 small py-2 mb-3">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                                    </div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger border-0 rounded-3 small py-2 mb-3">
                                        <ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                                    </div>
                                @endif

                                <form action="{{ route('profile.update_info') }}" method="POST" id="profile-edit-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" placeholder="Your full name" required>
                                        @error('name')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" placeholder="your@email.com" required>
                                        @error('email')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Preferred Display Currency</label>
                                        <select name="preferred_currency" class="form-select rounded-3">
                                            @foreach(['USD','EUR','GBP','PHP','NGN','AED','SGD','CAD','AUD'] as $currency)
                                                <option value="{{ $currency }}" @selected(($user->preferred_currency ?? 'USD') === $currency)>{{ $currency }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text small text-muted">Balances will also show the equivalent value in this currency.</div>
                                    </div>

                                    <hr class="my-3">
                                    <p class="small text-muted mb-2 fw-semibold">Change Password <span class="fw-normal">(leave blank to keep current)</span></p>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">New Password</label>
                                        <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror"
                                            placeholder="Minimum 8 characters" autocomplete="new-password">
                                        @error('password')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold small text-dark">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-control rounded-3"
                                            placeholder="Repeat new password" autocomplete="new-password">
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary fw-bold w-100 rounded-3 py-2" style="background:#2563eb; border:none;">
                                            <i class="bi bi-save me-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i>KYC Verification</h6>

                            @php
                                $kycStatus = $user->kyc_status ?? 'pending';
                                $hasSubmitted = $user->kyc_document_path && $user->kyc_selfie_path;
                            @endphp

                            @if($user->kyc_verified)
                                <div class="p-3 rounded-3 mb-3 d-flex align-items-center gap-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size:1.3rem;"></i>
                                    <div><div class="fw-bold small text-success">KYC Verified</div><div class="text-muted" style="font-size:0.78rem;">Your identity has been verified. All features are unlocked.</div></div>
                                </div>
                            @elseif($kycStatus === 'rejected')
                                <div class="p-3 rounded-3 mb-3 d-flex align-items-center gap-3" style="background:#fef2f2; border:1px solid #fecaca;">
                                    <i class="bi bi-x-circle-fill text-danger" style="font-size:1.3rem;"></i>
                                    <div><div class="fw-bold small text-danger">KYC Rejected</div><div class="text-muted" style="font-size:0.78rem;">{{ $user->kyc_rejected_reason ?? 'Documents did not pass verification. Please resubmit.' }}</div></div>
                                </div>
                            @elseif($hasSubmitted)
                                <div class="p-3 rounded-3 mb-3 d-flex align-items-center gap-3" style="background:#fffbeb; border:1px solid #fde68a;">
                                    <i class="bi bi-clock-fill text-warning" style="font-size:1.3rem;"></i>
                                    <div><div class="fw-bold small" style="color:#92400e;">KYC Under Review</div><div class="text-muted" style="font-size:0.78rem;">Your documents are being reviewed. This usually takes 1-2 business days.</div></div>
                                </div>
                            @else
                                <div class="p-3 rounded-3 mb-3 d-flex align-items-center gap-3" style="background:#fffbeb; border:1px solid #fde68a;">
                                    <i class="bi bi-exclamation-triangle-fill" style="color:#d97706; font-size:1.3rem;"></i>
                                    <div><div class="fw-bold small" style="color:#92400e;">KYC Pending</div><div class="text-muted" style="font-size:0.78rem;">Identity verification required to unlock higher deposit limits.</div></div>
                                </div>
                            @endif

                            @if(!$user->kyc_verified && !$hasSubmitted)
                                <form action="{{ route('kyc.submit') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Government ID Document</label>
                                        <input type="file" name="document" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                        <small class="text-muted">Passport, Driver's License, or National ID (max 5MB)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Selfie / Portrait Photo</label>
                                        <input type="file" name="selfie" class="form-control form-control-sm" accept=".jpg,.jpeg,.png" required>
                                        <small class="text-muted">A clear photo of your face (max 5MB)</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary fw-bold w-100 rounded-3 py-2" style="background:#2563eb;">
                                        <i class="bi bi-upload me-1"></i> Submit KYC Documents
                                    </button>
                                </form>
                            @elseif($kycStatus === 'rejected')
                                <form action="{{ route('kyc.submit') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Government ID Document</label>
                                        <input type="file" name="document" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small text-dark">Selfie / Portrait Photo</label>
                                        <input type="file" name="selfie" class="form-control form-control-sm" accept=".jpg,.jpeg,.png" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary fw-bold w-100 rounded-3 py-2" style="background:#2563eb;">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Resubmit KYC Documents
                                    </button>
                                </form>
                            @endif

                            <div class="mt-3">
                                <div class="d-flex justify-content-between small fw-semibold mb-1">
                                    <span>Profile Completion</span>
                                    <span>{{ $user->kyc_verified ? '100%' : ($hasSubmitted ? '80%' : '60%') }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height:8px;">
                                    <div class="progress-bar {{ $user->kyc_verified ? 'bg-success' : ($hasSubmitted ? 'bg-warning' : 'bg-primary') }}" style="width:{{ $user->kyc_verified ? '100' : ($hasSubmitted ? '80' : '60') }}%;"></div>
                                </div>
                            </div>
                            <div class="list-group list-group-flush small mt-2">
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between"><span><i class="bi bi-check-circle-fill text-success me-2"></i>Email Verified</span><span class="badge bg-success bg-opacity-10 text-success fw-bold">Done</span></div>
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between"><span><i class="bi bi-check-circle-fill text-success me-2"></i>Account Created</span><span class="badge bg-success bg-opacity-10 text-success fw-bold">Done</span></div>
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                    <span><i class="bi {{ $user->kyc_document_path ? 'bi-check-circle-fill text-success' : 'bi-clock text-warning' }} me-2"></i>ID Document Upload</span>
                                    <span class="badge {{ $user->kyc_document_path ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-15 text-warning' }} fw-bold">{{ $user->kyc_document_path ? 'Uploaded' : 'Pending' }}</span>
                                </div>
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                    <span><i class="bi {{ $user->kyc_selfie_path ? 'bi-check-circle-fill text-success' : 'bi-clock text-warning' }} me-2"></i>Selfie Verification</span>
                                    <span class="badge {{ $user->kyc_selfie_path ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-15 text-warning' }} fw-bold">{{ $user->kyc_selfie_path ? 'Uploaded' : 'Pending' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRANSFER FUNDS TAB -->
            <div x-show="activeTab === 'transfer'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;"><i class="bi bi-send-fill text-primary me-2"></i>Transfer Funds</h2>
                    <p class="mb-0" style="font-size:0.95rem; color:#475569;">Send AVC directly to another investor using their Account ID or email.</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-send-fill text-primary me-2"></i>Send to Another Investor</h6>

                            @if(session('success') && str_contains(session('success'), 'sent'))
                                <div class="alert alert-success border-0 rounded-3 small py-2 mb-3">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger border-0 rounded-3 small py-2 mb-3">
                                    <ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form action="{{ route('send-funds.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-dark">Recipient Account ID or Email <span class="text-danger">*</span></label>
                                    <input type="text" name="recipient" class="form-control rounded-3" placeholder="e.g. RDR-123456 or investor@email.com" required autocomplete="off">
                                    <div class="form-text small text-muted">
                                        Find the recipient's Account ID in their Profile tab. Your Account ID:
                                        <code class="fw-bold">{{ $user->account_id ?? 'RDR-000000' }}</code>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-dark">Amount (AVC) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted fw-bold">AVC</span>
                                        <input type="number" step="0.01" min="1" max="{{ $walletBalance }}" name="amount" class="form-control rounded-end-3 fw-bold" placeholder="e.g. 100.00" required>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mt-1">
                                        <span>Available: {{ format_avc($walletBalance) }}</span>
                                        <span>Min: 1.00 AVC</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;" onclick="return confirm('Send this amount to the recipient? The amount will be deducted from your AVC balance instantly.')">
                                    <i class="bi bi-send-fill me-1"></i> Send Funds
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%); color:#fff;">
                            <h6 class="fw-bold text-white mb-3"><i class="bi bi-info-circle me-2 text-warning"></i>How P2P Transfer Works</h6>
                            <ul class="list-unstyled small mb-3" style="color:#cbd5e1; line-height:1.7;">
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-1-circle text-warning flex-shrink-0 mt-0.5"></i><span>Enter the recipient's Account ID or registered email.</span></li>
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-2-circle text-warning flex-shrink-0 mt-0.5"></i><span>Enter the amount to send — it is debited instantly from your AVC balance.</span></li>
                                <li class="d-flex gap-2"><i class="bi bi-3-circle text-warning flex-shrink-0 mt-0.5"></i><span>The recipient receives the AVC in their balance instantly and both parties get a confirmation email.</span></li>
                            </ul>
                            <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                <div class="small" style="color:#93c5fd;">Your Transfer Recipients</div>
                                <div class="fw-bold" style="font-size:0.8rem;">
                                    <i class="bi bi-person-circle me-1"></i>{{ $user->name ?? 'You' }} · {{ $user->account_id ?? 'RDR-000000' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AVC MARKETPLACE (P2P) TAB -->
            <div x-show="activeTab === 'credit_swap'" x-transition>
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;"><i class="bi bi-arrow-repeat text-warning me-2"></i>AVC Marketplace</h2>
                        <p class="mb-0 text-muted" style="font-size:0.9rem;">Buy and sell AVC with admin-escrowed, Telegram-mediated deals.</p>
                    </div>
                    <a href="{{ route('marketplace') }}" class="btn btn-warning fw-bold px-4 py-2 rounded-3 shadow-sm text-dark">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Marketplace
                    </a>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:72px; height:72px; background:#fef3c7;">
                        <i class="bi bi-shop fs-2 text-warning"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">The AVC Marketplace now lives on its own page</h5>
                    <p class="text-muted small mb-4" style="max-width:520px; margin-inline:auto;">
                        Browse listings, create buy or sell offers, and manage deals with the finance team on the dedicated marketplace page.
                    </p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="{{ route('marketplace') }}" class="btn btn-warning fw-bold px-4 py-2 rounded-3 text-dark">
                            <i class="bi bi-arrow-repeat me-1"></i> Go to AVC Marketplace
                        </a>
                        @if(telegram_handle())
                            <a href="{{ telegram_url('Hello Finance Team, I have a question about the CreditSwap Marketplace.') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3">
                                <i class="bi bi-telegram me-1"></i> Contact Finance Team
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

<!-- ========================================== -->
<!-- MODALS (inside root x-data scope so x-show state binds) -->
<!-- ========================================== -->

<!-- ========================================== -->
<!-- STEP 2 MODAL: FINANCE REQUEST FORM -->
<!-- ========================================== -->

<div x-show="showFinanceModal" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">Create Finance Request</h5>
            <button type="button" class="btn-close" @click="showFinanceModal = false"></button>
        </div>
        <p class="text-muted small mb-3">Fill in the details below and our finance team will assist you.</p>

        <form action="{{ route('deposit.store') }}" method="POST">
            @csrf
            <!-- Transaction Type Toggle -->
            <div class="btn-group w-100 mb-3" role="group">
                <input type="radio" class="btn-check" name="transaction_type" id="type_dep" value="deposit" checked>
                <label class="btn btn-outline-primary fw-bold" for="type_dep">Deposit</label>
                <input type="radio" class="btn-check" name="transaction_type" id="type_wth" value="withdrawal">
                <label class="btn btn-outline-primary fw-bold" for="type_wth">Withdrawal</label>
            </div>

            <!-- Country & Currency -->
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold text-dark small">Country</label>
                    <select name="country" class="form-select">
                        <option value="Philippines">Philippines</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Singapore">Singapore</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold text-dark small">Currency</label>
                    <select name="currency" class="form-select">
                        <option value="PHP">PHP - Philippine Peso</option>
                        <option value="USD">USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="GBP">GBP - British Pound</option>
                        <option value="NGN">NGN - Nigerian Naira</option>
                        <option value="USDT">USDT - Tether Crypto</option>
                    </select>
                </div>
            </div>

            <!-- Amount -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">Amount</label>
                <input type="number" step="0.01" min="10" name="amount" class="form-control fw-bold" placeholder="4990" required>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">Payment Method</label>
                <select name="payment_method" class="form-select" x-model="financeMethod">
                    <option value="GCash">GCash</option>
                    <option value="Maya">Maya</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="credit_card">Credit / Debit Card</option>
                    <option value="wire_transfer">Wire Transfer</option>
                    <option value="crypto">Cryptocurrency (USDT/BTC)</option>
                </select>
            </div>

            <!-- Sender Account Details -->
            <div class="p-3 rounded-3 bg-light border mb-3">
                <h6 class="fw-bold text-dark mb-2" style="font-size:0.85rem;">Your Account Details (Sender)</h6>
                <div class="mb-2">
                    <label class="form-label text-muted small mb-1">Account Name</label>
                    <input type="text" name="sender_account_name" class="form-control form-control-sm" value="{{ $user->name ?? 'John Smith' }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label text-muted small mb-1">GCash/Account/Phone Number</label>
                    <input type="text" name="sender_account_number" class="form-control form-control-sm" placeholder="0917 123 4567" required>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted small mb-1">Email Address</label>
                    <input type="email" name="sender_email" class="form-control form-control-sm" value="{{ $user->email ?? 'johnsmith@gmail.com' }}" required>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Please send details for GCash. Thank you!"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3" style="background:#2563eb;">
                Submit Request
            </button>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- STEP 3 MODAL: REQUEST SUBMITTED (From Image) -->
<!-- ========================================== -->
<div x-show="showSubmittedModal" x-cloak class="custom-modal-backdrop" @click.self="showSubmittedModal = false">
    <div class="custom-modal-card p-4 text-center position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" @click="showSubmittedModal = false" aria-label="Close"></button>
        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Request Submitted!</h4>
        <p class="text-muted small mb-3" x-text="submittedRequestType === 'withdrawal'
            ? 'Your withdrawal request has been sent successfully and is now pending review by our finance team.'
            : 'Your finance request has been sent successfully. You will be notified once our finance team provides the payment details.'"></p>

        <div class="p-3 bg-light rounded-3 mb-4 border">
            <span class="text-muted small d-block mb-1">Request ID</span>
            <h5 class="fw-bold text-primary mb-0" x-text="submittedRequestId"></h5>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary fw-bold w-50 py-2 rounded-3" @click="showSubmittedModal = false">
                Close
            </button>
            <button type="button" class="btn btn-primary fw-bold w-50 py-2 rounded-3" style="background:#2563eb;" @click="showSubmittedModal = false; activeTab = submittedRequestType === 'withdrawal' ? 'withdraw' : 'transactions'">
                <i class="bi bi-list-ul me-1"></i> View My Requests
            </button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- STEP 5 & 7 MODAL: PAYMENT INSTRUCTIONS & EVIDENCE UPLOAD -->
<!-- ========================================== -->
<div x-show="selectedDepInstruction" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width: 580px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-success bg-opacity-15 text-success fw-bold me-2">Approved</span>
                <span class="fw-bold text-dark">Deposit Request</span>
            </div>
            <button type="button" class="btn-close" @click="selectedDepInstruction = null; stopInstructionTimer()"></button>
        </div>

        <div class="mb-3">
            <h3 class="fw-bold text-dark mb-1" x-text="(selectedDepInstruction?.currency || 'PHP') + ' ' + (selectedDepInstruction?.amount ? parseFloat(selectedDepInstruction?.amount).toFixed(2) : '4,990.00')"></h3>
            <small class="text-muted">Request ID: <strong class="text-dark" x-text="selectedDepInstruction?.deposit_code">FR-250520-0001</strong></small>
        </div>

        <!-- Timer Box (From Image Step 5) -->
        <div class="p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center"
             :class="instructionExpired ? 'bg-danger bg-opacity-10 border border-danger border-opacity-25' : 'bg-primary bg-opacity-10 border border-primary border-opacity-25'">
            <span class="small fw-bold" :class="instructionExpired ? 'text-danger' : 'text-primary'">Complete payment within:</span>
            <span class="fs-5 fw-bold" :class="instructionExpired ? 'text-danger' : 'text-primary'">
                <i class="bi bi-clock me-1"></i>
                <span x-text="instructionLeft">19:57</span>
                <template x-if="instructionExpired">
                    <span class="badge bg-danger bg-opacity-15 text-danger ms-2 align-middle">Expired</span>
                </template>
            </span>
        </div>

        <!-- Beneficiary Payment Details Box (From Image Step 5) -->
        <div class="p-3 rounded-3 bg-light border mb-3">
            <h6 class="fw-bold text-dark mb-3" style="font-size:0.88rem;">Payment Details</h6>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Payment Method</span>
                <strong class="text-dark small" x-text="selectedDepInstruction?.admin_instructions?.method || selectedDepInstruction?.payment_method || 'GCash'">GCash</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Account Number</span>
                <div class="d-flex align-items-center gap-1">
                    <strong class="text-dark small" x-text="selectedDepInstruction?.admin_instructions?.account_number || '09658726718'">09658726718</strong>
                    <button class="btn btn-sm btn-link p-0 text-primary" @click="copyText(selectedDepInstruction?.admin_instructions?.account_number || '09658726718')"><i class="bi bi-copy"></i></button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Account Name</span>
                <div class="d-flex align-items-center gap-1">
                    <strong class="text-dark small" x-text="selectedDepInstruction?.admin_instructions?.account_name || 'RINNY P.'">RINNY P.</strong>
                    <button class="btn btn-sm btn-link p-0 text-primary" @click="copyText(selectedDepInstruction?.admin_instructions?.account_name || 'RINNY P.')"><i class="bi bi-copy"></i></button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Reference Number</span>
                <div class="d-flex align-items-center gap-1">
                    <strong class="text-dark small" x-text="selectedDepInstruction?.admin_instructions?.reference_no || 'RDR250520001'">RDR250520001</strong>
                    <button class="btn btn-sm btn-link p-0 text-primary" @click="copyText(selectedDepInstruction?.admin_instructions?.reference_no || 'RDR250520001')"><i class="bi bi-copy"></i></button>
                </div>
            </div>
        </div>

        <!-- Instructions Text -->
        <div class="mb-4 text-secondary small">
            <h6 class="fw-bold text-dark mb-1" style="font-size:0.85rem;">Instructions</h6>
            <ul class="ps-3 mb-0">
                <template x-if="selectedDepInstruction?.admin_instructions?.instructions">
                    <template x-for="line in selectedDepInstruction.admin_instructions.instructions.split('\n')" :key="line">
                        <li x-text="line"></li>
                    </template>
                </template>
                <template x-if="!selectedDepInstruction?.admin_instructions?.instructions">
                    <li>Please send the exact amount.</li>
                    <li>Do not include any remarks.</li>
                    <li>Upload your payment receipt before the timer expires.</li>
                </template>
            </ul>
        </div>

        <!-- Step 7: Upload Evidence Form -->
        <form :action="'/deposit/evidence/' + (selectedDepInstruction?.id || '')" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="drag-drop-zone mb-3" @click="$refs.fileInput.click()">
                <i class="bi bi-cloud-arrow-up fs-1 text-primary d-block mb-1"></i>
                <span class="fw-bold text-dark d-block">Drag and drop your file here</span>
                <small class="text-muted">or click to browse (Supported: JPG, PNG, PDF Max 10MB)</small>
                <input type="file" name="receipt_file" x-ref="fileInput" class="d-none" @change="evidenceFileName = $event.target.files[0]?.name">
            </div>

            <template x-if="evidenceFileName">
                <div class="p-2 bg-light rounded-3 border mb-3 d-flex align-items-center justify-content-between">
                    <span class="small fw-bold text-primary" x-text="evidenceFileName"></span>
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
            </template>

            <div class="mb-3">
                <label class="form-label text-muted small mb-1">Additional Notes (Optional)</label>
                <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Payment sent. Please confirm."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 mb-2" style="background:#2563eb;" :disabled="instructionExpired">
                Submit Evidence
            </button>
            <button type="button" class="btn btn-link w-100 text-muted small text-decoration-none" @click="selectedDepInstruction = null; stopInstructionTimer()">
                Cancel Request
            </button>
        </form>
    </div>
</div>

<!-- Buy Property Modal -->
<div x-show="selectedProperty" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width:500px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold me-2">Property Purchase</span>
                <h5 class="fw-bold text-dark mb-0 mt-1" x-text="selectedProperty?.title"></h5>
            </div>
            <button type="button" class="btn-close" @click="selectedProperty = null"></button>
        </div>
        <div class="p-3 rounded-3 bg-light border mb-3">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Location</span><strong class="text-dark" x-text="selectedProperty?.location"></strong></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Category</span><strong class="text-dark" x-text="selectedProperty?.category"></strong></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Status</span><strong class="text-success" x-text="selectedProperty?.status === 'sold_out' ? 'Sold' : 'For Sale'"></strong></div>
        </div>
        <form :action="'/property/' + (selectedProperty?.id || '') + '/purchase'" method="POST" @submit="if (!confirm('Confirm purchase of ' + (selectedProperty?.title || '') + ' for $' + parseFloat(selectedProperty?.price || 0).toFixed(2) + '? This amount will be deducted from your wallet.')) { $event.preventDefault(); }">
            @csrf
            <div class="p-3 rounded-3 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe;">
                <div class="d-flex justify-content-between small fw-bold">
                    <span class="text-muted">Purchase Price</span>
                    <span class="text-primary fs-5" x-text="'$' + parseFloat(selectedProperty?.price || 0).toFixed(2)">$0.00</span>
                </div>
                <div class="text-muted mt-1" style="font-size:0.78rem;">Your AVC balance: <strong class="text-success">{{ format_avc($walletBalance) }}</strong></div>
            </div>
            <button type="submit" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;">
                <i class="bi bi-house-check me-1"></i> Confirm Purchase
            </button>
        </form>
    </div>
</div>

<!-- Transaction Preview Modal -->
<div x-show="selectedTxn" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width:480px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge fw-semibold rounded-pill px-2 py-1" style="font-size:0.68rem; background:#eff6ff; color:#2563eb;">
                    <i class="bi bi-receipt me-1"></i> Transaction Details
                </span>
                <h5 class="fw-bold text-dark mb-0 mt-1" x-text="selectedTxn?.reference" style="font-family:monospace;"></h5>
            </div>
            <button type="button" class="btn-close" @click="selectedTxn = null"></button>
        </div>
        <div class="p-3 rounded-3 mb-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Reference</span><strong class="text-dark" style="font-family:monospace;" x-text="selectedTxn?.reference"></strong></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Description</span><strong class="text-dark text-end" style="max-width:60%;" x-text="selectedTxn?.description"></strong></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Type</span>
                <span class="d-inline-flex align-items-center gap-1 fw-semibold small rounded-pill px-2 py-0.5" style="font-size:0.7rem; white-space:nowrap;"
                      :style="'background:' + (selectedTxn?.type === 'deposit' || selectedTxn?.type === 'receive_funds' ? '#f0fdf4' : selectedTxn?.type === 'withdrawal' ? '#fffbeb' : selectedTxn?.type === 'send_funds' ? '#fef2f2' : '#eff6ff') + '; color:' + (selectedTxn?.type === 'deposit' || selectedTxn?.type === 'receive_funds' ? '#16a34a' : selectedTxn?.type === 'withdrawal' ? '#d97706' : selectedTxn?.type === 'send_funds' ? '#dc2626' : '#2563eb')"
                      x-text="selectedTxn?.type ? selectedTxn.type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : ''">
                </span>
            </div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Amount</span>
                <strong class="fw-bold" style="font-size:1.1rem;"
                    :style="'color:' + (selectedTxn?.type === 'deposit' || selectedTxn?.type === 'receive_funds' ? '#16a34a' : '#dc2626')"
                    x-text="(selectedTxn?.type === 'deposit' || selectedTxn?.type === 'receive_funds' ? '+' : '-') + '$' + parseFloat(selectedTxn?.amount || 0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})">
                </strong>
            </div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Status</span>
                <span class="badge fw-semibold rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1" style="font-size:0.68rem;"
                      :style="selectedTxn?.status === 'completed' ? 'background:#f0fdf4; color:#16a34a;' : selectedTxn?.status === 'pending' ? 'background:#fffbeb; color:#d97706;' : 'background:#fef2f2; color:#dc2626;'">
                    <i class="bi" :class="selectedTxn?.status === 'completed' ? 'bi-check-circle-fill' : selectedTxn?.status === 'pending' ? 'bi-clock-fill' : 'bi-x-circle-fill'" style="font-size:0.6rem;"></i>
                    <span x-text="selectedTxn?.status ? selectedTxn.status.charAt(0).toUpperCase() + selectedTxn.status.slice(1) : ''"></span>
                </span>
            </div>
            <div class="d-flex justify-content-between small mb-0"><span class="text-muted">Date</span><strong class="text-dark" x-text="selectedTxn?.created_at ? new Date(selectedTxn.created_at).toLocaleDateString('en-US', {year:'numeric', month:'long', day:'numeric'}) : ''"></strong></div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary fw-bold w-50 py-2 rounded-3" style="background:#2563eb;" @click="printTransactionReceipt()">
                <i class="bi bi-printer me-1.5"></i> Print Receipt
            </button>
            <button type="button" class="btn btn-light fw-bold w-50 py-2 rounded-3 border" @click="selectedTxn = null">Close</button>
        </div>
    </div>
</div>

<!-- Receive Funds Modal -->
<div x-show="openReceiveModal" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4 text-center">
        <h5 class="fw-bold text-dark mb-2">Receive Funds</h5>
        <p class="text-muted small mb-3">Share your receiving details with another investor or peer.</p>
        <div class="p-3 bg-light rounded-3 border mb-3">
            <span class="text-muted small d-block mb-1">Account ID</span>
            <h4 class="fw-bold text-primary mb-0">{{ $user->account_id ?? 'RDR-884920' }}</h4>
            <span class="text-muted small d-block mt-2">Email Address</span>
            <strong class="text-dark">{{ $user->email ?? 'investor@radiantrealty.com' }}</strong>
        </div>
        <button class="btn btn-primary w-100 fw-bold py-2 mb-2" style="background:#2563eb;" @click="shareAccountDetails()">Share Credentials</button>
        <button class="btn btn-outline-secondary w-100 fw-bold py-2" @click="openReceiveModal = false">Close</button>
    </div>
</div>

<!-- Crypto Card Application Form Modal -->
<div x-show="openCardApplyModal" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width:540px;">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-credit-card-2-front text-primary me-2"></i>Apply for Crypto Card</h5>
            <button type="button" class="btn-close" @click="openCardApplyModal = false"></button>
        </div>
        <p class="text-muted small mb-3">Complete the form below. Our team reviews your application, then your card is generated and emailed to you.</p>

        <form action="{{ route('card.apply') }}" method="POST">
            @csrf
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small" style="color:#1e293b;">Full Name (on card) <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="cardholder_name" class="form-control rounded-3 border-secondary-subtle" value="{{ old('cardholder_name', $user->name ?? '') }}" placeholder="e.g. John Doe" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small" style="color:#1e293b;">Phone Number <span style="color:#ef4444;">*</span></label>
                    <input type="tel" name="phone" class="form-control rounded-3 border-secondary-subtle" value="{{ old('phone') }}" placeholder="e.g. +1 555 123 4567" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small" style="color:#1e293b;">Street Address <span style="color:#ef4444;">*</span></label>
                <input type="text" name="address" class="form-control rounded-3 border-secondary-subtle" value="{{ old('address') }}" placeholder="e.g. 123 Main Street, Apt 4B" required>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small" style="color:#1e293b;">City <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="city" class="form-control rounded-3 border-secondary-subtle" value="{{ old('city') }}" placeholder="e.g. New York" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small" style="color:#1e293b;">Country <span style="color:#ef4444;">*</span></label>
                    <select name="country" class="form-select rounded-3 border-secondary-subtle" required>
                        <option value="" disabled {{ old('country') ? '' : 'selected' }}>Select country...</option>
                        <option value="United States" {{ old('country') === 'United States' ? 'selected' : '' }}>United States</option>
                        <option value="United Kingdom" {{ old('country') === 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                        <option value="Nigeria" {{ old('country') === 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                        <option value="Philippines" {{ old('country') === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                        <option value="Singapore" {{ old('country') === 'Singapore' ? 'selected' : '' }}>Singapore</option>
                        <option value="Canada" {{ old('country') === 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Australia" {{ old('country') === 'Australia' ? 'selected' : '' }}>Australia</option>
                        <option value="United Arab Emirates" {{ old('country') === 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                        <option value="Germany" {{ old('country') === 'Germany' ? 'selected' : '' }}>Germany</option>
                        <option value="Spain" {{ old('country') === 'Spain' ? 'selected' : '' }}>Spain</option>
                        <option value="Other" {{ old('country') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small" style="color:#1e293b;">Card Type <span style="color:#ef4444;">*</span></label>
                    <select name="card_type" class="form-select rounded-3 border-secondary-subtle" required>
                        <option value="virtual" {{ old('card_type') === 'physical' ? '' : 'selected' }}>Virtual (instant digital)</option>
                        <option value="physical" {{ old('card_type') === 'physical' ? 'selected' : '' }}>Physical (delivered)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small" style="color:#1e293b;">Preferred Brand <span style="color:#ef4444;">*</span></label>
                    <select name="card_brand" class="form-select rounded-3 border-secondary-subtle" required>
                        <option value="Visa" {{ old('card_brand') === 'Mastercard' ? '' : 'selected' }}>Visa</option>
                        <option value="Mastercard" {{ old('card_brand') === 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                    </select>
                </div>
            </div>
            <div class="p-3 rounded-3 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe;">
                <small class="text-muted"><i class="bi bi-shield-lock me-1 text-primary"></i> Your information is kept confidential and used only for card verification and delivery.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary fw-bold w-50 py-2 rounded-3" @click="openCardApplyModal = false">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold w-50 py-2 rounded-3" style="background:#2563eb;">
                    <i class="bi bi-send me-1"></i> Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quick Finance Deposit Request Modal -->
<div x-show="showFinanceModal" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-wallet2 text-primary me-2"></i>New Deposit Request</h5>
            <button type="button" class="btn-close" @click="showFinanceModal = false"></button>
        </div>
        <form action="{{ route('deposit.store') }}" method="POST" x-data="{ modalDepMethod: 'bank_transfer' }">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small" style="color:#1e293b;">Payment Method <span style="color:#ef4444;">*</span></label>
                <select class="form-select rounded-3 border-secondary-subtle" name="payment_method" x-model="modalDepMethod" required>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="credit_card">Credit / Debit Card</option>
                    <option value="wire_transfer">Wire Transfer</option>
                    <option value="crypto">Cryptocurrency (USDT / BTC)</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small" style="color:#1e293b;">Deposit Amount ($ USD) <span style="color:#ef4444;">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-secondary-subtle fw-bold">$</span>
                    <input type="number" step="0.01" min="10" name="amount" class="form-control rounded-end-3 border-secondary-subtle" placeholder="e.g. 500.00" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small" style="color:#1e293b;">Sender Name / Account</label>
                <input type="text" name="sender_account_name" class="form-control rounded-3 border-secondary-subtle" value="{{ $user->name ?? '' }}" placeholder="Your Name or Account ID">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small" style="color:#1e293b;">Notes (Optional)</label>
                <textarea name="notes" class="form-control rounded-3 border-secondary-subtle" rows="2" placeholder="Add additional details for finance team..."></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary fw-bold w-50 py-2 rounded-3" @click="showFinanceModal = false">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold w-50 py-2 rounded-3" style="background:#2563eb;">Submit Deposit</button>
            </div>
        </form>
    </div>
</div>

</div>

<!-- Alpine JS Dashboard Engine -->
<script>
    function userDashboardEngine() {
        return {
            activeTab: (function() {
                var hash = window.location.hash.replace('#', '');
                var valid = ['overview','invest','saved_projects','marketplace','my_investments','deposit','withdraw','transfer','credit_swap','transactions','notifications','referrals','profile_kyc'];
                return valid.indexOf(hash) !== -1 ? hash : 'overview';
            })(),

            init() {
                var self = this;
                window.addEventListener('hashchange', function() {
                    var hash = window.location.hash.replace('#', '');
                    var valid = ['overview','invest','saved_projects','marketplace','my_investments','deposit','withdraw','transfer','credit_swap','transactions','notifications','referrals','profile_kyc'];
                    if (valid.indexOf(hash) !== -1) {
                        self.activeTab = hash;
                    }
                });
            },
            requestFilter: 'all',
            propFilter: 'all',
            showFinanceModal: false,
            showSubmittedModal: {{ session('submitted_request_id') ? 'true' : 'false' }},
            submittedRequestId: @json(session('submitted_request_id')),
            submittedRequestType: @json(session('submitted_request_type', 'deposit')),
            cardFlipped: false,
            financeMethod: 'GCash',
            selectedDepInstruction: null,
            evidenceFileName: '',
            instructionLeft: '--:--',
            instructionExpired: false,
            instructionTimer: null,
            openReceiveModal: false,
            openCardApplyModal: false,
            showEditProfile: false,
            selectedProperty: null,
            propertiesList: @json($properties),
            transactionsList: @json($transactions->values()),
            selectedTxn: null,

            openTxnPreview(index) {
                this.selectedTxn = this.transactionsList[index];
            },

            printTransactionReceipt() {
                if (!this.selectedTxn) return;
                const txn = this.selectedTxn;
                const userName = @json($user->name ?? 'Valued Investor');
                const userEmail = @json($user->email ?? '');
                const userAccount = @json($user->account_id ?? '');
                const logoUrl = @json(logo_url());

                const isCredit = ['deposit','receive_funds','roi_payout','affiliate_earning'].includes(txn.type);
                const formattedAmount = (isCredit ? '+' : '-') + '$' + parseFloat(txn.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const printWin = window.open('', '_blank', 'width=840,height=920');
                if (!printWin) {
                    alert('Please allow popups to print receipt.');
                    return;
                }
                printWin.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>Transaction Receipt - ${txn.reference}</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
                        <style>
                            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
                            body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #f1f5f9; color: #0f172a; padding: 40px 20px; }
                            .receipt-card { max-width: 680px; margin: 0 auto; background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; padding: 40px; box-shadow: 0 25px 50px -12px rgba(15,23,42,0.12); position: relative; overflow: hidden; }
                            .header-bar { border-bottom: 2px dashed #cbd5e1; padding-bottom: 24px; margin-bottom: 28px; }
                            .meta-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; }
                            .receipt-table td { padding: 14px 0; border-bottom: 1px solid #f1f5f9; font-size: 0.92rem; }
                            .receipt-table tr:last-child td { border-bottom: none; }
                            .watermark-seal { position: absolute; right: -20px; bottom: 60px; opacity: 0.04; font-size: 14rem; color: #1e3a8a; pointer-events: none; }
                            @media print {
                                body { background: #ffffff !important; padding: 0 !important; }
                                .no-print { display: none !important; }
                                .receipt-card { border: none !important; box-shadow: none !important; padding: 0 !important; border-radius: 0 !important; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="no-print mb-4 text-center">
                            <button onclick="window.print()" class="btn btn-primary fw-bold px-4 py-2.5 rounded-3 me-2 shadow-sm" style="background:#2563eb; border:none;"><i class="bi bi-printer me-2"></i>Print / Save as PDF</button>
                            <button onclick="window.close()" class="btn btn-outline-secondary fw-bold px-4 py-2.5 rounded-3">Close</button>
                        </div>
                        <div class="receipt-card">
                            <div class="watermark-seal"><i class="bi bi-shield-check"></i></div>
                            <div class="header-bar d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <img src="${logoUrl}" alt="{{ site_name() }}" style="height: 48px; max-width: 220px; width: auto; object-fit: contain;" class="mb-2">
                                    <div class="text-muted small fw-medium" style="font-size:0.8rem;">Official Financial Statement &amp; Transaction Receipt</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge fw-bold px-3 py-2 rounded-pill fs-6 d-inline-flex align-items-center gap-1.5" style="background:#f0fdf4; color:#16a34a; border: 1px solid #bbf7d0;">
                                        <i class="bi bi-patch-check-fill"></i> VERIFIED RECEIPT
                                    </span>
                                </div>
                            </div>

                            <div class="meta-box mb-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <span class="text-muted small d-block fw-semibold" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">ACCOUNT HOLDER</span>
                                        <strong class="text-dark fs-6 d-block mt-0.5">${userName}</strong>
                                        <div class="small text-secondary" style="font-size:0.78rem;">${userAccount} &middot; ${userEmail}</div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="text-muted small d-block fw-semibold" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">TRANSACTION REFERENCE</span>
                                        <code class="fw-bold text-primary fs-6 d-block mt-0.5" style="font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;">${txn.reference}</code>
                                        <div class="small text-secondary" style="font-size:0.78rem;">${txn.created_at ? new Date(txn.created_at).toLocaleDateString('en-US', {year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'}) : 'Recent'}</div>
                                    </div>
                                </div>
                            </div>

                            <table class="w-100 receipt-table mb-4">
                                <tbody>
                                    <tr>
                                        <td class="text-muted fw-semibold">Transaction Type</td>
                                        <td class="text-end fw-bold text-dark">${txn.type ? txn.type.replace(/_/g, ' ').toUpperCase() : 'TRANSACTION'}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Description / Note</td>
                                        <td class="text-end fw-bold text-dark" style="max-width:320px;">${txn.description || 'N/A'}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Payment Status</td>
                                        <td class="text-end py-2.5">
                                            <span class="badge fw-bold px-3 py-1 rounded-pill" style="font-size:0.78rem; ${txn.status === 'completed' ? 'background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;' : (txn.status === 'pending' ? 'background:#fffbeb; color:#d97706; border:1px solid #fef3c7;' : 'background:#fef2f2; color:#dc2626; border:1px solid #fecaca;')}">
                                                ${(txn.status || 'completed').toUpperCase()}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr style="border-top: 2px solid #e2e8f0; border-bottom: 2px solid #e2e8f0;">
                                        <td class="text-dark fw-bold fs-5 py-3">Total Amount</td>
                                        <td class="text-end fw-bold fs-3 py-3 ${isCredit ? 'text-success' : 'text-danger'}" style="font-variant-numeric: tabular-nums;">${formattedAmount}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="pt-3 border-top text-center text-muted small" style="font-size:0.78rem;">
                                <p class="mb-1 fw-bold text-dark"><i class="bi bi-shield-lock text-primary me-1"></i> {{ site_name() }} Corp. &middot; Automated Financial System</p>
                                <p class="mb-0 text-secondary" style="font-size:0.72rem;">This is an electronically generated official receipt. Digitally verified by {{ site_name() }} platform. No signature required.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                `);
                printWin.document.close();
            },

            openFinanceForm(type) {
                this.showFinanceModal = true;
            },

            openBuyModalById(id) {
                const prop = this.propertiesList.find(p => p.id == id);
                if (prop) {
                    this.selectedProperty = prop;
                }
            },

            showInstructionsModal(dep) {
                this.selectedDepInstruction = dep;
                if (this.instructionTimer) clearInterval(this.instructionTimer);
                this.instructionExpired = false;
                var self = this;
                var end = dep && dep.expires_at ? new Date(dep.expires_at).getTime() : null;
                var update = function() {
                    if (!end) { self.instructionLeft = '--:--'; return; }
                    var diff = Math.max(0, end - Date.now());
                    var h = Math.floor(diff / 3600000);
                    var m = Math.floor((diff % 3600000) / 60000);
                    var s = Math.floor((diff % 60000) / 1000);
                    self.instructionLeft = (h > 0 ? String(h).padStart(2, '0') + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    if (diff <= 0) {
                        self.instructionExpired = true;
                        if (self.instructionTimer) { clearInterval(self.instructionTimer); self.instructionTimer = null; }
                    }
                };
                update();
                if (end) { this.instructionTimer = setInterval(update, 1000); }
            },

            stopInstructionTimer() {
                if (this.instructionTimer) { clearInterval(this.instructionTimer); this.instructionTimer = null; }
            },

            copyText(text) {
                navigator.clipboard.writeText(text).then(() => {
                    const toast = document.createElement('div');
                    toast.textContent = 'Copied!';
                    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1d4ed8;color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;font-size:0.85rem;z-index:999999;box-shadow:0 4px 20px rgba(0,0,0,0.2);';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                });
            },

            shareAccountDetails() {
                const text = `{{ site_name() }} Credentials:\nAccount ID: {{ $user->account_id ?? "RDR-884920" }}\nEmail: {{ $user->email ?? "investor@radiantrealty.com" }}`;
                if (navigator.share) {
                    navigator.share({ title: 'Receive Funds', text: text }).catch(() => {});
                } else {
                    navigator.clipboard.writeText(text);
                    alert('Credentials copied to clipboard!');
                }
            }
        }
    }

    function shareProject(title, url) {
        if (navigator.share) {
            navigator.share({ title: title, url: url, text: 'Invest in this project on {{ site_name() }}' }).catch(() => {});
        } else {
            navigator.clipboard.writeText(url).then(() => {
                const toast = document.createElement('div');
                toast.textContent = 'Project link copied to clipboard!';
                toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1d4ed8;color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;font-size:0.85rem;z-index:999999;box-shadow:0 4px 20px rgba(0,0,0,0.2);';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            });
        }
    }

    function siteTourGuard() {
        return {
            tourStep: null,
            initTour() {
                this.tourStep = 0;
            },
            skipTour() {
                this.tourStep = null;
            }
        };
    }
</script>

@section('footer')
<footer class="bg-white border-top">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 px-3 px-md-4 py-3">
        <div class="small text-muted">© {{ date('Y') }} {{ site_name() }}. All Rights Reserved.</div>
        <div class="d-flex flex-wrap gap-3 small">
            <a href="{{ url('/') }}" class="text-muted text-decoration-none hover-primary">Home</a>
            <a href="{{ url('/invest') }}" class="text-muted text-decoration-none hover-primary">Project Marketplace</a>
            <a href="{{ url('/properties') }}" class="text-muted text-decoration-none hover-primary">Properties</a>
            <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none hover-primary">Dashboard</a>
            <a href="mailto:support@radiantdreamrealty.com" class="text-muted text-decoration-none hover-primary">Support</a>
        </div>
    </div>
</footer>
@endsection
@endsection
