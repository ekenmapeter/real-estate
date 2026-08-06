@extends('layouts.main')

@section('title', 'Payment Channel Management | Admin')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8fafc; min-height: 100vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Payment Channel Management</h2>
            <p class="text-muted mb-0">Create, configure, and manage official Finance Team payment channel accounts.</p>
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

    <div class="row g-4">
        <!-- LEFT: Create Channel Form -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle text-primary me-2"></i> Add Payment Channel</h5>
                
                <form action="{{ route('admin.payment-channels.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Payment Method</label>
                        <select name="method_key" class="form-select" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit / Debit Card</option>
                            <option value="wire_transfer">International Wire Transfer</option>
                            <option value="crypto">Cryptocurrency</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Channel Name</label>
                        <input type="text" name="channel_name" class="form-control" placeholder="e.g. BDO Unibank Account #1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Account Name</label>
                        <input type="text" name="account_name" class="form-control" placeholder="e.g. Aurevia Real Estate Corp">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Bank / Provider / Network</label>
                        <input type="text" name="bank_or_provider" class="form-control" placeholder="e.g. BDO, TRC-20, Stripe">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Account Number / Wallet Address</label>
                        <input type="text" name="account_number" class="form-control font-monospace" placeholder="Account # or Address">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">SWIFT / BIC</label>
                            <input type="text" name="swift_bic" class="form-control font-monospace">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">IBAN</label>
                            <input type="text" name="iban" class="form-control font-monospace">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">Country</label>
                            <input type="text" name="country" class="form-control" value="Philippines">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">Currency</label>
                            <input type="text" name="currency" class="form-control" value="PHP">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Channel Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="full_capacity">Full Capacity</option>
                            <option value="country_restricted">Country Restricted</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold w-100 rounded-3" style="background: #2563eb; border: none;">
                        <i class="bi bi-save me-1"></i> Create Payment Channel
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT: Channel List Table -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-ul text-primary me-2"></i> Configured Payment Channels</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Method</th>
                                <th>Channel Name</th>
                                <th>Account / Details</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($channels as $chan)
                                <tr>
                                    <td><span class="badge bg-light text-dark border">{{ $chan->methodLabel() }}</span></td>
                                    <td>
                                        <strong class="text-dark d-block">{{ $chan->channel_name }}</strong>
                                        <span class="text-muted small">{{ $chan->country }} &bull; {{ $chan->currency }}</span>
                                    </td>
                                    <td>
                                        <span class="small font-monospace d-block">{{ $chan->account_number ?: $chan->wallet_address ?: 'Dynamic Assignment' }}</span>
                                        <span class="text-muted small">{{ $chan->account_name }}</span>
                                    </td>
                                    <td><span class="badge {{ $chan->statusBadgeClass() }}">{{ $chan->statusLabel() }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.payment-channels.destroy', $chan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this payment channel?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No payment channels configured yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
