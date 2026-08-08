@php
    $currentUser = Auth::user();
    $affiliateName = $affiliate['name'] ?? $currentUser->name ?? 'Nelson E.';
    $affiliateInitials = $affiliate['initials'] ?? 'NE';
    $affiliateLevel = $affiliate['level'] ?? 'Gold Partner';
    $activeSection = (request()->route() && request()->route()->getName() === 'affiliate.section')
        ? request()->route('section')
        : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Affiliate Center | ' . site_name())</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        html { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-100 antialiased">
<div x-data="{ sidebarOpen: false, userMenu: false }" class="min-h-screen">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    {{-- Light sidebar (fixed, full height) --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-100 px-5">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-lg">
                @svg('heroicon-o-home-modern', 'h-5 w-5')
            </div>
            <div>
                <div class="text-sm font-extrabold tracking-wide text-slate-900">AVC Real Estate</div>
                <div class="text-[10px] font-semibold uppercase tracking-widest text-blue-600">Affiliate Center</div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm font-medium">

            <p class="mb-2 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Overview</p>
            <a href="{{ route('affiliate.center') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->routeIs('affiliate.center') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-squares-2x2', 'h-5 w-5')
                Overview
            </a>
            <a href="{{ route('affiliate.section', 'my-referrals') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'my-referrals' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-user-group', 'h-5 w-5')
                My Referrals
            </a>
            <a href="{{ route('affiliate.section', 'assigned-projects') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'assigned-projects' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-building-office-2', 'h-5 w-5')
                Assigned Projects
            </a>

            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Marketing Center</p>
            <a href="{{ route('affiliate.section', 'referral-link') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'referral-link' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-link', 'h-5 w-5') Referral Link
            </a>
            <a href="{{ route('affiliate.section', 'referral-code') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'referral-code' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-key', 'h-5 w-5') Referral Code
            </a>
            <a href="{{ route('affiliate.section', 'qr-code') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'qr-code' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-qr-code', 'h-5 w-5') QR Code
            </a>
            <a href="{{ route('affiliate.section', 'media-library') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'media-library' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-photo', 'h-5 w-5') Media Library
            </a>
            <a href="{{ route('affiliate.section', 'promo-builder') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'promo-builder' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-swatch', 'h-5 w-5') Promo Builder
            </a>
            <a href="{{ route('affiliate.section', 'downloads') }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'downloads' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-arrow-down-tray', 'h-5 w-5') Downloads
            </a>

            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Finance Support</p>
            <a href="{{ route('affiliate.section', 'finance-requests') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'finance-requests' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-banknotes', 'h-5 w-5') Finance Requests
            </a>
            <a href="{{ route('affiliate.section', 'support-history') }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'support-history' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-chat-bubble-left-right', 'h-5 w-5') Support History
            </a>

            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Commission</p>
            <a href="{{ route('affiliate.section', 'commission-wallet') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'commission-wallet' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-wallet', 'h-5 w-5') Commission Wallet
            </a>
            <a href="{{ route('affiliate.section', 'withdrawals') }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'withdrawals' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-arrow-up-tray', 'h-5 w-5') Withdrawals
            </a>

            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">History</p>
            <a href="{{ route('affiliate.section', 'referral-history') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'referral-history' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-clock', 'h-5 w-5') Referral History
            </a>
            <a href="{{ route('affiliate.section', 'commission-history') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'commission-history' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-banknotes', 'h-5 w-5') Commission History
            </a>
            <a href="{{ route('affiliate.section', 'finance-history') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'finance-history' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-receipt-percent', 'h-5 w-5') Finance History
            </a>
            <a href="{{ route('affiliate.section', 'downloads-history') }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'downloads-history' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-arrow-down-tray', 'h-5 w-5') Downloads History
            </a>

            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Settings</p>
            <a href="{{ route('affiliate.section', 'profile-settings') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'profile-settings' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-user-circle', 'h-5 w-5') Profile Settings
            </a>
            <a href="{{ route('affiliate.section', 'notification-settings') }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ $activeSection === 'notification-settings' ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-bell', 'h-5 w-5') Notification Settings
            </a>
        </nav>

        {{-- Bottom widgets --}}
        <div class="shrink-0 space-y-3 p-4">
            <div class="rounded-2xl bg-slate-900 p-4 text-white">
                <div class="flex items-center gap-3">
                    <svg viewBox="0 0 100 100" class="h-14 w-14 -rotate-90">
                        <circle cx="50" cy="50" r="42" fill="none" stroke="#334155" stroke-width="9" />
                        <circle cx="50" cy="50" r="42" fill="none" stroke="#3b82f6" stroke-width="9" stroke-linecap="round"
                                stroke-dasharray="263.9" stroke-dashoffset="73.9" />
                    </svg>
                    <div>
                        <div class="text-xl font-extrabold leading-none">72%</div>
                        <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Progress to Diamond</div>
                    </div>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-400">Reach the next level and unlock higher commission rates and exclusive campaigns.</p>
                <a href="{{ route('affiliate.section', 'profile-settings') }}" class="mt-3 block rounded-lg bg-white py-2 text-center text-xs font-bold text-slate-900 transition hover:bg-slate-200">
                    View Requirements
                </a>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                        @svg('heroicon-o-lifebuoy', 'h-5 w-5')
                    </span>
                    <div class="text-xs font-bold text-slate-800">Need Help?</div>
                </div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">Our Finance Team is here to assist you.</p>
                <a href="{{ route('affiliate.section', 'support-history') }}" class="mt-3 block rounded-lg bg-blue-600 py-2 text-center text-xs font-bold text-white transition hover:bg-blue-700">
                    Contact Support
                </a>
            </div>
        </div>
    </aside>

    {{-- Main column --}}
    <div class="flex min-h-screen flex-col lg:pl-64">

        {{-- Top navbar --}}
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6">
            <button @click="sidebarOpen = ! sidebarOpen" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                @svg('heroicon-o-bars-3', 'h-5 w-5')
            </button>

            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 text-white">
                    @svg('heroicon-o-megaphone', 'h-4 w-4')
                </div>
                <div class="text-sm font-extrabold tracking-tight text-slate-900">AVC Real Estate</div>
            </div>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                {{-- Notification bell with red dot --}}
                <button class="relative rounded-full p-2 text-slate-500 transition hover:bg-slate-100" title="Notifications">
                    @svg('heroicon-o-bell', 'h-5 w-5')
                    <span class="absolute right-1.5 top-1.5 h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                </button>

                {{-- User dropdown --}}
                <div class="relative" x-data @click.outside="userMenu = false">
                    <button @click="userMenu = ! userMenu" class="flex items-center gap-2.5 rounded-full p-1 pr-2 transition hover:bg-slate-100">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-sm font-bold text-white">
                            {{ $affiliateInitials }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-xs font-bold text-slate-900">{{ $affiliateName }}</span>
                            <span class="block text-[10px] font-semibold text-blue-600">{{ $affiliateLevel }}</span>
                        </span>
                        @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400')
                    </button>
                    <div x-show="userMenu" x-cloak class="absolute right-0 mt-1.5 w-48 rounded-xl border border-slate-200 bg-white py-1.5 shadow-xl">
                        <a href="{{ route('affiliate.section', 'profile-settings') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Profile Settings</a>
                        <a href="{{ route('affiliate.section', 'notification-settings') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Notification Settings</a>
                        <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Main Dashboard</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="block w-full px-3 py-2 text-left text-xs font-semibold text-rose-600 hover:bg-rose-50">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex w-full max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 sm:px-6 sm:flex-row">
                <div class="text-xs font-medium text-slate-500">© 2026 Real Estate Corporation. All Rights Reserved.</div>
                <nav class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Home</a>
                    <a href="{{ route('marketplace.index') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Project Marketplace</a>
                    <a href="{{ url('/properties') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Properties</a>
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('affiliate.section', 'support-history') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Support</a>
                </nav>
            </div>
        </footer>
    </div>
</div>
@stack('scripts')
</body>
</html>
