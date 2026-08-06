@extends('layouts.main')

@section('title', 'Admin Finance Requests | ' . site_name())

@section('content')
<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Admin Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Admin Panel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Finance Requests</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Finance Team Requests Management</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Review, assign payment instructions, inspect evidence & credit wallet balances for local requests.</p>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('admin.deposits.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3 py-2">
                    Standard Deposits
                </a>
                <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3 py-2">
                    Standard Withdrawals
                </a>
            </div>
        </div>

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter & Search Controls Card -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
            <form method="GET" action="{{ route('admin.finance-requests.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search ID, Name, Email..." value="{{ $search }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="type" class="form-select form-select-sm bg-light">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types (Deposit & Withdrawal)</option>
                        <option value="deposit" {{ $type === 'deposit' ? 'selected' : '' }}>Deposits Only</option>
                        <option value="withdrawal" {{ $type === 'withdrawal' ? 'selected' : '' }}>Withdrawals Only</option>
                    </select>
                </div>

                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm bg-light">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="payment_instructions_assigned" {{ $status === 'payment_instructions_assigned' ? 'selected' : '' }}>Instructions Sent</option>
                        <option value="evidence_submitted" {{ $status === 'evidence_submitted' ? 'selected' : '' }}>Evidence Submitted</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold rounded-2">Filter</button>
                    <a href="{{ route('admin.finance-requests.index') }}" class="btn btn-sm btn-outline-secondary rounded-2">Reset</a>
                </div>
            </form>
        </div>

        <!-- Requests Data Table (Matching Image 2 Admin Panel) -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
            @if($requests->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">No finance requests found</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Country / Currency</th>
                                <th>Amount & Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $fr)
                                <tr>
                                    <td><strong class="text-dark font-mono">{{ $fr->request_id }}</strong></td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $fr->user->name ?? $fr->sender_name }}</div>
                                        <small class="text-muted">{{ $fr->user->email ?? $fr->sender_email }}</small>
                                    </td>
                                    <td>
                                        @if($fr->type === 'deposit')
                                            <span class="badge bg-success bg-opacity-15 text-success fw-bold px-2 py-1">Deposit</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-15 text-danger fw-bold px-2 py-1">Withdrawal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $fr->country }}</div>
                                        <small class="text-muted">{{ $fr->currency }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ number_format($fr->amount, 2) }}</span>
                                        <small class="text-muted d-block">{{ $fr->payment_method }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $fr->statusBadgeClass() }} px-2.5 py-1 rounded-2">
                                            {{ $fr->formattedStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $fr->created_at->format('M d, Y') }}<br>
                                        <small>{{ $fr->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.finance-requests.show', $fr->id) }}" class="btn btn-sm btn-primary fw-bold rounded-2 px-3">
                                            Manage Request →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <small class="text-muted">Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} records</small>
                    <div>
                        {{ $requests->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
