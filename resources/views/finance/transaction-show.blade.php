@extends('layouts.main')

@section('title', 'Transaction #' . $transaction->reference . ' | Finance Center | ' . site_name())

@section('content')
<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Breadcrumb & Nav -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('finance.overview') }}" class="text-decoration-none">Finance Center</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finance.transactions') }}" class="text-decoration-none">Transaction History</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $transaction->reference }}</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <!-- Top Status Banner -->
                    <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                        <div>
                            <span class="text-muted small d-block">Transaction Reference</span>
                            <h4 class="fw-bold text-dark mb-0">{{ $transaction->reference }}</h4>
                        </div>
                        <span class="badge {{ $transaction->statusBadgeClass() }} fs-6 px-3 py-2 rounded-3">
                            {{ $transaction->formattedStatusLabel() }}
                        </span>
                    </div>

                    <!-- Amount Display -->
                    <div class="text-center py-3 bg-light rounded-4 mb-4">
                        <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Transaction Amount</span>
                        <h1 class="display-5 fw-bold mb-1 {{ $transaction->isCredit() ? 'text-success' : 'text-danger' }}">
                            {{ $transaction->signedAmount() }} <span class="fs-4 font-normal">AVC</span>
                        </h1>
                        <span class="text-muted small">≈ ${{ number_format($transaction->fiat_equivalent ?? $transaction->amount, 2) }} USD</span>
                    </div>

                    <!-- Ledger Details Grid -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i> Ledger Details</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <span class="text-muted small d-block">Date & Time</span>
                                <strong class="text-dark">{{ $transaction->created_at->format('M d, Y h:i A') }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <span class="text-muted small d-block">Category</span>
                                <strong class="text-dark">{{ ucfirst($transaction->category ?? $transaction->type) }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <span class="text-muted small d-block">Direction</span>
                                <strong class="{{ $transaction->isCredit() ? 'text-success' : 'text-danger' }}">
                                    {{ strtoupper($transaction->direction ?? ($transaction->isCredit() ? 'CREDIT' : 'DEBIT')) }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <span class="text-muted small d-block">Payment Channel / Method</span>
                                <strong class="text-dark">{{ $transaction->payment_method ?? 'Platform AVC Wallet' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Description & Notes -->
                    <div class="p-3 rounded-3 border mb-4">
                        <span class="text-muted small d-block mb-1">Description</span>
                        <p class="mb-0 text-dark fw-semibold">{{ $transaction->description }}</p>
                        @if($transaction->notes)
                            <hr class="my-2">
                            <span class="text-muted small d-block mb-1">Notes / Internal Reference</span>
                            <p class="mb-0 text-secondary small">{{ $transaction->notes }}</p>
                        @endif
                    </div>

                    <!-- Proof / Receipt Link if exists -->
                    @if($transaction->receipt_proof)
                        <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-20 d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-check-fill text-success fs-4"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size:0.9rem;">Payment Proof Attached</h6>
                                    <small class="text-muted">Verification document uploaded for this transaction</small>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $transaction->receipt_proof) }}" target="_blank" class="btn btn-sm btn-success fw-bold rounded-2">
                                View Proof
                            </a>
                        </div>
                    @endif

                    <!-- Back Button & Support -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('finance.transactions') }}" class="btn btn-outline-secondary fw-bold rounded-3">
                            ← Back to Ledger
                        </a>
                        <a href="mailto:finance@radiantdreamrealty.com?subject=Transaction Question #{{ $transaction->reference }}" class="btn btn-light text-primary fw-bold rounded-3">
                            <i class="bi bi-headset me-1"></i> Contact Support
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
