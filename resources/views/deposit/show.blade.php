@extends('layouts.main')

@section('title', 'Deposit Request ' . $deposit->deposit_code . ' | ' . site_name())

@section('content')
<style>
    .copy-btn { cursor: pointer; transition: all 0.2s; }
    .copy-btn:hover { background-color: #e2e8f0 !important; }
    .timeline-step { position: relative; padding-left: 30px; }
    .timeline-step::before { content: ''; position: absolute; left: 10px; top: 24px; bottom: -12px; width: 2px; background-color: #cbd5e1; }
    .timeline-step:last-child::before { display: none; }
    .timeline-icon { position: absolute; left: 0; top: 0; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: bold; }
</style>

<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;" x-data="depositDetailApp()">
    <div class="row g-0">
        
        <!-- Left Sidebar Column -->
        <div class="col-12 col-md-4 col-lg-3 d-none d-md-block">
            <div class="sticky-top" style="top:70px; height:calc(100vh - 70px);">
    @include('partials.navy-sidebar')
</div>
@section('footer')<!-- suppressed -->@endsection
        </div>

        <!-- Main Content Area -->
        <div class="col-12 col-md-8 col-lg-9 p-3 p-md-4">
            
            <!-- Breadcrumb Navigation -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('deposit.index') }}" class="text-decoration-none text-muted fw-semibold small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Recent AVC Activity
                </a>
                <span class="badge {{ $deposit->statusBadgeClass() }} px-3 py-2 fs-6 rounded-pill">
                    {{ $deposit->formattedStatusLabel() }}
                </span>
            </div>

            <!-- Flash Messages -->
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

            <!-- Request Top Summary Banner -->
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-6">
                        <span class="text-muted small d-block font-monospace">Request Code: <strong>{{ $deposit->deposit_code }}</strong></span>
                        <h3 class="fw-bold text-dark mb-1">{{ number_format($deposit->amount, 2) }} {{ $deposit->deposit_currency }}</h3>
                        <span class="text-success fw-bold">≈ {{ number_format($deposit->net_avc ?: $deposit->amount, 0) }} AVC to be credited</span>
                    </div>

                    <div class="col-12 col-md-6 text-md-end">
                        <div class="text-muted small">Payment Method: <strong class="text-dark">{{ $deposit->methodLabel() }}</strong></div>
                        <div class="text-muted small">Submitted Date: <strong class="text-dark">{{ $deposit->created_at->format('M d, Y H:i:s') }}</strong></div>
                        @if($deposit->credited_at)
                            <div class="text-success small fw-bold mt-1"><i class="bi bi-check-all me-1"></i> AVC Credited on {{ $deposit->credited_at->format('M d, Y H:i') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- LEFT COLUMN: Assigned Payment Instructions & Proof Upload Form -->
                <div class="col-12 col-lg-7">
                    
                    <!-- Official Assigned Payment Instruction Card (Section 5.2, 7.2, 8.3 & 10) -->
                    @if($deposit->admin_instructions || $deposit->paymentChannel)
                        @php
                            $inst = $deposit->admin_instructions ?: [];
                            $chan = $deposit->paymentChannel;
                        @endphp
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4 border-start border-primary border-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-bank text-primary me-2"></i> Official Assigned Payment Instructions</h5>
                                
                                <!-- Expiration Timer Badge (Section 10) -->
                                @if($deposit->expires_at && in_array($deposit->status, ['payment_instructions_assigned', 'awaiting_payment']))
                                    <div class="badge bg-warning text-dark px-3 py-2 rounded-pill font-monospace" style="font-size: 0.85rem;">
                                        <i class="bi bi-hourglass-split me-1"></i> Expires in <span x-text="timerFormatted">--:--</span>
                                    </div>
                                @elseif($deposit->isExpired())
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">Expired</span>
                                @endif
                            </div>

                            <div class="alert alert-warning border-0 p-3 rounded-3 mb-3 small d-flex align-items-center gap-2">
                                <i class="bi bi-shield-exclamation text-warning fs-4 flex-shrink-0"></i>
                                <span><strong>Important Security Notice:</strong> Send funds ONLY to the account displayed inside your authenticated AVC dashboard. Do not send funds to payment details received outside the platform.</span>
                            </div>

                            <div class="bg-light p-3 rounded-3 mb-4">
                                <div class="row g-3">
                                    @if(isset($inst['beneficiary_name']) || ($chan && $chan->account_name))
                                        <div class="col-12 col-sm-6">
                                            <span class="text-muted small d-block">Account / Beneficiary Name</span>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <strong class="text-dark">{{ $inst['beneficiary_name'] ?? $chan->account_name }}</strong>
                                                <button type="button" class="btn btn-sm btn-light border p-1 px-2 text-primary rounded-2 copy-btn" @click="copyText('{{ $inst['beneficiary_name'] ?? $chan->account_name }}')">Copy</button>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($inst['bank_or_provider']) || ($chan && $chan->bank_or_provider))
                                        <div class="col-12 col-sm-6">
                                            <span class="text-muted small d-block">Bank / Provider</span>
                                            <strong class="text-dark">{{ $inst['bank_or_provider'] ?? $chan->bank_or_provider }}</strong>
                                        </div>
                                    @endif

                                    @if(isset($inst['account_number']) || ($chan && $chan->account_number))
                                        <div class="col-12 col-sm-6">
                                            <span class="text-muted small d-block">Account / IBAN Number</span>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <strong class="text-dark font-monospace">{{ $inst['account_number'] ?? $chan->account_number }}</strong>
                                                <button type="button" class="btn btn-sm btn-light border p-1 px-2 text-primary rounded-2 copy-btn" @click="copyText('{{ $inst['account_number'] ?? $chan->account_number }}')">Copy</button>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($inst['swift_bic']) || ($chan && $chan->swift_bic))
                                        <div class="col-12 col-sm-6">
                                            <span class="text-muted small d-block">SWIFT / BIC Code</span>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <strong class="text-dark font-monospace">{{ $inst['swift_bic'] ?? $chan->swift_bic }}</strong>
                                                <button type="button" class="btn btn-sm btn-light border p-1 px-2 text-primary rounded-2 copy-btn" @click="copyText('{{ $inst['swift_bic'] ?? $chan->swift_bic }}')">Copy</button>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($inst['wallet_address']) || ($chan && $chan->wallet_address))
                                        <div class="col-12">
                                            <span class="text-muted small d-block">Crypto Wallet Address</span>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <strong class="text-dark font-monospace small text-break">{{ $inst['wallet_address'] ?? $chan->wallet_address }}</strong>
                                                <button type="button" class="btn btn-sm btn-light border p-1 px-2 text-primary rounded-2 copy-btn ms-2" @click="copyText('{{ $inst['wallet_address'] ?? $chan->wallet_address }}')">Copy Address</button>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-12">
                                        <span class="text-muted small d-block">Payment Reference Code (MUST INCLUDE)</span>
                                        <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded-2 border">
                                            <strong class="text-primary font-monospace fs-5">{{ $inst['reference_code'] ?? $deposit->deposit_code }}</strong>
                                            <button type="button" class="btn btn-sm btn-primary fw-bold rounded-2 copy-btn" @click="copyText('{{ $inst['reference_code'] ?? $deposit->deposit_code }}')">Copy Reference</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4 text-center">
                            <i class="bi bi-hourglass-split text-warning display-4 mb-2"></i>
                            <h5 class="fw-bold text-dark">Awaiting Payment Instruction Assignment</h5>
                            <p class="text-muted small">Your deposit request has been submitted to the Finance Team. The official payment account will be assigned and displayed here shortly.</p>
                        </div>
                    @endif

                    <!-- Payment Proof Submission Form (Section 5.3) -->
                    @if($deposit->canSubmitProof() || !$deposit->receipt_proof)
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-upload text-primary me-2"></i> Submit Payment Proof</h5>
                            
                            <form action="{{ route('deposit.proof.store', $deposit->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                @if($deposit->payment_method === 'crypto')
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Transaction Hash / TX ID</label>
                                        <input type="text" name="tx_hash" class="form-control font-monospace" placeholder="Enter blockchain transaction hash" value="{{ $deposit->tx_hash }}">
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Upload Payment Proof / Bank Receipt</label>
                                    <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <span class="text-muted small d-block mt-1">Accepted formats: JPG, PNG, PDF (Max 10MB)</span>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">Additional Notes for Finance Team</label>
                                    <textarea name="user_notes" class="form-control" rows="3" placeholder="Enter any extra details or transaction remarks">{{ $deposit->user_notes }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-3 shadow-sm" style="background: #16a34a; border: none;">
                                    <i class="bi bi-check-circle-fill me-1"></i> I Have Made the Payment
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Uploaded Proof View -->
                    @if($deposit->receipt_proof)
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-check text-success me-2"></i> Uploaded Payment Proof</h6>
                            <div class="p-3 bg-light rounded-3 text-center">
                                <a href="{{ asset($deposit->receipt_proof) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold rounded-3">
                                    <i class="bi bi-eye me-1"></i> View Submitted Receipt Proof
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- RIGHT COLUMN: 13-Stage Timeline, Admin Notes & Support Section -->
                <div class="col-12 col-lg-5">
                    
                    <!-- Status Timeline Component (Matching Spec Section 7.4 & 13) -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <h6 class="fw-bold text-dark mb-4"><i class="bi bi-diagram-3 text-primary me-2"></i> Request Status Timeline</h6>
                        
                        <div class="d-flex flex-column gap-3">
                            <div class="timeline-step">
                                <div class="timeline-icon bg-success text-white"><i class="bi bi-check"></i></div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">1. Request Created</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $deposit->created_at->format('M d, Y H:i') }}</span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ in_array($deposit->status, ['payment_instructions_assigned', 'awaiting_payment', 'payment_submitted', 'under_verification', 'confirmed', 'avc_credited']) ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">2. Payment Instructions Assigned</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">
                                    {{ $deposit->admin_instructions ? 'Instructions displayed' : 'Awaiting assignment' }}
                                </span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ in_array($deposit->status, ['payment_submitted', 'under_verification', 'confirmed', 'avc_credited']) ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-upload"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">3. Payment Submitted</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">
                                    {{ $deposit->receipt_proof ? 'Proof uploaded' : 'Awaiting proof' }}
                                </span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ in_array($deposit->status, ['under_verification', 'confirmed', 'avc_credited']) ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">4. Finance Verification</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">Bank settlement & proof audit</span>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ $deposit->isCredited() ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">5. AVC Credited</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">
                                    {{ $deposit->credited_at ? $deposit->credited_at->format('M d, Y H:i') : 'Pending credit' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes Visible To User -->
                    @if($deposit->admin_notes)
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4 border-start border-warning border-4">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text text-warning me-2"></i> Message from Finance Team</h6>
                            <p class="text-secondary small mb-0">{{ $deposit->admin_notes }}</p>
                        </div>
                    @endif

                    <!-- Section 20: Support Section with Pre-filled Message -->
                    @php
                        $supportMsg = rawurlencode("Hello AVC Finance Team, I need assistance with deposit request {$deposit->deposit_code}. User ID: #{$user->id}, Method: {$deposit->methodLabel()}, Amount: $" . number_format($deposit->amount, 2) . ", Status: {$deposit->formattedStatusLabel()}.");
                    @endphp
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h6 class="fw-bold text-dark mb-2">Need Assistance?</h6>
                        <p class="text-muted small mb-3">Our Finance Team is available 24/7 to assist with your request.</p>
                        
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

<script>
function depositDetailApp() {
    return {
        timerFormatted: '29:45',
        expiresAt: '{{ $deposit->expires_at ? $deposit->expires_at->toIso8601String() : "" }}',

        init() {
            if (this.expiresAt) {
                this.startTimer();
            }
        },

        startTimer() {
            const target = new Date(this.expiresAt).getTime();
            const interval = setInterval(() => {
                const now = new Date().getTime();
                const diff = target - now;

                if (diff <= 0) {
                    clearInterval(interval);
                    this.timerFormatted = 'Expired';
                    return;
                }

                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                this.timerFormatted = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            }, 1000);
        },

        copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }
    }
}
</script>
@endsection
