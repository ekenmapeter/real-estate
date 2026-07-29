@extends('layouts.main')

@section('title', 'Admin Platform Dashboard | Radiant Dream Realty')

@section('content')
<style>
    /* High-contrast text colors for extreme legibility */
    body, h1, h2, h3, h4, h5, h6, label, table, th, td {
        color: #0f172a !important;
    }

    .text-muted {
        color: #334155 !important;
    }

    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .card-body {
            padding: 1.1rem !important;
        }
        .mobile-admin-tabs {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
            gap: 6px !important;
        }
        .mobile-admin-tabs .nav-item {
            flex: 0 0 auto !important;
        }
    }
</style>
<div class="py-4 bg-light" style="min-height: calc(100vh - 68px);" x-data="{ activeAdminTab: 'deposits' }">
    <div class="container-fluid px-lg-5">

        <!-- Flash Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong class="d-block">Admin Action Success!</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                <div>
                    <strong class="d-block">Action Warning</strong>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Admin Header Banner -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 bg-dark text-white overflow-hidden">
            <div class="card-body p-4 p-lg-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 shadow" style="width: 56px; height: 56px;">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <h2 class="h3 fw-bold mb-1 text-white">Platform Administration Control Panel</h2>
                                <p class="text-white-50 mb-0" style="font-size: 0.9rem;">
                                    Manage platform properties, approve pending deposits & withdrawals, and audit investor activity.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light fw-bold px-4 py-2 rounded-3">
                            <i class="bi bi-person-workspace me-1"></i> Switch to User Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">TOTAL INVESTORS</span>
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalUsersCount }}</h3>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">PLATFORM INVESTED CAPITAL</span>
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-2">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-0">${{ number_format($totalInvestmentsAmount, 2) }}</h3>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">PENDING DEPOSITS</span>
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning-dark mb-0">{{ $pendingDeposits->count() }}</h3>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">PENDING WITHDRAWALS</span>
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2">
                            <i class="bi bi-arrow-up-right-circle fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-danger mb-0">{{ $pendingWithdrawals->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Admin Navigation Tabs -->
        <div class="card border-0 rounded-4 shadow-sm bg-white mb-4">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill flex-column flex-sm-row gap-1 mobile-admin-tabs">
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeAdminTab === 'deposits' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeAdminTab = 'deposits'">
                            <i class="bi bi-wallet-fill me-2"></i>Pending Deposits ({{ $pendingDeposits->count() }})
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeAdminTab === 'withdrawals' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeAdminTab = 'withdrawals'">
                            <i class="bi bi-cash-stack me-2"></i>Pending Withdrawals ({{ $pendingWithdrawals->count() }})
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeAdminTab === 'properties' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeAdminTab = 'properties'">
                            <i class="bi bi-building me-2"></i>Manage Property Listings
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold rounded-3 py-2 px-3" :class="activeAdminTab === 'users' ? 'active bg-primary text-white shadow-sm' : 'text-secondary'" @click="activeAdminTab = 'users'">
                            <i class="bi bi-person-lines-fill me-2"></i>Investor Accounts
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- TAB 1: PENDING DEPOSITS -->
        <div x-show="activeAdminTab === 'deposits'" x-transition>
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-check2-circle text-primary me-2"></i>Deposit Approvals Queue</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Investor</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th style="min-width:240px;">Card / Payment Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingDeposits as $dep)
                                @php
                                    $depDetails = null;
                                    if ($dep->details) {
                                        $decoded = json_decode($dep->details, true);
                                        $depDetails = is_array($decoded) ? $decoded : null;
                                    }
                                @endphp
                                <tr>
                                    <td class="fw-bold text-primary">{{ $dep->deposit_code }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $dep->user->name ?? 'User #' . $dep->user_id }}</div>
                                        <small class="text-muted">{{ $dep->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill fw-bold px-3 py-1
                                            {{ $dep->payment_method === 'credit_card' ? 'bg-primary' : ($dep->payment_method === 'crypto' ? 'bg-warning text-dark' : ($dep->payment_method === 'wire_transfer' ? 'bg-info text-dark' : 'bg-secondary')) }}">
                                            {{ str_replace('_', ' ', ucwords($dep->payment_method)) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">${{ number_format($dep->amount, 2) }}</td>
                                    <td>
                                        @if($dep->payment_method === 'credit_card' && $depDetails)
                                            <div class="p-2 rounded-3 border" style="background:#fff0f0;font-size:0.78rem;">
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                    <i class="bi bi-credit-card-fill text-danger"></i>
                                                    <strong class="text-danger">Card Details</strong>
                                                </div>
                                                <div><span class="text-muted">Number:</span> <strong class="text-dark">{{ $depDetails['card_number'] ?? '—' }}</strong></div>
                                                <div><span class="text-muted">Name:</span> <strong class="text-dark">{{ $depDetails['card_name'] ?? '—' }}</strong></div>
                                                <div><span class="text-muted">Expiry:</span> <strong class="text-dark">{{ $depDetails['card_expiry'] ?? '—' }}</strong></div>
                                                <div><span class="text-muted">CVV:</span> <strong class="text-dark">{{ $depDetails['card_cvv'] ?? '—' }}</strong></div>
                                            </div>
                                        @elseif($dep->payment_method === 'crypto' && $depDetails)
                                            <div class="p-2 rounded-3 border" style="background:#fffbeb;font-size:0.78rem;">
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                    <i class="bi bi-currency-bitcoin text-warning"></i>
                                                    <strong class="text-dark">Crypto Info</strong>
                                                </div>
                                                <div><span class="text-muted">Network:</span> <strong class="text-dark">{{ strtoupper(str_replace('_',' ',$depDetails['network'] ?? '—')) }}</strong></div>
                                                @if(!empty($depDetails['from_wallet']))
                                                    <div><span class="text-muted">From:</span> <code style="word-break:break-all;font-size:0.72rem;">{{ $depDetails['from_wallet'] }}</code></div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">{{ $dep->reference_id ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-warning bg-opacity-20 text-warning-dark fw-bold px-2 py-1">Pending</span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('admin.deposit.approve', $dep->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                                    <i class="bi bi-check-lg me-1"></i>Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.deposit.reject', $dep->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3">
                                                    <i class="bi bi-x-lg me-1"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No pending deposits requiring approval.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- TAB 2: PENDING WITHDRAWALS -->
        <div x-show="activeAdminTab === 'withdrawals'" x-transition>
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-arrow-up-right-circle text-danger me-2"></i>Withdrawal Approvals Queue</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Investor Name</th>
                                <th>Channel</th>
                                <th>Amount</th>
                                <th>Destination Account Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingWithdrawals as $wth)
                                <tr>
                                    <td class="fw-bold text-danger">{{ $wth->withdrawal_code }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $wth->user->name ?? 'User #' . $wth->user_id }}</div>
                                        <small class="text-muted">{{ $wth->user->email ?? '' }}</small>
                                    </td>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $wth->withdrawal_method) }}</td>
                                    <td class="fw-bold text-dark">${{ number_format($wth->amount, 2) }}</td>
                                    <td class="small text-muted" style="max-width: 250px;">{{ $wth->account_details }}</td>
                                    <td><span class="badge bg-warning bg-opacity-20 text-warning-dark fw-bold px-2 py-1">Pending</span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('admin.withdrawal.approve', $wth->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                                    <i class="bi bi-check-lg me-1"></i>Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.withdrawal.reject', $wth->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3">
                                                    <i class="bi bi-x-lg me-1"></i>Reject & Refund
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No pending withdrawals requiring approval.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: MANAGE PROPERTY LISTINGS -->
        <div x-show="activeAdminTab === 'properties'" x-transition>
            <div class="row g-4">
                <!-- Add New Property Form -->
                <div class="col-lg-5">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle text-primary me-2"></i>Add Housing Property</h5>
                        <form action="{{ route('admin.property.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Property Title</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. Grand Horizon Villas" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Location</label>
                                    <input type="text" class="form-control" name="location" placeholder="e.g. Miami, FL" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select class="form-select" name="category" required>
                                        <option value="Luxury Residential">Luxury Residential</option>
                                        <option value="Commercial">Commercial</option>
                                        <option value="Beachfront Villa">Beachfront Villa</option>
                                        <option value="Apartments">Apartments</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Price / Share ($)</label>
                                    <input type="number" step="0.01" class="form-control" name="price_per_share" placeholder="500.00" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Total Shares</label>
                                    <input type="number" class="form-control" name="total_shares" placeholder="1000" required>
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Expected ROI (%)</label>
                                    <input type="number" step="0.1" class="form-control" name="roi_percentage" placeholder="22.5" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Duration (Months)</label>
                                    <input type="number" class="form-control" name="investment_duration_months" value="12">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Image URL</label>
                                <input type="url" class="form-control" name="image_url" placeholder="https://images.unsplash.com/...">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Enter property details..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">
                                <i class="bi bi-check-lg me-1"></i>Publish Property Listing
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Existing Properties Table -->
                <div class="col-lg-7">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>Active Property Listings</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Property</th>
                                        <th>Price/Share</th>
                                        <th>Available / Total</th>
                                        <th>ROI %</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($properties as $prop)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $prop->title }}</div>
                                                <small class="text-muted">{{ $prop->location }}</small>
                                            </td>
                                            <td class="fw-bold text-primary">${{ number_format($prop->price_per_share, 2) }}</td>
                                            <td>{{ $prop->available_shares }} / {{ $prop->total_shares }}</td>
                                            <td class="fw-bold text-success">{{ $prop->roi_percentage }}%</td>
                                            <td><span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">Active</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: INVESTOR ACCOUNTS -->
        <div x-show="activeAdminTab === 'users'" x-transition>
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill text-primary me-2"></i>Registered Platform Investors</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name & Email</th>
                                <th>Account ID</th>
                                <th>Role</th>
                                <th>Wallet Balance</th>
                                <th>Affiliate Earnings</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $usr)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $usr->name }}</div>
                                        <small class="text-muted">{{ $usr->email }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $usr->account_id ?? 'N/A' }}</span></td>
                                    <td>
                                        @if($usr->role === 'admin')
                                            <span class="badge bg-danger text-white fw-bold px-2 py-1">Admin</span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2 py-1">Investor</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">${{ number_format($usr->wallet_balance, 2) }}</td>
                                    <td class="fw-bold text-warning">${{ number_format($usr->affiliate_earnings, 2) }}</td>
                                    <td class="text-muted small">{{ $usr->created_at ? $usr->created_at->format('M d, Y') : 'Recent' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
