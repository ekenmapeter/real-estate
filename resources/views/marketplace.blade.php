@extends('layouts.main')

@section('title', 'CreditSwap Marketplace | ' . site_name())

@section('content')
<style>
    [x-cloak] { display: none !important; }

    .custom-modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100vw; height: 100vh;
        background: rgba(11, 19, 41, 0.75) !important;
        backdrop-filter: blur(10px) !important;
        z-index: 99999 !important;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 1rem;
        overflow-y: auto;
    }

    .custom-modal-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 540px;
        width: 100%;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin: auto;
    }

    .market-card {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .market-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px 0 rgba(39, 86, 253, 0.12) !important;
        border-color: rgba(39, 86, 253, 0.35) !important;
    }
</style>

<div x-data="marketplaceEngine()">
<!-- Marketplace Hero Section -->
<section class="flat-slider home-1 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat; padding: 90px 0 80px;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-start reveal-on-scroll delay-1">
                <h1 class="title-large text-white mb-3" style="font-size: clamp(2.2rem, 4.2vw, 3.4rem); line-height: 1.15; font-weight: 900; letter-spacing: -0.5px;">
                    CREDITSWAP<br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">MARKETPLACE</span>
                </h1>
                <h5 class="text-white-50 fw-bold mb-3">Safely buy or sell {{ config('app.name', 'RDR') }} Credits with admin-assisted escrow.</h5>
                <p class="subtitle text-white-50 body-2 mb-4" style="font-size: 1.05rem; line-height: 1.65; font-weight: 500;">
                    Send or receive through bank or other secure methods. Every deal is handled by our finance team via Telegram and fully escrowed — your credits are only released when the deal is complete.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3 align-items-center">
                    <a href="#listings" class="tf-btn primary shadow-lg fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1rem; border-radius: 8px; font-weight: 800;">Browse Listings</a>
                    <button type="button" class="tf-btn glass-panel text-white fw-bold" style="min-width: 175px; padding: 15px 30px; font-size: 1rem; border-radius: 8px; font-weight: 700;" @click="showHowItWorks = true">
                        <i class="bi bi-journal-bookmark me-1"></i> How It Works
                    </button>
                </div>
            </div>
            <div class="col-lg-5 text-center position-relative reveal-on-scroll delay-2">
                <div class="hero-image-wrapper position-relative d-inline-block">
                    <img src="https://radiantdreamrealty.com/frontend/images/home/house-1.jpg" alt="CreditSwap Marketplace" class="img-fluid rounded-4 shadow-lg border border-2 border-white-50" style="max-height: 420px; object-fit: cover; width: 100%;">
                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 rounded-4 glass-panel-dark" style="max-width: 280px;">
                        <div class="d-flex align-items-center gap-3 text-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-0 fw-bold" style="font-size: 0.9rem;">Admin-Assisted Escrow</h6>
                                <small class="text-white-50" style="font-size: 0.78rem;">Every deal is monitored &amp; released by the finance team.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Action Buttons -->
