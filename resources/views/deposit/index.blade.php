@extends('layouts.main')

@section('title', 'Deposit / Buy AVC | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
.balance-card-gradient {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%);
    }
    .check-icon-green { color: #10b981; }
    .check-icon-purple { color: #8b5cf6; }
    .nav-pill-active { background-color: #f1f5f9; font-weight: 700; color: #0f172a; }
    .channel-card-hover { transition: transform 0.2s ease, shadow 0.2s ease; cursor: pointer; }
    .channel-card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08); }
    .pm-row { transition: background-color 0.15s ease; cursor: pointer; }
    .pm-row:hover { background-color: #f8fafc; }
    .bottom-mobile-nav {
        position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; border-top: 1px solid #e2e8f0; z-index: 1000;
    }
</style>

<div class="container-fluid px-0 user-shell-content" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Top Header Row -->
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Deposit / Buy AVC</h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Add AVC to your account through the Finance Team or purchase AVC from verified sellers through Admin Escrow.</p>
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
            
            <!-- LEFT MAIN COLUMN (col-lg-8) -->
            <div class="col-12 col-lg-8">
                
                <!-- 1. Available AVC Balance Card (Matching Photo 2 Top Left) -->
                <div class="card border-0 rounded-4 text-white p-4 mb-4 shadow-sm balance-card-gradient">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-3 gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="rounded-3 bg-white bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                    <i class="bi bi-wallet2 text-white fs-5"></i>
                                </div>
                                <span class="text-white-50 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Available AVC Balance</span>
                                <button type="button" class="btn btn-link text-white-50 p-0 border-0 ms-1"><i class="bi bi-eye-fill"></i></button>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mb-1">
                                <h1 class="fw-bold text-white mb-0 display-5">{{ number_format($availableBalance, 0) }} <span class="fs-4 font-normal text-white-50">AVC</span></h1>
                            </div>
                            <span class="text-white-50 small d-block mb-2">≈ ${{ number_format($estimatedUsd, 2) }} USD</span>
                            <span class="badge bg-white bg-opacity-15 text-white fw-normal px-2.5 py-1 rounded-2" style="font-size:0.78rem;">Available for Use: <strong>{{ number_format($availableBalance, 0) }} AVC</strong></span>
                        </div>

                        <!-- Top-Right Pending & Escrow Stats -->
                        <div class="text-md-end text-white-50 small">
                            <div class="mb-1">Pending AVC: <strong class="text-white">{{ number_format($pendingAvc, 0) }} AVC</strong></div>
                            <div>Escrow AVC: <strong class="text-white">{{ number_format($escrowAvc, 0) }} AVC</strong></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                        <a href="{{ route('deposit.channel', 'bank_transfer') }}" class="btn btn-primary fw-bold rounded-3 px-4 py-2.5 shadow-sm" style="background: #2563eb; border: none; font-size: 0.92rem;">
                            <i class="bi bi-plus-circle-fill me-1.5"></i> Deposit Through Finance Team
                        </a>
                        <a href="{{ route('avc-marketplace.index') }}" class="btn btn-outline-light fw-bold rounded-3 px-4 py-2.5" style="font-size: 0.92rem; background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2);">
                            <i class="bi bi-cart3 me-1.5"></i> Buy AVC From Marketplace
                        </a>
                    </div>
                </div>

                <!-- 2. Choose How to Add AVC Section (Matching Photo 2 Middle Left) -->
                <h5 class="fw-bold text-dark mb-3">Choose How to Add AVC</h5>

                <div class="row g-3 mb-4">
                    <!-- Card 1: Deposit Through Finance Team -->
                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 d-flex flex-column" style="background: #f0fdf4;">
                            <div class="rounded-circle bg-emerald-100 p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7;">
                                <i class="bi bi-bank fs-4" style="color: #059669;"></i>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">Deposit Through Finance Team</h5>
                            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.45;">Fund your account using official company payment channels in your local currency or via crypto.</p>

                            <!-- Checkmark List -->
                            <ul class="list-unstyled small mb-4 flex-grow-1" style="font-size: 0.82rem;">
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-green"></i> <span>Local bank transfer & e-wallets</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-green"></i> <span>International wire transfer</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-green"></i> <span>Cryptocurrency</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-green"></i> <span>Supported in multiple countries</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-green"></i> <span>Assisted by Finance Team</span></li>
                            </ul>

                            <a href="{{ route('deposit.channel', 'bank_transfer') }}" class="btn btn-emerald text-white fw-bold w-100 rounded-3 py-2.5 shadow-sm" style="background: #059669; border: none;">
                                Start Finance Deposit
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Buy AVC From Marketplace -->
                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 d-flex flex-column" style="background: #faf5ff;">
                            <div class="rounded-circle p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #f3e8ff;">
                                <i class="bi bi-bag-check fs-4" style="color: #7c3aed;"></i>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">Buy AVC From Marketplace</h5>
                            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.45;">Purchase AVC from verified sellers through our secure Admin Escrow protection.</p>

                            <!-- Checkmark List -->
                            <ul class="list-unstyled small mb-4 flex-grow-1" style="font-size: 0.82rem;">
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-purple"></i> <span>Verified sellers only</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-purple"></i> <span>Admin Escrow protection</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-purple"></i> <span>Compare best rates</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-purple"></i> <span>Multiple payment methods</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill check-icon-purple"></i> <span>Fast & secure delivery</span></li>
                            </ul>

                            <a href="{{ route('avc-marketplace.index') }}" class="btn btn-purple text-white fw-bold w-100 rounded-3 py-2.5 shadow-sm" style="background: #7c3aed; border: none;">
                                Browse AVC Marketplace
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Escrow Banner Notice (Matching Photo 2) -->
                <div class="card border-0 rounded-4 shadow-sm p-3 mb-4" style="background: #eff6ff; border-left: 4px solid #3b82f6;">
                    <div class="d-flex align-items-center gap-2.5 text-primary" style="font-size: 0.82rem;">
                        <i class="bi bi-shield-check fs-5 flex-shrink-0"></i>
                        <span>All payments and purchases are secured by the AVC Admin Escrow System. Buyers and sellers do not communicate directly on the platform.</span>
                    </div>
                </div>

                <!-- 4. Official AVC Payment Channels (Finance Team) (Matching Photo 2 Lower Left) -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Official AVC Payment Channels (Finance Team)</h6>
                        <span class="text-muted small" style="font-size: 0.78rem;">These are the available deposit methods through our Finance Team.</span>
                    </div>
                    <a href="{{ route('deposit.channel', 'bank_transfer') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3" style="font-size: 0.75rem;">View All</a>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Channel 1: Bank Transfer / GCash -->
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('deposit.channel', 'bank_transfer') }}" class="card border-0 rounded-4 shadow-sm bg-white p-3 text-center text-decoration-none channel-card-hover h-100">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-bank fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.82rem;">Bank Transfer / GCash</h6>
                            <span class="badge bg-success text-white fw-semibold px-2 py-0.5 rounded-pill mx-auto mt-auto" style="font-size: 0.7rem;">Active</span>
                        </a>
                    </div>

                    <!-- Channel 2: International Wire Transfer -->
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('deposit.channel', 'wire_transfer') }}" class="card border-0 rounded-4 shadow-sm bg-white p-3 text-center text-decoration-none channel-card-hover h-100">
                            <div class="rounded-circle bg-info bg-opacity-10 text-info p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-globe fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.82rem;">International Wire Transfer</h6>
                            <span class="badge bg-primary text-white fw-semibold px-2 py-0.5 rounded-pill mx-auto mt-auto" style="font-size: 0.7rem;">Active</span>
                        </a>
                    </div>

                    <!-- Channel 3: Cryptocurrency -->
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('deposit.channel', 'crypto') }}" class="card border-0 rounded-4 shadow-sm bg-white p-3 text-center text-decoration-none channel-card-hover h-100">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-currency-bitcoin fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.82rem;">Cryptocurrency</h6>
                            <span class="badge bg-success text-white fw-semibold px-2 py-0.5 rounded-pill mx-auto mt-auto" style="font-size: 0.7rem;">Active</span>
                        </a>
                    </div>

                    <!-- Channel 4: Other Local Methods (Expandable) -->
                    <div class="col-6 col-sm-3">
                        <button type="button" class="card border-0 rounded-4 shadow-sm bg-white p-3 text-center w-100 h-100 channel-card-hover" data-bs-toggle="collapse" data-bs-target="#otherLocalMethodsCollapse" aria-expanded="false" aria-controls="otherLocalMethodsCollapse" style="border: 0; background: #fff;">
                            <div class="rounded-circle bg-purple bg-opacity-10 p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(147,51,234,0.1); color: #9333ea;">
                                <i class="bi bi-three-dots fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.82rem;">More Payment Options</h6>
                            <span class="badge bg-success text-white fw-semibold px-2 py-0.5 rounded-pill mx-auto mt-auto" style="font-size: 0.7rem;"><i class="bi bi-chevron-down me-1"></i>Active</span>
                        </button>
                    </div>

                    <!-- Expanded Section: Remaining Local Payment Methods -->
                    <div class="col-12 collapse" id="otherLocalMethodsCollapse">
                        <div class="card border-0 rounded-4 shadow-sm bg-white mt-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center gap-2 px-3 py-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="bi bi-wallet2 text-primary me-2"></i>Select a Payment Option</h6>
                                    <span class="text-muted small" style="font-size: 0.75rem;">Pay securely or request an assisted Finance Team deposit.</span>
                                </div>
                                <span class="badge bg-light text-muted fw-semibold px-2 py-1 rounded-pill flex-shrink-0" style="font-size: 0.7rem;">2 Options</span>
                            </div>

                            <!-- Card Payment -->
                            <a href="{{ route('deposit.channel', 'credit_card') }}" class="pm-row d-flex align-items-center gap-3 px-3 py-3 text-decoration-none border-bottom">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(147,51,234,0.1); color: #9333ea;">
                                    <i class="bi bi-credit-card-2-front fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong class="text-dark d-block" style="font-size: 0.88rem;">Card Payment</strong>
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Visa, Mastercard, AMEX</span>
                                </div>
                                <span class="badge fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.68rem; background: #dcfce7; color: #059669;">Instant</span>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>

                            <!-- Finance Team Request -->
                            <a href="{{ route('deposit.channel', 'bank_transfer') }}" class="pm-row d-flex align-items-center gap-3 px-3 py-3 text-decoration-none">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(37,99,235,0.1); color: #2563eb;">
                                    <i class="bi bi-headset fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong class="text-dark d-block" style="font-size: 0.88rem;">Finance Team Request</strong>
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Assisted manual deposit request</span>
                                </div>
                                <span class="badge fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.68rem; background: #eff6ff; color: #2563eb;">Assisted</span>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3" style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 10px 14px;">
                            <i class="bi bi-info-circle text-primary flex-shrink-0"></i>
                            <span class="text-muted small" style="font-size: 0.75rem;">Both options use the official Finance Team deposit flow. Only send payments to the accounts provided in your deposit request.</span>
                        </div>
                    </div>
                </div>

                <!-- 5. Why Deposit with Our Finance Team? (Matching Photo 2 Bottom Left) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3">Why Deposit with Our Finance Team?</h6>
                    
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-shield-check text-emerald-600 fs-5" style="color: #059669;"></i>
                                <div>
                                    <strong class="text-dark d-block small mb-0.5">Secure & Official</strong>
                                    <span class="text-muted" style="font-size: 0.72rem; line-height: 1.35; display: block;">All payments are made to official company accounts.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-clock-history text-primary fs-5"></i>
                                <div>
                                    <strong class="text-dark d-block small mb-0.5">Fast Verification</strong>
                                    <span class="text-muted" style="font-size: 0.72rem; line-height: 1.35; display: block;">Our team verifies and credits your account quickly.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-globe text-purple-600 fs-5" style="color: #7c3aed;"></i>
                                <div>
                                    <strong class="text-dark d-block small mb-0.5">Global Support</strong>
                                    <span class="text-muted" style="font-size: 0.72rem; line-height: 1.35; display: block;">Multiple countries and payment options supported.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-headset text-warning fs-5"></i>
                                <div>
                                    <strong class="text-dark d-block small mb-0.5">Admin Assistance</strong>
                                    <span class="text-muted" style="font-size: 0.72rem; line-height: 1.35; display: block;">Our finance team is ready to assist you anytime.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Bottom Warning Callout (Matching Photo 2 Bottom Alert) -->
                <div class="alert alert-warning border-0 rounded-4 p-3.5 shadow-sm d-flex align-items-center gap-3 mb-0" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-3 flex-shrink-0"></i>
                    <span class="text-dark small" style="font-size: 0.82rem; line-height: 1.45;">
                        <strong>Important:</strong> Only send payments to the official accounts provided in your deposit request. Do not send funds to anyone claiming to be an administrator or seller outside the platform.
                    </span>
                </div>

            </div>

            <!-- RIGHT SIDEBAR COLUMN (col-lg-4) (Matching Photo 2 Right Column) -->
            <div class="col-12 col-lg-4">
                
                <!-- 1. Active Transaction Card (Matching Photo 2 Top Right) -->
                @php
                    $activeDeposit = \App\Models\Deposit::where('user_id', $user->id)
                        ->whereIn('status', ['submitted', 'awaiting_finance_review', 'payment_instructions_assigned', 'awaiting_payment', 'payment_submitted', 'under_verification', 'additional_info_required'])
                        ->latest()
                        ->first();
                @endphp

                @if($activeDeposit)
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">Active Transaction</h6>
                            <span class="badge bg-warning bg-opacity-20 text-warning-800 fw-semibold px-2.5 py-1 rounded-2 small" style="background:#fef3c7; color:#92400e;">
                                <i class="bi bi-hourglass-split me-1"></i> {{ $activeDeposit->formattedStatusLabel() }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Request ID</span>
                            <code class="fw-bold text-dark fs-6">{{ $activeDeposit->deposit_code }}</code>
                        </div>

                        <div class="row g-2 mb-3 small">
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.75rem;">Amount</span>
                                <strong class="text-dark">${{ number_format($activeDeposit->amount, 2) }} USD</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.75rem;">Estimated AVC</span>
                                <strong class="text-success">{{ number_format($activeDeposit->net_avc ?: $activeDeposit->amount, 0) }} AVC</strong>
                            </div>
                            <div class="col-6 mt-2">
                                <span class="text-muted d-block" style="font-size: 0.75rem;">Method</span>
                                <strong class="text-dark">{{ $activeDeposit->methodLabel() }}</strong>
                            </div>
                            <div class="col-6 mt-2">
                                <span class="text-muted d-block" style="font-size: 0.75rem;">Submitted</span>
                                <span class="text-muted">{{ $activeDeposit->created_at->format('M d, Y H:i A') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('deposit.show', $activeDeposit->id) }}" class="btn btn-outline-primary fw-bold w-100 rounded-3 py-2" style="font-size: 0.85rem;">
                            View Request
                        </a>
                    </div>
                @endif

                <!-- 2. Recent AVC Activity Widget (Matching Photo 2 Middle Right) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Recent AVC Activity</h6>
                        <a href="{{ route('deposit.index', ['filter' => 'all']) }}" class="text-primary text-decoration-none fw-semibold small" style="font-size: 0.78rem;">View All</a>
                    </div>

                    <!-- Activity Filter Pills -->
                    <div class="d-flex gap-1 mb-3 bg-light p-1 rounded-3">
                        <a href="{{ route('deposit.index', ['filter' => 'all']) }}" class="btn btn-sm flex-fill fw-semibold rounded-2 {{ $filter === 'all' ? 'btn-white shadow-sm font-bold text-dark bg-white' : 'text-muted border-0' }}" style="font-size: 0.72rem;">All</a>
                        <a href="{{ route('deposit.index', ['filter' => 'finance']) }}" class="btn btn-sm flex-fill fw-semibold rounded-2 {{ $filter === 'finance' ? 'btn-white shadow-sm font-bold text-dark bg-white' : 'text-muted border-0' }}" style="font-size: 0.72rem;">Finance Deposits</a>
                        <a href="{{ route('deposit.index', ['filter' => 'marketplace']) }}" class="btn btn-sm flex-fill fw-semibold rounded-2 {{ $filter === 'marketplace' ? 'btn-white shadow-sm font-bold text-dark bg-white' : 'text-muted border-0' }}" style="font-size: 0.72rem;">Marketplace</a>
                    </div>

                    <!-- Activity List Items -->
                    @if($recentActivity->count() > 0)
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($recentActivity->take(5) as $act)
                                <a href="{{ $act['url'] }}" class="p-2.5 rounded-3 border bg-light text-decoration-none d-block channel-card-hover">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width:28px; height:28px; background: {{ $act['type'] === 'finance_deposit' ? '#dcfce7' : '#f3e8ff' }}; color: {{ $act['type'] === 'finance_deposit' ? '#059669' : '#7c3aed' }};">
                                                <i class="bi {{ $act['type'] === 'finance_deposit' ? 'bi-bank' : 'bi-bag' }}" style="font-size:0.75rem;"></i>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 0.78rem;">{{ $act['title'] }}</strong>
                                                <code class="text-muted" style="font-size: 0.7rem;">{{ $act['code'] }}</code>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 0.72rem;">
                                        <span class="badge {{ $act['badge_class'] }} px-2 py-0.5">{{ $act['status_label'] }}</span>
                                        <div class="text-end">
                                            <strong class="text-dark d-block">${{ number_format($act['amount'], 2) }} USD</strong>
                                            <span class="text-muted">{{ number_format($act['avc'], 0) }} AVC</span>
                                        </div>
                                    </div>
                                    <span class="text-muted d-block mt-1" style="font-size: 0.68rem;">{{ $act['created_at']->format('M d, Y &bull; h:i A') }}</span>
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('deposit.index', ['filter' => 'all']) }}" class="text-primary fw-bold text-decoration-none small d-block text-center mt-3" style="font-size: 0.78rem;">
                            View All Activity <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @else
                        <div class="text-center py-4 text-muted small">No recent AVC activity.</div>
                    @endif
                </div>

                <!-- 3. Need Help? Card (Matching Photo 2 Bottom Right) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-1">Need Help?</h6>
                    <p class="text-muted small mb-3" style="font-size: 0.78rem;">Our support team is here to help you with your deposit or purchase.</p>

                    <div class="d-flex flex-column gap-2">
                        <a href="https://wa.me/?text=Hello%20AVC%20Support,%20I%20need%20help%20with%20a%20deposit" target="_blank" class="btn btn-outline-success btn-sm fw-bold rounded-3 py-2 text-start px-3">
                            <i class="bi bi-whatsapp me-2"></i> WhatsApp Support
                        </a>
                        <a href="https://t.me/" target="_blank" class="btn btn-outline-info btn-sm fw-bold rounded-3 py-2 text-start px-3">
                            <i class="bi bi-telegram me-2"></i> Telegram Support
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm fw-bold rounded-3 py-2 text-start px-3">
                            <i class="bi bi-headset me-2"></i> Support Center
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var collapseEl = document.getElementById('otherLocalMethodsCollapse');
    if (!collapseEl) return;
    var trigger = document.querySelector('[data-bs-target="#otherLocalMethodsCollapse"]');
    function setChevron(open) {
        var chevron = trigger ? trigger.querySelector('.bi-chevron-down') : null;
        if (chevron) chevron.classList.toggle('bi-chevron-up', open);
    }
    collapseEl.addEventListener('show.bs.collapse', function () { setChevron(true); });
    collapseEl.addEventListener('hide.bs.collapse', function () { setChevron(false); });
});
</script>
@endsection
