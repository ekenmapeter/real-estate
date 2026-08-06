@extends('layouts.main')

@section('title', 'Manage Request #' . $financeRequest->request_id . ' | Admin | ' . site_name())

@section('content')
<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Breadcrumb & Back -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Admin Panel</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.finance-requests.index') }}" class="text-decoration-none">Finance Requests</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $financeRequest->request_id }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.finance-requests.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold rounded-3">
                ← Back to Request List
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

        <div class="row g-4">
            
            <!-- LEFT COLUMN: Request Information & Evidence (col-lg-7) -->
            <div class="col-12 col-lg-7">
                
                <!-- Request Summary Header Card (Matching Image 2 Request Details Admin View) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary font-mono px-2.5 py-1 rounded-2">{{ $financeRequest->request_id }}</span>
                            <h4 class="fw-bold text-dark mb-0 mt-1">
                                {{ ucfirst($financeRequest->type) }} Request: {{ number_format($financeRequest->amount, 2) }} <span class="fs-6 font-normal text-muted">{{ $financeRequest->currency }}</span>
                            </h4>
                        </div>
                        <span class="badge {{ $financeRequest->statusBadgeClass() }} fs-6 px-3 py-2 rounded-3">
                            {{ $financeRequest->formattedStatusLabel() }}
                        </span>
                    </div>

                    <!-- User & Request Info Split -->
                    <div class="row g-3">
                        <div class="col-12 col-md-6 border-end">
                            <h6 class="fw-bold text-dark mb-2 text-uppercase small" style="letter-spacing: 0.5px;">User Information</h6>
                            <div class="mb-1"><span class="text-muted small">Name:</span> <strong class="text-dark">{{ $financeRequest->user->name ?? $financeRequest->sender_name }}</strong></div>
                            <div class="mb-1"><span class="text-muted small">Email:</span> <span class="text-dark">{{ $financeRequest->user->email ?? $financeRequest->sender_email }}</span></div>
                            <div class="mb-1"><span class="text-muted small">Country:</span> <span class="text-dark">{{ $financeRequest->country }}</span></div>
                            <div class="mb-1"><span class="text-muted small">Phone / Acc:</span> <span class="text-dark">{{ $financeRequest->sender_account }}</span></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-dark mb-2 text-uppercase small" style="letter-spacing: 0.5px;">Request Information</h6>
                            <div class="mb-1"><span class="text-muted small">Amount:</span> <strong class="text-dark">{{ number_format($financeRequest->amount, 2) }} {{ $financeRequest->currency }}</strong></div>
                            <div class="mb-1"><span class="text-muted small">Payment Method:</span> <span class="text-dark">{{ $financeRequest->payment_method }}</span></div>
                            <div class="mb-1"><span class="text-muted small">Account Name:</span> <span class="text-dark">{{ $financeRequest->sender_name }}</span></div>
                            <div class="mb-1"><span class="text-muted small">Requested At:</span> <span class="text-dark">{{ $financeRequest->created_at->format('M d, Y h:i A') }}</span></div>
                        </div>
                    </div>

                    @if($financeRequest->user_notes)
                        <div class="mt-3 pt-3 border-top">
                            <span class="text-muted small d-block">User Notes:</span>
                            <p class="mb-0 text-dark small bg-light p-2.5 rounded-3">{{ $financeRequest->user_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Admin Reviews Evidence Card (Matching Image 2 Admin Reviews Evidence) -->
                @if($financeRequest->payment_evidence)
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4 border-2 border-primary">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-check-fill text-success me-2"></i> User Uploaded Payment Evidence</h5>

                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small">Submitted At: {{ $financeRequest->evidence_submitted_at ? $financeRequest->evidence_submitted_at->format('M d, Y h:i A') : '' }}</span>
                                <a href="{{ asset('storage/' . $financeRequest->payment_evidence) }}" target="_blank" class="btn btn-sm btn-outline-primary fw-bold rounded-2">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Open Evidence File
                                </a>
                            </div>

                            @if($financeRequest->evidence_notes)
                                <div class="small">
                                    <span class="text-muted">Notes from User:</span>
                                    <strong class="text-dark d-block">{{ $financeRequest->evidence_notes }}</strong>
                                </div>
                            @endif
                        </div>

                        <!-- Image Preview Thumbnail if image -->
                        @if(Str::endsWith(strtolower($financeRequest->payment_evidence), ['.jpg', '.jpeg', '.png']))
                            <div class="text-center mb-4 p-2 border rounded-3 bg-light">
                                <img src="{{ asset('storage/' . $financeRequest->payment_evidence) }}" alt="Receipt Evidence" class="img-fluid rounded-3" style="max-height: 350px;">
                            </div>
                        @endif

                        @if($financeRequest->status !== 'completed')
                            <!-- Approval Action Buttons -->
                            <div class="d-flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.finance-requests.approve', $financeRequest->id) }}" class="flex-fill" onsubmit="return confirm('Approve this payment evidence and credit user wallet?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success fw-bold w-100 py-2.5 rounded-3 shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1.5"></i> Approve & Credit User Balance
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.finance-requests.reject', $financeRequest->id) }}" class="flex-fill" onsubmit="return confirm('Reject this request?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger fw-bold w-100 py-2.5 rounded-3">
                                        <i class="bi bi-x-circle me-1.5"></i> Reject Request
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-success mb-0 rounded-3">
                                <i class="bi bi-patch-check-fill me-2"></i> Approved & Completed on {{ $financeRequest->completed_at ? $financeRequest->completed_at->format('M d, Y h:i A') : '' }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN: Provide Payment Instructions Form (col-lg-5) -->
            <div class="col-12 col-lg-5">
                
                <!-- Provide Payment Instructions Form Card (Matching Image 2 Provide Payment Instructions Admin) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-send-fill text-primary me-2"></i> Provide Payment Instructions</h5>
                    <p class="text-muted small mb-3">Input payment destination details & expiration timeframe for the user to make payment.</p>

                    <form method="POST" action="{{ route('admin.finance-requests.assign-instructions', $financeRequest->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Payment Method</label>
                            <input type="text" name="assigned_payment_method" class="form-control bg-light rounded-3" value="{{ old('assigned_payment_method', $financeRequest->assigned_payment_method ?? $financeRequest->payment_method) }}" required placeholder="e.g. GCash">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Account Name</label>
                            <input type="text" name="assigned_account_name" class="form-control bg-light rounded-3" value="{{ old('assigned_account_name', $financeRequest->assigned_account_name ?? 'RINNY P.') }}" required placeholder="e.g. RINNY P.">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Account / Phone / Wallet Number</label>
                            <input type="text" name="assigned_account_number" class="form-control bg-light rounded-3" value="{{ old('assigned_account_number', $financeRequest->assigned_account_number ?? '09658726718') }}" required placeholder="e.g. 09658726718">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-7">
                                <label class="form-label fw-semibold text-dark small">Reference Code</label>
                                <input type="text" name="assigned_reference" class="form-control bg-light rounded-3" value="{{ old('assigned_reference', $financeRequest->assigned_reference ?? 'RDR' . date('YmdHis')) }}" required>
                            </div>
                            <div class="col-5">
                                <label class="form-label fw-semibold text-dark small">Expiration Time</label>
                                <select name="expiration_minutes" class="form-select bg-light rounded-3" required>
                                    <option value="20" selected>20 Minutes</option>
                                    <option value="30">30 Minutes</option>
                                    <option value="60">1 Hour</option>
                                    <option value="120">2 Hours</option>
                                    <option value="1440">24 Hours</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">Instructions for User</label>
                            <textarea name="assigned_instructions" rows="3" class="form-control bg-light rounded-3" required>{{ old('assigned_instructions', $financeRequest->assigned_instructions ?? "• Please send the exact amount.\n• Do not include any remarks.\n• Upload your payment receipt before the timer expires.") }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold w-100 py-2.5 rounded-3 shadow-sm" style="background:#2563eb;">
                            <i class="bi bi-send-check-fill me-2"></i> Send to User
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
