@extends('layouts.main')

@section('title', 'Transaction History | Finance Center | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
.nav-pill-tab {
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.86rem;
        color: #64748b;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #f1f5f9;
        display: inline-block;
    }
    .nav-pill-tab:hover, .nav-pill-tab.active {
        background-color: #2563eb;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
</style>

<div class="container-fluid px-0 user-shell-content" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Top Header & Export Row -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('finance.overview') }}" class="text-decoration-none">Finance Center</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Transaction History</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Complete Transaction History</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Official ledger recording all AVC deposits, withdrawals, marketplace trades, escrow releases & investments.</p>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('finance.transactions.export.csv', request()->query()) }}" class="btn btn-outline-secondary fw-bold rounded-3 px-3 py-2">
                    <i class="bi bi-download me-1.5"></i> Export CSV
                </a>
                <a href="{{ route('deposit.index') }}" class="btn btn-primary fw-bold rounded-3 px-3 py-2 shadow-sm" style="background: #2563eb; border:none;">
                    <i class="bi bi-plus-circle-fill me-1.5"></i> New Transaction
                </a>
            </div>
        </div>

        <!-- Summary Metrics Row -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 rounded-3 p-3 shadow-sm bg-white">
                    <span class="text-muted small d-block mb-1">Total Ledger Records</span>
                    <h4 class="fw-bold text-dark mb-0">{{ number_format($totalTransactionsCount) }}</h4>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 rounded-3 p-3 shadow-sm bg-white">
                    <span class="text-muted small d-block mb-1">Total Credits (Inflow)</span>
                    <h4 class="fw-bold text-success mb-0">+{{ number_format($totalCredits, 0) }} AVC</h4>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 rounded-3 p-3 shadow-sm bg-white">
                    <span class="text-muted small d-block mb-1">Total Debits (Outflow)</span>
                    <h4 class="fw-bold text-danger mb-0">-{{ number_format($totalDebits, 0) }} AVC</h4>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 rounded-3 p-3 shadow-sm bg-white">
                    <span class="text-muted small d-block mb-1">Net AVC Flow</span>
                    <h4 class="fw-bold {{ $netFlow >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        {{ $netFlow >= 0 ? '+' : '' }}{{ number_format($netFlow, 0) }} AVC
                    </h4>
                </div>
            </div>
        </div>

        <!-- Category Tab Filters Bar -->
        <div class="d-flex align-items-center gap-2 overflow-x-auto pb-2 mb-4">
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'all'])) }}" class="nav-pill-tab {{ $category === 'all' ? 'active' : '' }}">All Records</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'deposit'])) }}" class="nav-pill-tab {{ $category === 'deposit' ? 'active' : '' }}">Deposits</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'withdrawal'])) }}" class="nav-pill-tab {{ $category === 'withdrawal' ? 'active' : '' }}">Withdrawals</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'marketplace'])) }}" class="nav-pill-tab {{ $category === 'marketplace' ? 'active' : '' }}">Marketplace</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'escrow'])) }}" class="nav-pill-tab {{ $category === 'escrow' ? 'active' : '' }}">Escrow</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'investment'])) }}" class="nav-pill-tab {{ $category === 'investment' ? 'active' : '' }}">Investments</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'earnings'])) }}" class="nav-pill-tab {{ $category === 'earnings' ? 'active' : '' }}">Earnings & ROI</a>
            <a href="{{ route('finance.transactions', array_merge(request()->query(), ['category' => 'fees'])) }}" class="nav-pill-tab {{ $category === 'fees' ? 'active' : '' }}">Fees</a>
        </div>

        <!-- Search & Filter Controls Card -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
            <form method="GET" action="{{ route('finance.transactions') }}" class="row g-2 align-items-center">
                <input type="hidden" name="category" value="{{ $category }}">
                
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search reference, method or keyword..." value="{{ $search }}">
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm bg-light">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed / Rejected</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <input type="date" name="date_from" class="form-control form-select-sm bg-light" value="{{ $dateFrom }}" placeholder="From Date">
                </div>

                <div class="col-6 col-md-2">
                    <input type="date" name="date_to" class="form-control form-select-sm bg-light" value="{{ $dateTo }}" placeholder="To Date">
                </div>

                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold rounded-2">Filter</button>
                    <a href="{{ route('finance.transactions') }}" class="btn btn-sm btn-outline-secondary rounded-2">Reset</a>
                </div>
            </form>
        </div>

        <!-- Transactions Ledger Table Card -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
            @if($transactions->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-receipt-cutoff display-4 text-muted d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">No transaction records found</h5>
                    <p class="text-muted small">Try adjusting your filters or date selection.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Reference</th>
                                <th>Category / Description</th>
                                <th>Payment Method</th>
                                <th>Amount (AVC)</th>
                                <th>Fiat Value (USD)</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $t)
                                <tr>
                                    <td class="text-muted" style="font-size:0.82rem;">
                                        <div class="fw-semibold text-dark">{{ $t->created_at->format('M d, Y') }}</div>
                                        <small>{{ $t->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border fw-mono">{{ $t->reference }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi {{ $t->categoryIcon() }} fs-5"></i>
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="font-size:0.86rem;">{{ ucfirst($t->category ?? $t->type) }}</span>
                                                <small class="text-muted">{{ Str::limit($t->description, 35) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary">{{ $t->payment_method ?? 'Platform Wallet' }}</td>
                                    <td>
                                        <span class="fw-bold fs-6 {{ $t->isCredit() ? 'text-success' : 'text-danger' }}">
                                            {{ $t->signedAmount() }} AVC
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        ${{ number_format($t->fiat_equivalent ?? $t->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $t->statusBadgeClass() }} px-2.5 py-1 rounded-2">
                                            {{ $t->formattedStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('finance.transactions.show', $t->id) }}" class="btn btn-sm btn-light border rounded-2 text-primary fw-semibold">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <small class="text-muted">Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} records</small>
                    <div>
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
