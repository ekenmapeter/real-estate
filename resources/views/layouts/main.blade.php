<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'radiantdreamrealty | Global Property Investment & Co-Ownership Platform')</title>
    <meta name="description" content="Real Estate and property investment platform">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- font -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/fonts.css') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/font-icons.css') }}">
    <!-- Bootstrap Icons CDN for bi-* icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- CSS Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/styles.css') }}" />

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('frontend/images/logo/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('frontend/images/logo/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
      /* ── Prevent Mobile Horizontal Scroll Overflow ── */
      html, body {
        max-width: 100vw;
        overflow-x: hidden;
      }

      /* ── Navbar base ── */
      .nav-link-item {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        font-size: 0.84rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        border-radius: 6px;
        white-space: nowrap;
        transition: color 0.18s ease, background 0.18s ease;
        line-height: 1;
      }
      .nav-link-item:hover,
      .nav-link-item.active {
        color: #2756fd;
        background: #eff4ff;
      }

      /* ── Partners dropdown items ── */
      .nav-dropdown-item {
        display: flex;
        align-items: center;
        padding: 9px 18px;
        font-size: 0.84rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
      }
      .nav-dropdown-item:hover {
        background: #f0f5ff;
        color: #2756fd;
      }

      /* push page content below fixed header */
      body { padding-top: 70px; }

      /* Mobile Sidebar - same as dashboard sidebar */
      .sidebar-dark {
        background-color: #0b1329 !important;
        color: #ffffff;
      }
      .sidebar-dark .nav-link-sidebar {
        color: #94a3b8;
        padding: 9px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        cursor: pointer;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
      }
      .sidebar-dark .nav-link-sidebar:hover,
      .sidebar-dark .nav-link-sidebar.active {
        color: #ffffff !important;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
      }
      .sidebar-dark .nav-link-sidebar i {
        font-size: 1.05rem;
        width: 18px;
        flex-shrink: 0;
      }
      .sidebar-group-label {
        color: #475569;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        padding: 0 14px;
        margin-top: 14px;
        margin-bottom: 4px;
        display: block;
      }

      /* Glassmorphism Design Tokens */
      .glass-panel {
        background: rgba(255, 255, 255, 0.72) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        box-shadow: 0 10px 30px 0 rgba(31, 38, 135, 0.07);
      }

      .glass-panel-dark {
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(20px) saturate(190%);
        -webkit-backdrop-filter: blur(20px) saturate(190%);
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
      }

      .glass-card {
        background: rgba(255, 255, 255, 0.82) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      }

      .glass-card:hover {
        transform: translateY(-8px) scale(1.015);
        box-shadow: 0 20px 40px 0 rgba(39, 86, 253, 0.14);
        border-color: rgba(39, 86, 253, 0.4) !important;
      }

      /* Navbar Glassmorphism */
      .main-header.header-fixed {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.4);
      }

      /* Scroll-Driven Motion Reveal Animations */
      .reveal-on-scroll {
        opacity: 0;
        transform: translateY(35px);
        transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
      }

      .reveal-on-scroll.is-visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* Stagger delays */
      .delay-1 { transition-delay: 0.1s; }
      .delay-2 { transition-delay: 0.2s; }
      .delay-3 { transition-delay: 0.3s; }
      .delay-4 { transition-delay: 0.4s; }

      /* ── Comprehensive Mobile Responsive Overrides ── */
      @media (max-width: 767.98px) {
        body {
          padding-top: 64px;
        }

        #header img {
          height: 34px !important;
        }

        .container, .container-fluid {
          padding-left: 14px !important;
          padding-right: 14px !important;
        }

        h1, .display-4, .display-5, .display-6 {
          font-size: clamp(1.75rem, 6vw, 2.5rem) !important;
          line-height: 1.25 !important;
        }

        h2 {
          font-size: clamp(1.4rem, 5vw, 1.85rem) !important;
        }

        h3 {
          font-size: clamp(1.25rem, 4.5vw, 1.5rem) !important;
        }

        .card-body {
          padding: 1.15rem !important;
        }

        .table-responsive {
          -webkit-overflow-scrolling: touch;
        }

        /* Mobile buttons touch targets */
        .btn {
          padding-top: 0.55rem;
          padding-bottom: 0.55rem;
        }

        /* Footer mobile stack spacing */
        footer .row {
          text-align: left;
        }
      }
    </style>
