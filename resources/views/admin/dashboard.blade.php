@extends('layouts.main')

@section('title', 'Admin Panel - Finance Requests | Radiant Dream Realty')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    body {
        background-color: #f8fafc !important;
    }

    .sidebar-admin {
        background-color: #0b1329 !important;
        color: #ffffff;
        min-height: calc(100vh - 70px);
    }

    .sidebar-admin .nav-link-admin {
        color: #94a3b8;
        padding: 12px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }

    .sidebar-admin .nav-link-admin:hover,
    .sidebar-admin .nav-link-admin.active {
        color: #ffffff !important;
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
    }

    .sidebar-admin .nav-link-admin i {
        font-size: 1.15rem;
    }

    /* Glass Modal Backdrop */
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
        max-width: 620px;
        width: 100%;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
</style>

<div x-data="adminDashboardEngine()">
<div class="container-fluid px-0">
    <div class="row g-0">

        <!-- Left Admin Dark Navy Sidebar -->
        <div class="col-lg-2 col-md-3 sidebar-admin p-3 d-none d-md-block">
            <!-- Admin Profile Widget at top of sidebar (From Image) -->
            <div class="p-3 mb-4 rounded-3 text-white" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-purple text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;">
                        AU
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.95rem;">Admin User</h6>
                        <small class="badge bg-purple bg-opacity-20 text-purple-300 fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; color: #c4b5fd; background: rgba(124, 58, 237, 0.2);">Super Admin</small>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="nav flex-column">
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'finance_requests' }" @click.prevent="activeAdminTab = 'finance_requests'">
                    <i class="bi bi-wallet2"></i> Finance Requests
                    @if($pendingDeposits->count() > 0)
                        <span class="badge bg-warning text-dark ms-auto rounded-pill">{{ $pendingDeposits->count() }}</span>
                    @endif
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'properties' }" @click.prevent="activeAdminTab = 'properties'">
                    <i class="bi bi-building"></i> Investments / Properties
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'projects' }" @click.prevent="activeAdminTab = 'projects'">
                    <i class="bi bi-rocket-takeoff"></i> Projects
                    @if($totalProjectsCount > 0)
                        <span class="badge bg-warning text-dark ms-auto rounded-pill">{{ $totalProjectsCount }}</span>
                    @endif
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'users' }" @click.prevent="activeAdminTab = 'users'">
                    <i class="bi bi-people-fill"></i> Users
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'kyc_reviews' }" @click.prevent="activeAdminTab = 'kyc_reviews'">
                    <i class="bi bi-shield-check"></i> KYC Reviews
                    @if($kycPendingUsers->count() > 0)
                        <span class="badge bg-warning text-dark ms-auto rounded-pill">{{ $kycPendingUsers->count() }}</span>
                    @endif
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'withdrawals' }" @click.prevent="activeAdminTab = 'withdrawals'">
                    <i class="bi bi-arrow-up-right-circle"></i> Withdrawals
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'cards' }" @click.prevent="activeAdminTab = 'cards'">
                    <i class="bi bi-credit-card-2-front"></i> Crypto Cards
                    @if($cards->where('status', 'pending')->count() > 0)
                        <span class="badge bg-warning text-dark ms-auto rounded-pill">{{ $cards->where('status', 'pending')->count() }}</span>
                    @endif
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'referrals' }" @click.prevent="activeAdminTab = 'referrals'">
                    <i class="bi bi-gift-fill"></i> Referral Bonuses
                </a>
                <a href="{{ route('dashboard') }}" class="nav-link-admin text-info">
                    <i class="bi bi-person-workspace"></i> User View Dashboard
                </a>
                <hr class="border-secondary opacity-25 my-3">
                <a href="#" class="nav-link-admin">
                    <i class="bi bi-gear-fill"></i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="nav-link-admin w-100 border-0 bg-transparent text-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-10 col-md-9 p-3 p-lg-4" style="min-height: calc(100vh - 70px); background: #f8fafc;">

            <!-- Flash Notifications -->
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
                        <strong class="d-block">Notice</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- ADMIN PANEL - FINANCE REQUESTS (From Image) -->
            <div x-show="activeAdminTab === 'finance_requests'" x-transition>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-uppercase fw-bold text-purple small" style="color: #7c3aed;">ADMIN PANEL – FINANCE REQUESTS</span>
                        <h3 class="fw-bold text-dark mb-0">Finance Requests</h3>
                    </div>
                </div>

                <!-- Filter Controls (From Image) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" x-model="filterType">
                                <option value="all">All Types</option>
                                <option value="deposit">Deposit</option>
                                <option value="withdrawal">Withdrawal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" x-model="filterStatus">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending / Under Review</option>
                                <option value="awaiting_payment">Approved (Instructions Sent)</option>
                                <option value="evidence_submitted">Evidence Submitted</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm" value="May 20, 2025" readonly>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search request ID, user or email..." x-model="searchQuery">
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-secondary btn-sm w-100 fw-bold" @click="alert('Exporting finance requests report...')">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Finance Requests Table (From Image) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr class="text-uppercase small text-muted">
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Country</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allDeposits as $dep)
                                    <tr>
                                        <td class="fw-bold text-primary" style="font-size:0.88rem;">{{ $dep->deposit_code }}</td>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size:0.9rem;">{{ $dep->user->name ?? 'John Smith' }}</div>
                                            <small class="text-muted">{{ $dep->user->email ?? 'johnsmith@gmail.com' }}</small>
                                        </td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">Deposit</span></td>
                                        <td class="small">{{ $dep->country ?? 'Philippines' }}</td>
                                        <td class="fw-bold small">USD</td>
                                        <td class="fw-bold text-dark">${{ number_format($dep->amount, 2) }}</td>
                                        <td class="small fw-semibold">{{ ucwords(str_replace('_', ' ', $dep->payment_method)) }}</td>
                                        <td>
                                            @if($dep->status === 'completed')
                                                <span class="badge bg-success bg-opacity-15 text-success fw-bold px-3 py-1 rounded-pill">Completed</span>
                                            @elseif($dep->status === 'awaiting_payment')
                                                <span class="badge bg-info text-white fw-bold px-3 py-1 rounded-pill">Instructions Sent</span>
                                            @elseif($dep->status === 'evidence_submitted')
                                                <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">Evidence Uploaded</span>
                                            @elseif($dep->status === 'rejected')
                                                <span class="badge bg-danger bg-opacity-15 text-danger fw-bold px-3 py-1 rounded-pill">Rejected</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-20 text-warning-dark fw-bold px-3 py-1 rounded-pill">Under Review</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $dep->created_at ? $dep->created_at->format('M d, Y') : 'May 20, 2025' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <!-- Action 1: Provide Payment Instructions -->
                                                <button class="btn btn-sm btn-outline-primary fw-bold" @click="openInstructionModal({{ json_encode($dep) }})">
                                                    Instructions
                                                </button>
                                                <!-- Action 2: Review Evidence & Approve -->
                                                <button class="btn btn-sm btn-purple text-white fw-bold" style="background:#7c3aed;" @click="openReviewModal({{ json_encode($dep) }})">
                                                    Review
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">No finance requests pending review.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- OTHER ADMIN TABS: Properties Management -->
            <div x-show="activeAdminTab === 'properties'" x-transition>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Publish New Housing Property Listing</h5>
                    <form action="{{ route('admin.property.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Property Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Aura Grand Penthouse" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Location</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Manhattan, New York" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-dark small">Full Purchase Price ($)</label>
                                <input type="number" step="0.01" min="1" name="price" class="form-control" placeholder="150000.00">
                                <small class="text-muted">One-time purchase price for direct buyers.</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-dark small">Price Per Share ($)</label>
                                <input type="number" step="0.01" name="price_per_share" class="form-control" placeholder="500.00" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-dark small">Total Shares</label>
                                <input type="number" name="total_shares" class="form-control" placeholder="1000" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-dark small">Target ROI (%)</label>
                                <input type="number" step="0.1" name="roi_percentage" class="form-control" placeholder="24.5" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Image URL</label>
                                <input type="url" name="image_url" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Gallery Images (Upload)</label>
                                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">You can upload multiple images — they will show in a carousel on the property page.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark small">Gallery Image URLs (one per line)</label>
                                <textarea name="gallery_urls" class="form-control" rows="2" placeholder="https://...&#10;https://..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3" style="background:#2563eb;">Publish Listing</button>
                    </form>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4">
                    <div class="px-4 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="bi bi-building me-2" style="color:#2563eb;"></i>All Property Listings</h6>
                        <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem;">{{ $properties->count() }} listings</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th class="px-4 py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">PROPERTY</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">PRICE</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">SHARE PRICE</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">ROI %</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">DURATION</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">SHARES LEFT</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">UPDATE SETTINGS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($properties as $prop)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td class="px-4 py-3">
                                            <div class="fw-bold small text-dark">{{ $prop->title }}</div>
                                            <small class="text-muted" style="font-size:0.7rem;">{{ $prop->location }}</small>
                                            <span class="badge fw-semibold rounded-pill ms-1" style="background:{{ $prop->status === 'active' ? '#f0fdf4' : '#f1f5f9' }}; color:{{ $prop->status === 'active' ? '#16a34a' : '#64748b' }}; font-size:0.62rem;">{{ $prop->status }}</span>
                                        </td>
                                        <td class="py-3"><span class="small fw-semibold text-dark">${{ number_format($prop->purchasePrice(), 2) }}</span></td>
                                        <td class="py-3"><span class="small fw-semibold text-dark">${{ number_format($prop->price_per_share, 2) }}</span></td>
                                        <td class="py-3"><span class="fw-bold" style="color:#7c3aed;">{{ $prop->roi_percentage }}%</span></td>
                                        <td class="py-3"><span class="small text-muted">{{ $prop->investment_duration_months }} months</span></td>
                                        <td class="py-3"><span class="small text-muted">{{ $prop->available_shares }} / {{ $prop->total_shares }}</span></td>
                                        <td class="py-3">
                                            <form action="{{ route('admin.property.update', $prop->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="number" step="0.1" min="0" name="roi_percentage" class="form-control form-control-sm" style="width:70px; font-size:0.75rem;" value="{{ $prop->roi_percentage }}" title="ROI %">
                                                <input type="number" step="0.01" min="1" name="price_per_share" class="form-control form-control-sm" style="width:80px; font-size:0.75rem;" value="{{ $prop->price_per_share }}" title="Share price">
                                                <input type="number" min="1" name="investment_duration_months" class="form-control form-control-sm" style="width:60px; font-size:0.75rem;" value="{{ $prop->investment_duration_months }}" title="Duration (months)">
                                                <button type="submit" class="btn btn-sm fw-bold rounded-pill px-2" style="background:#2563eb; color:#fff; border:none; font-size:0.7rem;" title="Save settings"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <small class="text-muted d-block mt-1" style="font-size:0.62rem;">ROI % &middot; Price &middot; Months</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5" style="color:#94a3b8;"><i class="bi bi-building fs-2 d-block mb-2 opacity-25"></i><span style="font-size:0.9rem;">No properties yet.</span></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PROJECTS TAB -->
            <div x-show="activeAdminTab === 'projects'" x-transition>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-uppercase fw-bold text-purple small" style="color: #7c3aed;">INVESTMENT PROJECTS</span>
                        <h3 class="fw-bold text-dark mb-0">Projects</h3>
                        <p class="mb-0" style="font-size:0.9rem; font-weight:500; color:#475569;">Create and manage flexible-amount investment projects with target funding and expected returns.</p>
                    </div>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Publish New Investment Project</h5>
                    <form action="{{ route('admin.project.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Project Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Horizon Towers Development" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Location</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Makati City, Philippines" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Category</label>
                                <select name="category" class="form-select">
                                    @foreach(['Residential', 'Commercial', 'Luxury', 'Vacation', 'Land', 'Multi-Family'] as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Target Amount ($)</label>
                                <input type="number" step="0.01" min="1" name="target_amount" class="form-control" placeholder="100000.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Minimum Investment ($)</label>
                                <input type="number" step="0.01" min="1" name="minimum_investment" class="form-control" placeholder="100.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Expected Return (%)</label>
                                <input type="number" step="0.1" min="0" name="expected_return_percentage" class="form-control" placeholder="24.5" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Rating (0 - 5)</label>
                                <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" placeholder="4.5">
                                <small class="text-muted">Shown as stars on project listings.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Duration (Months)</label>
                                <input type="number" min="1" name="investment_duration_months" class="form-control" placeholder="12" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Image URL</label>
                                <input type="url" name="image_url" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Project Document (PDF/DOC)</label>
                                <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Uploaded documents are downloadable by investors from the project page.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Gallery Images (Upload)</label>
                                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">You can upload multiple images — they will show in a carousel on the project page.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark small">Gallery Image URLs (one per line)</label>
                                <textarea name="gallery_urls" class="form-control" rows="2" placeholder="https://...&#10;https://..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark small">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Describe the project, location benefits, and return expectations..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3" style="background:#2563eb;">
                            <i class="bi bi-plus-circle me-1"></i> Publish Project
                        </button>
                    </form>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
                    <div class="px-4 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="bi bi-rocket-takeoff me-2" style="color:#7c3aed;"></i>All Projects</h6>
                        <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem;">{{ $projects->count() }} projects</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th class="px-4 py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">PROJECT</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">TARGET</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">MIN INVEST</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">RETURN %</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">RATING</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">DURATION</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">INVESTORS</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">STATUS</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $proj)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td class="px-4 py-3">
                                            <div class="fw-bold small text-dark">{{ $proj->title }}</div>
                                            <small class="text-muted" style="font-size:0.7rem;">{{ $proj->location }} &middot; {{ $proj->category }}</small>
                                        </td>
                                        <td class="py-3"><span class="small fw-semibold text-dark">${{ number_format($proj->target_amount, 2) }}</span></td>
                                        <td class="py-3"><span class="small text-muted">${{ number_format($proj->minimum_investment, 2) }}</span></td>
                                        <td class="py-3"><span class="fw-bold" style="color:#7c3aed;">{{ $proj->expected_return_percentage }}%</span></td>
                                        <td class="py-3">
                                            @if($proj->rating > 0)
                                                <span class="fw-bold" style="color:#f59e0b;"><i class="bi bi-star-fill me-1"></i>{{ number_format((float) $proj->rating, 1) }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3"><span class="small text-muted">{{ $proj->investment_duration_months }} mos</span></td>
                                        <td class="py-3"><span class="small text-muted">{{ $proj->investments_count }} investor(s)</span></td>
                                        <td class="py-3">
                                            <span class="badge fw-semibold rounded-pill" style="background:{{ $proj->status === 'active' ? '#f0fdf4' : '#f1f5f9' }}; color:{{ $proj->status === 'active' ? '#16a34a' : '#64748b' }}; font-size:0.62rem;">{{ $proj->status }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.project.edit', $proj->id) }}" class="btn btn-sm fw-bold rounded-pill px-3 text-white" style="background:#7c3aed; border:none; font-size:0.7rem;">
                                                    <i class="bi bi-pencil me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.project.delete', $proj->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3 text-danger" style="background:#fef2f2; border:1px solid #fecaca; font-size:0.7rem;" onclick="return confirm('Delete project &quot;{{ $proj->title }}&quot;? This also removes all investments and saved records.')">
                                                        <i class="bi bi-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center py-5" style="color:#94a3b8;"><i class="bi bi-rocket-takeoff fs-2 d-block mb-2 opacity-25"></i><span style="font-size:0.9rem;">No projects yet. Publish your first investment project above.</span></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REFERRAL BONUSES TAB -->
            <div x-show="activeAdminTab === 'referrals'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;">Referral Bonuses</h2>
                    <p class="mb-0" style="font-size:0.95rem; font-weight:500; color:#475569;">Award referral bonuses to users who have referred new investors.</p>
                </div>
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
                    <div class="px-4 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="bi bi-gift-fill me-2" style="color:#7c3aed;"></i>Users with Referrals</h6>
                        <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem;">{{ $referrers->count() }} referrers</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th class="px-4 py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">USER</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">CODE</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">REFERRALS</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">EARNINGS</th>
                                    <th class="py-3 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">AWARD BONUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrers as $refUser)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:36px; height:36px; background:linear-gradient(135deg,#7c3aed,#6d28d9); font-size:0.75rem;">
                                                    {{ strtoupper(substr($refUser->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold small text-dark">{{ $refUser->name }}</div>
                                                    <small class="text-muted" style="font-size:0.7rem;">{{ $refUser->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3"><span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f3e8ff; color:#7c3aed; font-size:0.72rem;">{{ $refUser->affiliate_code ?? '—' }}</span></td>
                                        <td class="py-3"><span class="fw-bold">{{ $refUser->referrals_count }}</span></td>
                                        <td class="py-3"><span class="fw-bold text-success">${{ number_format($refUser->affiliate_earnings ?? 0, 2) }}</span></td>
                                        <td class="py-3">
                                            <form action="{{ route('admin.referral-bonus') }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $refUser->id }}">
                                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control form-control-sm" style="width:100px; font-size:0.8rem;" placeholder="Amount" required>
                                                <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3" style="background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; border:none; font-size:0.72rem;">
                                                    <i class="bi bi-gift me-1"></i> Award
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-5" style="color:#94a3b8;"><i class="bi bi-gift fs-2 d-block mb-2 opacity-25"></i><span style="font-size:0.9rem;">No users with referrals yet.</span></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- USERS TAB -->
            <div x-show="activeAdminTab === 'users'" x-transition>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-uppercase fw-bold text-purple small" style="color: #7c3aed;">USER MANAGEMENT</span>
                        <h3 class="fw-bold text-dark mb-0">All Users</h3>
                    </div>
                    <span class="badge fw-semibold rounded-pill px-3 py-2" style="background:#f1f5f9; color:#475569;">{{ $totalUsersCount }} registered users</span>
                </div>

                @php
                    $usersKycVerified = $users->where('kyc_status', 'approved')->count();
                    $usersWalletTotal = $users->sum('wallet_balance');
                    $usersInvestedTotal = $users->sum(fn($u) => $u->investments->sum('total_amount'));
                @endphp
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:42px;height:42px;background:linear-gradient(135deg,#2563eb,#1d4ed8);font-size:1.1rem;"><i class="bi bi-people-fill"></i></div>
                                <div><small class="text-muted d-block" style="font-size:0.7rem;">TOTAL USERS</small><strong class="text-dark fs-5">{{ $totalUsersCount }}</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:42px;height:42px;background:linear-gradient(135deg,#22c55e,#16a34a);font-size:1.1rem;"><i class="bi bi-shield-check"></i></div>
                                <div><small class="text-muted d-block" style="font-size:0.7rem;">KYC VERIFIED</small><strong class="text-dark fs-5">{{ $usersKycVerified }}</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:42px;height:42px;background:linear-gradient(135deg,#f59e0b,#d97706);font-size:1.1rem;"><i class="bi bi-wallet2"></i></div>
                                <div><small class="text-muted d-block" style="font-size:0.7rem;">WALLET BALANCE</small><strong class="text-dark fs-5">${{ number_format($usersWalletTotal, 2) }}</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:42px;height:42px;background:linear-gradient(135deg,#7c3aed,#6d28d9);font-size:1.1rem;"><i class="bi bi-graph-up-arrow"></i></div>
                                <div><small class="text-muted d-block" style="font-size:0.7rem;">TOTAL INVESTED</small><strong class="text-dark fs-5">${{ number_format($usersInvestedTotal, 2) }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4 users-card">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search name, email or account ID..." x-model="usersSearch" @input="recountUsers($el)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" x-model="usersKycFilter" @change="recountUsers($el)">
                                <option value="all">All KYC Statuses</option>
                                <option value="approved">Approved</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                                <option value="none">Not Submitted</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" x-model="usersSortBy" @change="sortUsers()">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="invested_high">Highest Invested</option>
                                <option value="wallet_high">Highest Wallet</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="small text-muted fw-semibold">Showing <span x-text="usersVisible">0</span> / {{ $users->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
                    <div class="table-responsive" id="usersTableWrap">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr class="text-uppercase small text-muted">
                                    <th>User</th>
                                    <th>Account ID</th>
                                    <th>Wallet</th>
                                    <th>Invested</th>
                                    <th>Activity</th>
                                    <th>KYC</th>
                                    <th>Joined</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $userRow)
                                    <tr data-user-row data-created="{{ $userRow->created_at?->timestamp ?? 0 }}" data-invested="{{ $userRow->investments->sum('total_amount') }}" data-wallet="{{ $userRow->wallet_balance ?? 0 }}" x-data="{ user: {{ json_encode($userRow) }} }" x-show="userMatches(user)" x-transition>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:36px; height:36px; background:linear-gradient(135deg,#2563eb,#1d4ed8); font-size:0.75rem;">
                                                    {{ strtoupper(substr($userRow->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size:0.9rem;">{{ $userRow->name }}</div>
                                                    <small class="text-muted">{{ $userRow->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#eff6ff; color:#2563eb; font-size:0.7rem;">{{ $userRow->account_id ?? 'N/A' }}</span></td>
                                        <td class="fw-bold text-dark">${{ number_format($userRow->wallet_balance ?? 0, 2) }}</td>
                                        <td class="fw-bold" style="color:#7c3aed;">${{ number_format($userRow->investments->sum('total_amount'), 2) }}</td>
                                        <td>
                                            <span class="d-inline-flex align-items-center gap-2 text-muted small">
                                                <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.66rem;" title="Deposits">{{ $userRow->deposits->count() }} D</span>
                                                <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.66rem;" title="Withdrawals">{{ $userRow->withdrawals->count() }} W</span>
                                                <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f1f5f9; color:#475569; font-size:0.66rem;" title="Referrals">{{ $userRow->referrals->count() }} R</span>
                                            </span>
                                        </td>
                                        <td>
                                            @if($userRow->kyc_status === 'approved')
                                                <span class="badge bg-success bg-opacity-15 text-success fw-bold px-2 py-1 rounded-pill" style="font-size:0.68rem;">Approved</span>
                                            @elseif($userRow->kyc_status === 'pending')
                                                <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill" style="font-size:0.68rem;">Pending</span>
                                            @elseif($userRow->kyc_status === 'rejected')
                                                <span class="badge bg-danger bg-opacity-15 text-danger fw-bold px-2 py-1 rounded-pill" style="font-size:0.68rem;">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-1 rounded-pill" style="font-size:0.68rem;">Not Submitted</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $userRow->created_at?->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn btn-sm btn-outline-primary fw-bold" @click="openUserPreview(user)">
                                                    <i class="bi bi-eye me-1"></i> Preview
                                                </button>
                                                <form action="{{ route('admin.users.impersonate', $userRow->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm fw-bold text-white" style="background:#7c3aed; border:none;" onclick="return confirm('Impersonate {{ addslashes($userRow->name) }}? You will be logged in as this user.')">
                                                        <i class="bi bi-person-workspace me-1"></i> Impersonate
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5" style="color:#94a3b8;">
                                            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                                            <span style="font-size:0.9rem;">No users registered yet.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KYC REVIEWS TAB -->
            <div x-show="activeAdminTab === 'kyc_reviews'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;">KYC Reviews</h2>
                    <p class="mb-0" style="font-size:0.95rem; font-weight:500; color:#475569;">Review and approve or reject user KYC document submissions.</p>
                </div>
                @forelse($kycPendingUsers as $kycUser)
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-3">
                        <div class="p-4">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:42px; height:42px; background:linear-gradient(135deg,#2563eb,#1d4ed8); font-size:0.85rem;">
                                        {{ strtoupper(substr($kycUser->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $kycUser->name }}</div>
                                        <div class="small text-muted">{{ $kycUser->email }} &middot; {{ $kycUser->account_id ?? 'N/A' }}</div>
                                        <span class="badge fw-semibold rounded-pill mt-1" style="background:#fffbeb; color:#d97706; font-size:0.68rem;">Submitted {{ $kycUser->kyc_submitted_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted mb-1 d-block">ID Document</label>
                                    <a href="{{ asset('storage/' . $kycUser->kyc_document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold rounded-3 w-100">
                                        <i class="bi bi-eye me-1"></i> View Document
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted mb-1 d-block">Selfie</label>
                                    <a href="{{ asset('storage/' . $kycUser->kyc_selfie_path) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold rounded-3 w-100">
                                        <i class="bi bi-eye me-1"></i> View Selfie
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                <form action="{{ route('admin.kyc.approve', $kycUser->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3" style="background:#22c55e; color:#fff; border:none;">
                                        <i class="bi bi-check-lg me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $kycUser->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="text" name="reason" class="form-control form-control-sm" style="width:200px; font-size:0.8rem;" placeholder="Rejection reason" required>
                                    <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3" style="background:#ef4444; color:#fff; border:none;">
                                        <i class="bi bi-x-lg me-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center" style="color:#94a3b8;">
                        <i class="bi bi-shield-check fs-1 d-block mb-2 opacity-25"></i>
                        <span style="font-size:0.9rem;">No pending KYC reviews.</span>
                    </div>
                @endforelse
            </div>

            <!-- CRYPTO CARDS TAB -->
            <div x-show="activeAdminTab === 'cards'" x-transition>
                <div class="mb-4">
                    <h2 class="fw-bold mb-1" style="font-size:1.6rem; color:#0f172a;">Crypto Cards</h2>
                    <p class="mb-0" style="font-size:0.95rem; font-weight:500; color:#475569;">Review card applications. On approval, card details are generated and emailed to the user automatically.</p>
                </div>

                @php
                    $pendingCards = $cards->where('status', 'pending');
                    $approvedCards = $cards->where('status', 'approved');
                    $rejectedCards = $cards->where('status', 'rejected');
                @endphp

                <!-- Pending Applications -->
                <h6 class="fw-bold text-dark mb-3" style="font-size:0.95rem;"><i class="bi bi-clock-history me-2" style="color:#f59e0b;"></i>Pending Applications
                    <span class="badge bg-warning bg-opacity-20 text-warning fw-bold rounded-pill ms-1" style="color:#b45309;">{{ $pendingCards->count() }}</span>
                </h6>
                @forelse($pendingCards as $card)
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-3">
                        <div class="p-4">
                            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:42px; height:42px; background:linear-gradient(135deg,#2563eb,#1d4ed8); font-size:0.85rem;">
                                        {{ strtoupper(substr($card->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $card->user->name }}</div>
                                        <div class="small text-muted">{{ $card->user->email }} &middot; {{ $card->user->account_id ?? 'N/A' }}</div>
                                        <span class="badge fw-semibold rounded-pill mt-1" style="background:#fffbeb; color:#d97706; font-size:0.68rem;">Applied {{ $card->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <span class="badge fw-bold rounded-pill px-3 py-1.5" style="background:#fffbeb; color:#b45309;">Pending</span>
                            </div>
                            <div class="row g-2 mt-2 mb-3">
                                <div class="col-md-3">
                                    <span class="text-muted small d-block" style="font-size:0.68rem;">PHONE</span>
                                    <span class="small fw-semibold text-dark">{{ $card->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted small d-block" style="font-size:0.68rem;">LOCATION</span>
                                    <span class="small fw-semibold text-dark">{{ $card->city ? $card->city . ', ' . $card->country : ($card->country ?? 'N/A') }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted small d-block" style="font-size:0.68rem;">CARD TYPE</span>
                                    <span class="small fw-semibold text-dark">{{ ucfirst($card->card_type ?? 'virtual') }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted small d-block" style="font-size:0.68rem;">PREFERRED BRAND</span>
                                    <span class="small fw-semibold text-dark">{{ $card->card_brand ?? 'Any' }}</span>
                                </div>
                                @if($card->address)
                                <div class="col-12">
                                    <span class="text-muted small d-block" style="font-size:0.68rem;">ADDRESS</span>
                                    <span class="small fw-semibold text-dark">{{ $card->address }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                <form action="{{ route('admin.card.approve', $card->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3" style="background:#22c55e; color:#fff; border:none;" onclick="return confirm('Approve the Crypto Card for {{ $card->user->name }}? Card details will be generated and emailed instantly.')">
                                        <i class="bi bi-check-lg me-1"></i> Approve &amp; Generate Card
                                    </button>
                                </form>
                                <form action="{{ route('admin.card.reject', $card->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="text" name="reason" class="form-control form-control-sm" style="width:200px; font-size:0.8rem;" placeholder="Rejection reason" required>
                                    <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3" style="background:#ef4444; color:#fff; border:none;">
                                        <i class="bi bi-x-lg me-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center mb-4" style="color:#94a3b8;">
                        <i class="bi bi-credit-card-2-front fs-1 d-block mb-2 opacity-25"></i>
                        <span style="font-size:0.9rem;">No pending Crypto Card applications.</span>
                    </div>
                @endforelse

                <!-- Issued Cards -->
                <h6 class="fw-bold text-dark mb-3 mt-4" style="font-size:0.95rem;"><i class="bi bi-patch-check me-2" style="color:#16a34a;"></i>Issued Cards
                    <span class="badge fw-bold rounded-pill ms-1" style="background:#f0fdf4; color:#16a34a;">{{ $approvedCards->count() }}</span>
                </h6>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="font-size:0.85rem;">
                            <thead>
                                <tr class="bg-light">
                                    <th class="py-2.5">USER</th>
                                    <th class="py-2.5">BRAND</th>
                                    <th class="py-2.5">CARD NUMBER</th>
                                    <th class="py-2.5">EXPIRY</th>
                                    <th class="py-2.5">APPROVED</th>
                                    <th class="py-2.5 text-end">CVV</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedCards as $card)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $card->user->name }}</div>
                                            <div class="small text-muted">{{ $card->user->email }}</div>
                                        </td>
                                        <td><span class="badge fw-bold rounded-pill" style="background:#eff6ff; color:#2563eb;">{{ $card->card_brand }}</span></td>
                                        <td><code class="fw-bold text-dark">{{ $card->maskedNumber() }}</code></td>
                                        <td>{{ $card->expiryLabel() }}</td>
                                        <td class="text-muted small">{{ $card->approved_at?->format('M d, Y') }}</td>
                                        <td class="text-end"><code>{{ $card->cvv }}</code></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No cards issued yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rejected Applications -->
                <h6 class="fw-bold text-dark mb-3" style="font-size:0.95rem;"><i class="bi bi-x-octagon me-2" style="color:#dc2626;"></i>Rejected
                    <span class="badge fw-bold rounded-pill ms-1" style="background:#fef2f2; color:#dc2626;">{{ $rejectedCards->count() }}</span>
                </h6>
                @forelse($rejectedCards as $card)
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-2">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <div class="fw-bold text-dark small">{{ $card->user->name }}</div>
                                <span class="text-muted small">{{ $card->user->email }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge fw-semibold rounded-pill" style="background:#fef2f2; color:#dc2626; font-size:0.68rem;">{{ $card->rejection_reason }}</span>
                                <form action="{{ route('admin.card.approve', $card->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3" onclick="return confirm('Re-open and approve this application?')">
                                        <i class="bi bi-check-lg me-1"></i> Approve Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center" style="color:#94a3b8;">
                        <span style="font-size:0.9rem;">No rejected applications.</span>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- PROVIDE PAYMENT INSTRUCTIONS MODAL (Admin View - From Image) -->
<!-- ========================================== -->
<div x-show="selectedDepForInstruction" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width: 580px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Provide Payment Instructions (Admin)</h5>
                <small class="text-muted">Request ID: <strong class="text-primary" x-text="selectedDepForInstruction?.deposit_code">FR-250520-0001</strong></small>
            </div>
            <button type="button" class="btn-close" @click="selectedDepForInstruction = null"></button>
        </div>

        <form :action="'/admin/deposit/instructions/' + (selectedDepForInstruction?.id || '')" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold text-dark small">Payment Method</label>
                <select name="beneficiary_method" class="form-select" required>
                    <option value="GCash">GCash</option>
                    <option value="Maya">Maya</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Wire Transfer">Wire Transfer</option>
                    <option value="USDT TRC20">USDT (TRC20)</option>
                </select>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label fw-semibold text-dark small">Account Number</label>
                    <input type="text" name="beneficiary_account_number" class="form-control" value="09658726718" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold text-dark small">Account Name</label>
                    <input type="text" name="beneficiary_account_name" class="form-control" value="RINNY P." required>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label fw-semibold text-dark small">Reference Number (Optional)</label>
                    <input type="text" name="reference_number" class="form-control" value="RDR250520001">
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold text-dark small">Expiration Time</label>
                    <select name="expiration_minutes" class="form-select">
                        <option value="20">20 Minutes</option>
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                        <option value="1440">24 Hours</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark small">Instructions for User</label>
                <textarea name="instructions" class="form-control" rows="3">Please send the exact amount. Do not include any remarks. Upload your payment receipt before the timer expires.</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary fw-bold px-4" @click="selectedDepForInstruction = null">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold px-4" style="background:#7c3aed; border:none;">Send to User</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- REVIEW EVIDENCE & APPROVAL MODAL (Admin View - From Image) -->
<!-- ========================================== -->
<div x-show="selectedDepForReview" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width: 680px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">Request Details (Admin View)</h5>
            <button type="button" class="btn-close" @click="selectedDepForReview = null"></button>
        </div>

        <div class="p-3 bg-light rounded-3 mb-4 border d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-primary me-2">Deposit Request</span>
                <span class="fw-bold text-dark" x-text="selectedDepForReview?.deposit_code">FR-250520-0001</span>
            </div>
            <span class="badge bg-success bg-opacity-15 text-success fw-bold px-3 py-1" x-text="selectedDepForReview?.status || 'Approved'">Approved</span>
        </div>

        <div class="row g-3 mb-4">
            <!-- User Information Box -->
            <div class="col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2" style="font-size:0.85rem;">User Information</h6>
                    <div class="small text-muted mb-1">Name: <strong class="text-dark" x-text="selectedDepForReview?.user?.name || 'John Smith'">John Smith</strong></div>
                    <div class="small text-muted mb-1">Email: <strong class="text-dark" x-text="selectedDepForReview?.user?.email || 'johnsmith@gmail.com'">johnsmith@gmail.com</strong></div>
                    <div class="small text-muted mb-1">Country: <strong class="text-dark" x-text="selectedDepForReview?.country || 'Philippines'">Philippines</strong></div>
                    <div class="small text-muted">Phone: <strong class="text-dark" x-text="selectedDepForReview?.sender_account_number || '0917 123 4567'">0917 123 4567</strong></div>
                </div>
            </div>

            <!-- Request Information Box -->
            <div class="col-md-6">
                <div class="p-3 rounded-3 border bg-white h-100">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2" style="font-size:0.85rem;">Request Information</h6>
                    <div class="small text-muted mb-1">Amount: <strong class="text-dark" x-text="'$ ' + (selectedDepForReview?.amount ? parseFloat(selectedDepForReview?.amount).toFixed(2) : '4,990.00')">$4,990.00</strong></div>
                    <div class="small text-muted mb-1">Payment Method: <strong class="text-dark" x-text="selectedDepForReview?.payment_method || 'GCash'">GCash</strong></div>
                    <div class="small text-muted mb-1">Account Name: <strong class="text-dark" x-text="selectedDepForReview?.sender_account_name || 'John Smith'">John Smith</strong></div>
                    <div class="small text-muted">GCash Number: <strong class="text-dark" x-text="selectedDepForReview?.sender_account_number || '0917 123 4567'">0917 123 4567</strong></div>
                </div>
            </div>
        </div>

        <!-- Payment Evidence Section (From Image) -->
        <div class="p-3 rounded-3 bg-light border mb-4">
            <h6 class="fw-bold text-dark mb-2" style="font-size:0.88rem;">Payment Evidence Uploaded</h6>
            <div class="p-3 bg-white rounded-3 border d-flex align-items-center gap-3 mb-2">
                <i class="bi bi-file-earmark-image fs-1 text-primary"></i>
                <div>
                    <strong class="text-dark d-block small">GCash_Receipt.jpg</strong>
                    <small class="text-muted">1.2 MB • Uploaded by user</small>
                </div>
            </div>
            <div class="small text-muted">Notes from User: <em class="text-dark">"Payment sent. Please confirm."</em></div>
        </div>

        <!-- Action Buttons (From Image: Approve / Reject / Request New Evidence) -->
        <div class="d-flex gap-2">
            <form :action="'/admin/deposit/approve/' + (selectedDepForReview?.id || '')" method="POST" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 rounded-3">
                    <i class="bi bi-check-lg me-1"></i> Approve & Credit Wallet
                </button>
            </form>
            <form :action="'/admin/deposit/reject/' + (selectedDepForReview?.id || '')" method="POST" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3">
                    <i class="bi bi-x-lg me-1"></i> Reject
                </button>
            </form>
            <button type="button" class="btn btn-outline-secondary fw-bold px-3 rounded-3" @click="selectedDepForReview = null">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- USER PREVIEW MODAL (Admin View) -->
<!-- ========================================== -->
<div x-show="selectedUserForPreview" x-cloak class="custom-modal-backdrop">
    <div class="custom-modal-card p-4" style="max-width: 860px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">User Preview</h5>
                <small class="text-muted">Full activity overview for <strong class="text-primary" x-text="selectedUserForPreview?.name">John Smith</strong> (<span x-text="selectedUserForPreview?.account_id || 'N/A'">N/A</span>)</small>
            </div>
            <button type="button" class="btn-close" @click="selectedUserForPreview = null"></button>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3 border bg-white h-100">
                    <small class="text-muted d-block" style="font-size:0.68rem;">WALLET BALANCE</small>
                    <strong class="text-dark" style="font-size:1.05rem;" x-text="'$' + Number(selectedUserForPreview?.wallet_balance || 0).toFixed(2)">$0.00</strong>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3 border bg-white h-100">
                    <small class="text-muted d-block" style="font-size:0.68rem;">TOTAL INVESTED</small>
                    <strong class="text-dark" style="font-size:1.05rem;" x-text="'$' + totalInvestedPreview.toFixed(2)">$0.00</strong>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3 border bg-white h-100">
                    <small class="text-muted d-block" style="font-size:0.68rem;">REFERRALS</small>
                    <strong class="text-dark" style="font-size:1.05rem;" x-text="selectedUserForPreview?.referrals?.length || 0">0</strong>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3 border bg-white h-100">
                    <small class="text-muted d-block" style="font-size:0.68rem;">AFFILIATE EARNINGS</small>
                    <strong class="text-success" style="font-size:1.05rem;" x-text="'$' + Number(selectedUserForPreview?.affiliate_earnings || 0).toFixed(2)">$0.00</strong>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills nav-fill mb-3" style="font-size:0.8rem;">
            <li class="nav-item"><button class="nav-link fw-bold" :class="previewTab === 'investments' ? 'active' : ''" @click="previewTab = 'investments'" style="color:#475569;">Investments</button></li>
            <li class="nav-item"><button class="nav-link fw-bold" :class="previewTab === 'deposits' ? 'active' : ''" @click="previewTab = 'deposits'" style="color:#475569;">Deposits</button></li>
            <li class="nav-item"><button class="nav-link fw-bold" :class="previewTab === 'withdrawals' ? 'active' : ''" @click="previewTab = 'withdrawals'" style="color:#475569;">Withdrawals</button></li>
            <li class="nav-item"><button class="nav-link fw-bold" :class="previewTab === 'transactions' ? 'active' : ''" @click="previewTab = 'transactions'" style="color:#475569;">Transactions</button></li>
        </ul>

        <div style="max-height: 340px; overflow-y: auto;">
            <template x-if="previewTab === 'investments'">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase text-muted" style="font-size:0.65rem;">
                            <th>Property</th><th>Shares</th><th>Amount</th><th>ROI Earned</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="inv in (selectedUserForPreview?.investments || [])" :key="inv.id">
                            <tr>
                                <td class="fw-bold text-dark" x-text="inv.property?.title || '—'"></td>
                                <td x-text="inv.shares_bought"></td>
                                <td x-text="'$' + Number(inv.total_amount).toFixed(2)"></td>
                                <td x-text="'$' + Number(inv.roi_earned || 0).toFixed(2)"></td>
                                <td><span class="badge fw-semibold rounded-pill px-2 py-1" style="font-size:0.65rem;" :style="inv.status === 'active' ? 'background:#f0fdf4; color:#16a34a;' : 'background:#f1f5f9; color:#64748b;'" x-text="inv.status || 'active'"></span></td>
                            </tr>
                        </template>
                        <tr x-show="!selectedUserForPreview?.investments?.length"><td colspan="5" class="text-center text-muted py-4">No investments yet.</td></tr>
                    </tbody>
                </table>
            </template>

            <template x-if="previewTab === 'deposits'">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase text-muted" style="font-size:0.65rem;">
                            <th>Code</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="dep in (selectedUserForPreview?.deposits || [])" :key="dep.id">
                            <tr>
                                <td class="fw-bold text-primary" x-text="dep.deposit_code"></td>
                                <td class="fw-bold text-dark" x-text="'$' + Number(dep.amount).toFixed(2)"></td>
                                <td x-text="(dep.payment_method || '').replace(/_/g, ' ')"></td>
                                <td><span class="badge fw-semibold rounded-pill px-2 py-1" style="font-size:0.65rem;" :style="dep.status === 'completed' ? 'background:#f0fdf4; color:#16a34a;' : 'background:#fffbeb; color:#d97706;'" x-text="(dep.status || 'pending').replace(/_/g, ' ')"></span></td>
                                <td class="text-muted" x-text="dep.created_at ? dep.created_at.split('T')[0] : '—'"></td>
                            </tr>
                        </template>
                        <tr x-show="!selectedUserForPreview?.deposits?.length"><td colspan="5" class="text-center text-muted py-4">No deposits yet.</td></tr>
                    </tbody>
                </table>
            </template>

            <template x-if="previewTab === 'withdrawals'">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase text-muted" style="font-size:0.65rem;">
                            <th>Code</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="wd in (selectedUserForPreview?.withdrawals || [])" :key="wd.id">
                            <tr>
                                <td class="fw-bold text-primary" x-text="wd.withdrawal_code"></td>
                                <td class="fw-bold text-dark" x-text="'$' + Number(wd.amount).toFixed(2)"></td>
                                <td x-text="(wd.withdrawal_method || '').replace(/_/g, ' ')"></td>
                                <td><span class="badge fw-semibold rounded-pill px-2 py-1" style="font-size:0.65rem;" :style="wd.status === 'approved' ? 'background:#f0fdf4; color:#16a34a;' : 'background:#fffbeb; color:#d97706;'" x-text="(wd.status || 'pending').replace(/_/g, ' ')"></span></td>
                                <td class="text-muted" x-text="wd.created_at ? wd.created_at.split('T')[0] : '—'"></td>
                            </tr>
                        </template>
                        <tr x-show="!selectedUserForPreview?.withdrawals?.length"><td colspan="5" class="text-center text-muted py-4">No withdrawals yet.</td></tr>
                    </tbody>
                </table>
            </template>

            <template x-if="previewTab === 'transactions'">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase text-muted" style="font-size:0.65rem;">
                            <th>Reference</th><th>Type</th><th>Amount</th><th>Description</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="tx in (selectedUserForPreview?.transactions || [])" :key="tx.id">
                            <tr>
                                <td class="fw-bold text-primary" x-text="tx.reference"></td>
                                <td x-text="(tx.type || '').replace(/_/g, ' ')"></td>
                                <td class="fw-bold text-dark" x-text="'$' + Number(tx.amount).toFixed(2)"></td>
                                <td class="text-muted" x-text="tx.description || '—'"></td>
                                <td><span class="badge fw-semibold rounded-pill px-2 py-1" style="font-size:0.65rem;" :style="tx.status === 'completed' ? 'background:#f0fdf4; color:#16a34a;' : 'background:#f1f5f9; color:#64748b;'" x-text="tx.status || '—'"></span></td>
                            </tr>
                        </template>
                        <tr x-show="!selectedUserForPreview?.transactions?.length"><td colspan="5" class="text-center text-muted py-4">No transactions yet.</td></tr>
                    </tbody>
                </table>
            </template>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <form :action="'/admin/users/' + (selectedUserForPreview?.id || '') + '/impersonate'" method="POST">
                @csrf
                <button type="submit" class="btn fw-bold px-4 text-white" style="background:#7c3aed; border:none;">
                    <i class="bi bi-person-workspace me-1"></i> Impersonate User
                </button>
            </form>
            <button type="button" class="btn btn-outline-secondary fw-bold px-4" @click="selectedUserForPreview = null">Close</button>
        </div>
    </div>
</div>
</div>

<script>
    function adminDashboardEngine() {
        return {
            activeAdminTab: 'finance_requests',
            filterType: 'all',
            filterStatus: 'all',
            searchQuery: '',
            selectedDepForInstruction: null,
            selectedDepForReview: null,
            selectedUserForPreview: null,
            previewTab: 'investments',
            totalInvestedPreview: 0,
            usersSearch: '',
            usersKycFilter: 'all',
            usersSortBy: 'newest',
            usersVisible: {{ $users->count() }},

            openInstructionModal(dep) {
                this.selectedDepForInstruction = dep;
            },

            openReviewModal(dep) {
                this.selectedDepForReview = dep;
            },

            openUserPreview(user) {
                this.selectedUserForPreview = user;
                this.previewTab = 'investments';
                this.totalInvestedPreview = (user.investments || []).reduce((sum, inv) => sum + parseFloat(inv.total_amount || 0), 0);
            },

            userMatches(user) {
                const q = (this.usersSearch || '').trim().toLowerCase();
                const matchesQuery = !q
                    || (user.name || '').toLowerCase().includes(q)
                    || (user.email || '').toLowerCase().includes(q)
                    || (user.account_id || '').toLowerCase().includes(q);
                const kyc = user.kyc_status || 'none';
                const matchesKyc = this.usersKycFilter === 'all' || kyc === this.usersKycFilter;
                return matchesQuery && matchesKyc;
            },

            recountUsers(el) {
                const rows = document.querySelectorAll('#usersTableWrap tbody tr[data-user-row]');
                let visible = 0;
                rows.forEach(r => { if (r.style.display !== 'none') visible++; });
                this.usersVisible = visible;
            },

            sortUsers() {
                const wrap = document.getElementById('usersTableWrap');
                const tbody = wrap ? wrap.querySelector('tbody') : null;
                if (!tbody) return;
                const rows = Array.from(tbody.querySelectorAll('tr[data-user-row]'));
                const key = this.usersSortBy;
                const val = (r) => {
                    if (key === 'newest') return -parseFloat(r.dataset.created || 0);
                    if (key === 'oldest') return parseFloat(r.dataset.created || 0);
                    if (key === 'invested_high') return -parseFloat(r.dataset.invested || 0);
                    if (key === 'wallet_high') return -parseFloat(r.dataset.wallet || 0);
                    return 0;
                };
                rows.sort((a, b) => val(a) - val(b)).forEach(r => tbody.appendChild(r));
            }
        }
    }
</script>
@endsection
