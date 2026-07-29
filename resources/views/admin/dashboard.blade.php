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

<div class="container-fluid px-0" x-data="adminDashboardEngine()">
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
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'users' }" @click.prevent="activeAdminTab = 'users'">
                    <i class="bi bi-people-fill"></i> Users
                </a>
                <a href="#" class="nav-link-admin" :class="{ 'active': activeAdminTab === 'withdrawals' }" @click.prevent="activeAdminTab = 'withdrawals'">
                    <i class="bi bi-arrow-up-right-circle"></i> Withdrawals
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
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm fw-bold rounded-3">
                        <i class="bi bi-arrow-left me-1"></i> Switch to User View
                    </a>
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
                                        <td class="fw-bold small">{{ $dep->currency ?? 'PHP' }}</td>
                                        <td class="fw-bold text-dark">{{ number_format($dep->amount, 2) }}</td>
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
                    <form action="{{ route('admin.property.store') }}" method="POST">
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
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Price Per Share ($)</label>
                                <input type="number" step="0.01" name="price_per_share" class="form-control" placeholder="500.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Total Shares</label>
                                <input type="number" name="total_shares" class="form-control" placeholder="1000" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Target ROI (%)</label>
                                <input type="number" step="0.1" name="roi_percentage" class="form-control" placeholder="24.5" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3" style="background:#2563eb;">Publish Listing</button>
                    </form>
                </div>
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
                    <div class="small text-muted mb-1">Amount: <strong class="text-dark" x-text="(selectedDepForReview?.currency || 'PHP') + ' ' + (selectedDepForReview?.amount ? parseFloat(selectedDepForReview?.amount).toFixed(2) : '4,990.00')">₱4,990.00</strong></div>
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

<script>
    function adminDashboardEngine() {
        return {
            activeAdminTab: 'finance_requests',
            filterType: 'all',
            filterStatus: 'all',
            searchQuery: '',
            selectedDepForInstruction: null,
            selectedDepForReview: null,

            openInstructionModal(dep) {
                this.selectedDepForInstruction = dep;
            },

            openReviewModal(dep) {
                this.selectedDepForReview = dep;
            }
        }
    }
</script>
@endsection
