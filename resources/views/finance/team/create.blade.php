@extends('layouts.main')

@section('title', 'Create Finance Request | ' . site_name())

@section('content')
<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Breadcrumb & Title -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('finance.overview') }}" class="text-decoration-none">Finance Center</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('finance.team.index') }}" class="text-decoration-none">Finance Team</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Request</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Create Finance Request</h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Fill in the details below and our Finance Team will assist with your deposit or withdrawal in local currency.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-7">
                
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <form method="POST" action="{{ route('finance.team.store') }}">
                        @csrf

                        <!-- 1. Transaction Type Toggle (Deposit vs Withdrawal) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">Transaction Type</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="type" id="type_deposit" value="deposit" {{ $type === 'deposit' ? 'checked' : '' }} onchange="toggleTypeNotice('deposit')">
                                <label class="btn btn-outline-primary fw-bold flex-fill py-2.5 rounded-3" for="type_deposit">
                                    <i class="bi bi-arrow-down-left-circle-fill me-1.5"></i> Deposit Funds
                                </label>

                                <input type="radio" class="btn-check" name="type" id="type_withdrawal" value="withdrawal" {{ $type === 'withdrawal' ? 'checked' : '' }} onchange="toggleTypeNotice('withdrawal')">
                                <label class="btn btn-outline-danger fw-bold flex-fill py-2.5 rounded-3" for="type_withdrawal">
                                    <i class="bi bi-arrow-up-right-circle-fill me-1.5"></i> Withdraw Funds
                                </label>
                            </div>
                        </div>

                        <!-- 2. Country & Currency -->
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="country" class="form-label fw-semibold text-dark small">Country</label>
                                <select name="country" id="country" class="form-select bg-light rounded-3" required>
                                    <option value="Philippines" selected>Philippines</option>
                                    <option value="United States">United States</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="currency" class="form-label fw-semibold text-dark small">Currency</label>
                                <select name="currency" id="currency" class="form-select bg-light rounded-3" required>
                                    <option value="PHP - Philippine Peso" selected>PHP - Philippine Peso</option>
                                    <option value="USD - US Dollar">USD - US Dollar</option>
                                    <option value="NGN - Nigerian Naira">NGN - Nigerian Naira</option>
                                    <option value="EUR - Euro">EUR - Euro</option>
                                    <option value="GBP - British Pound">GBP - British Pound</option>
                                    <option value="USDT (TRC20)">USDT (TRC20)</option>
                                    <option value="AED - UAE Dirham">AED - UAE Dirham</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3. Amount & Payment Method -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="amount" class="form-label fw-semibold text-dark small">Amount</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="1" name="amount" id="amount" class="form-control bg-light rounded-start-3" placeholder="e.g. 4990" required>
                                    <span class="input-group-text bg-light border-start-0 text-muted fw-semibold" id="currency_addon">PHP</span>
                                </div>
                                <span class="form-text text-muted small">Enter the total local currency amount you wish to transfer.</span>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="payment_method" class="form-label fw-semibold text-dark small">Local Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-select bg-light rounded-3" required>
                                    <option value="GCash" selected>GCash</option>
                                    <option value="Bank Transfer">Bank Transfer (BDO, BPI, Metrobank)</option>
                                    <option value="Maya">Maya</option>
                                    <option value="USDT (TRC20)">USDT (TRC20)</option>
                                    <option value="Wise">Wise / TransferWise</option>
                                    <option value="Wire Transfer">Wire Transfer</option>
                                </select>
                            </div>
                        </div>

                        <!-- 4. Sender / Receiver Account Details -->
                        <div class="p-3 rounded-3 bg-light border mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge-fill text-primary me-2"></i> Your Account Details (Sender)</h6>
                            
                            <div class="mb-3">
                                <label for="sender_name" class="form-label fw-semibold text-dark small">Full Account Name</label>
                                <input type="text" name="sender_name" id="sender_name" class="form-control bg-white rounded-3" value="{{ old('sender_name', $user->name) }}" required placeholder="e.g. John Smith">
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="sender_account" class="form-label fw-semibold text-dark small">Account / Phone / Wallet Number</label>
                                    <input type="text" name="sender_account" id="sender_account" class="form-control bg-white rounded-3" value="{{ old('sender_account', $user->phone ?? '') }}" required placeholder="e.g. 09171234567">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="sender_email" class="form-label fw-semibold text-dark small">Contact Email Address</label>
                                    <input type="email" name="sender_email" id="sender_email" class="form-control bg-white rounded-3" value="{{ old('sender_email', $user->email) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Optional Notes -->
                        <div class="mb-4">
                            <label for="user_notes" class="form-label fw-semibold text-dark small">Notes for Finance Team (Optional)</label>
                            <textarea name="user_notes" id="user_notes" rows="3" class="form-control bg-light rounded-3" placeholder="Please send details for GCash! Thank you."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary fw-bold w-100 py-3 rounded-3 shadow-sm" style="background:#2563eb; border:none; font-size:1rem;">
                            <i class="bi bi-send-fill me-2"></i> Submit Finance Request
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('currency').addEventListener('change', function() {
        var addon = document.getElementById('currency_addon');
        var val = this.value;
        if (val.indexOf('PHP') !== -1) addon.textContent = 'PHP';
        else if (val.indexOf('USD') !== -1) addon.textContent = 'USD';
        else if (val.indexOf('NGN') !== -1) addon.textContent = 'NGN';
        else if (val.indexOf('EUR') !== -1) addon.textContent = 'EUR';
        else if (val.indexOf('GBP') !== -1) addon.textContent = 'GBP';
        else if (val.indexOf('USDT') !== -1) addon.textContent = 'USDT';
        else addon.textContent = 'VAL';
    });
</script>
@endsection
