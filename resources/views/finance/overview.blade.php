@extends('layouts.main')

@section('title', 'Finance Center | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
.finance-hero-card {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%);
        border: none;
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08) !important;
    }
    .nav-pill-custom {
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .nav-pill-custom.active, .nav-pill-custom:hover {
        background-color: #ffffff;
        color: #2563eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
</style>

<div class="container-fluid px-0 user-shell-content" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Header Title Row -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Finance Center Overview</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Monitor your AVC wallet balance, track pending requests, manage funding routes & review complete financial activity.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('finance.transactions') }}" class="btn btn-outline-primary fw-bold rounded-3 px-3 py-2">
                    <i class="bi bi-clock-history me-1.5"></i> Full Transaction Ledger
                </a>
                <a href="{{ route('deposit.index') }}" class="btn btn-primary fw-bold rounded-3 px-3 py-2 shadow-sm" style="background: #2563eb; border:none;">
                    <i class="bi bi-plus-circle-fill me-1.5"></i> Add / Buy AVC
                </a>
            </div>
        </div>

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            
            <!-- LEFT MAIN COLUMN (col-lg-8) -->
            <div class="col-12 col-lg-8">
                
                <!-- 1. Available AVC Balance Hero Card (Matching Deposit/Withdrawal System Visuals) -->
                <div class="card border-0 rounded-4 text-white p-4 mb-4 shadow-sm finance-hero-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-3 gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="rounded-3 bg-white bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                    <i class="bi bi-wallet2 text-white fs-5"></i>
                                </div>
                                <span class="text-white-50 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Available AVC Balance</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mb-1">
                                <h1 class="fw-bold text-white mb-0 display-5">{{ number_format($availableBalance, 0) }} <span class="fs-4 font-normal text-white-50">AVC</span></h1>
                            </div>
                            <span class="text-white-50 small d-block mb-2">≈ ${{ number_format($estimatedUsd, 2) }} USD</span>
                            <span class="badge bg-white bg-opacity-15 text-white fw-normal px-2.5 py-1 rounded-2" style="font-size:0.78rem;">
                                Account Status: <strong class="text-success"><i class="bi bi-patch-check-fill me-1"></i> Verified & Active</strong>
                            </span>
                        </div>

                        <!-- Right Stats breakdown -->
                        <div class="text-md-end text-white-50 small bg-white bg-opacity-10 p-3 rounded-3">
                            <div class="mb-2">Pending Deposits: <strong class="text-white">{{ number_format($pendingDeposits, 0) }} AVC</strong></div>
                            <div class="mb-2">Pending Withdrawals: <strong class="text-white">{{ number_format($pendingWithdrawals, 0) }} AVC</strong></div>
                            <div>Locked in Escrow: <strong class="text-warning">{{ number_format($escrowAvc, 0) }} AVC</strong></div>
                        </div>
                    </div>

                    <!-- Quick Funding Action Buttons (Matching Image 1) -->
                    <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top border-white border-opacity-10">
                        
                        <!-- Deposit Funds Dropdown -->
                        <div class="dropdown flex-fill">
                            <button class="btn btn-light fw-bold rounded-3 px-3 py-2 w-100 text-primary shadow-sm dropdown-toggle d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:0.88rem;">
                                <span><i class="bi bi-arrow-down-circle-fill me-1.5"></i> Deposit Funds</span>
                            </button>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2 mt-1">
                                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="{{ route('deposit.channel', 'usdt_trc20') }}"><i class="bi bi-currency-bitcoin text-warning me-2"></i> 1. Crypto Deposit (USDT/USDC)</a></li>
                                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="{{ route('deposit.channel', 'wire_transfer') }}"><i class="bi bi-bank text-primary me-2"></i> 2. Wire Transfer / Bank</a></li>
                                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="{{ route('deposit.channel', 'card_payment') }}"><i class="bi bi-credit-card-fill text-info me-2"></i> 3. Credit / Debit Card</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-2 py-2 fw-bold text-primary" href="{{ route('finance.team.create', ['type' => 'deposit']) }}"><i class="bi bi-people-fill me-2"></i> 4. Finance Team Assistance</a></li>
                            </ul>
                        </div>

                        <!-- Withdraw Funds Dropdown -->
                        <div class="dropdown flex-fill">
                            <button class="btn btn-outline-light fw-bold rounded-3 px-3 py-2 w-100 dropdown-toggle d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:0.88rem; background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.2);">
                                <span><i class="bi bi-arrow-up-circle-fill me-1.5"></i> Withdraw Funds</span>
                            </button>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2 mt-1">
                                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="{{ route('withdraw.index') }}"><i class="bi bi-bank2 text-primary me-2"></i> Bank Withdrawal</a></li>
                                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="{{ route('withdraw.index') }}"><i class="bi bi-currency-bitcoin text-warning me-2"></i> Crypto Cash-Out</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-2 py-2 fw-bold text-danger" href="{{ route('finance.team.create', ['type' => 'withdrawal']) }}"><i class="bi bi-people-fill me-2"></i> Finance Team Assistance</a></li>
                            </ul>
                        </div>

                        <!-- Finance Team Direct Link -->
                        <a href="{{ route('finance.team.index') }}" class="btn btn-outline-light fw-bold rounded-3 px-3 py-2 flex-fill" style="font-size:0.88rem; background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.2);">
                            <i class="bi bi-people-fill me-1.5 text-info"></i> Finance Team
                        </a>

                        <!-- AVC Marketplace -->
                        <a href="{{ route('marketplace') }}" class="btn btn-outline-light fw-bold rounded-3 px-3 py-2 flex-fill text-warning" style="font-size:0.88rem; background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.2);">
                            <i class="bi bi-lightning-charge-fill me-1.5"></i> AVC Marketplace
                        </a>
                    </div>
                </div>

                <!-- 2. Lifetime Financial Stats Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-3 p-3 shadow-sm bg-white hover-lift">
                            <span class="text-muted small d-block mb-1">Lifetime Deposits</span>
                            <h5 class="fw-bold text-success mb-0">+{{ number_format($lifetimeDeposits, 0) }} AVC</h5>
                            <span class="text-muted small" style="font-size:0.72rem;">≈ ${{ number_format($lifetimeDeposits, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-3 p-3 shadow-sm bg-white hover-lift">
                            <span class="text-muted small d-block mb-1">Lifetime Withdrawals</span>
                            <h5 class="fw-bold text-danger mb-0">-{{ number_format($lifetimeWithdrawals, 0) }} AVC</h5>
                            <span class="text-muted small" style="font-size:0.72rem;">≈ ${{ number_format($lifetimeWithdrawals, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-3 p-3 shadow-sm bg-white hover-lift">
                            <span class="text-muted small d-block mb-1">Fees Paid</span>
                            <h5 class="fw-bold text-dark mb-0">${{ number_format($totalFeesPaid, 2) }}</h5>
                            <span class="text-muted small" style="font-size:0.72rem;">Processing & Network</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 rounded-3 p-3 shadow-sm bg-white hover-lift">
                            <span class="text-muted small d-block mb-1">Daily Limit Left</span>
                            <h5 class="fw-bold text-primary mb-0">${{ number_format($remainingLimit, 0) }}</h5>
                            <span class="text-muted small" style="font-size:0.72rem;">of ${{ number_format($dailyLimit, 0) }} Daily</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Finance Team Requests Card (Matching Image 1) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i> Finance Team Requests</h5>
                            <small class="text-muted">Assisted local currency deposits & withdrawals managed directly with Finance Desk</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('finance.team.index') }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-3">View All Requests →</a>
                            <a href="{{ route('finance.team.create') }}" class="btn btn-sm btn-primary fw-bold rounded-3" style="background:#2563eb; border:none;">+ New Request</a>
                        </div>
                    </div>

                    @if($financeTeamRequests->isEmpty())
                        <div class="text-center py-4 text-muted bg-light rounded-3">
                            <i class="bi bi-inbox fs-3 d-block mb-1 text-muted"></i>
                            <p class="mb-2 small">You haven't submitted any Finance Team requests yet.</p>
                            <a href="{{ route('finance.team.create') }}" class="btn btn-sm btn-primary fw-bold rounded-2">Create Request</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Type & Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($financeTeamRequests as $fr)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $fr->request_id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($fr->type === 'deposit')
                                                        <i class="bi bi-arrow-down-left-circle-fill text-success fs-5"></i>
                                                    @else
                                                        <i class="bi bi-arrow-up-right-circle-fill text-danger fs-5"></i>
                                                    @endif
                                                    <div>
                                                        <span class="fw-semibold text-dark d-block" style="font-size:0.85rem;">{{ ucfirst($fr->type) }} Request</span>
                                                        <small class="text-muted" style="font-size:0.75rem;">{{ $fr->payment_method }} ({{ $fr->currency }})</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-dark">
                                                {{ number_format($fr->amount, 2) }} <small class="text-muted">{{ $fr->currency }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $fr->statusBadgeClass() }} px-2.5 py-1 rounded-2">
                                                    {{ $fr->formattedStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $fr->created_at->format('M d, Y') }}<br>
                                                <small>{{ $fr->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('finance.team.show', $fr->request_id) }}" class="btn btn-sm btn-light border rounded-2 text-primary fw-semibold">
                                                    View Details →
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- 4. Saved Withdrawal & Payment Methods Quick Card -->
                <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-wallet-fill text-primary me-2"></i> Saved Payment Methods</h5>
                            <small class="text-muted">Managed bank accounts, mobile wallets & crypto destinations for payouts</small>
                        </div>
                        <a href="{{ route('withdraw.index') }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-3">Manage Methods</a>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-bank2 text-primary fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size:0.88rem;">Bank Accounts</h6>
                                        <small class="text-muted" style="font-size:0.75rem;">{{ $methodsCount['bank_account'] }} Saved</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">Active</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-phone-vibrate text-success fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size:0.88rem;">Mobile Wallets</h6>
                                        <small class="text-muted" style="font-size:0.75rem;">{{ $methodsCount['mobile_wallet'] }} Saved (GCash)</small>
                                    </div>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Active</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-currency-bitcoin text-warning fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size:0.88rem;">Crypto Wallets</h6>
                                        <small class="text-muted" style="font-size:0.75rem;">{{ $methodsCount['crypto_wallet'] }} Saved (USDT/USDC)</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis rounded-pill">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Recent Financial Ledger Table (Last 10 Activity Records) -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-arrow-down-up text-primary me-2"></i> Recent Financial Ledger</h5>
                            <small class="text-muted">Real-time ledger updates across deposits, withdrawals, escrow & investments</small>
                        </div>
                        <a href="{{ route('finance.transactions') }}" class="btn btn-sm btn-link fw-bold text-primary p-0 text-decoration-none">
                            View All History →
                        </a>
                    </div>

                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                            <p class="mb-0">No financial transactions recorded yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Reference</th>
                                        <th>Category / Type</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $t)
                                        <tr>
                                            <td class="text-muted" style="font-size:0.8rem;">
                                                {{ $t->created_at->format('M d, Y') }}<br>
                                                <small>{{ $t->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="fw-semibold text-dark">{{ $t->reference }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <i class="bi {{ $t->categoryIcon() }} fs-6"></i>
                                                    <div>
                                                        <span class="fw-semibold text-dark d-block" style="font-size:0.85rem;">{{ ucfirst($t->category ?? $t->type) }}</span>
                                                        <small class="text-muted" style="font-size:0.75rem;">{{ $t->description }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $t->payment_method ?? 'System' }}</td>
                                            <td>
                                                <span class="fw-bold {{ $t->isCredit() ? 'text-success' : 'text-danger' }}">
                                                    {{ $t->signedAmount() }} AVC
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $t->statusBadgeClass() }} px-2 py-1 rounded-2">
                                                    {{ $t->formattedStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('finance.transactions.show', $t->id) }}" class="btn btn-sm btn-light rounded-2 text-primary fw-semibold">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            <!-- RIGHT SIDEBAR (col-lg-4) -->
            <div class="col-12 col-lg-4">
                
                <!-- 1. Marketplace Quick Action Cards -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cart-fill text-warning me-2"></i> AVC Peer Marketplace</h6>
                    <p class="text-muted small mb-3">Buy or sell AVC directly with verified community members via Admin Escrow protection.</p>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('marketplace') }}" class="btn btn-warning text-dark fw-bold rounded-3 py-2 shadow-sm d-flex align-items-center justify-content-between px-3">
                            <span><i class="bi bi-bag-plus-fill me-2"></i> Buy AVC from Sellers</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('marketplace') }}" class="btn btn-outline-warning text-dark fw-bold rounded-3 py-2 d-flex align-items-center justify-content-between px-3">
                            <span><i class="bi bi-tag-fill me-2"></i> Create AVC Sell Listing</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 2. Security & Limits Notice -->
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                        <i class="bi bi-shield-lock-fill fs-4"></i>
                        <h6 class="fw-bold mb-0 text-white">Finance Security Protocol</h6>
                    </div>
                    <p class="small text-white-50 mb-3" style="font-size:0.82rem;">
                        All deposits and withdrawals are processed through verified platform channels and subject to 2FA authentication and anti-fraud monitoring.
                    </p>
                    <div class="p-2.5 rounded-3 bg-white bg-opacity-10 mb-2 small">
                        <div class="d-flex justify-content-between text-white-50">
                            <span>Daily Withdrawal Limit:</span>
                            <strong class="text-white">${{ number_format($dailyLimit, 2) }}</strong>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-3 bg-white bg-opacity-10 small">
                        <div class="d-flex justify-content-between text-white-50">
                            <span>Used Today:</span>
                            <strong class="text-warning">${{ number_format($todayWithdrawn, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- 3. Need Finance Support? Widget -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-headset text-primary me-2"></i> Dedicated Finance Support</h6>
                    <p class="text-muted small mb-3">Have questions about your deposit, withdrawal, or ledger transaction?</p>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="https://wa.me/1234567890" target="_blank" class="btn btn-outline-success fw-bold rounded-3 text-start btn-sm py-2 px-3">
                            <i class="bi bi-whatsapp me-2"></i> WhatsApp Support
                        </a>
                        <a href="https://t.me/aurevia_finance" target="_blank" class="btn btn-outline-info fw-bold rounded-3 text-start btn-sm py-2 px-3">
                            <i class="bi bi-telegram me-2"></i> Telegram Finance Desk
                        </a>
                        <a href="mailto:finance@radiantdreamrealty.com" class="btn btn-light text-dark fw-bold rounded-3 text-start btn-sm py-2 px-3">
                            <i class="bi bi-envelope-fill me-2"></i> Email Finance Team
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
