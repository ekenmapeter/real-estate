@php
    $user = Auth::user();
    $activeRoute = request()->route() ? request()->route()->getName() : '';
    $userBalance = $user ? (float) ($user->wallet_balance ?? 27500.00) : 27500.00;
@endphp

<div class="app-sidebar-wrap p-3 bg-white border-end h-100" style="min-height: 100vh; font-family: 'Inter', sans-serif;">
    
    <!-- User Profile Widget -->
    <div class="p-3 mb-4 rounded-4 bg-light border d-flex align-items-center gap-3">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm flex-shrink-0" style="width: 48px; height: 48px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;">
            {{ strtoupper(substr($user->name ?? 'Sarah Jenkins', 0, 2)) }}
        </div>
        <div class="overflow-hidden">
            <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.95rem;">{{ $user->name ?? 'Sarah Jenkins' }}</h6>
            <span class="text-muted small d-block" style="font-size: 0.78rem;">Investor</span>
            @if(($user->kyc_verified ?? true))
                <span class="badge fw-semibold rounded-pill px-2 py-0.5 mt-1" style="font-size: 0.68rem; background: rgba(34, 197, 94, 0.15); color: #15803d;">
                    Verified
                </span>
            @else
                <span class="badge fw-semibold rounded-pill px-2 py-0.5 mt-1" style="font-size: 0.68rem; background: rgba(239, 68, 68, 0.15); color: #dc2626;">
                    Unverified
                </span>
            @endif
        </div>
    </div>

    <!-- Navigation Groups -->
    <nav class="nav flex-column gap-1 mb-4">
        
        <!-- GROUP: MY PORTFOLIO -->
        <span class="sidebar-group-header">MY PORTFOLIO</span>
        
        <a href="{{ url('/dashboard') }}" class="sidebar-item {{ $activeRoute == 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        
        <a href="{{ route('marketplace.index') }}" class="sidebar-item {{ str_contains($activeRoute, 'marketplace') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> Project Marketplace
        </a>

        <a href="{{ url('/properties') }}" class="sidebar-item {{ request()->is('properties*') ? 'active' : '' }}">
            <i class="bi bi-search"></i> Browse Properties
        </a>

        <a href="{{ route('portfolio.index') }}" class="sidebar-item {{ $activeRoute == 'portfolio.index' ? 'active' : '' }}">
            <i class="bi bi-briefcase-fill"></i> My Project Shares
        </a>

        <a href="{{ route('portfolio.index') }}#active" class="sidebar-item">
            <i class="bi bi-clock-history"></i> Active Cycles
        </a>

        <a href="{{ route('portfolio.index') }}#saved" class="sidebar-item">
            <i class="bi bi-bookmark-star-fill"></i> Saved Projects
        </a>

        <a href="{{ route('portfolio.index') }}#completed" class="sidebar-item">
            <i class="bi bi-check-circle-fill"></i> Completed Cycles
        </a>

        <!-- GROUP: PROPERTY TOOLS -->
        <span class="sidebar-group-header mt-3">PROPERTY TOOLS</span>

        <a href="{{ route('properties.create') }}" class="sidebar-item {{ request()->routeIs('properties.create', 'properties.store') ? 'active' : '' }}">
            <i class="bi bi-plus-square"></i> List Your Property
        </a>

        <a href="{{ route('properties.mine') }}" class="sidebar-item {{ request()->routeIs('properties.mine', 'properties.edit', 'properties.update') ? 'active' : '' }}">
            <i class="bi bi-house-gear"></i> My Property Listings
        </a>

        <a href="{{ route('properties.saved') }}" class="sidebar-item {{ request()->routeIs('properties.saved') ? 'active' : '' }}">
            <i class="bi bi-bookmark-heart"></i> Saved Properties
        </a>

        <a href="{{ route('properties.inquiries') }}" class="sidebar-item {{ request()->routeIs('properties.inquiries') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i> Property Inquiries
        </a>

        <a href="{{ route('properties.viewing-requests') }}" class="sidebar-item {{ request()->routeIs('properties.viewing-requests') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Viewing Requests
        </a>

        <!-- GROUP: WALLET & FINANCE CENTER -->
        <span class="sidebar-group-header mt-3">WALLET & FINANCE</span>
        
        <a href="{{ route('finance.overview') }}" class="sidebar-item {{ request()->is('finance') ? 'active' : '' }}">
            <i class="bi bi-wallet2 text-primary"></i> Finance Center
        </a>

        <a href="{{ route('finance.team.index') }}" class="sidebar-item {{ request()->is('finance/team*') ? 'active' : '' }}">
            <i class="bi bi-people-fill text-info"></i> Finance Team Desk
            @php
                $pendingTeamCount = \App\Models\FinanceRequest::where('user_id', Auth::id())->whereIn('status', ['under_review', 'payment_instructions_assigned', 'evidence_submitted'])->count();
            @endphp
            @if($pendingTeamCount > 0)
                <span class="badge bg-warning text-dark rounded-pill ms-auto px-2 py-0.5" style="font-size:0.68rem;">{{ $pendingTeamCount }}</span>
            @endif
        </a>

        <a href="{{ route('deposit.index') }}" class="sidebar-item {{ request()->is('deposit*') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-circle-fill text-success"></i> Deposit / Buy AVC
        </a>

        <a href="{{ route('withdraw.index') }}" class="sidebar-item {{ request()->is('withdraw*') ? 'active' : '' }}">
            <i class="bi bi-arrow-up-circle-fill text-danger"></i> Withdraw / Sell AVC
        </a>

        <a href="{{ route('finance.transactions') }}" class="sidebar-item {{ request()->is('finance/transactions*') ? 'active' : '' }}">
            <i class="bi bi-clock-history text-info"></i> Transaction History
        </a>

        <a href="{{ route('avc-marketplace.index') }}" class="sidebar-item">
            <i class="bi bi-lightning-charge-fill text-warning"></i> AVC Marketplace
        </a>

        <!-- GROUP: ACCOUNT -->
        <span class="sidebar-group-header mt-3">ACCOUNT</span>

        <a href="{{ route('dashboard') }}" class="sidebar-item">
            <i class="bi bi-bell-fill"></i> Notifications
            <span class="badge bg-danger rounded-pill ms-auto px-2 py-0.5" style="font-size: 0.68rem;">3</span>
        </a>

        <a href="{{ route('affiliate.center') }}" class="sidebar-item">
            <i class="bi bi-people-fill"></i> Affiliate Center
        </a>

        <a href="{{ route('profile.settings') }}" class="sidebar-item">
            <i class="bi bi-person-badge-fill"></i> Profile & Settings
        </a>

        <a href="{{ route('documents.index') }}" class="sidebar-item {{ $activeRoute == 'documents.index' ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i> Documents
        </a>

        <a href="{{ route('profile.settings') }}" class="sidebar-item">
            <i class="bi bi-gear-fill"></i> Settings
        </a>

        <a href="mailto:support@aurevia.com" class="sidebar-item">
            <i class="bi bi-question-circle-fill"></i> Help & Support
        </a>
    </nav>

    <!-- View Site & Logout -->
    <div class="d-flex flex-column gap-2 mb-4">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-primary fw-bold w-100 rounded-3 py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #2563eb; border: none;">
            View Site
        </a>
        
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light text-danger fw-semibold w-100 rounded-3 py-2 border-0 d-flex align-items-center justify-content-center gap-2" style="background: #fef2f2;">
                Logout
            </button>
        </form>
        @endauth
    </div>

    <!-- Available AVC Balance Widget (Matching Mockup Image 1) -->
    <div class="card border-0 rounded-4 text-white p-3 mb-4 shadow-sm" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
        <span class="text-white-50 small d-block fw-semibold mb-1" style="font-size: 0.78rem;">Available AVC Balance</span>
        <div class="d-flex align-items-center justify-content-between mb-1">
            <h3 class="fw-bold text-white mb-0">{{ number_format($userBalance, 0) }} <span class="fs-6 font-normal">AVC</span></h3>
            <i class="bi bi-eye-fill text-white-50"></i>
        </div>
        <span class="text-white-50 small d-block mb-3" style="font-size: 0.72rem;">≈ ${{ number_format($userBalance, 2) }} USD</span>
        
        <div class="d-flex gap-2 mb-2">
            <a href="{{ route('deposit.index') }}" class="btn btn-light btn-sm flex-fill fw-bold text-primary rounded-3" style="font-size: 0.75rem;">
                Deposit
            </a>
            <a href="{{ route('transfer.index') }}" class="btn btn-outline-light btn-sm flex-fill fw-bold rounded-3" style="font-size: 0.75rem;">
                AVC Transfer
            </a>
        </div>
        <a href="{{ route('avc-marketplace.index') }}" class="btn btn-primary btn-sm w-100 fw-bold rounded-3 border-light border-opacity-20 text-white" style="background: rgba(255,255,255,0.15); font-size: 0.75rem;">
            <i class="bi bi-lightning-charge-fill text-warning"></i> AVC Marketplace
        </a>
    </div>

    <!-- Quick Actions Widget -->
    <div class="bg-light p-3 rounded-4 border">
        <span class="sidebar-group-header d-block mb-2 text-warning fw-bold">QUICK ACTIONS</span>
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('marketplace.index') }}" class="btn btn-white border btn-sm text-start fw-semibold text-dark rounded-3 bg-white py-2">
                Buy Shares
            </a>
            <a href="{{ route('portfolio.index') }}" class="btn btn-white border btn-sm text-start fw-semibold text-dark rounded-3 bg-white py-2">
                <i class="bi bi-briefcase-fill"></i> My Project Shares
            </a>
            <a href="{{ route('portfolio.index') }}#active" class="btn btn-white border btn-sm text-start fw-semibold text-dark rounded-3 bg-white py-2">
                <i class="bi bi-clock-history"></i> Active Cycles
            </a>
            <a href="{{ route('portfolio.index') }}#saved" class="btn btn-white border btn-sm text-start fw-semibold text-dark rounded-3 bg-white py-2">
                <i class="bi bi-bookmark-star-fill"></i> Saved Projects
            </a>
        </div>
    </div>
</div>

<style>
    .sidebar-group-header {
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #94a3b8;
        padding: 0 10px;
        margin-bottom: 4px;
        display: block;
    }
    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .sidebar-item:hover {
        color: #2563eb;
        background-color: #eff6ff;
    }
    .sidebar-item.active {
        color: #ffffff !important;
        background-color: #2563eb !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .sidebar-item i {
        font-size: 1rem;
        width: 18px;
    }
</style>
