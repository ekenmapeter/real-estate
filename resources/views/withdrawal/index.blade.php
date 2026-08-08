@extends('layouts.main')

@section('title', 'Withdraw / Sell AVC | ' . site_name())

@section('content')
<style>
    .balance-card-dark {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%);
    }
    .step-wizard-badge {
        width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.72rem;
    }
    .method-card-btn {
        border: 2px solid #e2e8f0; transition: all 0.2s ease; cursor: pointer; background: #fff;
    }
    .method-card-btn:hover { border-color: #3b82f6; transform: translateY(-2px); }
    .method-card-btn.active { border-color: #2563eb; background: #eff6ff; }
</style>

<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;" x-data="withdrawalHubApp()">
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
            
            <!-- Page Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Withdraw / Sell AVC</h2>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">Convert your available AVC into cash, send to your bank account, wallet or crypto, or sell AVC to a verified buyer through Admin Escrow.</p>
                </div>
            </div>

            <!-- Flash Alert Messages -->
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
                    
                    <!-- 1. Choose How to Cash Out AVC (Matching Mockup Top Left) -->
                    <h5 class="fw-bold text-dark mb-3">Choose How to Cash Out AVC</h5>

                    <div class="row g-3 mb-4">
                        <!-- Option 1: Withdraw Through Finance Team -->
                        <div class="col-12 col-md-6">
                            <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 d-flex flex-column" style="background: #ffffff; border: 1px solid #e2e8f0;">
                                <div class="rounded-circle p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #eff6ff; color: #2563eb;">
                                    <i class="bi bi-bank fs-3"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-1">Withdraw Through Finance Team</h5>
                                <p class="text-muted small mb-4" style="font-size: 0.82rem; line-height: 1.45;">Request a payout from the AVC Finance Team using bank transfer, e-wallet, wire transfer or cryptocurrency.</p>

                                <button type="button" class="btn btn-primary fw-bold w-100 rounded-3 py-2.5 shadow-sm mt-auto" style="background: #2563eb; border: none;" @click="scrollToForm()">
                                    Start Withdrawal Request
                                </button>
                            </div>
                        </div>

                        <!-- Option 2: Sell AVC on Marketplace -->
                        <div class="col-12 col-md-6">
                            <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 d-flex flex-column" style="background: #ffffff; border: 1px solid #e2e8f0;">
                                <div class="rounded-circle p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #dcfce7; color: #059669;">
                                    <i class="bi bi-cart-check fs-3"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-1">Sell AVC on Marketplace</h5>
                                <p class="text-muted small mb-4" style="font-size: 0.82rem; line-height: 1.45;">Create or accept AVC sale offers from verified buyers using Admin Escrow for a secure transaction.</p>

                                <a href="{{ route('avc-marketplace.index') }}" class="btn btn-emerald text-white fw-bold w-100 rounded-3 py-2.5 shadow-sm mt-auto" style="background: #059669; border: none;">
                                    Go to Marketplace
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Blue Callout Banner -->
                    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4" style="background: #eff6ff; border-left: 4px solid #3b82f6;">
                        <div class="d-flex align-items-center gap-2.5 text-primary" style="font-size: 0.82rem;">
                            <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                            <span>All withdrawals and sales are secured and coordinated by the AVC Finance Team.</span>
                        </div>
                    </div>

                    <!-- 2. Step Wizard Progress Bar (Matching Mockup) -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4" id="withdrawalFormSection">
                        <div class="d-flex justify-content-around align-items-center small text-muted font-semibold">
                            <div class="d-flex align-items-center gap-1.5 text-primary fw-bold">
                                <span class="step-wizard-badge bg-primary text-white">1</span> Amount
                            </div>
                            <i class="bi bi-chevron-right text-muted opacity-50"></i>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="step-wizard-badge bg-light text-secondary border">2</span> Method
                            </div>
                            <i class="bi bi-chevron-right text-muted opacity-50"></i>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="step-wizard-badge bg-light text-secondary border">3</span> Details
                            </div>
                            <i class="bi bi-chevron-right text-muted opacity-50"></i>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="step-wizard-badge bg-light text-secondary border">4</span> Review
                            </div>
                            <i class="bi bi-chevron-right text-muted opacity-50"></i>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="step-wizard-badge bg-light text-secondary border">5</span> Confirm
                            </div>
                        </div>
                    </div>

                    <!-- 3. Withdrawal Form Card -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <form action="{{ route('withdraw.create.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="withdrawal_method" :value="selectedMethod">
                            <input type="hidden" name="saved_withdrawal_method_id" :value="selectedSavedMethodId">

                            <!-- Section: Enter Withdrawal Amount -->
                            <h6 class="fw-bold text-dark mb-3">Enter Withdrawal Amount</h6>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">AVC to Withdraw</label>
                                    <div class="input-group">
                                        <input type="number" name="amount" class="form-control fw-bold text-dark fs-5" x-model.number="withdrawalAmount" @input="calculatePayout()" min="10" max="{{ $availableBalance }}" step="1" required>
                                        <span class="input-group-text bg-white fw-bold">AVC</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 0.75rem;">
                                        <span class="text-muted">Minimum: <strong>10 AVC</strong></span>
                                        <span class="text-muted">Available: <strong class="text-primary">{{ number_format($availableBalance, 0) }} AVC</strong></span>
                                        <button type="button" class="btn btn-link text-primary p-0 text-decoration-none fw-bold small" @click="useMax()">Use Max</button>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">Estimated Payout (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold">$</span>
                                        <input type="text" class="form-control fw-bold text-dark fs-5 bg-white" :value="grossUsd.toFixed(2)" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Calculation Breakdown Card (Matching Mockup) -->
                            <div class="p-3 rounded-3 bg-light border mb-4">
                                <div class="row g-2 text-center small" style="font-size: 0.82rem;">
                                    <div class="col-3 border-end">
                                        <span class="text-muted d-block" style="font-size:0.72rem;">Rate</span>
                                        <strong class="text-dark">1 AVC = $1.00</strong>
                                    </div>
                                    <div class="col-3 border-end">
                                        <span class="text-muted d-block" style="font-size:0.72rem;">Gross Value</span>
                                        <strong class="text-dark">$<span x-text="grossUsd.toFixed(2)">500.00</span></strong>
                                    </div>
                                    <div class="col-3 border-end">
                                        <span class="text-muted d-block" style="font-size:0.72rem;">Withdrawal Fee <i class="bi bi-info-circle text-muted" title="Standard payout processing fee"></i></span>
                                        <strong class="text-dark">$<span x-text="feeAmount.toFixed(2)">2.50</span></strong>
                                    </div>
                                    <div class="col-3">
                                        <span class="text-muted d-block" style="font-size:0.72rem;">Estimated Net Payout</span>
                                        <strong class="text-success fs-6">$<span x-text="estimatedNet.toFixed(2)">497.50</span></strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Select Withdrawal Method (Clickable Method Cards) -->
                            <h6 class="fw-bold text-dark mb-1">Select Withdrawal Method</h6>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">Choose how you want to receive your funds.</p>

                            <div class="row g-3 mb-4">
                                <!-- Card 1: Local Bank Transfer -->
                                <div class="col-6 col-sm-3">
                                    <div class="method-card-btn rounded-4 p-3 text-center h-100" :class="{ 'active': selectedMethod === 'bank_transfer' }" @click="selectMethod('bank_transfer', 2.50)">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="bi bi-bank fs-5"></i>
                                        </div>
                                        <strong class="text-dark d-block mb-0.5" style="font-size: 0.8rem;">Local Bank Transfer</strong>
                                        <span class="text-muted d-block" style="font-size: 0.68rem;">1-3 business days</span>
                                        <span class="badge bg-light text-dark border mt-1" style="font-size: 0.65rem;">Fee: $2.50</span>
                                    </div>
                                </div>

                                <!-- Card 2: GCash / Mobile Wallet -->
                                <div class="col-6 col-sm-3">
                                    <div class="method-card-btn rounded-4 p-3 text-center h-100" :class="{ 'active': selectedMethod === 'mobile_wallet' }" @click="selectMethod('mobile_wallet', 1.00)">
                                        <div class="rounded-circle bg-purple bg-opacity-10 p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background:rgba(147,51,234,0.1); color:#9333ea;">
                                            <i class="bi bi-phone fs-5"></i>
                                        </div>
                                        <strong class="text-dark d-block mb-0.5" style="font-size: 0.8rem;">GCash / Mobile Wallet</strong>
                                        <span class="text-muted d-block" style="font-size: 0.68rem;">Instant - 1 day</span>
                                        <span class="badge bg-light text-dark border mt-1" style="font-size: 0.65rem;">Fee: $1.00</span>
                                    </div>
                                </div>

                                <!-- Card 3: International Wire Transfer -->
                                <div class="col-6 col-sm-3">
                                    <div class="method-card-btn rounded-4 p-3 text-center h-100" :class="{ 'active': selectedMethod === 'wire_transfer' }" @click="selectMethod('wire_transfer', 15.00)">
                                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="bi bi-globe fs-5"></i>
                                        </div>
                                        <strong class="text-dark d-block mb-0.5" style="font-size: 0.8rem;">International Wire Transfer</strong>
                                        <span class="text-muted d-block" style="font-size: 0.68rem;">2-5 business days</span>
                                        <span class="badge bg-light text-dark border mt-1" style="font-size: 0.65rem;">Fee: $15.00</span>
                                    </div>
                                </div>

                                <!-- Card 4: Cryptocurrency -->
                                <div class="col-6 col-sm-3">
                                    <div class="method-card-btn rounded-4 p-3 text-center h-100" :class="{ 'active': selectedMethod === 'crypto' }" @click="selectMethod('crypto', 2.00)">
                                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2.5 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="bi bi-currency-bitcoin fs-5"></i>
                                        </div>
                                        <strong class="text-dark d-block mb-0.5" style="font-size: 0.8rem;">Cryptocurrency</strong>
                                        <span class="text-muted d-block" style="font-size: 0.68rem;">Within 1 day</span>
                                        <span class="badge bg-light text-dark border mt-1" style="font-size: 0.65rem;">Network fee</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Dynamic Form Fields per Selected Method (Matching Mockup) -->
                            <div class="p-3.5 rounded-3 bg-light border mb-4">
                                <h6 class="fw-bold text-dark mb-3">
                                    <span x-text="selectedMethod === 'bank_transfer' ? 'Local Bank Transfer Details' : (selectedMethod === 'mobile_wallet' ? 'Mobile Wallet Details' : (selectedMethod === 'wire_transfer' ? 'International Wire Transfer Details' : 'Cryptocurrency Wallet Details'))"></span>
                                </h6>

                                <!-- LOCAL BANK FORM -->
                                <template x-if="selectedMethod === 'bank_transfer'">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Account Holder Name*</label>
                                            <input type="text" name="account_name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Bank Name*</label>
                                            <select name="bank_or_provider" class="form-select" required>
                                                <option value="BDO (Bank of the Philippine Islands)">BDO (Bank of the Philippine Islands)</option>
                                                <option value="BPI (Bank of the Philippine Islands)">BPI (Bank of the Philippine Islands)</option>
                                                <option value="Metrobank">Metrobank</option>
                                                <option value="UnionBank of the Philippines">UnionBank of the Philippines</option>
                                                <option value="Landbank">Landbank</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Account Number*</label>
                                            <input type="text" name="account_number" class="form-control font-monospace" placeholder="1234 5678 9012" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Account Type</label>
                                            <select name="account_type" class="form-select">
                                                <option value="Savings">Savings</option>
                                                <option value="Checking">Checking</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Country*</label>
                                            <select name="country" class="form-select">
                                                <option value="Philippines">Philippines</option>
                                                <option value="United States">United States</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Currency*</label>
                                            <select name="currency" class="form-select">
                                                <option value="PHP">PHP</option>
                                                <option value="USD">USD</option>
                                            </select>
                                        </div>
                                    </div>
                                </template>

                                <!-- MOBILE WALLET FORM -->
                                <template x-if="selectedMethod === 'mobile_wallet'">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Registered Name*</label>
                                            <input type="text" name="account_name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Wallet Provider*</label>
                                            <select name="bank_or_provider" class="form-select" required>
                                                <option value="GCash">GCash</option>
                                                <option value="Maya">Maya</option>
                                                <option value="Cash App">Cash App</option>
                                                <option value="PayPal">PayPal</option>
                                                <option value="Venmo">Venmo</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Mobile Number / Wallet Account*</label>
                                            <input type="text" name="account_number" class="form-control font-monospace" placeholder="0917 123 4491" required>
                                        </div>
                                    </div>
                                </template>

                                <!-- CRYPTO FORM -->
                                <template x-if="selectedMethod === 'crypto'">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Crypto Asset</label>
                                            <select name="crypto_asset" class="form-select fw-semibold" x-model="selectedCrypto">
                                                <option value="USDT">USDT</option>
                                                <option value="BTC">BTC</option>
                                                <option value="ETH">ETH</option>
                                                <option value="BNB">BNB</option>
                                                <option value="SOL">SOL</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label small fw-bold text-secondary">Network</label>
                                            <select name="crypto_network" class="form-select fw-semibold">
                                                <option value="TRC-20">TRC-20</option>
                                                <option value="ERC-20">ERC-20</option>
                                                <option value="BEP-20">BEP-20</option>
                                                <option value="Bitcoin">Bitcoin</option>
                                                <option value="Solana">Solana</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-secondary">Destination Wallet Address*</label>
                                            <input type="text" name="wallet_address" class="form-control font-monospace" placeholder="Enter your destination wallet address" required>
                                            <input type="hidden" name="account_name" value="Wallet Destination">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Security Verification PIN / Password -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Transaction PIN or Account Password*</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password or PIN to authorize withdrawal" required>
                            </div>

                            <!-- Confirmation Checkbox (Matching Mockup) -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="confirm_checkbox" id="confirmCheckbox" required>
                                <label class="form-check-label text-dark small fw-semibold" for="confirmCheckbox">
                                    I confirm that this account belongs to me and that the information provided is correct.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-2.5" style="background: #2563eb; border: none;">
                                Continue to Review <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- RIGHT SIDEBAR COLUMN (col-lg-4) (Matching Mockup Right Column) -->
                <div class="col-12 col-lg-4">
                    
                    <!-- 1. AVC BALANCE Card (Dark Navy Gradient) -->
                    <div class="card border-0 rounded-4 text-white p-4 mb-4 shadow-sm balance-card-dark">
                        <span class="text-white-50 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">AVC Balance</span>
                        <div class="d-flex align-items-baseline gap-2 mb-1">
                            <h2 class="fw-bold text-white mb-0">{{ number_format($availableBalance, 0) }} <span class="fs-5 text-white-50">AVC</span></h2>
                        </div>
                        <span class="text-white-50 small d-block mb-3">≈ ${{ number_format($estimatedUsd, 2) }} USD</span>

                        <div class="row g-2 text-white-50 small border-top border-white border-opacity-10 pt-3 mb-3" style="font-size: 0.76rem;">
                            <div class="col-4">
                                <span class="d-block" style="font-size: 0.68rem;">Available AVC</span>
                                <strong class="text-white">{{ number_format($availableBalance, 0) }} AVC</strong>
                            </div>
                            <div class="col-4">
                                <span class="d-block" style="font-size: 0.68rem;">Pending Withdrawal</span>
                                <strong class="text-white">{{ number_format($pendingWithdrawal, 0) }} AVC</strong>
                            </div>
                            <div class="col-4">
                                <span class="d-block" style="font-size: 0.68rem;">Held in Escrow</span>
                                <strong class="text-white">{{ number_format($escrowAvc, 0) }} AVC</strong>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top border-white border-opacity-10 pt-2" style="font-size: 0.78rem;">
                            <div>
                                <span class="text-white-50 d-block" style="font-size: 0.68rem;">Daily Withdrawal Limit</span>
                                <strong class="text-white">${{ number_format($dailyLimit, 0) }}</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-white-50 d-block" style="font-size: 0.68rem;">Remaining Today</span>
                                <strong class="text-emerald-400" style="color: #34d399;">${{ number_format($remainingToday, 0) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Withdrawal Information Card (Matching Mockup) -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i> Withdrawal Information</h6>
                        
                        <ul class="list-unstyled small mb-0" style="font-size: 0.8rem; line-height: 1.6;">
                            <li class="mb-2.5 d-flex align-items-start gap-2">
                                <i class="bi bi-box-arrow-up text-primary mt-0.5"></i>
                                <span>Minimum withdrawal: <strong>10 AVC</strong></span>
                            </li>
                            <li class="mb-2.5 d-flex align-items-start gap-2">
                                <i class="bi bi-clock-history text-primary mt-0.5"></i>
                                <span>Processing time: <strong>1 – 3 business days</strong></span>
                            </li>
                            <li class="mb-2.5 d-flex align-items-start gap-2">
                                <i class="bi bi-shield-check text-primary mt-0.5"></i>
                                <span>KYC may be required for high-value withdrawals</span>
                            </li>
                            <li class="mb-2.5 d-flex align-items-start gap-2">
                                <i class="bi bi-currency-dollar text-primary mt-0.5"></i>
                                <span>Fees and rates are shown before confirmation</span>
                            </li>
                            <li class="mb-2.5 d-flex align-items-start gap-2">
                                <i class="bi bi-key text-primary mt-0.5"></i>
                                <span>Destination account must belong to you</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start gap-2">
                                <i class="bi bi-person-check text-primary mt-0.5"></i>
                                <span>Withdrawals may be reviewed by Finance Team</span>
                            </li>
                        </ul>
                    </div>

                    <!-- 3. Saved Withdrawal Methods Card (Matching Mockup) -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">Saved Withdrawal Methods</h6>
                            <a href="#" class="text-primary text-decoration-none fw-semibold small" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#addSavedMethodModal">View All</a>
                        </div>

                        <div class="d-flex flex-column gap-2.5">
                            @forelse($savedMethods as $sm)
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px; background:#fff; border:1px solid #cbd5e1;">
                                            <i class="bi {{ $sm->methodIcon() }} fs-5"></i>
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block" style="font-size: 0.82rem;">{{ $sm->title }}</strong>
                                            <span class="text-muted" style="font-size: 0.72rem;">{{ $sm->account_name }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5">
                                        @if($sm->is_default)
                                            <span class="badge bg-success bg-opacity-15 text-success fw-semibold px-2 py-0.5" style="font-size: 0.68rem;">Default</span>
                                        @endif
                                        <form action="{{ route('saved-withdrawal-methods.destroy', $sm->id) }}" method="POST" onsubmit="return confirm('Remove saved account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-muted p-0 border-0 ms-1" title="Delete"><i class="bi bi-three-dots-vertical"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">No saved withdrawal accounts yet.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 4. Recent Withdrawal Activity Card (Matching Mockup) -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">Recent Withdrawal Activity</h6>
                            <a href="{{ route('withdraw.index', ['filter' => 'all']) }}" class="text-primary text-decoration-none fw-semibold small" style="font-size: 0.78rem;">View All</a>
                        </div>

                        @if($recentActivity->count() > 0)
                            <div class="d-flex flex-column gap-2.5">
                                @foreach($recentActivity->take(5) as $act)
                                    <a href="{{ $act['url'] }}" class="p-2.5 rounded-3 border bg-light text-decoration-none d-block">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark" style="font-size: 0.82rem;">{{ $act['title'] }}</strong>
                                            <span class="badge {{ $act['badge_class'] }} px-2 py-0.5" style="font-size: 0.68rem;">{{ $act['status_label'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 0.72rem;">
                                            <div>
                                                <strong class="text-dark d-block">{{ number_format($act['amount'], 0) }} AVC</strong>
                                                <code class="text-muted" style="font-size: 0.68rem;">{{ $act['code'] }}</code>
                                            </div>
                                            <div class="text-end">
                                                <span class="text-muted d-block">Net: ${{ number_format($act['net'], 2) }}</span>
                                                <span class="text-muted" style="font-size: 0.68rem;">{{ $act['created_at']->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted small">No recent withdrawal activity.</div>
                        @endif
                    </div>

                    <!-- 5. Need Help? Card -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h6 class="fw-bold text-dark mb-1">Need Help?</h6>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">If you have any questions or need assistance with your withdrawal, our support team is here to help you.</p>

                        <div class="d-flex gap-2">
                            <a href="https://wa.me/?text=Hello%20AVC%20Support,%20I%20need%20help%20with%20a%20withdrawal" target="_blank" class="btn btn-emerald text-white btn-sm flex-fill fw-bold rounded-3 py-2" style="background:#059669;">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp Support
                            </a>
                            <a href="https://t.me/" target="_blank" class="btn btn-primary btn-sm flex-fill fw-bold rounded-3 py-2" style="background:#2563eb;">
                                <i class="bi bi-telegram me-1"></i> Telegram Support
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function withdrawalHubApp() {
    return {
        withdrawalAmount: 500,
        grossUsd: 500,
        feeAmount: 2.50,
        estimatedNet: 497.50,
        selectedMethod: 'bank_transfer',
        selectedCrypto: 'USDT',
        selectedSavedMethodId: null,

        init() {
            this.calculatePayout();
        },

        calculatePayout() {
            if (this.withdrawalAmount < 1) this.withdrawalAmount = 1;
            this.grossUsd = this.withdrawalAmount * 1.00;
            this.estimatedNet = Math.max(0, this.grossUsd - this.feeAmount);
        },

        selectMethod(methodKey, fee) {
            this.selectedMethod = methodKey;
            this.feeAmount = fee;
            this.calculatePayout();
        },

        useMax() {
            this.withdrawalAmount = {{ $availableBalance }};
            this.calculatePayout();
        },

        scrollToForm() {
            document.getElementById('withdrawalFormSection').scrollIntoView({ behavior: 'smooth' });
        }
    }
}
</script>
@endsection
