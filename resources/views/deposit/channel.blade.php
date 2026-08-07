@extends('layouts.main')

@section('title', ucfirst(str_replace('_', ' ', $method)) . ' Deposit | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
    .channel-container { background: #f8fafc; min-height: 100vh; }
    .green-callout { background-color: #ecfdf5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46; font-size: 0.85rem; padding: 12px 16px; }
    .blue-info-box { background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; color: #1e40af; font-size: 0.82rem; padding: 10px 14px; }
    .copy-btn { font-size: 0.78rem; font-weight: 600; padding: 3px 10px; cursor: pointer; }
    .check-bullet { color: #10b981; margin-right: 6px; }
    .qr-box { width: 90px; height: 90px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px; background: #fff; }
</style>

<div class="channel-container py-4 user-shell-content" x-data="depositChannelApp('{{ $method }}')">
    <div class="container-xl px-3 px-md-4">

        <!-- Main Modal / Form Card Container (Matching Photo 1) -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 mb-4 position-relative">
            
            <!-- Close Button Top Right -->
            <a href="{{ route('deposit.index') }}" class="btn-close position-absolute top-0 end-0 m-4" aria-label="Close"></a>

            <!-- Header Title Row -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #eff6ff; color: #2563eb;">
                    @if($method === 'bank_transfer') <i class="bi bi-bank fs-3"></i>
                    @elseif($method === 'credit_card') <i class="bi bi-credit-card-2-front fs-3"></i>
                    @elseif($method === 'wire_transfer') <i class="bi bi-globe fs-3"></i>
                    @elseif($method === 'crypto') <i class="bi bi-currency-bitcoin fs-3 text-warning"></i>
                    @endif
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">
                        @if($method === 'bank_transfer') Bank Transfer / GCash
                        @elseif($method === 'credit_card') Credit / Debit Card
                        @elseif($method === 'wire_transfer') International Wire Transfer
                        @elseif($method === 'crypto') Cryptocurrency
                        @endif
                    </h3>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                        @if($method === 'bank_transfer') Transfer funds directly from your bank account or GCash.
                        @elseif($method === 'credit_card') Deposit instantly using your Visa, Mastercard or other supported cards.
                        @elseif($method === 'wire_transfer') Send funds via international wire transfer (SWIFT).
                        @elseif($method === 'crypto') Send crypto to the wallet address below.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Green Important Callout Banner -->
            <div class="green-callout mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill text-emerald-600 fs-5 flex-shrink-0" style="color: #10b981;"></i>
                <span>
                    @if($method === 'bank_transfer') <strong>Important:</strong> Please use the payment details below and follow the instructions carefully.
                    @elseif($method === 'wire_transfer') <strong>Important:</strong> Include the reference code below in your transfer notes.
                    @elseif($method === 'crypto') <strong>Important:</strong> Send only the selected cryptocurrency to the address below.
                    @else <strong>Important:</strong> Fill in your deposit information and card details securely.
                    @endif
                </span>
            </div>

            <!-- Flash Error Messages -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                
                <!-- LEFT FORM COLUMN (col-lg-8) -->
                <div class="col-12 col-lg-8">
                    <form action="{{ route('deposit.create.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="{{ $method }}">
                        <input type="hidden" name="crypto_asset" :value="selectedCrypto">
                        <input type="hidden" name="crypto_network" :value="selectedNetwork">

                        <!-- METHOD 1: BANK TRANSFER / GCASH (Matching Photo 1 Top Left) -->
                        @if($method === 'bank_transfer')
                            
                            <!-- 1. Payment Instructions -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0">1. Payment Instructions</h6>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.payment-channels.index') }}" class="text-primary text-decoration-none small fw-semibold" style="font-size: 0.75rem;">Admin Settings</a>
                                @endif
                            </div>

                            <div class="bg-light p-3.5 rounded-3 mb-4 border">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Name</span>
                                        <strong class="text-dark">Real Estate Corporation Corp.</strong>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Bank Name</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark">BDO Unibank, Inc.</strong>
                                            <span class="badge bg-dark text-white fw-bold px-1.5 py-0.5" style="font-size: 0.65rem;">BDO</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Number</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark font-monospace fs-6">8965 8726 718</strong>
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 copy-btn" @click="copyText('8965 8726 718')"><i class="bi bi-copy me-1"></i>Copy</button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Type</span>
                                        <strong class="text-dark">Savings</strong>
                                    </div>
                                </div>

                                <div class="blue-info-box mt-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <span>Copy the account details above and make your transfer.</span>
                                </div>
                            </div>

                            <!-- 2. Deposit Information -->
                            <h6 class="fw-bold text-dark mb-3">2. Deposit Information</h6>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Amount (USD)*</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">$</span>
                                    <input type="number" name="amount" class="form-control fw-bold" placeholder="e.g. 500.00" x-model.number="depositAmount" @input="updateConversion()" min="10" step="1" required>
                                </div>
                                <span class="text-success fw-bold small d-block mt-1" style="font-size: 0.82rem;">You will receive approximately: <span x-text="estimatedAvc.toLocaleString()">500</span> AVC</span>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">Sender Account Name*</label>
                                    <input type="text" name="sender_account_name" class="form-control" placeholder="e.g. John Doe" value="{{ $user->name }}" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">Reference / Notes (Optional)</label>
                                    <input type="text" name="user_notes" class="form-control" placeholder="e.g. Invoice #123">
                                </div>
                            </div>

                            <!-- 3. Upload Payment Proof -->
                            <h6 class="fw-bold text-dark mb-3">3. Upload Payment Proof</h6>
                            <div class="mb-4">
                                <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <span class="text-muted small d-block mt-1" style="font-size: 0.75rem;">Accepted formats: .JPG, PNG, PDF (Max: 5MB)</span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-2.5" style="background: #2563eb; border: none;">
                                I Have Made the Payment
                            </button>

                        @endif

                        <!-- METHOD 2: CREDIT / DEBIT CARD (Matching Photo 1 Top Right) -->
                        @if($method === 'credit_card')
                            
                            <!-- 1. Deposit Information -->
                            <h6 class="fw-bold text-dark mb-3">1. Deposit Information</h6>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Amount (USD)*</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">$</span>
                                    <input type="number" name="amount" class="form-control fw-bold" placeholder="e.g. 500.00" x-model.number="depositAmount" @input="updateConversion()" min="10" step="1" required>
                                </div>
                                <span class="text-success fw-bold small d-block mt-1" style="font-size: 0.82rem;">You will receive approximately: <span x-text="estimatedAvc.toLocaleString()">500</span> AVC</span>
                            </div>

                            <!-- 2. Card Information -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0">2. Card Information</h6>
                                <div class="d-flex gap-1.5">
                                    <span class="badge bg-light text-primary border font-semibold">VISA</span>
                                    <span class="badge bg-light text-danger border font-semibold">Mastercard</span>
                                    <span class="badge bg-light text-info border font-semibold">AMEX</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Cardholder Name*</label>
                                <input type="text" name="card_name" class="form-control" placeholder="e.g. John Doe" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Card Number*</label>
                                <input type="text" name="card_number" class="form-control font-monospace" placeholder="1234 5678 9012 3456" required maxlength="19">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-secondary">Expiry Date*</label>
                                    <input type="text" name="card_exp_date" class="form-control text-center" placeholder="MM / YY" required maxlength="5">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-secondary">CVV* <i class="bi bi-question-circle text-muted" title="3 or 4 digit security code on back of card"></i></label>
                                    <input type="password" name="card_cvv" class="form-control text-center" placeholder="123" required maxlength="4">
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="saveCard" checked>
                                <label class="form-check-label text-muted small" for="saveCard">Save this card for future use</label>
                            </div>

                            <div class="blue-info-box mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill text-primary"></i>
                                <span>Your payment is secured by 256-bit SSL encryption.</span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-2.5" style="background: #2563eb; border: none;">
                                <i class="bi bi-lock-fill me-1"></i> Pay Securely Now
                            </button>

                        @endif

                        <!-- METHOD 3: INTERNATIONAL WIRE TRANSFER (Matching Photo 1 Bottom Left) -->
                        @if($method === 'wire_transfer')
                            
                            <!-- 1. Wire Transfer Details -->
                            <h6 class="fw-bold text-dark mb-3">1. Wire Transfer Details</h6>

                            <div class="bg-light p-3.5 rounded-3 mb-4 border">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Name</span>
                                        <strong class="text-dark">Real Estate Corporation Corp.</strong>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Bank Name</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark">JPMorgan Chase Bank, N.A.</strong>
                                            <span class="badge bg-primary text-white fw-bold px-1.5 py-0.5" style="font-size: 0.65rem;">CHASE</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">SWIFT / BIC Code</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark font-monospace fs-6">CHASUS33XXX</strong>
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 copy-btn" @click="copyText('CHASUS33XXX')"><i class="bi bi-copy me-1"></i>Copy</button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Account Number</span>
                                        <strong class="text-dark font-monospace">1234567890</strong>
                                    </div>
                                    <div class="col-12">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Bank Address</span>
                                        <strong class="text-dark">270 Park Avenue, New York, NY 10017, USA</strong>
                                    </div>
                                    <div class="col-12">
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Reference Code (Required)</span>
                                        <div class="d-flex align-items-center justify-content-between p-2 rounded-2" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                                            <strong class="text-emerald-700 font-monospace fs-6" style="color: #047857;">AVC-DEP-2024-55721</strong>
                                            <button type="button" class="btn btn-sm btn-emerald text-white rounded-2 copy-btn" style="background:#059669;" @click="copyText('AVC-DEP-2024-55721')"><i class="bi bi-copy me-1"></i>Copy</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="blue-info-box mt-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <span>include the reference code above when making your transfer.</span>
                                </div>
                            </div>

                            <!-- 2. Deposit Information -->
                            <h6 class="fw-bold text-dark mb-3">2. Deposit Information</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Amount (USD)*</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">$</span>
                                    <input type="number" name="amount" class="form-control fw-bold" placeholder="e.g. 1,000.00" x-model.number="depositAmount" @input="updateConversion()" min="100" step="1" required>
                                </div>
                                <span class="text-success fw-bold small d-block mt-1" style="font-size: 0.82rem;">You will receive approximately: <span x-text="estimatedAvc.toLocaleString()">1,000</span> AVC</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Sender Name / Company*</label>
                                <input type="text" name="sender_account_name" class="form-control" placeholder="e.g. John Doe / ABC Company" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Upload Payment Proof*</label>
                                <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                <span class="text-muted small d-block mt-1" style="font-size: 0.75rem;">Accepted formats: .PDF, JPG, PNG (Max: 5MB)</span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-2.5" style="background: #2563eb; border: none;">
                                I Have Made the Transfer
                            </button>

                        @endif

                        <!-- METHOD 4: CRYPTOCURRENCY (Matching Photo 1 Bottom Right) -->
                        @if($method === 'crypto')
                            
                            <!-- 1. Select Coin -->
                            <h6 class="fw-bold text-dark mb-3">1. Select Coin</h6>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Cryptocurrency*</label>
                                <select class="form-select fw-semibold" x-model="selectedCrypto" @change="updateNetworks()">
                                    <option value="USDT">USDT (TRC-20)</option>
                                    <option value="BTC">BTC (Bitcoin)</option>
                                    <option value="ETH">ETH (Ethereum)</option>
                                    <option value="BNB">BNB (Binance Smart Chain)</option>
                                    <option value="SOL">SOL (Solana)</option>
                                </select>
                            </div>

                            <!-- 2. Payment Details -->
                            <h6 class="fw-bold text-dark mb-3">2. Payment Details</h6>

                            <div class="bg-light p-3.5 rounded-3 mb-4 border">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-sm-8">
                                        <div class="mb-2">
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Network</span>
                                            <strong class="text-dark" x-text="selectedNetwork">TRC-20</strong>
                                        </div>

                                        <div class="mb-2">
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Wallet Address</span>
                                            <div class="d-flex align-items-center gap-1.5">
                                                <strong class="text-dark font-monospace small text-truncate" style="max-width:200px;">TYd1kL9nEXTP2q4W5nEWe3h8K9Ln1e2e</strong>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-2 copy-btn" @click="copyText('TYd1kL9nEXTP2q4W5nEWe3h8K9Ln1e2e')"><i class="bi bi-copy me-1"></i>Copy</button>
                                            </div>
                                        </div>

                                        <div class="row g-2 small mt-1">
                                            <div class="col-6">
                                                <span class="text-muted d-block" style="font-size:0.72rem;">Minimum Deposit</span>
                                                <strong class="text-dark">10 USDT</strong>
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted d-block" style="font-size:0.72rem;">Confirmations Required</span>
                                                <strong class="text-dark">12 Confirmations</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- QR Code Box on the Right (Matching Photo 1) -->
                                    <div class="col-12 col-sm-4 text-center">
                                        <div class="qr-box mx-auto d-flex flex-column align-items-center justify-content-center shadow-sm">
                                            <i class="bi bi-qr-code text-dark display-6"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="blue-info-box mt-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <span>Send the exact amount or more. Your deposit will be credited after confirmations.</span>
                                </div>
                            </div>

                            <!-- 3. Deposit Information -->
                            <h6 class="fw-bold text-dark mb-3">3. Deposit Information</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Amount (USDT)*</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">$</span>
                                    <input type="number" name="amount" class="form-control fw-bold" placeholder="e.g. 500.00" x-model.number="depositAmount" @input="updateConversion()" min="10" step="1" required>
                                </div>
                                <span class="text-success fw-bold small d-block mt-1" style="font-size: 0.82rem;">You will receive approximately: <span x-text="estimatedAvc.toLocaleString()">500</span> AVC</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Transaction ID / TX Hash*</label>
                                <input type="text" name="tx_hash" class="form-control font-monospace" placeholder="e.g. abc123def456..." required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Upload Payment Proof (Screenshot or TX Receipt)</label>
                                <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <span class="text-muted small d-block mt-1" style="font-size: 0.75rem;">Accepted formats: .JPG, PNG, PDF (Max: 5MB)</span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-2.5" style="background: #2563eb; border: none;">
                                I Have Sent the Payment
                            </button>

                        @endif

                    </form>
                </div>

                <!-- RIGHT SIDEBAR COLUMN (col-lg-4) (Matching Photo 1 Right Column) -->
                <div class="col-12 col-lg-4">
                    
                    <!-- Security Notice Card (Green Bullet Checkmarks) -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4" style="background: #fafafa;">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-emerald-600 me-2" style="color:#10b981;"></i> Security Notice</h6>
                        
                        <ul class="list-unstyled small mb-0" style="font-size: 0.82rem; line-height: 1.5;">
                            <li class="mb-2 d-flex align-items-start"><i class="bi bi-check-circle-fill check-bullet"></i> <span>Make payment only to the account details shown here.</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="bi bi-check-circle-fill check-bullet"></i> <span>Do not send funds to anyone claiming to be an admin.</span></li>
                            <li class="mb-0 d-flex align-items-start"><i class="bi bi-check-circle-fill check-bullet"></i> <span>Your deposit will be reviewed and credited after verification.</span></li>
                        </ul>
                    </div>

                    <!-- Need Help? Card -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h6 class="fw-bold text-dark mb-1">Need Help?</h6>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">Contact our support team anytime for assistance.</p>

                        <div class="d-flex flex-column gap-2">
                            <a href="https://wa.me/?text=Hello%20AVC%20Support" target="_blank" class="btn btn-outline-success btn-sm fw-bold rounded-3 py-2 text-start px-3">
                                <i class="bi bi-whatsapp me-2"></i> WhatsApp
                            </a>
                            <a href="https://t.me/" target="_blank" class="btn btn-outline-info btn-sm fw-bold rounded-3 py-2 text-start px-3">
                                <i class="bi bi-telegram me-2"></i> Telegram
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm fw-bold rounded-3 py-2 text-start px-3">
                                <i class="bi bi-headset me-2"></i> Support Center
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BOTTOM SECTION: RECENT DEPOSITS ROW ITEM (Matching Photo 1 Bottom Bar) -->
            <div class="mt-5 pt-4 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">Recent Deposits</h6>
                    <a href="{{ route('deposit.index') }}" class="text-primary text-decoration-none fw-semibold small" style="font-size: 0.78rem;">View All</a>
                </div>

                <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eff6ff; color: #2563eb;">
                            @if($method === 'bank_transfer') <i class="bi bi-bank fs-5"></i>
                            @elseif($method === 'credit_card') <i class="bi bi-credit-card-2-front fs-5"></i>
                            @elseif($method === 'wire_transfer') <i class="bi bi-globe fs-5"></i>
                            @elseif($method === 'crypto') <i class="bi bi-currency-bitcoin fs-5 text-warning"></i>
                            @endif
                        </div>
                        <div>
                            <strong class="text-dark d-block" style="font-size: 0.88rem;">
                                @if($method === 'bank_transfer') Bank Transfer / GCash
                                @elseif($method === 'credit_card') Credit / Debit Card
                                @elseif($method === 'wire_transfer') Wire Transfer
                                @elseif($method === 'crypto') USDT (TRC-20)
                                @endif
                            </strong>
                            <code class="text-muted" style="font-size: 0.75rem;">DEP-2024-000125</code>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4">
                        <div>
                            <strong class="text-dark d-block">$500.00 USD</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">500 AVC</span>
                        </div>

                        <div>
                            <span class="badge bg-warning bg-opacity-20 text-warning-800 fw-semibold px-2.5 py-1 rounded-2" style="background: #fef3c7; color: #92400e; font-size: 0.75rem;">
                                Awaiting Verification
                            </span>
                            <span class="text-muted d-block mt-0.5" style="font-size: 0.7rem;">May 21, 2024 &bull; 09:30 AM</span>
                        </div>

                        <a href="{{ route('deposit.index') }}" class="text-muted"><i class="bi bi-chevron-right fs-5"></i></a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function depositChannelApp(method) {
    return {
        depositAmount: 500,
        estimatedAvc: 500,
        selectedCrypto: 'USDT',
        selectedNetwork: 'TRC-20',

        init() {
            this.updateConversion();
        },

        updateConversion() {
            if (this.depositAmount < 1) this.depositAmount = 1;
            this.estimatedAvc = this.depositAmount;
        },

        updateNetworks() {
            if (this.selectedCrypto.includes('USDT')) this.selectedNetwork = 'TRC-20';
            else if (this.selectedCrypto.includes('BTC')) this.selectedNetwork = 'BTC Network';
            else if (this.selectedCrypto.includes('ETH')) this.selectedNetwork = 'ERC-20';
            else if (this.selectedCrypto.includes('BNB')) this.selectedNetwork = 'BEP-20';
            else if (this.selectedCrypto.includes('SOL')) this.selectedNetwork = 'Solana Network';
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
