@extends('layouts.main')

@section('title', 'Request #' . $financeRequest->request_id . ' | Finance Team | ' . site_name())

@section('content')
<style>
    .timer-badge {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: 1px;
    }
    .copy-btn {
        cursor: pointer;
        transition: color 0.15s;
    }
    .copy-btn:hover {
        color: #2563eb;
    }
</style>

<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Breadcrumb & Back -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('finance.overview') }}" class="text-decoration-none">Finance Center</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('finance.team.index') }}" class="text-decoration-none">Finance Team</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $financeRequest->request_id }}</li>
                </ol>
            </nav>
            <a href="{{ route('finance.team.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3">
                ← Back to Requests
            </a>
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

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <!-- 1. Request Overview Header Card -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-3 border-bottom mb-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-mono px-2.5 py-1 rounded-2">{{ $financeRequest->request_id }}</span>
                                <span class="text-muted small">• Submitted {{ $financeRequest->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <h3 class="fw-bold text-dark mb-0">
                                {{ ucfirst($financeRequest->type) }} Request: {{ number_format($financeRequest->amount, 2) }} <span class="fs-5 font-normal text-muted">{{ $financeRequest->currency }}</span>
                            </h3>
                        </div>

                        <div>
                            <span class="badge {{ $financeRequest->statusBadgeClass() }} fs-6 px-3 py-2 rounded-3">
                                {{ $financeRequest->formattedStatusLabel() }}
                            </span>
                        </div>
                    </div>

                    <!-- Summary Grid -->
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block mb-1">Country</span>
                            <strong class="text-dark">{{ $financeRequest->country }}</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block mb-1">Payment Method</span>
                            <strong class="text-dark">{{ $financeRequest->payment_method }}</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block mb-1">Sender Account</span>
                            <strong class="text-dark">{{ $financeRequest->sender_account }}</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block mb-1">Sender Name</span>
                            <strong class="text-dark">{{ $financeRequest->sender_name }}</strong>
                        </div>
                    </div>
                </div>

                <!-- 2. STATUS STEP 4: Under Review Notice (When Admin hasn't assigned instructions yet) -->
                @if($financeRequest->status === 'under_review')
                    <div class="card border-0 rounded-4 shadow-sm bg-warning bg-opacity-10 border border-warning border-opacity-20 p-4 mb-4 text-center">
                        <div class="mb-2 text-warning">
                            <i class="bi bi-clock-history display-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Request Under Review by Finance Team</h5>
                        <p class="text-muted small mb-0" style="max-width: 500px; margin: 0 auto;">
                            Your request has been logged successfully! Our finance desk operator is currently preparing your payment details. You will receive notification instructions here shortly.
                        </p>
                    </div>
                @endif

                <!-- 3. STATUS STEP 5: Payment Instructions Received Card (Matching Image 2 Step 5) -->
                @if($financeRequest->status === 'payment_instructions_assigned')
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4 border-2 border-primary">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                            <div>
                                <span class="badge bg-success bg-opacity-15 text-success fw-bold px-2.5 py-1 rounded-2 mb-1">Approved for Payment</span>
                                <h5 class="fw-bold text-dark mb-0">Payment Details Received</h5>
                            </div>

                            <!-- Interactive Expiration Countdown Timer -->
                            @if($financeRequest->expires_at)
                                <div class="text-md-end bg-danger bg-opacity-10 px-3 py-2 rounded-3 text-danger border border-danger border-opacity-20">
                                    <span class="small d-block fw-semibold" style="font-size:0.75rem;">Complete payment within</span>
                                    <div class="timer-badge" id="countdownTimer" data-seconds="{{ $financeRequest->remainingSeconds() }}">--:--</div>
                                </div>
                            @endif
                        </div>

                        <!-- Payment Details Grid -->
                        <div class="p-3 rounded-3 bg-light border mb-4">
                            <h6 class="fw-bold text-dark mb-3 text-uppercase small" style="letter-spacing: 0.5px;">Payment Details</h6>

                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted small">Payment Method:</span>
                                <strong class="text-dark">{{ $financeRequest->assigned_payment_method ?? $financeRequest->payment_method }}</strong>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted small">Account Name:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-dark" id="assignedName">{{ $financeRequest->assigned_account_name }}</strong>
                                    <button class="btn btn-sm btn-link p-0 text-muted copy-btn" onclick="copyText('assignedName')"><i class="bi bi-copy"></i></button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted small">Account / Phone Number:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-primary fs-6 font-mono" id="assignedNum">{{ $financeRequest->assigned_account_number }}</strong>
                                    <button class="btn btn-sm btn-link p-0 text-muted copy-btn" onclick="copyText('assignedNum')"><i class="bi bi-copy"></i></button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted small">Reference Code:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-dark font-mono" id="assignedRef">{{ $financeRequest->assigned_reference }}</strong>
                                    <button class="btn btn-sm btn-link p-0 text-muted copy-btn" onclick="copyText('assignedRef')"><i class="bi bi-copy"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-20 mb-4">
                            <h6 class="fw-bold text-dark mb-1 small"><i class="bi bi-exclamation-triangle-fill text-warning me-1.5"></i> Special Instructions:</h6>
                            <p class="mb-0 text-dark small">{{ $financeRequest->assigned_instructions }}</p>
                        </div>

                        <a href="#uploadSection" class="btn btn-primary fw-bold w-100 py-2.5 rounded-3 shadow-sm" style="background:#2563eb;">
                            <i class="bi bi-upload me-2"></i> Upload Payment Evidence
                        </a>
                    </div>
                @endif

                <!-- 4. STATUS STEP 6: Upload Payment Evidence Card (Matching Image 2 Step 6) -->
                @if(in_array($financeRequest->status, ['payment_instructions_assigned', 'evidence_submitted', 'under_verification']))
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4" id="uploadSection">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-arrow-up-fill text-primary me-2"></i> Upload Payment Evidence</h5>

                        @if($financeRequest->payment_evidence)
                            <!-- Already Submitted Display -->
                            <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-20 d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size:0.9rem;">Payment Proof Uploaded</h6>
                                        <small class="text-muted">Submitted {{ $financeRequest->evidence_submitted_at ? $financeRequest->evidence_submitted_at->format('M d, Y h:i A') : '' }}</small>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $financeRequest->payment_evidence) }}" target="_blank" class="btn btn-sm btn-success fw-bold rounded-2">
                                    View Uploaded File
                                </a>
                            </div>

                            <p class="text-muted small text-center mb-0">Our finance desk is currently verifying your payment evidence. Funds will be credited to your account upon confirmation!</p>
                        @else
                            <!-- Upload Form -->
                            <form method="POST" action="{{ route('finance.team.evidence.store', $financeRequest->request_id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark small">Upload Receipt / Proof Image or PDF</label>
                                    <input type="file" name="receipt" class="form-control bg-light rounded-3" accept="image/jpeg,image/png,application/pdf" required>
                                    <span class="form-text text-muted small">Supported: JPG, PNG, PDF (Max 10MB)</span>
                                </div>

                                <div class="mb-4">
                                    <label for="evidence_notes" class="form-label fw-semibold text-dark small">Additional Notes (Optional)</label>
                                    <input type="text" name="evidence_notes" id="evidence_notes" class="form-control bg-light rounded-3" placeholder="Payment sent. Reference # 123456789. Please confirm.">
                                </div>

                                <button type="submit" class="btn btn-primary fw-bold w-100 py-2.5 rounded-3 shadow-sm" style="background:#2563eb;">
                                    <i class="bi bi-check-circle-fill me-2"></i> Submit Evidence
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                <!-- 5. STATUS STEP 7: Completed Request Banner -->
                @if($financeRequest->status === 'completed')
                    <div class="card border-0 rounded-4 shadow-sm bg-success bg-opacity-10 border border-success border-opacity-20 p-4 mb-4 text-center">
                        <div class="mb-2 text-success">
                            <i class="bi bi-patch-check-fill display-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Payment Confirmed & Completed!</h4>
                        <p class="text-muted small mb-3">
                            Funds have been successfully credited to your wallet balance.
                        </p>
                        <a href="{{ route('finance.transactions') }}" class="btn btn-sm btn-success fw-bold rounded-2 px-4 py-2">
                            View Transaction History →
                        </a>
                    </div>
                @endif

                <!-- Cancel Request Button if Pending -->
                @if(in_array($financeRequest->status, ['under_review', 'payment_instructions_assigned']))
                    <div class="text-center pt-2">
                        <form method="POST" action="{{ route('finance.team.cancel', $financeRequest->request_id) }}" onsubmit="return confirm('Are you sure you want to cancel this finance request?')">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger text-decoration-none small fw-semibold">
                                <i class="bi bi-x-circle me-1"></i> Cancel Request
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

<script>
    // Copy button helper
    function copyText(elementId) {
        var el = document.getElementById(elementId);
        if (!el) return;
        var text = el.innerText || el.textContent;
        navigator.clipboard.writeText(text).then(function() {
            alert('Copied to clipboard: ' + text);
        });
    }

    // Countdown Timer JS
    document.addEventListener('DOMContentLoaded', function() {
        var timerEl = document.getElementById('countdownTimer');
        if (!timerEl) return;

        var secondsLeft = parseInt(timerEl.getAttribute('data-seconds'), 10);

        function updateDisplay() {
            if (secondsLeft <= 0) {
                timerEl.textContent = 'EXPIRED';
                timerEl.classList.add('text-muted');
                return;
            }
            var m = Math.floor(secondsLeft / 60);
            var s = secondsLeft % 60;
            timerEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
            secondsLeft--;
        }

        updateDisplay();
        setInterval(updateDisplay, 1000);
    });
</script>
@endsection