</head>

<body class="body">

    <div id="wrapper">
        <div id="pagee" class="clearfix">

            <!-- Main Header -->
            <header id="header" style="position: fixed; top: 0; left: 0; right: 0; z-index: 99999; background: #ffffff !important; border-bottom: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <div class="container-fluid px-3 px-lg-5">
                    <div class="d-flex align-items-center justify-content-between" style="height: 70px;">

                        <!-- Logo -->
                        <a href="{{ url('/') }}" class="flex-shrink-0 d-flex align-items-center">
                            <img src="{{ asset('frontend/images/logo/radiantblue.png') }}" alt="radiantdreamrealty" height="42" style="object-fit: contain;">
                        </a>

                        <!-- Desktop & Laptop Navigation (Visible on Large Screens >= 992px) -->
                        <nav class="d-none d-lg-flex align-items-center gap-1">
                            <a href="{{ url('/') }}" class="nav-link-item {{ request()->is('/') ? 'active' : '' }}">Home</a>
                            <a href="{{ url('/properties') }}" class="nav-link-item {{ request()->is('properties*') ? 'active' : '' }}">Properties</a>
                            <a href="{{ url('/invest') }}" class="nav-link-item {{ request()->is('invest') ? 'active' : '' }}">Invest</a>
                            <a href="{{ url('/list-property') }}" class="nav-link-item {{ request()->is('list-property*') ? 'active' : '' }}">List Property</a>

                            <!-- Partners Dropdown -->
                            <div class="nav-dropdown-wrap" style="position:relative;">
                                <button class="nav-link-item d-flex align-items-center gap-1 border-0 bg-transparent" style="cursor:pointer;" onclick="toggleNavDropdown(this)">
                                    Partners <i class="bi bi-chevron-down ms-1" style="font-size:0.7rem; transition: transform 0.2s;"></i>
                                </button>
                                <div class="nav-dropdown-menu" style="display:none; position:absolute; top:calc(100% + 8px); left:0; background:#ffffff; min-width:220px; border-radius:12px; box-shadow:0 12px 35px rgba(15,23,42,0.15); border:1px solid #e2e8f0; padding:8px 0; z-index:999999;">
                                    <a href="{{ url('/team') }}" class="nav-dropdown-item">
                                        <i class="bi bi-people-fill me-2 text-primary"></i>Meet The Team
                                    </a>
                                    <a href="{{ url('/agent') }}" class="nav-dropdown-item">
                                        <i class="bi bi-person-badge-fill me-2 text-primary"></i>Become an Agent
                                    </a>
                                    <a href="{{ url('/affiliate') }}" class="nav-dropdown-item">
                                        <i class="bi bi-share-fill me-2 text-primary"></i>Become an Affiliate
                                    </a>
                                    <a href="{{ url('/career') }}" class="nav-dropdown-item">
                                        <i class="bi bi-briefcase-fill me-2 text-primary"></i>Careers
                                    </a>
                                </div>
                            </div>

                            <a href="{{ url('/resources') }}" class="nav-link-item {{ request()->is('resources*') ? 'active' : '' }}">Resources</a>
                        </nav>

                        <!-- Right Header CTA & Profile Actions -->
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            @auth
                                <!-- User Dropdown (same as sidebar nav) -->
                                <div class="nav-dropdown-wrap" style="position:relative;">
                                    <button class="d-flex align-items-center gap-2 border-0 bg-transparent" style="cursor:pointer; padding:4px 8px; border-radius:8px;" onclick="toggleNavDropdown(this)">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:34px; height:34px; background:linear-gradient(135deg,#2563eb,#3b82f6)!important; font-size:0.8rem;">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <i class="bi bi-chevron-down" style="font-size:0.65rem; color:#64748b; transition:transform 0.2s;"></i>
                                    </button>
                                    <div class="nav-dropdown-menu" style="display:none; position:absolute; top:calc(100% + 6px); right:0; left:auto; background:#ffffff; min-width:230px; border-radius:12px; box-shadow:0 12px 40px rgba(15,23,42,0.18); border:1px solid #e2e8f0; padding:8px 0; z-index:999999;">
                                        <!-- User Info -->
                                        <div class="px-3 py-2 border-bottom mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                            <div class="fw-bold text-dark" style="font-size:0.9rem;">{{ Auth::user()->name ?? 'User' }}</div>
                                            @if(Auth::user()->kyc_verified)
                                                <small class="badge fw-semibold rounded-pill px-2" style="font-size:0.65rem; background:rgba(34,197,94,0.12); color:#16a34a;"><i class="bi bi-patch-check-fill me-1"></i>Verified</small>
                                            @else
                                                <small class="badge fw-semibold rounded-pill px-2" style="font-size:0.65rem; background:rgba(248,113,113,0.12); color:#f87171;"><i class="bi bi-shield-exclamation me-1"></i>Unverified</small>
                                            @endif
                                        </div>
                                            <small class="text-muted" style="font-size:0.78rem;">{{ Auth::user()->email ?? '' }}</small>
                                        </div>

                                        <span class="sidebar-group-label px-3" style="font-size:0.65rem; font-weight:800; letter-spacing:0.09em; text-transform:uppercase; color:#94a3b8; display:block; margin-top:6px; margin-bottom:2px;">My Portfolio</span>
                                        <a href="{{ url('/dashboard') }}" class="nav-dropdown-item"><i class="bi bi-grid-fill me-2 text-primary" style="width:16px;"></i>Dashboard</a>
                                        <a href="{{ url('/dashboard') }}#invest" class="nav-dropdown-item"><i class="bi bi-lightning-charge-fill me-2 text-primary" style="width:16px;"></i>Invest</a>
                                        <a href="{{ url('/properties') }}" class="nav-dropdown-item"><i class="bi bi-building me-2 text-primary" style="width:16px;"></i>Browse Properties</a>
                                        <a href="{{ url('/dashboard') }}#my_investments" class="nav-dropdown-item"><i class="bi bi-pie-chart-fill me-2 text-primary" style="width:16px;"></i>My Investments</a>

                                        <span class="sidebar-group-label px-3" style="font-size:0.65rem; font-weight:800; letter-spacing:0.09em; text-transform:uppercase; color:#94a3b8; display:block; margin-top:6px; margin-bottom:2px;">Wallet</span>
                                        <a href="{{ url('/dashboard') }}#deposit" class="nav-dropdown-item"><i class="bi bi-arrow-down-circle-fill me-2 text-primary" style="width:16px;"></i>Deposit</a>
                                        <a href="{{ url('/dashboard') }}#withdraw" class="nav-dropdown-item"><i class="bi bi-arrow-up-circle-fill me-2 text-primary" style="width:16px;"></i>Withdraw</a>
                                        <a href="{{ url('/dashboard') }}#credit_swap" class="nav-dropdown-item"><i class="bi bi-arrow-repeat me-2 text-warning" style="width:16px;"></i>Credit Swap</a>
                                        <a href="{{ url('/dashboard') }}#transactions" class="nav-dropdown-item"><i class="bi bi-arrow-down-up me-2 text-primary" style="width:16px;"></i>Transactions</a>

                                        <span class="sidebar-group-label px-3" style="font-size:0.65rem; font-weight:800; letter-spacing:0.09em; text-transform:uppercase; color:#94a3b8; display:block; margin-top:6px; margin-bottom:2px;">Account</span>
                                        <a href="{{ url('/dashboard') }}#notifications" class="nav-dropdown-item"><i class="bi bi-bell-fill me-2 text-primary" style="width:16px;"></i>Notifications</a>
                                        <a href="{{ url('/dashboard') }}#referrals" class="nav-dropdown-item"><i class="bi bi-people-fill me-2 text-primary" style="width:16px;"></i>Referrals</a>
                                        <a href="{{ url('/dashboard') }}#profile_kyc" class="nav-dropdown-item"><i class="bi bi-person-badge-fill me-2 text-primary" style="width:16px;"></i>Profile & KYC</a>

                                        <div class="border-top mt-1 pt-1"></div>
                                        <a href="{{ url('/') }}" class="nav-dropdown-item" target="_blank"><i class="bi bi-box-arrow-up-right me-2 text-secondary" style="width:16px;"></i>View Site</a>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="nav-dropdown-item w-100 border-0 bg-transparent" style="color:#f87171; text-align:left;"><i class="bi bi-box-arrow-right me-2" style="width:16px;"></i>Logout</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm fw-bold px-3 py-2" style="color:#0f172a; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:8px; background:#f8fafc;">Sign In</a>
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2" style="background:#2756fd; border:none; border-radius:8px; font-size:0.85rem;">Get Started</a>
                            @endauth

                            <!-- Mobile Menu Toggle Button (Visible on Screens < 992px) -->
                            <button class="btn btn-sm d-lg-none ms-1 p-2 border-0" id="mobileMenuToggle" style="border-radius:8px; background:#f1f5f9; color:#0f172a;" onclick="toggleMobileSidebar()" aria-label="Toggle navigation">
                                <i class="bi bi-list" style="font-size:1.4rem; line-height:1;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Off-Canvas Sidebar (matches desktop sidebar design) -->
                <div id="mobileSidebarOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-lg-none" style="z-index:999998; background:rgba(11,19,41,0.6); display:none;" onclick="toggleMobileSidebar()"></div>
                <div id="mobileSidebar" class="d-lg-none sidebar-dark p-3 position-fixed top-0 end-0 h-100 shadow-lg" style="z-index:999999; width:300px; max-width:85vw; transform:translateX(100%); transition:transform 0.3s cubic-bezier(0.16,1,0.3,1); overflow-y:auto;">
                    <!-- Close button -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-white-50 fw-bold small">NAVIGATION</span>
                        <button class="btn btn-sm text-white-50 border-0 p-1" onclick="toggleMobileSidebar()" style="font-size:1.4rem; line-height:1;">&times;</button>
                    </div>

                    @auth
                        <!-- User Profile Widget (same as desktop sidebar) -->
                        <div class="p-3 mb-4 rounded-3 text-white" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.95rem;">{{ Auth::user()->name ?? 'User' }}</h6>
                                    <div class="d-flex gap-1 mt-1">
                                        <small class="badge bg-primary bg-opacity-20 text-blue-300 fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; color: #93c5fd;">Investor</small>
                                        @if(Auth::user()->kyc_verified)
                                            <small class="badge fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; background:rgba(34,197,94,0.15); color:#22c55e;"><i class="bi bi-patch-check-fill me-1"></i>Verified</small>
                                        @else
                                            <small class="badge fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.72rem; background:rgba(248,113,113,0.15); color:#f87171;"><i class="bi bi-shield-exclamation me-1"></i>Unverified</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <nav class="nav flex-column pb-3">
                                           

                    @auth
                        <hr class="border-secondary opacity-20 my-3">

                        <!-- GROUP: MY PORTFOLIO -->
                        <span class="sidebar-group-label">My Portfolio</span>
                        <a href="{{ url('/dashboard') }}" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                        <a href="{{ url('/dashboard') }}#invest" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-lightning-charge-fill"></i> Invest
                        </a>
                        <a href="{{ url('/properties') }}" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-building"></i> Browse Properties
                        </a>
                        <a href="{{ url('/dashboard') }}#my_investments" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-pie-chart-fill"></i> My Investments
                        </a>

                        <!-- GROUP: WALLET -->
                        <span class="sidebar-group-label">Wallet</span>
                        <a href="{{ url('/dashboard') }}#deposit" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-arrow-down-circle-fill"></i> Deposit
                        </a>
                        <a href="{{ url('/dashboard') }}#withdraw" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-arrow-up-circle-fill"></i> Withdraw
                        </a>
                        <a href="{{ url('/dashboard') }}#credit_swap" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-arrow-repeat text-warning"></i> Credit Swap
                        </a>
                        <a href="{{ url('/dashboard') }}#transactions" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-arrow-down-up"></i> Transactions
                        </a>

                        <!-- GROUP: ACCOUNT -->
                        <span class="sidebar-group-label">Account</span>
                        <a href="{{ url('/dashboard') }}#notifications" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-bell-fill"></i> Notifications
                        </a>
                        <a href="{{ url('/dashboard') }}#referrals" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-people-fill"></i> Referrals
                        </a>
                        <a href="{{ url('/dashboard') }}#profile_kyc" class="nav-link-sidebar" onclick="toggleMobileSidebar()">
                            <i class="bi bi-person-badge-fill"></i> Profile & KYC
                        </a>

                        <hr class="border-secondary opacity-20 my-3">

                        <a href="{{ url('/') }}" class="nav-link-sidebar" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i> View Site
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="nav-link-sidebar" style="color: #f87171;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    @else
                        <hr class="border-secondary opacity-20 my-3">
                        <a href="{{ route('login') }}" class="nav-link-sidebar">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </a>
                        <a href="{{ route('register') }}" class="nav-link-sidebar">
                            <i class="bi bi-person-plus-fill"></i> Get Started
                        </a>
                    @endauth
                    </nav>
                </div>
            </header>

            <!-- Main Content -->
            <main>
                @yield('content')
            </main>
            <!-- End Main Content -->

            <!-- Main Footer -->
            @hasSection('footer')
                @yield('footer')
            @else
            <footer class="footer bg-dark text-white pt-5 pb-4" style="background-color: #0d1b2a !important;">
                <div class="container">
                    <!-- Top Footer Header / Social & Contact -->
                    <div class="row align-items-center pb-4 mb-4 border-bottom border-secondary-subtle">
                        <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('frontend/images/logo/radiantblue.png') }}" alt="radiantdreamrealty Logo" width="170" class="bg-white p-2 rounded">
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="fw-bold text-white-50">Follow Us:</span>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="bi bi-facebook"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="bi bi-instagram"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="bi bi-linkedin"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="bi bi-youtube"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="bi bi-twitter-x"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 text-lg-end text-white-50">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <i class="bi bi-envelope-fill text-primary"></i>
                                <a href="mailto:support@radiantdreamrealty.com" class="text-white text-decoration-none">support@radiantdreamrealty.com</a>
                            </div>
                        </div>
                    </div>

                    <!-- HQ Address Sub-bar -->
                    <div class="mb-4 text-white-50 small">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        <span>HQ – 123 Main Street, New York, NY 10001, USA</span>
                    </div>

                    <!-- Inner Footer Grid Columns -->
                    <div class="row g-4 py-3">
                        <!-- Column 1: Company & Services -->
                        <div class="col-lg-3 col-md-6">
                            <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Company</h5>
                            <ul class="list-unstyled text-white-50 mb-4" style="line-height: 2;">
                                <li><a href="{{ url('/about') }}" class="text-white-50 text-decoration-none hover-white">About Us</a></li>
                                <li><a href="{{ url('/team') }}" class="text-white-50 text-decoration-none">Meet the Team</a></li>
                                <li><a href="{{ url('/agent') }}" class="text-white-50 text-decoration-none">Become an Agent</a></li>
                                <li><a href="{{ url('/affiliate') }}" class="text-white-50 text-decoration-none">Become an Affiliate</a></li>
                                <li><a href="{{ url('/career') }}" class="text-white-50 text-decoration-none">Careers</a></li>
                            </ul>
                        </div>

                        <!-- Column 2: Services & Properties -->
                        <div class="col-lg-3 col-md-6">
                            <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Services</h5>
                            <ul class="list-unstyled text-white-50 mb-0" style="line-height: 2;">
                                <li><a href="{{ url('/properties') }}" class="text-white-50 text-decoration-none">Buy Properties</a></li>
                                <li><a href="{{ url('/properties') }}" class="text-white-50 text-decoration-none">Rent Properties</a></li>
                                <li><a href="{{ url('/invest') }}" class="text-white-50 text-decoration-none">Invest in Projects</a></li>
                                <li><a href="{{ url('/list-property') }}" class="text-white-50 text-decoration-none">List Your Property</a></li>
                            </ul>
                        </div>

                        <!-- Column 3: Resources -->
                        <div class="col-lg-3 col-md-6">
                            <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Resources</h5>
                            <ul class="list-unstyled text-white-50 mb-0" style="line-height: 2;">
                                <li><a href="{{ url('/resources') }}" class="text-white-50 text-decoration-none">Why Radiant</a></li>
                                <li><a href="{{ url('/resources') }}" class="text-white-50 text-decoration-none">Market Performance</a></li>
                                <li><a href="{{ url('/resources') }}" class="text-white-50 text-decoration-none">Property Guides</a></li>
                                <li><a href="{{ url('/resources') }}" class="text-white-50 text-decoration-none">Investor Education Hub</a></li>
                                <li><a href="{{ url('/resources') }}" class="text-white-50 text-decoration-none">Success Stories</a></li>
                            </ul>
                        </div>

                        <!-- Column 4: Quick Links -->
                        <div class="col-lg-3 col-md-6">
                            <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Quick Links</h5>
                            <ul class="list-unstyled text-white-50 mb-0" style="line-height: 2;">
                                <li><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
                                <li><a href="{{ url('/faq') }}" class="text-white-50 text-decoration-none">Support / FAQ</a></li>
                                <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Login / Sign Up</a></li>
                                <li><a href="{{ url('/contact') }}" class="text-white-50 text-decoration-none">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Copyright & Legal Links -->
                    <div class="pt-4 mt-4 border-top border-secondary-subtle d-flex flex-wrap justify-content-between align-items-center gap-3 text-white-50 small">
                        <div>© 2019 - {{ date('Y') }} radiantdreamrealty. All Rights Reserved.</div>
                        <div class="d-flex gap-4">
                            <a href="{{ url('/privacy') }}" class="text-white-50 text-decoration-none">Privacy Policy</a>
                            <a href="{{ url('/terms') }}" class="text-white-50 text-decoration-none">Terms of Service</a>
                            <a href="{{ url('/cookies') }}" class="text-white-50 text-decoration-none">Cookies</a>
                        </div>
                    </div>
                </div>
            </footer>
            @endif
        </div>
    </div>

    <!-- Session Expiry Warning Modal -->
    <div id="sessionWarningModal" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="z-index: 999999; background: rgba(11,19,41,0.75); backdrop-filter: blur(10px);">
      <div class="bg-white rounded-4 p-4 shadow-lg text-center" style="max-width: 400px; width: 90%;">
        <div class="mb-3 text-warning">
          <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Session Expiring</h4>
        <p class="text-muted small mb-3">Your session will expire in <strong id="sessionCountdown" class="text-danger">1:00</strong> due to inactivity.</p>
        <button id="stayLoggedInBtn" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2756fd;">
          <i class="bi bi-shield-check me-1"></i> Stay Logged In
        </button>
      </div>
    </div>

    <!-- Session Expired Modal -->
    <div id="sessionExpiredModal" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="z-index: 999999; background: rgba(11,19,41,0.85); backdrop-filter: blur(14px);">
      <div class="bg-white rounded-4 p-4 shadow-lg text-center" style="max-width: 400px; width: 90%; animation: fadeInUp 0.3s ease;">
        <div class="mb-3 text-danger">
          <i class="bi bi-shield-exclamation" style="font-size: 2.8rem;"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Session Expired</h4>
        <p class="text-muted small mb-3">Your session has expired due to 15 minutes of inactivity. Please sign in again to continue.</p>
        <a href="{{ route('login') }}" class="btn btn-primary fw-bold w-100 py-2 rounded-3" style="background:#2756fd;">
          <i class="bi bi-box-arrow-in-right me-1"></i> Sign In Again
        </a>
      </div>
    </div>

    <style>
      @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>

    <script>
      // ── Session Inactivity Timer (15 min = 900000 ms) ──
      (function() {
        var INACTIVITY_LIMIT = 15 * 60 * 1000;      // 15 min
        var WARNING_BEFORE   = 1  * 60 * 1000;        // show warning 1 min before
        var warningTimer     = null;
        var expiryTimer      = null;
        var countdownInterval = null;
        var remainingSec     = 60;

        var warningModal  = document.getElementById('sessionWarningModal');
        var expiredModal  = document.getElementById('sessionExpiredModal');
        var countdownEl   = document.getElementById('sessionCountdown');
        var stayBtn       = document.getElementById('stayLoggedInBtn');

        function resetTimers() {
          clearTimeout(warningTimer);
          clearTimeout(expiryTimer);
          clearInterval(countdownInterval);
          warningModal.classList.add('d-none');
          warningModal.style.display = 'none';

          warningTimer = setTimeout(showWarning, INACTIVITY_LIMIT - WARNING_BEFORE);
          expiryTimer  = setTimeout(showExpired, INACTIVITY_LIMIT);
        }

        function showWarning() {
          remainingSec = 60;
          warningModal.classList.remove('d-none');
          warningModal.style.display = 'flex';

          countdownInterval = setInterval(function() {
            remainingSec--;
            var m = Math.floor(remainingSec / 60);
            var s = remainingSec % 60;
            if (countdownEl) countdownEl.textContent = m + ':' + (s < 10 ? '0' : '') + s;
            if (remainingSec <= 0) {
              clearInterval(countdownInterval);
            }
          }, 1000);
        }

        function showExpired() {
          warningModal.classList.add('d-none');
          warningModal.style.display = 'none';
          clearInterval(countdownInterval);
          expiredModal.classList.remove('d-none');
          expiredModal.style.display = 'flex';
        }

        function hideModals() {
          warningModal.classList.add('d-none');
          warningModal.style.display = 'none';
          expiredModal.classList.add('d-none');
          expiredModal.style.display = 'none';
          clearInterval(countdownInterval);
        }

        // Reset on any user activity
        var activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart', 'mousemove'];
        activityEvents.forEach(function(ev) {
          document.addEventListener(ev, function() {
            hideModals();
            resetTimers();
          }, { passive: true });
        });

        // Stay Logged In button
        if (stayBtn) {
          stayBtn.addEventListener('click', function() {
            // Ping server to extend session
            fetch('/sanctum/csrf-cookie', { method: 'GET', credentials: 'same-origin' }).catch(function(){});
            hideModals();
            resetTimers();
          });
        }

        // Start
        resetTimers();
      })();
    </script>

    <!-- Scroll-Driven Reveal Observer & Navbar JS -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
            }
          });
        }, {
          threshold: 0.1,
          rootMargin: "0px 0px -50px 0px"
        });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
      });

      function toggleNavDropdown(btn) {
        const menu = btn.nextElementSibling;
        if (menu) {
          const isOpen = menu.style.display === 'block';
          document.querySelectorAll('.nav-dropdown-menu').forEach(m => m.style.display = 'none');
          menu.style.display = isOpen ? 'none' : 'block';
        }
      }

      function toggleMobileSidebar() {
        var sidebar = document.getElementById('mobileSidebar');
        var overlay = document.getElementById('mobileSidebarOverlay');
        if (!sidebar) return;
        var isOpen = sidebar.style.transform === 'translateX(0px)' || sidebar.getAttribute('data-open') === 'true';
        if (isOpen) {
          sidebar.style.transform = 'translateX(100%)';
          sidebar.setAttribute('data-open', 'false');
          if (overlay) overlay.style.display = 'none';
        } else {
          sidebar.style.transform = 'translateX(0px)';
          sidebar.setAttribute('data-open', 'true');
          if (overlay) overlay.style.display = 'block';
        }
      }

      document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-dropdown-wrap')) {
          document.querySelectorAll('.nav-dropdown-menu').forEach(el => el.style.display = 'none');
        }
      });
    </script>

    <!-- Project/Property Countdowns & Share Helpers -->
    <script>
      (function() {
        function pad(n) { return n < 10 ? '0' + n : '' + n; }

        function formatRemaining(sec) {
          if (sec <= 0) return null;
          var d = Math.floor(sec / 86400);
          var h = Math.floor((sec % 86400) / 3600);
          var m = Math.floor((sec % 3600) / 60);
          var s = sec % 60;
          if (d > 0) return d + 'd ' + h + 'h ' + pad(m) + 'm';
          if (h > 0) return h + 'h ' + pad(m) + 'm ' + pad(s) + 's';
          return m + 'm ' + pad(s) + 's';
        }

        function tick() {
          document.querySelectorAll('[data-countdown-ends]').forEach(function(el) {
            var endTs = parseInt(el.getAttribute('data-countdown-ends'), 10);
            if (!endTs) return;
            var label = formatRemaining(endTs - Math.floor(Date.now() / 1000));
            if (label === null) {
              el.textContent = 'Ended';
              el.classList.add('text-danger');
            } else {
              el.textContent = label;
            }
          });
        }

        document.addEventListener('DOMContentLoaded', function() {
          tick();
          setInterval(tick, 1000);
        });
      })();

      function shareContent(title, url, label) {
        if (navigator.share) {
          navigator.share({ title: title, url: url, text: label + ' on radiantdreamrealty' }).catch(function() {});
        } else {
          navigator.clipboard.writeText(url).then(function() {
            showToast(title + ' link copied to clipboard!', 'info');
          });
        }
      }

      function showToast(message, type) {
        var bg = '#16a34a';
        if (type === 'error') bg = '#dc2626';
        else if (type === 'info') bg = '#1d4ed8';
        var toast = document.createElement('div');
        toast.textContent = message;
        toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:' + bg + ';color:#fff;padding:12px 22px;border-radius:10px;font-weight:600;font-size:0.88rem;z-index:999999;box-shadow:0 8px 28px rgba(0,0,0,0.25);opacity:0;transform:translateY(12px);transition:all .25s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function() {
          toast.style.opacity = '1';
          toast.style.transform = 'translateY(0)';
        });
        setTimeout(function() {
          toast.style.opacity = '0';
          toast.style.transform = 'translateY(12px)';
          setTimeout(function() { toast.remove(); }, 300);
        }, 2600);
      }

      // AJAX project save/unsave (no page reload)
      document.addEventListener('submit', function(e) {
        var form = e.target;
        if (!form.classList.contains('js-save-project')) return;
        e.preventDefault();

        var btn = form.querySelector('button[type="submit"]');
        if (btn) btn.disabled = true;

        fetch(form.getAttribute('action'), {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
          credentials: 'same-origin'
        }).then(function(res) {
          if (res.redirected && res.url.indexOf('/login') !== -1) {
            window.location.href = '/login';
            throw new Error('redirect');
          }
          if (res.status === 419) {
            window.location.reload();
            throw new Error('reload');
          }
          return res.json().then(function(data) {
            if (!res.ok) throw new Error(data.message || 'error');
            return data;
          });
        }).then(function(data) {
          var saved = !!data.saved;
          if (btn) {
            btn.disabled = false;
            var icon = btn.querySelector('i');
            if (icon) {
              icon.classList.toggle('bi-bookmark-fill', saved);
              icon.classList.toggle('bi-bookmark', !saved);
            }
            if (saved) {
              btn.classList.add('saved');
              btn.classList.remove('bg-white');
              btn.classList.add('text-danger');
            } else {
              btn.classList.remove('saved');
              btn.classList.add('bg-white');
              btn.classList.remove('text-danger');
            }
            btn.title = saved ? 'Remove from saved' : 'Save project';
          }

          showToast(saved ? 'Project saved to your list!' : 'Project removed from your saved list.', saved ? 'success' : 'info');

          if (form.hasAttribute('data-remove-card') && !saved) {
            var card = form.closest('[data-saved-card]');
            if (card) {
              card.style.transition = 'opacity .3s ease, transform .3s ease';
              card.style.opacity = '0';
              card.style.transform = 'scale(0.96)';
              setTimeout(function() {
                card.remove();
                var grid = document.getElementById('savedProjectsGrid');
                if (grid && !grid.querySelector('[data-saved-card]')) {
                  grid.innerHTML = '<div class="col-12"><div class="card border-0 rounded-4 shadow-sm bg-white p-5 text-center">' +
                    '<div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px; height:64px; background:#f1f5f9;">' +
                    '<i class="bi bi-bookmark-star fs-2 text-muted"></i></div>' +
                    '<h5 class="fw-bold text-dark mb-2">No Saved Projects</h5>' +
                    '<p class="text-muted small mb-0">Save projects you are interested in and they will appear here for quick access.</p>' +
                    '</div></div>';
                }
              }, 300);
            }
            var pill = document.getElementById('savedCountPill');
            if (pill) {
              var n = parseInt(pill.textContent, 10) || 0;
              pill.textContent = Math.max(0, n - 1);
              if (n - 1 <= 0) pill.classList.add('d-none');
            }
            var badge = document.getElementById('savedProjectsBadge');
            if (badge) {
              var m = parseInt(badge.textContent, 10) || 0;
              badge.textContent = Math.max(0, m - 1);
              if (m - 1 <= 0) badge.classList.add('d-none');
            }
          }
        }).catch(function(err) {
          if (btn) btn.disabled = false;
          if (err && (err.message === 'redirect' || err.message === 'reload')) return;
          showToast('Something went wrong. Please try again.', 'error');
        });
      });
    </script>
</body>
</html>
