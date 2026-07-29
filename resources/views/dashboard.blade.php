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
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Welcome back, {{ $user->name ?? 'Investor' }} 👋</h4>
                        <p class="text-muted mb-0" style="font-size: 0.88rem;">Manage your real estate portfolio, request funds, and complete payments seamlessly.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm" style="background:#2563eb;" @click="openFinanceForm('deposit')">
                            <i class="bi bi-plus-circle me-1"></i> Deposit Funds
                        </button>
                        <button class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3" @click="openFinanceForm('withdrawal')">
                            Withdraw Funds
                        </button>
                    </div>
                </div>

                <!-- Balance Summary Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6 col-md-12">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="text-muted small fw-semibold">Total Balance</span>
                                    <h2 class="display-6 fw-bold text-dark mb-0 mt-1">${{ number_format(($walletBalance + $totalInvested), 2) }}</h2>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">+12.5%</span>
                            </div>
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                                <span>Available Wallet Balance:</span>
                                <strong class="text-dark fs-6">${{ number_format($walletBalance, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Finance Team Callout Widget (From Image) -->
                    <div class="col-lg-6 col-md-12">
                        <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 1px solid #bfdbfe !important;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: #2563eb !important;">
                                        <i class="bi bi-headset fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Finance Team Support</h6>
                                        <p class="text-secondary mb-0 small">Need help with deposits or withdrawals in your local currency?</p>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary fw-bold w-100 py-2 rounded-3 shadow-sm mt-auto" style="background: #2563eb;" @click="openFinanceForm('deposit')">
                                Open Finance Request
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions List (Step 1 From Image) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Recent Transactions</h6>
                        <button class="btn btn-link text-primary p-0 fw-bold small text-decoration-none" @click="activeTab = 'transactions'">View all</button>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($transactions->take(4) as $txn)
                            <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; {{ in_array($txn->type, ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']) ? 'background: #dcfce7; color: #16a34a;' : 'background: #fee2e2; color: #dc2626;' }}">
                                        <i class="bi {{ in_array($txn->type, ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']) ? 'bi-arrow-down-left' : 'bi-arrow-up-right' }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ ucwords(str_replace('_', ' ', $txn->type)) }}</h6>
                                        <small class="text-muted">{{ $txn->created_at ? $txn->created_at->format('M d, Y') : 'Recent' }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold {{ in_array($txn->type, ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']) ? 'text-success' : 'text-danger' }}">
                                    {{ in_array($txn->type, ['deposit', 'receive_funds', 'affiliate_earning', 'roi_payout']) ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                                </span>
                            </div>
                        @empty
                            <div class="py-3 text-center text-muted small">No recent transactions recorded.</div>
                        @endforelse
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

            <!-- OTHER TABS (Wallet, Portfolio, Marketplace, Transactions) -->
            <div x-show="activeTab === 'wallet'" x-transition>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-wallet2 text-primary me-2"></i>My Wallet Balance</h5>
                    <h2 class="fw-bold text-success mb-2">${{ number_format($walletBalance, 2) }}</h2>
                    <p class="text-muted small">Your funds are ready for real estate property share investments or instant peer transfers.</p>
                </div>
            </div>

            <div x-show="activeTab === 'marketplace'" x-transition>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>Available Property Share Marketplace</h5>
                    <div class="row g-4">
                        @foreach($properties as $prop)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 rounded-4 shadow-sm bg-light overflow-hidden">
                                    <img src="{{ $prop->image_url ?? 'https://radiantdreamrealty.com/frontend/images/home/house-1.jpg' }}" height="180" style="object-fit:cover;">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark mb-1">{{ $prop->title }}</h6>
                                        <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $prop->location }}</p>
                                        <div class="d-flex justify-content-between small fw-bold mb-3">
                                            <span>Share Price: ${{ number_format($prop->price_per_share, 2) }}</span>
                                            <span class="text-success">{{ $prop->roi_percentage }}% ROI</span>
                                        </div>
                                        <button class="btn btn-primary btn-sm w-100 fw-bold" style="background:#2563eb;" @click="openBuyModal({{ json_encode($prop) }})">Buy Shares</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'transactions'" x-transition>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Transaction Ledger</h5>
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
                                    <tr><td colspan="5" class="text-center text-muted">No transactions.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- STEP 2 MODAL: FINANCE REQUEST FORM (From Image) -->
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

            openFinanceForm(type) {
                this.showFinanceModal = true;
            },

            showInstructionsModal(dep) {
                this.selectedDepInstruction = dep;
            },

            copyText(text) {
                navigator.clipboard.writeText(text);
                alert('Copied to clipboard: ' + text);
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
