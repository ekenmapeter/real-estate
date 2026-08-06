@extends('layouts.main')

@section('title', 'Deposit Request Management | Admin')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8fafc; min-height: 100vh;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Deposit Request Management</h2>
            <p class="text-muted mb-0">Assign payment instructions, review proof uploads, and credit AVC via double-entry wallet ledger.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.payment-channels.index') }}" class="btn btn-outline-primary rounded-3">
                <i class="bi bi-gear me-1"></i> Payment Channels
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Admin Dashboard
            </a>
        </div>
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

    <!-- Filters Toolbar -->
    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
        <form action="{{ route('admin.deposits.index') }}" method="GET" class="row g-2">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search request code, user, hash..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="payment_instructions_assigned" {{ request('status') == 'payment_instructions_assigned' ? 'selected' : '' }}>Instructions Assigned</option>
                    <option value="payment_submitted" {{ request('status') == 'payment_submitted' ? 'selected' : '' }}>Payment Submitted</option>
                    <option value="avc_credited" {{ request('status') == 'avc_credited' ? 'selected' : '' }}>AVC Credited</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="method" class="form-select" onchange="this.form.submit()">
                    <option value="">All Payment Methods</option>
                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="wire_transfer" {{ request('method') == 'wire_transfer' ? 'selected' : '' }}>Wire Transfer</option>
                    <option value="crypto" {{ request('method') == 'crypto' ? 'selected' : '' }}>Crypto</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button type="submit" class="btn btn-primary rounded-3">Filter</button>
            </div>
        </form>
    </div>

    <!-- Deposit Requests Table -->
    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code / User</th>
                        <th>Method</th>
                        <th>Amount / AVC</th>
                        <th>Proof / TX Hash</th>
                        <th>Timer / Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deposits as $dep)
                        <tr>
                            <td>
                                <strong class="text-primary font-monospace d-block">{{ $dep->deposit_code }}</strong>
                                <span class="text-dark fw-semibold small">{{ $dep->user->name ?? 'User #' . $dep->user_id }}</span>
                                <span class="text-muted small d-block">{{ $dep->user->email ?? '' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $dep->methodLabel() }}</span>
                                <span class="text-muted small d-block">{{ $dep->country }}</span>
                            </td>
                            <td>
                                <strong class="text-dark d-block">${{ number_format($dep->amount, 2) }} {{ $dep->deposit_currency }}</strong>
                                <span class="text-success fw-bold small">+{{ number_format($dep->net_avc ?: $dep->amount, 0) }} AVC</span>
                            </td>
                            <td>
                                @if($dep->receipt_proof)
                                    <a href="{{ asset($dep->receipt_proof) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-2"><i class="bi bi-file-earmark-image me-1"></i> Proof</a>
                                @elseif($dep->tx_hash)
                                    <code class="small text-truncate d-inline-block" style="max-width: 120px;">{{ $dep->tx_hash }}</code>
                                @else
                                    <span class="text-muted small">No proof</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small d-block">{{ $dep->created_at->format('M d H:i') }}</span>
                                @if($dep->expires_at && in_array($dep->status, ['payment_instructions_assigned', 'awaiting_payment']))
                                    <span class="badge bg-warning text-dark small"><i class="bi bi-clock me-1"></i> {{ $dep->expires_at->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $dep->statusBadgeClass() }}">{{ $dep->formattedStatusLabel() }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('deposit.show', $dep->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-eye"></i> View</a>
                                    
                                    @if(!$dep->isCredited() && $dep->status !== 'rejected')
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $dep->id }}">Instructions</button>
                                        
                                        <form action="{{ route('admin.deposits.credit-avc', $dep->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Credit {{ number_format($dep->net_avc ?: $dep->amount, 0) }} AVC to {{ $dep->user->name }} via double-entry wallet ledger?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success fw-bold"><i class="bi bi-check-lg"></i> Credit AVC</button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Assign Instructions Modal -->
                                <div class="modal fade text-start" id="assignModal{{ $dep->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 bg-primary text-white">
                                                <h6 class="modal-title fw-bold">Assign Payment Instructions: {{ $dep->deposit_code }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.deposits.assign-instructions', $dep->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Beneficiary Account Name</label>
                                                        <input type="text" name="beneficiary_name" class="form-control" value="Aurevia Real Estate Services Inc." required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Bank / Wallet Provider</label>
                                                        <input type="text" name="bank_or_provider" class="form-control" value="BDO Unibank / JPMorgan Chase" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Account Number / Wallet Address</label>
                                                        <input type="text" name="account_number" class="form-control font-monospace" value="0081-2299-4410" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Reference Code</label>
                                                        <input type="text" name="reference_code" class="form-control font-monospace" value="{{ $dep->deposit_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-secondary">Expiration Timer (Minutes)</label>
                                                        <input type="number" name="expiration_minutes" class="form-control" value="30" min="5" max="1440" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3" style="background: #2563eb;">Send Instructions</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No deposit requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $deposits->links() }}
        </div>
    </div>

</div>
@endsection