<section class="py-4 border-bottom glass-panel">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-0" style="font-size:1.15rem;"><i class="bi bi-arrow-repeat text-warning me-2"></i>AVC Marketplace</h5>
                <small class="text-muted">1 AVC = 1 USD &middot; @auth Your balance: <strong class="text-success">{{ format_avc($walletBalance) }}</strong> @endauth</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-warning fw-bold px-4 py-2 rounded-3 text-dark shadow-sm" @click="showCreateModal = true">
                    <i class="bi bi-plus-circle-fill me-1"></i> Create Listing
                </button>
                <a href="#my-listings" class="btn btn-outline-primary fw-bold px-4 py-2 rounded-3">
                    <i class="bi bi-journal-text me-1"></i> My Listings
                </a>
                <button type="button" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3" @click="showHowItWorks = true">
                    <i class="bi bi-journal-bookmark me-1"></i> How It Works
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Filters & Search -->
<section id="listings" class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shop text-primary me-2"></i>Available Listings</h5>
            <span class="badge bg-light text-muted fw-bold rounded-pill px-3 py-1.5">{{ $activeOffers->count() }} live</span>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size:0.68rem; letter-spacing:0.05em;">COUNTRY</label>
                    <select class="form-select form-select-sm rounded-3" x-model="filters.country">
                        <option value="">All Countries</option>
                        @foreach($activeOffers->pluck('country')->filter()->unique()->sort()->values() as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size:0.68rem; letter-spacing:0.05em;">MIN AMOUNT</label>
                    <input type="number" min="0" step="0.01" class="form-control form-control-sm rounded-3" x-model="filters.amountMin" placeholder="e.g. 100">
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size:0.68rem; letter-spacing:0.05em;">MAX AMOUNT</label>
                    <input type="number" min="0" step="0.01" class="form-control form-control-sm rounded-3" x-model="filters.amountMax" placeholder="e.g. 5000">
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size:0.68rem; letter-spacing:0.05em;">LISTING TYPE</label>
                    <select class="form-select form-select-sm rounded-3" x-model="filters.type">
                        <option value="">All Types</option>
                        <option value="sell">I Want to Sell Credits</option>
                        <option value="buy">I Want to Buy Credits</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size:0.68rem; letter-spacing:0.05em;">PAYMENT METHOD</label>
                    <input type="text" class="form-control form-control-sm rounded-3" x-model="filters.payment" placeholder="e.g. Bank Transfer, PayPal, GCash...">
                </div>
            </div>
            <div class="mt-3 d-flex flex-wrap align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary fw-bold rounded-3 px-3" @click="filters = { country: '', amountMin: '', amountMax: '', payment: '', type: '' }">
                    <i class="bi bi-x-circle me-1"></i> Clear Filters
                </button>
                <span class="small text-muted" x-text="filteredOffers().length + ' of ' + offers.length + ' listings'"></span>
            </div>
        </div>

        <!-- Listings Grid -->
        <div class="row g-4">
            @forelse($activeOffers as $swap)
                @php
                    $isBuyOffer = $swap->offer_type === 'buy';
                    $posterName = $swap->seller->name ?? 'Verified User';
                    $dealMsg = $isBuyOffer
                        ? 'Hello Finance Team, I\'d like to sell my AVC for Listing ' . $swap->listingLabel() . ' (' . format_avc($swap->amount) . '). Payment method: ' . $swap->payment_method . '. Please guide me through the next steps.'
                        : 'Hello Finance Team, I\'m interested in Listing ' . $swap->listingLabel() . '. I\'d like to buy ' . format_avc($swap->amount) . '. My payment method: ' . $swap->payment_method . '. Please guide me through the next steps.';
                @endphp
                <div class="col-lg-4 col-md-6" x-show="matchesOffer(@js($swap->country ?? ''), @js((float) $swap->amount), @js($swap->payment_method ?? ''), @js($swap->offer_type))">
                    <div class="card h-100 border-0 rounded-4 shadow-sm bg-white market-card p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge fw-bold px-2.5 py-1 rounded-pill" style="background:#eff6ff; color:#2563eb; font-size:0.7rem;">
                                <i class="bi bi-tag-fill me-1"></i> Listing {{ $swap->listingLabel() }}
                            </span>
                            <span class="badge fw-bold px-2 py-1 rounded-pill" style="{{ $isBuyOffer ? 'background:#f0fdf4; color:#16a34a;' : 'background:#eff6ff; color:#2563eb;' }} font-size:0.65rem;">
                                {{ $isBuyOffer ? 'BUYING' : 'SELLING' }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:34px; height:34px; font-size:0.72rem; background:linear-gradient(135deg,#2563eb,#3b82f6) !important;">
                                {{ strtoupper(substr(masked_name($posterName), 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold small text-dark" style="font-size:0.82rem;">{{ masked_name($posterName) }}</div>
                                <small class="text-success fw-semibold" style="font-size:0.68rem;"><i class="bi bi-patch-check-fill me-1"></i>Verified</small>
                            </div>
                        </div>

                        <h3 class="fw-bold text-dark mb-2">{{ format_avc($swap->amount) }}</h3>

                        <div class="d-flex flex-wrap gap-1.5 mb-3">
                            <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f8fafc; color:#475569; font-size:0.68rem; border:1px solid #e2e8f0;">
                                <i class="bi bi-geo-alt-fill me-1" style="color:#2563eb;"></i> {{ $swap->country ?? 'Not specified' }}
                            </span>
                            <span class="badge fw-semibold rounded-pill px-2 py-1" style="background:#f8fafc; color:#475569; font-size:0.68rem; border:1px solid #e2e8f0;">
                                <i class="bi bi-credit-card me-1" style="color:#16a34a;"></i> {{ ucwords(str_replace('_', ' ', $swap->payment_method)) }}
                            </span>
                        </div>

                        @if($swap->notes)
                            <div class="p-2 rounded-3 bg-light border small mb-3" style="font-size:0.75rem; color:#475569;">
                                <i class="bi bi-chat-left-text me-1 text-muted"></i> {{ Str::limit($swap->notes, 90) }}
                            </div>
                        @endif

                        <div class="mt-auto">
                            @auth
                                @if($user && $swap->user_id === $user->id)
                                    <button class="btn btn-outline-secondary btn-sm w-100 fw-bold rounded-3" disabled>
                                        <i class="bi bi-person-check me-1"></i> Your Listing
                                    </button>
                                @elseif(!telegram_handle())
                                    <button class="btn btn-secondary btn-sm w-100 fw-bold rounded-3" disabled>
                                        <i class="bi bi-telegram me-1"></i> Deals Temporarily Disabled
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 py-2" style="background:#2563eb;"
                                            @click="openDeal(@js([
                                                'id' => $swap->id,
                                                'label' => $swap->listingLabel(),
                                                'amount' => format_avc($swap->amount),
                                                'payment' => ucwords(str_replace('_', ' ', $swap->payment_method)),
                                                'type' => $swap->offer_type,
                                            ]))">
                                        <i class="bi bi-telegram me-1"></i> View Deal via Telegram
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 py-2" style="background:#2563eb;">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Start a Deal
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center text-muted">
                        <i class="bi bi-arrow-repeat fs-1 d-block mb-2 text-warning opacity-50"></i>
                        <h6 class="fw-bold text-dark">No Active Listings Yet</h6>
                        <p class="small mb-3">Be the first to post a buy or sell AVC listing.</p>
                        <div>
                            <button class="btn btn-primary btn-sm fw-bold px-4 py-2 rounded-3" style="background:#2563eb;" @click="showCreateModal = true">
                                <i class="bi bi-plus-lg me-1"></i> Create Listing
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
            <div class="col-12" x-show="offers.length > 0 && filteredOffers().length === 0" x-cloak>
                <div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center text-muted">
                    <i class="bi bi-search fs-1 d-block mb-2 text-primary opacity-50"></i>
                    <h6 class="fw-bold text-dark">No Listings Match Your Filters</h6>
                    <p class="small mb-0">Try adjusting the country, amount, type or payment method filters above.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- My Listings -->
<section id="my-listings" class="py-5" style="background-color: #ffffff;">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1"><i class="bi bi-journal-text text-primary me-2"></i>My Listings</h4>
                <p class="text-muted small mb-0">Track your listings: pending approval, live, in a deal, or completed.</p>
            </div>
            <button type="button" class="btn btn-warning fw-bold px-4 py-2 rounded-3 text-dark shadow-sm" @click="showCreateModal = true">
                <i class="bi bi-plus-circle-fill me-1"></i> Create Listing
            </button>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size:0.85rem; border-collapse:separate; border-spacing:0;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th class="px-3 py-2.5 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">LISTING</th>
                            <th class="py-2.5 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">TYPE</th>
                            <th class="py-2.5 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">AMOUNT</th>
                            <th class="py-2.5 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">PAYMENT</th>
                            <th class="py-2.5 small fw-bold text-muted" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">STATUS</th>
                            <th class="px-3 py-2.5 small fw-bold text-muted text-end" style="border-bottom:1px solid #e2e8f0; font-size:0.7rem; letter-spacing:0.06em;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mySwaps as $mySwap)
                            @php
                                $isBuy = $mySwap->offer_type === 'buy';
                                $isPoster = $mySwap->user_id === $user->id;
                                $isSeller = $isBuy ? $mySwap->seller_id === $user->id : $isPoster;
                                $counterparty = $isBuy
                                    ? ($isSeller ? $mySwap->seller : $mySwap->responder)
                                    : ($isPoster ? $mySwap->buyer : $mySwap->seller);
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td class="px-3 py-3">
                                    <code class="fw-bold text-primary">#{{ $mySwap->listingLabel() }}</code>
                                    <small class="d-block text-muted" style="font-size:0.68rem;">{{ $mySwap->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="badge fw-bold px-2 py-1 rounded-pill" style="{{ $isBuy ? 'background:#f0fdf4; color:#16a34a;' : 'background:#eff6ff; color:#2563eb;' }}">
                                        {{ $isBuy ? 'BUY' : 'SELL' }}
                                    </span>
                                </td>
                                <td class="py-3 fw-bold">{{ format_avc($mySwap->amount) }}</td>
                                <td class="py-3 text-muted">{{ ucwords(str_replace('_', ' ', $mySwap->payment_method)) }}</td>
                                <td class="py-3">
                                    <span class="badge fw-bold px-2 py-1 rounded-pill" style="{{ $mySwap->status === 'completed' ? 'background:#f0fdf4; color:#16a34a;' : ($mySwap->status === 'pending' ? 'background:#fffbeb; color:#d97706;' : ($mySwap->status === 'pending_payment' ? 'background:#fffbeb; color:#d97706;' : ($mySwap->status === 'in_deal' ? 'background:#eff6ff; color:#2563eb;' : ($mySwap->status === 'active' ? 'background:#e0f2fe; color:#0284c7;' : ($mySwap->status === 'paused' ? 'background:#fff7ed; color:#ea580c;' : 'background:#fef2f2; color:#dc2626;'))))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $mySwap->status)) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-end">
                                    @if($mySwap->status === 'in_deal' || $mySwap->status === 'pending_payment')
                                        @if($isSeller)
                                            <form action="{{ route('credit-swap.release', $mySwap->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-2.5 py-1 rounded-3" onclick="return confirm('Confirm payment received? This releases {{ format_avc($mySwap->amount) }} to the buyer.')">
                                                    <i class="bi bi-check-circle me-1"></i> Release AVC
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-warning fw-bold small"><i class="bi bi-clock me-1"></i> Awaiting Finance Team</span>
                                        @endif
                                    @elseif($isPoster && in_array($mySwap->status, ['pending', 'active']))
                                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold px-2.5 py-1 rounded-3 me-1"
                                                @click="openEdit(@js([
                                                    'id' => $mySwap->id,
                                                    'amount' => (float) $mySwap->amount,
                                                    'country' => $mySwap->country ?? '',
                                                    'payment_method' => $mySwap->payment_method ?? '',
                                                    'notes' => $mySwap->notes ?? '',
                                                ]))">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                        <form action="{{ route('credit-swap.cancel', $mySwap->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-2.5 py-1 rounded-3" onclick="return confirm('Cancel this listing? {{ $mySwap->offer_type === 'sell' ? 'Escrowed AVC will be returned to your balance.' : '' }}')">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif($isPoster && in_array($mySwap->status, ['rejected', 'cancelled']))
                                        <form action="{{ route('credit-swap.repost', $mySwap->id) }}" method="POST" class="d-inline me-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary fw-bold px-2.5 py-1 rounded-3" style="background:#2563eb;">
                                                <i class="bi bi-arrow-repeat me-1"></i> Repost
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold px-2.5 py-1 rounded-3"
                                                @click="openEdit(@js([
                                                    'id' => $mySwap->id,
                                                    'amount' => (float) $mySwap->amount,
                                                    'country' => $mySwap->country ?? '',
                                                    'payment_method' => $mySwap->payment_method ?? '',
                                                    'notes' => $mySwap->notes ?? '',
                                                ]))">
                                            <i class="bi bi-pencil me-1"></i> Edit &amp; Resubmit
                                        </button>
                                    @elseif($mySwap->status === 'paused')
                                        <span class="text-danger fw-bold small"><i class="bi bi-pause-circle me-1"></i> Paused by Admin</span>
                                    @elseif($mySwap->status === 'completed')
                                        <span class="text-success fw-bold small"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
                                    @else
                                        <span class="text-muted small">--</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-25"></i>
                                    No listings yet. Create your first buy or sell listing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Safety Note Banner -->
<section class="py-4" style="background-color: #f8fafc;">
    <div class="container">
        <div class="card border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #451a03 0%, #7c2d12 100%);">
            <div class="p-4 d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; background:rgba(255,255,255,0.12);">
                        <i class="bi bi-shield-exclamation fs-3 text-warning"></i>
                    </div>
                </div>
                <div class="flex-grow-1 text-white">
                    <h6 class="fw-bold mb-1" style="font-size:0.95rem;">Safety Note</h6>
                    <ul class="list-unstyled small mb-0" style="color:#fdba74; line-height:1.9;">
                        <li><i class="bi bi-exclamation-triangle-fill me-2" style="font-size:0.7rem;"></i>Only use the official Finance Team Telegram channel for deals.</li>
                        <li><i class="bi bi-check-circle-fill me-2 text-success" style="font-size:0.7rem;"></i>All deals are monitored by {{ site_name() }} admins for transparency.</li>
                        <li><i class="bi bi-x-circle-fill me-2 text-danger" style="font-size:0.7rem;"></i>Never send funds outside admin guidance.</li>
                    </ul>
                </div>
                @if(telegram_handle())
                    <a href="{{ telegram_url('Hello Finance Team, I have a question about the CreditSwap Marketplace.') }}" target="_blank" rel="noopener" class="btn btn-warning fw-bold px-4 py-2 rounded-3 text-dark flex-shrink-0">
                        <i class="bi bi-telegram me-1"></i> Contact Finance Team
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- CREATE LISTING MODAL -->
<!-- ========================================== -->
<div x-show="showCreateModal" x-cloak class="custom-modal-backdrop" @click.self="showCreateModal = false">
    <div class="custom-modal-card p-4" style="max-width:560px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle-fill text-warning me-2"></i>Create Listing</h5>
                <small class="text-muted">Listings are reviewed by the finance team before going live.</small>
            </div>
            <button type="button" class="btn-close" @click="showCreateModal = false"></button>
        </div>

        <form action="{{ route('credit-swap.create') }}" method="POST" x-data="{ swapType: 'sell' }">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">I Want To <span class="text-danger">*</span></label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="offer_type" id="mp_type_sell" value="sell" checked @change="swapType = 'sell'">
                    <label class="btn btn-outline-primary fw-bold" for="mp_type_sell"><i class="bi bi-cash-coin me-1"></i> Sell Credits</label>
                    <input type="radio" class="btn-check" name="offer_type" id="mp_type_buy" value="buy" @change="swapType = 'buy'">
                    <label class="btn btn-outline-success fw-bold" for="mp_type_buy"><i class="bi bi-bag-plus me-1"></i> Buy Credits</label>
                </div>
                <div class="form-text small text-muted" x-show="swapType === 'sell'">Your credits are held in escrow until the deal is completed. Available: {{ format_avc($walletBalance) }}</div>
                <div class="form-text small text-muted" x-show="swapType === 'buy'">A seller can respond to your listing. The finance team arranges the deal over Telegram.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">Amount of Credits (AVC) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted fw-bold">AVC</span>
                    <input type="number" step="0.01" min="10" name="amount" class="form-control fw-bold" placeholder="e.g. 500.00" required>
                </div>
                <div class="form-text small text-muted">Minimum 10 AVC. 1 AVC = 1 USD.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">Your Preferred Payment Method <span class="text-danger">*</span></label>
                <input type="text" name="payment_method" class="form-control" placeholder="e.g. Bank Transfer, PayPal, GCash, Crypto..." required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">Your Country <span class="text-danger">*</span></label>
                <input type="text" name="country" class="form-control" placeholder="e.g. United States" required>
                <div class="form-text small text-muted">So buyers &amp; sellers can see which country you are offering from.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-dark">Optional Notes</label>
                <textarea name="notes" class="form-control rounded-3" rows="2" maxlength="1000" placeholder="Anything buyers or sellers should know (no bank or account numbers — those are exchanged privately via the finance team)."></textarea>
            </div>

            <div class="alert alert-warning py-2 px-3 small rounded-3 mb-3" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e; font-size:0.78rem;">
                <i class="bi bi-shield-check me-1"></i> Never include bank or account numbers on your public listing. Payment instructions are arranged privately by the finance team.
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary fw-bold w-50 py-2 rounded-3" @click="showCreateModal = false">Cancel</button>
                <button type="submit" class="btn btn-warning fw-bold w-50 py-2 rounded-3 text-dark shadow-sm">
                    <i class="bi bi-upload me-1"></i> Submit Listing
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- HOW IT WORKS MODAL -->
<!-- ========================================== -->
<div x-show="showHowItWorks" x-cloak class="custom-modal-backdrop" @click.self="showHowItWorks = false">
    <div class="custom-modal-card p-4" style="max-width:620px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-bookmark text-primary me-2"></i>How the Marketplace Works</h5>
            <button type="button" class="btn-close" @click="showHowItWorks = false"></button>
        </div>
        <ol class="list-unstyled mb-4" style="color:#475569;">
            <li class="d-flex gap-3 mb-3">
                <span class="badge fw-bold rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:28px; height:28px; background:#eff6ff; color:#2563eb;">1</span>
                <span><strong class="text-dark">Create a listing</strong> — post a <strong>Sell</strong> listing to sell your AVC (escrowed instantly) or a <strong>Buy</strong> listing to purchase AVC.</span>
            </li>
            <li class="d-flex gap-3 mb-3">
                <span class="badge fw-bold rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:28px; height:28px; background:#eff6ff; color:#2563eb;">2</span>
                <span><strong class="text-dark">Admin approval</strong> — every listing is reviewed and assigned a Listing Number before going live.</span>
            </li>
            <li class="d-flex gap-3 mb-3">
                <span class="badge fw-bold rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:28px; height:28px; background:#eff6ff; color:#2563eb;">3</span>
                <span><strong class="text-dark">Deal via Telegram</strong> — interested buyers/sellers start a deal which opens a pre-filled message to the Finance Team.</span>
            </li>
            <li class="d-flex gap-3 mb-3">
                <span class="badge fw-bold rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:28px; height:28px; background:#eff6ff; color:#2563eb;">4</span>
                <span><strong class="text-dark">Finance team handles the deal</strong> — seller availability, payment instructions, confirmation of receipt, and credits transfer check.</span>
            </li>
            <li class="d-flex gap-3 mb-3">
                <span class="badge fw-bold rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:28px; height:28px; background:#eff6ff; color:#2563eb;">5</span>
                <span><strong class="text-dark">Final release</strong> — once payment is confirmed, credits are released from escrow to the buyer and the listing is removed from the marketplace. Admin may pause or cancel any suspicious deal at any point.</span>
            </li>
        </ol>
        @if(telegram_handle())
            <a href="{{ telegram_url('Hello Finance Team, I have a question about the CreditSwap Marketplace.') }}" target="_blank" rel="noopener" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;">
                <i class="bi bi-telegram me-1"></i> Chat with the Finance Team ({{ '@' . telegram_handle() }})
            </a>
        @else
            <div class="alert alert-warning py-2 px-3 small rounded-3 mb-0" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e;">
                The Telegram support channel is not configured yet. Please contact support.
            </div>
        @endif
    </div>
</div>

<!-- ========================================== -->
<!-- DEAL CONFIRM MODAL -->
<!-- ========================================== -->
<div x-show="dealSwap" x-cloak class="custom-modal-backdrop" @click.self="dealSwap = null">
    <div class="custom-modal-card p-4" style="max-width:540px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-telegram text-primary me-2"></i>Start a Deal</h5>
                <small class="text-muted" x-text="'Listing ' + (dealSwap?.label || '')"></small>
            </div>
            <button type="button" class="btn-close" @click="dealSwap = null"></button>
        </div>
        <div class="p-3 rounded-3 bg-light border mb-3">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Amount</span><strong class="text-dark" x-text="dealSwap?.amount"></strong></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Payment Method</span><strong class="text-dark" x-text="dealSwap?.payment"></strong></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Type</span><strong class="text-dark" x-text="dealSwap?.type === 'buy' ? 'Selling Credits' : 'Buying Credits'"></strong></div>
        </div>
        <div class="p-3 rounded-3 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe;">
            <label class="form-label small fw-bold text-muted mb-1" style="font-size:0.7rem; letter-spacing:0.05em;">PRE-FILLED TELEGRAM MESSAGE</label>
            <p class="small mb-0 text-dark" style="line-height:1.6;" x-text="dealMessage()"></p>
        </div>
        <form :action="'/credit-swap/deal/' + (dealSwap?.id || '')" method="POST" target="_blank">
            @csrf
            <button type="submit" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2563eb;">
                <i class="bi bi-send me-1"></i> Open Telegram &amp; Start Deal
            </button>
        </form>
        <small class="text-muted d-block text-center mt-2" style="font-size:0.72rem;">The deal is recorded instantly. The finance team will guide you through payment and escrow release.</small>
    </div>
</div>

<!-- ========================================== -->
<!-- EDIT LISTING MODAL -->
<!-- ========================================== -->
<div x-show="editSwap" x-cloak class="custom-modal-backdrop" @click.self="editSwap = null">
    <div class="custom-modal-card p-4" style="max-width:540px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Listing</h5>
                <small class="text-muted">Changes are re-submitted for admin review.</small>
            </div>
            <button type="button" class="btn-close" @click="editSwap = null"></button>
        </div>
        <form :action="'/credit-swap/update/' + (editSwap?.id || '')" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">Amount (AVC) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted fw-bold">AVC</span>
                    <input type="number" step="0.01" min="10" name="amount" class="form-control fw-bold" x-model.number="editForm.amount" required>
                </div>
                <div class="form-text small text-muted">Escrow is adjusted automatically for sell listings.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">Your Country <span class="text-danger">*</span></label>
                <input type="text" name="country" class="form-control" x-model="editForm.country" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small text-dark">Preferred Payment Method <span class="text-danger">*</span></label>
                <input type="text" name="payment_method" class="form-control" x-model="editForm.payment_method" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small text-dark">Optional Notes</label>
                <textarea name="notes" class="form-control rounded-3" rows="2" maxlength="1000" x-model="editForm.notes"></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary fw-bold w-50 py-2 rounded-3" @click="editSwap = null">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold w-50 py-2 rounded-3" style="background:#2563eb;">
                    <i class="bi bi-save me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function marketplaceEngine() {
        return {
            filters: { country: '', amountMin: '', amountMax: '', payment: '', type: '' },
            offers: @json($activeOffers->map(fn ($s) => ['country' => $s->country ?? '', 'amount' => (float) $s->amount, 'payment' => $s->payment_method ?? '', 'type' => $s->offer_type])->values()),
            showCreateModal: false,
            showHowItWorks: false,
            dealSwap: null,
            editSwap: null,
            editForm: { amount: '', country: '', payment_method: '', notes: '' },

            matchesOffer(country, amount, payment, type) {
                const m = (v) => String(v ?? '').toLowerCase().trim();
                const f = this.filters;
                if (f.country && m(country) !== m(f.country)) return false;
                if (f.amountMin !== '' && !isNaN(parseFloat(f.amountMin)) && parseFloat(amount) < parseFloat(f.amountMin)) return false;
                if (f.amountMax !== '' && !isNaN(parseFloat(f.amountMax)) && parseFloat(amount) > parseFloat(f.amountMax)) return false;
                if (f.payment && !m(payment).includes(m(f.payment))) return false;
                if (f.type && type !== f.type) return false;
                return true;
            },

            filteredOffers() {
                return this.offers.filter(o => this.matchesOffer(o.country, o.amount, o.payment, o.type));
            },

            openDeal(swap) {
                this.dealSwap = swap;
            },

            dealMessage() {
                const s = this.dealSwap;
                if (!s) return '';
                return s.type === 'sell'
                    ? 'Hello Finance Team, I\'m interested in Listing ' + s.label + '. I\'d like to buy ' + s.amount + '. My payment method: ' + s.payment + '. Please guide me through the next steps.'
                    : 'Hello Finance Team, I\'d like to sell my AVC for Listing ' + s.label + ' (' + s.amount + '). My payment method: ' + s.payment + '. Please guide me through the next steps.';
            },

            openEdit(swap) {
                this.editSwap = swap;
                this.editForm = {
                    amount: swap.amount,
                    country: swap.country,
                    payment_method: swap.payment_method,
                    notes: swap.notes || ''
                };
            }
        }
    }
</script>
</div>
@endsection
