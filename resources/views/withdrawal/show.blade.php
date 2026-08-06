@extends('layouts.main')

@section('title', 'Withdrawal Request ' . $withdrawal->withdrawal_code . ' | ' . site_name())

@section('content')
<style>
    .timeline-step { position: relative; padding-left: 30px; }
    .timeline-step::before { content: ''; position: absolute; left: 10px; top: 24px; bottom: -12px; width: 2px; background-color: #cbd5e1; }
    .timeline-step:last-child::before { display: none; }
    .timeline-icon { position: absolute; left: 0; top: 0; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: bold; }
</style>

<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="row g-0">
        
        <!-- Left Sidebar Column -->
        <div class="col-12 col-md-4 col-lg-3 d-none d-md-block">
            @include('partials.app-sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="col-12 col-md-8 col-lg-9 p-3 p-md-4">
            
            <!-- Breadcrumb Navigation -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('withdraw.index') }}" class="text-decoration-none text-muted fw-semibold small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Withdraw / Sell AVC Hub
                </a>
                <span class="badge {{ $withdrawal->statusBadgeClass() }} px-3 py-2 fs-6 rounded-pill">
                    {{ $withdrawal->formattedStatusLabel() }}
                </span>
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

            <!-- Withdrawal Summary Header Banner -->
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-6">
                        <span class="text-muted small d-block font-monospace">Withdrawal ID: <strong>{{ $withdrawal->withdrawal_code }}</strong></span>
                        <h3 class="fw-bold text-dark mb-1">${{ number_format($withdrawal->estimated_net_payout ?: ($withdrawal->amount - 2.50), 2) }} {{ $withdrawal->payout_currency }}</h3>
                        <span class="text-primary fw-bold">Converted from {{ number_format($withdrawal->amount, 0) }} AVC</span>
                    </div>

                    <div class="col-12 col-md-6 text-md-end">
                        <div class="text-muted small">Payout Method: <strong class="text-dark">{{ $withdrawal->methodLabel() }}</strong></div>
                        <div class="text-muted small">Requested On: <strong class="text-dark">{{ $withdrawal->created_at->format('M d, Y H:i:s') }}</strong></div>
                        
                        @if($withdrawal->isCancellable())
                            <form action="{{ route('withdraw.cancel', $withdrawal->id) }}" method="POST" class="d-inline-block mt-2" onsubmit="return confirm('Cancel this withdrawal request and refund {{ number_format($withdrawal->amount, 0) }} AVC to your wallet?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 fw-bold"><i class="bi bi-x-circle me-1"></i> Cancel Withdrawal</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- LEFT COLUMN: Destination Details & Breakdown -->
                <div class="col-12 col-lg-7">
                    
                    <!-- Payout Destination Details -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bank text-primary me-2"></i> Payout Destination Details</h5>
                        
                        <div class="bg-light p-3.5 rounded-3 border">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Holder Name</span>
                                    <strong class="text-dark">{{ $withdrawal->account_name }}</strong>
                                </div>

                                @if($withdrawal->bank_or_provider)
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Bank / Provider</span>
                                        <strong class="text-dark">{{ $withdrawal->bank_or_provider }}</strong>
                                    </div>
                                @endif

                                @if($withdrawal->account_number)
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Number / ID</span>
                                        <strong class="text-dark font-monospace fs-6">{{ $withdrawal->account_number }}</strong>
                                    </div>
                                @endif

                                @if($withdrawal->wallet_address)
                                    <div class="col-12">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Crypto Wallet Address</span>
                                        <strong class="text-dark font-monospace text-break">{{ $withdrawal->wallet_address }}</strong>
                                        <span class="text-muted d-block small mt-1">Network: <strong>{{ $withdrawal->crypto_network ?? 'TRC-20' }}</strong></span>
                                    </div>
                                @endif

                                <div class="col-12 col-sm-6">
                                    <span class="text-muted small d-block" style="font-size: 0.75rem;">Country / Currency</span>
                                    <strong class="text-dark">{{ $withdrawal->country }} ({{ $withdrawal->currency }})</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calculation Breakdown Card -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calculator text-primary me-2"></i> Conversion & Fee Breakdown</h6>
                        
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">AVC Amount Withdrawn</td>
                                        <td class="text-end fw-bold text-dark">{{ number_format($withdrawal->amount, 0) }} AVC</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Conversion Rate</td>
                                        <td class="text-end text-dark">1 AVC = $1.00 USD</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Gross USD Value</td>
                                        <td class="text-end text-dark">${{ number_format($withdrawal->amount * 1.00, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Processing Fee</td>
                                        <td class="text-end text-dark">-${{ number_format($withdrawal->processing_fee ?: 2.50, 2) }}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="fw-bold text-dark">Estimated Net Payout</td>
                                        <td class="text-end fw-bold text-success fs-5">${{ number_format($withdrawal->estimated_net_payout ?: ($withdrawal->amount - 2.50), 2) }} USD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Admin Payout Receipt Proof -->
                    @if($withdrawal->receipt_proof || $withdrawal->transaction_reference)
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-receipt text-success me-2"></i> Payout Settlement Confirmation</h6>
                            
                            @if($withdrawal->transaction_reference)
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Transaction Reference / TX Hash</span>
                                    <code class="fw-bold text-primary">{{ $withdrawal->transaction_reference }}</code>
                                </div>
                            @endif

                            @if($withdrawal->receipt_proof)
                                <div class="mt-2">
                                    <a href="{{ asset($withdrawal->receipt_proof) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold rounded-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Download Finance Payout Receipt
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- RIGHT COLUMN: 13-Stage Timeline & Support Section -->
                <div class="col-12 col-lg-5">
                    
                    <!-- 13-Stage Status Timeline Tracker -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <h6 class="fw-bold text-dark mb-4"><i class="bi bi-diagram-3 text-primary me-2"></i> Withdrawal Status Timeline</h6>
                        
                        <div class="d-flex flex-column gap-3">
                            <div class="timeline-step">
                                <div class="timeline-icon bg-success text-white"><i class="bi bi-check"></i></div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">1. Request Submitted</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $withdrawal->created_at->format('M d, Y H:i') }}</span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ in_array($withdrawal->status, ['finance_review', 'approved', 'processing', 'payment_sent', 'completed']) ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">2. Security Verification</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">PIN & limit verification</span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ in_array($withdrawal->status, ['approved', 'processing', 'payment_sent', 'completed']) ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">3. Finance Team Review</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">Payout destination audit</span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ in_array($withdrawal->status, ['payment_sent', 'completed']) ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-send"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">4. Payment Sent</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">Bank/Crypto transfer initiated</span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ $withdrawal->isCompleted() ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-check-all"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">5. Completed</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">
                                    {{ $withdrawal->completed_at ? $withdrawal->completed_at->format('M d, Y H:i') : 'Pending settlement' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    @if($withdrawal->admin_notes)
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4 border-start border-warning border-4">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text text-warning me-2"></i> Message from Finance Team</h6>
                            <p class="text-secondary small mb-0">{{ $withdrawal->admin_notes }}</p>
                        </div>
                    @endif

                    <!-- Support Section (Pre-filled Message - Spec Section 20) -->
                    @php
                        $supportMsg = rawurlencode("Hello AVC Finance Team, I need assistance with withdrawal request {$withdrawal->withdrawal_code}. User ID: #{$user->id}, Method: {$withdrawal->methodLabel()}, Net Amount: $" . number_format($withdrawal->estimated_net_payout ?: ($withdrawal->amount - 2.50), 2) . ", Status: {$withdrawal->formattedStatusLabel()}.");
                    @endphp
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h6 class="fw-bold text-dark mb-2">Need Help?</h6>
                        <p class="text-muted small mb-3">Our Finance Team is available 24/7 to assist with your payout request.</p>
                        
                        <div class="d-flex flex-column gap-2">
                            <a href="https://wa.me/?text={{ $supportMsg }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold rounded-3 py-2">
                                <i class="bi bi-whatsapp me-2"></i> WhatsApp Support
                            </a>
                            <a href="https://t.me/?text={{ $supportMsg }}" target="_blank" class="btn btn-outline-info btn-sm fw-bold rounded-3 py-2">
                                <i class="bi bi-telegram me-2"></i> Telegram Support
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
