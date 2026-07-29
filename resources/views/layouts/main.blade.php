<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Radiant Dream Realty | Global Property Investment & Co-Ownership Platform')</title>
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

    /* Custom Glassmorphism & Navbar Styling */
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
                            <img src="{{ asset('frontend/images/logo/radiantblue.png') }}" alt="Radiant Dream Realty" height="42" style="object-fit: contain;">
                        </a>

                        <!-- Desktop & Laptop Navigation (Visible on Large Screens >= 992px) -->
                        <nav class="d-none d-lg-flex align-items-center gap-1">
                            <a href="{{ url('/') }}" class="nav-link-item {{ request()->is('/') ? 'active' : '' }}">Home</a>
                            <a href="{{ url('/properties') }}" class="nav-link-item {{ request()->is('properties*') ? 'active' : '' }}">Properties</a>
                            <a href="{{ url('/list-property') }}" class="nav-link-item {{ request()->is('list-property*') ? 'active' : '' }}">List Property</a>
                            <a href="{{ url('/project-marketplace') }}" class="nav-link-item {{ request()->is('project-marketplace*') ? 'active' : '' }}">Marketplace</a>

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
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2" style="background:#2756fd; border:none; border-radius:8px; font-size:0.85rem;">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm fw-bold px-3 py-2" style="color:#0f172a; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:8px; background:#f8fafc;">Sign In</a>
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2" style="background:#2756fd; border:none; border-radius:8px; font-size:0.85rem;">Get Started</a>
                            @endauth

                            <!-- Mobile Menu Toggle Button (Visible on Screens < 992px) -->
                            <button class="btn btn-sm d-lg-none ms-1 p-2 border-0" id="mobileMenuToggle" style="border-radius:8px; background:#f1f5f9; color:#0f172a;" onclick="toggleMobileMenu()" aria-label="Toggle navigation">
                                <i class="bi bi-list" style="font-size:1.4rem; line-height:1;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Mobile Slide-Down Menu -->
                <div id="mobileNavMenu" style="display:none; background:#ffffff; border-top:1px solid #e2e8f0; padding:16px 20px 24px; box-shadow:0 12px 30px rgba(0,0,0,0.12);">
                    <ul class="list-unstyled mb-0">
                        <li class="border-bottom py-2"><a href="{{ url('/') }}" class="text-dark fw-bold text-decoration-none d-block" style="font-size:0.95rem;">Home</a></li>
                        <li class="border-bottom py-2"><a href="{{ url('/properties') }}" class="text-dark fw-bold text-decoration-none d-block" style="font-size:0.95rem;">Properties</a></li>
                        <li class="border-bottom py-2"><a href="{{ url('/list-property') }}" class="text-dark fw-bold text-decoration-none d-block" style="font-size:0.95rem;">List Property</a></li>
                        <li class="border-bottom py-2"><a href="{{ url('/project-marketplace') }}" class="text-dark fw-bold text-decoration-none d-block" style="font-size:0.95rem;">Marketplace</a></li>
                        <li class="border-bottom py-2">
                            <span class="fw-bold text-dark d-block mb-1" style="font-size:0.95rem;">Partners</span>
                            <ul class="list-unstyled ms-3 mb-0">
                                <li class="py-1"><a href="{{ url('/team') }}" class="text-secondary fw-semibold text-decoration-none" style="font-size:0.88rem;">Meet The Team</a></li>
                                <li class="py-1"><a href="{{ url('/agent') }}" class="text-secondary fw-semibold text-decoration-none" style="font-size:0.88rem;">Become an Agent</a></li>
                                <li class="py-1"><a href="{{ url('/affiliate') }}" class="text-secondary fw-semibold text-decoration-none" style="font-size:0.88rem;">Become an Affiliate</a></li>
                                <li class="py-1"><a href="{{ url('/career') }}" class="text-secondary fw-semibold text-decoration-none" style="font-size:0.88rem;">Careers</a></li>
                            </ul>
                        </li>
                        <li class="pt-2"><a href="{{ url('/resources') }}" class="text-dark fw-bold text-decoration-none d-block" style="font-size:0.95rem;">Resources</a></li>
                    </ul>
                    <div class="d-flex gap-2 mt-3 pt-2 border-top">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm w-100 fw-bold py-2" style="background:#2756fd; border:none; font-size:0.9rem;">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm flex-fill fw-bold py-2" style="font-size:0.9rem;">Sign In</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm flex-fill fw-bold py-2" style="background:#2756fd; border:none; font-size:0.9rem;">Get Started</a>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main>
                @yield('content')
            </main>
            <!-- End Main Content -->

            <!-- Main Footer -->
            <footer class="footer bg-dark text-white pt-5 pb-4" style="background-color: #0d1b2a !important;">
                <div class="container">
                    <!-- Top Footer Header / Social & Contact -->
                    <div class="row align-items-center pb-4 mb-4 border-bottom border-secondary-subtle">
                        <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('frontend/images/logo/radiantblue.png') }}" alt="Radiant Dream Realty Logo" width="170" class="bg-white p-2 rounded">
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
                                <li><a href="{{ url('/project-marketplace') }}" class="text-white-50 text-decoration-none">Project Marketplace</a></li>
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
                        <div>© 2019 - {{ date('Y') }} Radiant Dream Realty. All Rights Reserved.</div>
                        <div class="d-flex gap-4">
                            <a href="{{ url('/privacy') }}" class="text-white-50 text-decoration-none">Privacy Policy</a>
                            <a href="{{ url('/terms') }}" class="text-white-50 text-decoration-none">Terms of Service</a>
                            <a href="{{ url('/cookies') }}" class="text-white-50 text-decoration-none">Cookies</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

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

      function toggleMobileMenu() {
        const mobileNav = document.getElementById('mobileNavMenu');
        if (mobileNav) {
          mobileNav.style.display = mobileNav.style.display === 'none' ? 'block' : 'none';
        }
      }

      document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-dropdown-wrap')) {
          document.querySelectorAll('.nav-dropdown-menu').forEach(el => el.style.display = 'none');
        }
      });
    </script>
</body>
</html>
