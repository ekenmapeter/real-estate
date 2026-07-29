@extends('layouts.main')

@section('title', 'User Dashboard | Radiant Dream Realty Investment Platform')

@section('content')
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
        align-items: center;
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
    }
</style>

<div class="container-fluid px-0" x-data="userDashboardEngine()">
    <div class="row g-0">

        <!-- Left Dark Navy Sidebar -->
        <div class="col-lg-2 col-md-3 sidebar-dark p-3 d-none d-md-block">
            <!-- User Profile Widget at top of sidebar -->
            <div class="p-3 mb-4 rounded-3 text-white" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;">
                        {{ strtoupper(substr($user->name ?? 'JS', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.95rem;">{{ $user->name ?? 'John Smith' }}</h6>
                        <small class="badge bg-primary bg-opacity-20 text-blue-300 fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; color: #93c5fd;">Investor</small>
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
                    <i class="bi bi-lightning-charge-fill"></i> Invest
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'marketplace' }" @click.prevent="activeTab = 'marketplace'">
                    <i class="bi bi-building"></i> Browse Properties
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'my_investments' }" @click.prevent="activeTab = 'my_investments'">
                    <i class="bi bi-pie-chart-fill"></i> My Investments
                </a>

                <!-- GROUP: WALLET -->
                <span class="sidebar-group-label">Wallet</span>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'deposit' }" @click.prevent="activeTab = 'deposit'">
                    <i class="bi bi-arrow-down-circle-fill"></i> Deposit
                </a>
                <a href="#" class="nav-link-sidebar nav-link-sub" :class="{ 'active': activeTab === 'withdraw' }" @click.prevent="activeTab = 'withdraw'">
                    <i class="bi bi-arrow-up-circle-fill"></i> Withdraw
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
        <div class="col-lg-10 col-md-9 p-3 p-lg-4" style="min-height: calc(100vh - 70px); background: #f8fafc;">

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
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'invest' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'invest'">Invest</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'marketplace' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'marketplace'">Browse</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'my_investments' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'my_investments'">My Investments</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'deposit' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'deposit'">Deposit</button>
                    <button class="btn btn-sm fw-semibold" :class="activeTab === 'withdraw' ? 'btn-primary' : 'btn-light border'" @click="activeTab = 'withdraw'">Withdraw</button>
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
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold text-dark mb-0">Dashboard</h3>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1 rounded-pill" style="font-size:0.78rem;">
                                    {{ $user->name ? $user->name : 'New User' }}
                                </span>
                            </div>
                            <p class="text-muted mb-0 small">Overview of your real estate investment portfolio and account activity.</p>
                        </div>
                        <div class="text-md-end bg-light p-3 rounded-3 border">
                            <span class="text-muted small d-block">Total Account Balance</span>
                            <h2 class="fw-bold text-dark mb-0">${{ number_format(($walletBalance + $totalInvested), 2) }}</h2>
                        </div>
                    </div>
                </div>

                <!-- 4 Key Metric Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px; height:44px; background:#eff6ff; color:#2563eb;">
                                    <i class="bi bi-wallet2 fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Wallet Balance</span>
                                    <h4 class="fw-bold text-dark mb-0">${{ number_format($walletBalance, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px; height:44px; background:#f0fdf4; color:#16a34a;">
                                    <i class="bi bi-building fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Total Invested</span>
                                    <h4 class="fw-bold text-dark mb-0">${{ number_format($totalInvested, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px; height:44px; background:#faf5ff; color:#9333ea;">
                                    <i class="bi bi-graph-up-arrow fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Total Earned</span>
                                    <h4 class="fw-bold text-success mb-0">${{ number_format($totalRoiEarned, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px; height:44px; background:#fffbeb; color:#d97706;">
                                    <i class="bi bi-pie-chart-fill fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Active Investments</span>
                                    <h4 class="fw-bold text-dark mb-0">{{ $activeProjectsCount }}</h4>
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
                                    <span class="text-dark">${{ number_format($totalInvested, 2) }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height:10px;">
                                    <div class="progress-bar" style="width: {{ $totalInvested > 0 ? min(100, round(($totalInvested / max(1, $totalInvested + $totalRoiEarned)) * 100)) : 0 }}%; background: linear-gradient(90deg, #2563eb, #3b82f6);"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span class="text-muted"><i class="bi bi-circle-fill text-success me-1" style="font-size:0.6rem;"></i> Total Returns Earned</span>
                                    <span class="text-success">${{ number_format($totalRoiEarned, 2) }}</span>
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
                    <!-- Recent Notifications -->
                    <div class="col-lg-4">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-bell me-1 text-primary"></i>Recent Notifications</h6>
                                <button class="btn btn-link text-primary p-0 fw-bold small text-decoration-none" @click="activeTab = 'notifications'">View All</button>
                            </div>
                            @php $awaiting = $deposits->where('status', 'awaiting_payment'); @endphp
                            <div class="list-group list-group-flush">
                                @forelse($awaiting->take(3) as $dep)
                                    <div class="list-group-item px-0 py-2 border-bottom">
                                        <div class="fw-bold text-dark small">{{ $dep->deposit_code }}</div>
                                        <small class="text-muted d-block">Payment instructions ready for {{ $dep->currency }} {{ number_format($dep->amount, 2) }}</small>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-muted small">No notifications yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Active Investments -->
                    <div class="col-lg-4">
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
                    <div class="col-lg-4">
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

            <!-- INVEST TAB -->
            <div x-show="activeTab === 'invest'" x-transition>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Invest in Properties</h4>
                        <p class="text-muted mb-0 small">Purchase fractional shares in premium real estate projects.</p>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse($properties as $prop)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden bg-white">
                                <div style="height:180px; overflow:hidden; position:relative;">
                                    <img src="{{ $prop->image_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $prop->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    <span class="badge position-absolute top-0 end-0 m-2 rounded-pill fw-bold" style="background:#2563eb; font-size:0.75rem;">{{ $prop->roi_percentage }}% ROI</span>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark mb-1">{{ $prop->title }}</h6>
                                    <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $prop->location }}</p>
                                    <div class="d-flex justify-content-between small mb-3">
                                        <span class="text-muted">Share Price</span>
                                        <strong class="text-dark">${{ number_format($prop->price_per_share, 2) }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Funding</span>
                                            <span>{{ $prop->total_shares > 0 ? round((($prop->total_shares - $prop->available_shares) / $prop->total_shares) * 100) : 0 }}%</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height:6px;">
                                            <div class="progress-bar bg-primary" style="width:{{ $prop->total_shares > 0 ? round((($prop->total_shares - $prop->available_shares) / $prop->total_shares) * 100) : 0 }}%;"></div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 mt-1" style="background:#2563eb;" @click="openBuyModalById({{ $prop->id }})">
                                        <i class="bi bi-lightning-charge me-1"></i> Invest Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-building fs-1 d-block mb-2 opacity-25"></i>
                            No active properties available at this time.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- MY INVESTMENTS TAB -->
            <div x-show="activeTab === 'my_investments'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">My Investments</h4>
                    <p class="text-muted mb-0 small">Track all your active and completed property share investments.</p>
                </div>
                <div class="row g-3">
                    @forelse($userInvestments as $inv)
                        <div class="col-lg-6">
                            <div class="card border-0 rounded-4 shadow-sm bg-white p-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px; background:#eff6ff;">
                                        <i class="bi bi-building text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $inv->property->title ?? 'Property Investment' }}</h6>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $inv->property->location ?? 'Location' }}</small>
                                    </div>
                                    <span class="badge rounded-pill fw-bold px-3 py-1 {{ $inv->status === 'active' ? 'bg-success bg-opacity-15 text-success' : 'bg-secondary bg-opacity-15 text-secondary' }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </div>
                                <div class="row g-2 text-center">
                                    <div class="col-4 p-2 rounded-3 bg-light">
                                        <div class="small text-muted">Shares</div>
                                        <div class="fw-bold text-dark">{{ $inv->shares_bought }}</div>
                                    </div>
                                    <div class="col-4 p-2 rounded-3 bg-light">
                                        <div class="small text-muted">Invested</div>
                                        <div class="fw-bold text-dark">${{ number_format($inv->total_amount, 2) }}</div>
                                    </div>
                                    <div class="col-4 p-2 rounded-3 bg-light">
                                        <div class="small text-muted">ROI Earned</div>
                                        <div class="fw-bold text-success">${{ number_format($inv->roi_earned, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center">
                                <i class="bi bi-pie-chart fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <h5 class="fw-bold text-dark mb-2">No Investments Yet</h5>
                                <p class="text-muted small mb-4">Browse available properties and purchase fractional shares to start earning ROI.</p>
                                <button class="btn btn-primary fw-bold px-4 py-2 rounded-3 mx-auto" style="background:#2563eb; max-width:200px;" @click="activeTab = 'invest'">
                                    <i class="bi bi-lightning-charge me-1"></i> Start Investing
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- DEPOSIT TAB -->
            <div x-show="activeTab === 'deposit'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Deposit Funds</h4>
                    <p class="text-muted mb-0 small">Submit a finance request to deposit funds into your wallet.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px; background:#eff6ff;">
                                    <i class="bi bi-wallet2 text-primary fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Available Balance</div>
                                    <h4 class="fw-bold text-dark mb-0">${{ number_format($walletBalance, 2) }}</h4>
                                </div>
                            </div>
                            <button class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;" @click="showFinanceModal = true">
                                <i class="bi bi-plus-circle me-1"></i> New Deposit Request
                            </button>
                        </div>
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3">Accepted Methods</h6>
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-2" style="background:#f8fafc;"><i class="bi bi-bank2 text-primary"></i><span class="small fw-semibold text-dark">Bank Transfer</span></div>
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-2" style="background:#f8fafc;"><i class="bi bi-credit-card text-primary"></i><span class="small fw-semibold text-dark">Credit / Debit Card</span></div>
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-2" style="background:#f8fafc;"><i class="bi bi-globe2 text-primary"></i><span class="small fw-semibold text-dark">Wire Transfer</span></div>
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc;"><i class="bi bi-currency-bitcoin text-warning"></i><span class="small fw-semibold text-dark">Cryptocurrency (USDT, BTC, ETH...)</span></div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3">Recent Deposit Requests</h6>
                            @forelse($deposits->take(5) as $dep)
                                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $dep->currency ?? 'PHP' }} {{ number_format($dep->amount, 2) }}</div>
                                        <div class="text-muted" style="font-size:0.78rem;">{{ $dep->deposit_code }} &middot; {{ $dep->created_at?->format('M d, Y') }}</div>
                                    </div>
                                    @if($dep->status === 'completed')
                                        <span class="badge bg-success bg-opacity-15 text-success fw-bold rounded-pill px-2">Completed</span>
                                    @elseif($dep->status === 'awaiting_payment')
                                        <button class="btn btn-primary btn-sm fw-bold rounded-pill px-3 py-1" style="background:#2563eb; font-size:0.78rem;" @click="showInstructionsModal({{ json_encode($dep) }})">Pay Now</button>
                                    @elseif($dep->status === 'evidence_submitted')
                                        <span class="badge bg-info bg-opacity-15 text-info fw-bold rounded-pill px-2">Under Review</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-15 text-warning fw-bold rounded-pill px-2">Pending</span>
                                    @endif
                                </div>
                            @empty
                                <div class="py-4 text-center text-muted small">No deposit requests yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- WITHDRAW TAB -->
            <div x-show="activeTab === 'withdraw'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Withdraw Funds</h4>
                    <p class="text-muted mb-0 small">Request a withdrawal from your available wallet balance.</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:#f0fdf4;">
                                <i class="bi bi-wallet2 text-success fs-3"></i>
                                <div>
                                    <div class="text-muted small">Available to Withdraw</div>
                                    <h3 class="fw-bold text-success mb-0">${{ number_format($walletBalance, 2) }}</h3>
                                </div>
                            </div>
                            <form action="{{ route('withdraw.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Withdrawal Amount (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold text-primary">$</span>
                                        <input type="number" step="0.01" min="10" name="amount" class="form-control fw-bold" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Withdrawal Method</label>
                                    <select name="withdrawal_method" class="form-select">
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="wire_transfer">Wire Transfer</option>
                                        <option value="crypto">Cryptocurrency</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark small">Account / Wallet Details</label>
                                    <textarea name="account_details" class="form-control" rows="2" placeholder="Bank name, account number, account name..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Submit Withdrawal Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BROWSE PROPERTIES TAB -->
            <div x-show="activeTab === 'marketplace'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Browse Properties</h4>
                    <p class="text-muted mb-0 small">Explore all available real estate investment opportunities.</p>
                </div>
                <div class="row g-4">
                    @foreach($properties as $prop)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden">
                                <img src="{{ $prop->image_url ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800&auto=format&fit=crop' }}" height="180" style="object-fit:cover; width:100%;">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark mb-1">{{ $prop->title }}</h6>
                                    <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $prop->location }}</p>
                                    <div class="d-flex justify-content-between small fw-bold mb-3">
                                        <span>Share Price: ${{ number_format($prop->price_per_share, 2) }}</span>
                                        <span class="text-success">{{ $prop->roi_percentage }}% ROI</span>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 mt-1" style="background:#2563eb;" @click="openBuyModalById({{ $prop->id }})">
                                        <i class="bi bi-lightning-charge me-1"></i> Invest Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TRANSACTIONS TAB -->
            <div x-show="activeTab === 'transactions'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Transaction History</h4>
                    <p class="text-muted mb-0 small">Complete ledger of all your account activity.</p>
                </div>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr><th>Reference</th><th>Type</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $txn->reference }}</td>
                                        <td class="text-capitalize">{{ str_replace('_', ' ', $txn->type) }}</td>
                                        <td class="fw-bold">${{ number_format($txn->amount, 2) }}</td>
                                        <td><span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">Completed</span></td>
                                        <td class="text-muted small">{{ $txn->created_at ? $txn->created_at->format('M d, Y') : '' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No transactions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- NOTIFICATIONS TAB -->
            <div x-show="activeTab === 'notifications'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Notifications</h4>
                    <p class="text-muted mb-0 small">Important updates about your finance requests and investments.</p>
                </div>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    @php $awaitingDeps = $deposits->where('status', 'awaiting_payment'); @endphp
                    @forelse($awaitingDeps as $dep)
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-2" style="background:#fffbeb; border:1px solid #fde68a;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:36px; height:36px; background:#fef3c7;">
                                <i class="bi bi-bell-fill" style="color:#d97706;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark small mb-1">Payment Instructions Ready &mdash; {{ $dep->deposit_code }}</div>
                                <div class="text-muted" style="font-size:0.8rem;">Your request for <strong>{{ $dep->currency ?? 'PHP' }} {{ number_format($dep->amount, 2) }}</strong> has been approved. Complete payment before it expires.</div>
                                <button class="btn btn-sm btn-primary fw-bold mt-2 rounded-pill px-3" style="background:#2563eb; font-size:0.8rem;" @click="showInstructionsModal({{ json_encode($dep) }})">
                                    <i class="bi bi-wallet2 me-1"></i> View &amp; Pay
                                </button>
                            </div>
                            <small class="text-muted flex-shrink-0" style="font-size:0.75rem;">{{ $dep->created_at?->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-semibold">No new notifications</div>
                            <small>You're all caught up!</small>
                        </div>
                    @endforelse
                    @foreach($deposits->where('status', 'completed') as $dep)
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-2" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:36px; height:36px; background:#dcfce7;">
                                <i class="bi bi-check-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark small mb-1">Deposit Confirmed &mdash; {{ $dep->deposit_code }}</div>
                                <div class="text-muted" style="font-size:0.8rem;"><strong>{{ $dep->currency ?? 'PHP' }} {{ number_format($dep->amount, 2) }}</strong> credited to your wallet.</div>
                            </div>
                            <small class="text-muted flex-shrink-0" style="font-size:0.75rem;">{{ $dep->updated_at?->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- REFERRALS TAB -->
            <div x-show="activeTab === 'referrals'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Referrals</h4>
                    <p class="text-muted mb-0 small">Invite friends to invest and earn affiliate commissions.</p>
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
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 text-center">
                            <i class="bi bi-cash-coin fs-1 text-primary mb-2"></i>
                            <div class="text-muted small mb-1">Total Affiliate Earnings</div>
                            <h2 class="fw-bold text-success mb-0">${{ number_format($affiliateEarnings, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROFILE & KYC TAB -->
            <div x-show="activeTab === 'profile_kyc'" x-transition>
                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Profile &amp; KYC</h4>
                    <p class="text-muted mb-0 small">Manage your personal information and identity verification.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-circle text-primary me-2"></i>Personal Information</h6>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 text-white flex-shrink-0" style="width:60px; height:60px; background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                                    {{ strtoupper(substr($user->name ?? 'IN', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name ?? 'Investor Name' }}</div>
                                    <div class="text-muted small">{{ $user->email ?? 'investor@email.com' }}</div>
                                    <span class="badge rounded-pill mt-1" style="background:#eff6ff; color:#2563eb; font-size:0.72rem;">Account ID: {{ $user->account_id ?? 'RDR-000000' }}</span>
                                </div>
                            </div>
                            <div class="row g-2 small">
                                <div class="col-6"><div class="p-2 rounded-3" style="background:#f8fafc;"><div class="text-muted">Wallet Balance</div><div class="fw-bold text-success">${{ number_format($walletBalance, 2) }}</div></div></div>
                                <div class="col-6"><div class="p-2 rounded-3" style="background:#f8fafc;"><div class="text-muted">Active Investments</div><div class="fw-bold text-dark">{{ $activeProjectsCount }}</div></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i>KYC Verification</h6>
                            <div class="p-3 rounded-3 mb-3 d-flex align-items-center gap-3" style="background:#fffbeb; border:1px solid #fde68a;">
                                <i class="bi bi-exclamation-triangle-fill" style="color:#d97706; font-size:1.3rem;"></i>
                                <div><div class="fw-bold small" style="color:#92400e;">KYC Pending</div><div class="text-muted" style="font-size:0.78rem;">Identity verification required to unlock higher deposit limits.</div></div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small fw-semibold mb-1"><span>Profile Completion</span><span>60%</span></div>
                                <div class="progress rounded-pill" style="height:8px;"><div class="progress-bar bg-primary" style="width:60%;"></div></div>
                            </div>
                            <div class="list-group list-group-flush small">
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between"><span><i class="bi bi-check-circle-fill text-success me-2"></i>Email Verified</span><span class="badge bg-success bg-opacity-10 text-success fw-bold">Done</span></div>
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between"><span><i class="bi bi-check-circle-fill text-success me-2"></i>Account Created</span><span class="badge bg-success bg-opacity-10 text-success fw-bold">Done</span></div>
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between"><span><i class="bi bi-clock text-warning me-2"></i>ID Document Upload</span><span class="badge bg-warning bg-opacity-15 text-warning fw-bold">Pending</span></div>
                                <div class="list-group-item px-0 d-flex align-items-center justify-content-between"><span><i class="bi bi-clock text-warning me-2"></i>Selfie Verification</span><span class="badge bg-warning bg-opacity-15 text-warning fw-bold">Pending</span></div>
                            </div>
                            <button class="btn btn-primary fw-bold w-100 py-2 rounded-3 mt-3" style="background:#2563eb;"><i class="bi bi-upload me-1"></i> Submit KYC Documents</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

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
@if(session('submitted_request_id'))
<div class="custom-modal-backdrop">
    <div class="custom-modal-card p-4 text-center">
        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Request Submitted!</h4>
        <p class="text-muted small mb-3">Your finance request has been sent successfully. You will be notified once our finance team provides the payment details.</p>
        
        <div class="p-3 bg-light rounded-3 mb-4 border">
            <span class="text-muted small d-block mb-1">Request ID</span>
            <h5 class="fw-bold text-primary mb-0">{{ session('submitted_request_id') }}</h5>
        </div>

        <button type="button" class="btn btn-primary w-100 fw-bold py-2 rounded-3" style="background:#2563eb;" onclick="window.location.href='{{ route('dashboard') }}'">
            View My Requests
        </button>
    </div>
</div>
@endif

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
            <button type="button" class="btn-close" @click="selectedDepInstruction = null"></button>
        </div>

        <div class="mb-3">
            <h3 class="fw-bold text-dark mb-1" x-text="(selectedDepInstruction?.currency || 'PHP') + ' ' + (selectedDepInstruction?.amount ? parseFloat(selectedDepInstruction?.amount).toFixed(2) : '4,990.00')"></h3>
            <small class="text-muted">Request ID: <strong class="text-dark" x-text="selectedDepInstruction?.deposit_code">FR-250520-0001</strong></small>
        </div>

        <!-- Timer Box (From Image Step 5) -->
        <div class="p-3 rounded-3 mb-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 d-flex justify-content-between align-items-center">
            <span class="small fw-bold text-primary">Complete payment within:</span>
            <span class="fs-5 fw-bold text-primary"><i class="bi bi-clock me-1"></i> 19:57</span>
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
                <li>Please send the exact amount.</li>
                <li>Do not include any remarks.</li>
                <li>Upload your payment receipt before the timer expires.</li>
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

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 mb-2" style="background:#2563eb;">
                Submit Evidence
            </button>
            <button type="button" class="btn btn-link w-100 text-muted small text-decoration-none" @click="selectedDepInstruction = null">
                Cancel Request
            </button>
        </form>
    </div>
</div>

<!-- Buy Shares Modal -->
<div x-show="selectedProperty" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width:500px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold me-2">Property Investment</span>
                <h5 class="fw-bold text-dark mb-0 mt-1" x-text="selectedProperty?.title"></h5>
            </div>
            <button type="button" class="btn-close" @click="selectedProperty = null"></button>
        </div>
        <div class="p-3 rounded-3 bg-light border mb-3">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Location</span><strong class="text-dark" x-text="selectedProperty?.location"></strong></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Price Per Share</span><strong class="text-dark" x-text="'$' + parseFloat(selectedProperty?.price_per_share || 0).toFixed(2)"></strong></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">ROI</span><strong class="text-success" x-text="(selectedProperty?.roi_percentage || 0) + '%'"></strong></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Available Shares</span><strong class="text-dark" x-text="selectedProperty?.available_shares"></strong></div>
        </div>
        <form action="{{ route('buy-shares.store') }}" method="POST">
            @csrf
            <input type="hidden" name="property_id" :value="selectedProperty?.id">
            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">Number of Shares to Buy</label>
                <input type="number" name="shares" min="1" class="form-control fw-bold" placeholder="1" x-model="buySharesQty" @input="buyTotal = buySharesQty * (selectedProperty?.price_per_share || 0)" required>
            </div>
            <div class="p-3 rounded-3 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe;">
                <div class="d-flex justify-content-between small fw-bold">
                    <span class="text-muted">Total Cost</span>
                    <span class="text-primary fs-5" x-text="'$' + parseFloat(buyTotal || 0).toFixed(2)">$0.00</span>
                </div>
                <div class="text-muted mt-1" style="font-size:0.78rem;">Your wallet: <strong class="text-success">${{ number_format($walletBalance, 2) }}</strong></div>
            </div>
            <button type="submit" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;">
                <i class="bi bi-lightning-charge me-1"></i> Confirm Purchase
            </button>
        </form>
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

<!-- Alpine JS Dashboard Engine -->
<script>
    function userDashboardEngine() {
        return {
            activeTab: 'overview',
            requestFilter: 'all',
            showFinanceModal: false,
            financeMethod: 'GCash',
            selectedDepInstruction: null,
            evidenceFileName: '',
            openReceiveModal: false,
            selectedProperty: null,
            buySharesQty: 1,
            buyTotal: 0,
            propertiesList: @json($properties),

            openFinanceForm(type) {
                this.showFinanceModal = true;
            },

            openBuyModalById(id) {
                const prop = this.propertiesList.find(p => p.id == id);
                if (prop) {
                    this.selectedProperty = prop;
                    this.buySharesQty = 1;
                    this.buyTotal = parseFloat(prop.price_per_share || 0);
                }
            },

            showInstructionsModal(dep) {
                this.selectedDepInstruction = dep;
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
                const text = `Radiant Dream Realty Credentials:\nAccount ID: {{ $user->account_id ?? "RDR-884920" }}\nEmail: {{ $user->email ?? "investor@radiantrealty.com" }}`;
                if (navigator.share) {
                    navigator.share({ title: 'Receive Funds', text: text }).catch(() => {});
                } else {
                    navigator.clipboard.writeText(text);
                    alert('Credentials copied to clipboard!');
                }
            }
        }
    }
</script>
@endsection
