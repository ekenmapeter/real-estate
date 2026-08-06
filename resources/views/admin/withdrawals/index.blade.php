@extends('layouts.main')

@section('title', 'Withdrawal Request Management | Admin')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8fafc; min-height: 100vh;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Withdrawal Request Management</h2>
            <p class="text-muted mb-0">Review payout destinations, audit KYC, send bank/crypto payments, and complete withdrawals.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Admin Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filters Toolbar -->
    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
        <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="row g-2">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search code, user, account, TX hash..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="finance_review" {{ request('status') == 'finance_review' ? 'selected' : '' }}>Finance Review</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="method" class="form-select" onchange="this.form.submit()">
                    <option value="">All Methods</option>
                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="mobile_wallet" {{ request('method') == 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
                    <option value="wire_transfer" {{ request('method') == 'wire_transfer' ? 'selected' : '' }}>Wire Transfer</option>
                    <option value="crypto" {{ request('method') == 'crypto' ? 'selected' : '' }}>Crypto</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button type="submit" class="btn btn-primary rounded-3">Filter</button>
            </div>
        </form>
    </div>

    <!-- Withdrawals Requests Table -->
    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code / User</th>
                        <th>Method</th>
                        <th>Amount / Net Payout</th>
                        <th>Destination Account</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $wdr)
                        <tr>
                            <td>
                                <strong class="text-primary font-monospace d-block">{{ $wdr->withdrawal_code }}</strong>
                                <span class="text-dark fw-semibold small">{{ $wdr->user->name ?? 'User #' . $wdr->user_id }}</span>
                                <span class="text-muted small d-block">{{ $wdr->user->email ?? '' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $wdr->methodLabel() }}</span>
                                <span class="text-muted small d-block">{{ $wdr->country }}</span>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ number_format($wdr->amount, 0) }} AVC</strong>
                                <span class="text-success fw-bold small">Net: ${{ number_format($wdr->estimated_net_payout ?: ($wdr->amount - 2.50), 2) }}</span>
                            </td>
                            <td>
                                <strong class="text-dark small d-block">{{ $wdr->account_name }}</strong>
                                <span class="font-monospace text-muted small d-block">{{ $wdr->account_number ?: $wdr->wallet_address }}</span>
                                <span class="text-muted small">{{ $wdr->bank_or_provider ?: $wdr->crypto_network }}</span>
                            </td>
                            <td><span class="text-muted small">{{ $wdr->created_at->format('M d H:i') }}</span></td>
                            <td><span class="badge {{ $wdr->statusBadgeClass() }}">{{ $wdr->formattedStatusLabel() }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('withdraw.show', $wdr->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-eye"></i> View</a>

                                    @if(!$wdr->isCompleted() && !in_array($wdr->status, ['rejected', 'cancelled']))
                                        <form action="{{ route('admin.withdrawals.approve', $wdr->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-play-fill"></i> Approve</button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#completeModal{{ $wdr->id }}"><i class="bi bi-check-lg"></i> Complete</button>

                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $wdr->id }}"><i class="bi bi-x-circle"></i> Reject</button>
                                    @endif
                                </div>

                                <!-- Complete Payout Modal -->
                                <div class="modal fade text-start" id="completeModal{{ $wdr->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 bg-success text-white">
                                                <h6 class="modal-title fw-bold">Complete Payout: {{ $wdr->withdrawal_code }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.withdrawals.complete', $wdr->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Transaction Reference Code / TX Hash</label>
                                                        <input type="text" name="transaction_reference" class="form-control font-monospace" placeholder="e.g. Bank Ref # / TX Hash" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Upload Payout Receipt Proof (Optional)</label>
                                                        <input type="file" name="receipt_file" class="form-control" accept=".jpg,.png,.pdf">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Finance Team Notes</label>
                                                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="Payout sent via bank transfer"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success fw-bold px-4 rounded-3">Finalize & Complete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Payout Modal -->
                                <div class="modal fade text-start" id="rejectModal{{ $wdr->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 bg-danger text-white">
                                                <h6 class="modal-title fw-bold">Reject Payout: {{ $wdr->withdrawal_code }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.withdrawals.reject', $wdr->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Reason for Rejection</label>
                                                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Enter reason for rejecting withdrawal (e.g. invalid account details)" required></textarea>
                                                    </div>
                                                    <p class="text-danger small mb-0"><i class="bi bi-exclamation-triangle me-1"></i> Rejecting this request will automatically refund {{ number_format($wdr->amount, 0) }} AVC back to the user's available balance.</p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger fw-bold px-4 rounded-3">Reject & Refund AVC</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No withdrawal requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $withdrawals->links() }}
        </div>
    </div>

</div>
@endsection
