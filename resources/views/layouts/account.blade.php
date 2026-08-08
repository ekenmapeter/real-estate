@php
    $currentUser = Auth::user();
    $accountName = $profile['name'] ?? $currentUser->name ?? 'new';
    $accountInitials = $profile['initials'] ?? 'NE';
    $accountRole = 'Investor';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Profile & Settings | ' . site_name())</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <div class="text-[10px] font-semibold uppercase tracking-widest text-blue-600">Invest · Own · Earn</div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->routeIs('dashboard') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-squares-2x2', 'h-5 w-5') Dashboard
            </a>
            <a href="{{ route('marketplace.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('project-marketplace*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-building-office-2', 'h-5 w-5') Project Marketplace
            </a>
            <a href="{{ route('avc-marketplace.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->routeIs('marketplace') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-arrows-right-left', 'h-5 w-5') AVC Marketplace
            </a>
            <a href="{{ route('portfolio.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition text-slate-600 hover:bg-slate-100 hover:text-blue-700">
                @svg('heroicon-o-heart', 'h-5 w-5') Saved
            </a>
            <a href="{{ route('portfolio.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->routeIs('portfolio.*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-briefcase', 'h-5 w-5') My Portfolio
            </a>
            <a href="{{ route('project-earnings.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('project-earnings*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-banknotes', 'h-5 w-5') Project Earnings
            </a>
            <a href="{{ route('finance.overview') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('finance') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-banknotes', 'h-5 w-5') Finance
            </a>
            <a href="{{ route('finance.transactions') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('finance/transactions') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-receipt-percent', 'h-5 w-5') Transactions
            </a>
            <a href="{{ url('/properties') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('properties*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-building-office', 'h-5 w-5') Properties
            </a>
            <a href="{{ route('documents.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->routeIs('documents.*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-folder', 'h-5 w-5') Documents
            </a>
            <a href="{{ route('affiliate.center') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('affiliate-center*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-user-group', 'h-5 w-5') Affiliate
            </a>
            <a href="{{ route('profile.settings') }}" class="mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->routeIs('profile.settings') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-user-circle', 'h-5 w-5') Profile & Settings
            </a>
            <a href="{{ route('support.index') }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition
                {{ request()->is('support*') ? 'bg-blue-600 font-semibold text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700' }}">
                @svg('heroicon-o-lifebuoy', 'h-5 w-5') Support Center
            </a>
            <a href="{{ route('dashboard', ['tour' => 1]) }}" class="mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 transition text-slate-600 hover:bg-slate-100 hover:text-blue-700">
                @svg('heroicon-o-rocket-launch', 'h-5 w-5') How AVC Works
            </a>
        </nav>

        {{-- Bottom widgets --}}
        <div class="shrink-0 space-y-3 p-4">
            <div class="rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-4 text-white shadow-lg">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-amber-300">
                        @svg('heroicon-o-trophy', 'h-5 w-5')
                    </span>
                    <div class="text-sm font-extrabold">Upgrade to VIP</div>
                </div>
                <p class="mt-2 text-xs leading-relaxed text-blue-100">Unlock premium projects, higher limits and exclusive benefits.</p>
                <a href="{{ route('marketplace.index') }}" class="mt-3 block rounded-lg bg-white py-2 text-center text-xs font-bold text-blue-700 transition hover:bg-blue-50">
                    Upgrade Now
                </a>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                        @svg('heroicon-o-lifebuoy', 'h-5 w-5')
                    </span>
                    <div class="text-xs font-bold text-slate-800">Need Help?</div>
                </div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">Our support team is available 24/7</p>
                <a href="{{ route('support.index') }}" class="mt-3 block rounded-lg border border-slate-300 bg-white py-2 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-100">
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
                    @svg('heroicon-o-home-modern', 'h-4 w-4')
                </div>
                <div class="text-sm font-extrabold tracking-tight text-slate-900">AVC</div>
            </div>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                {{-- Notification bell with red badge --}}
                <button class="relative rounded-full p-2 text-slate-500 transition hover:bg-slate-100" title="Notifications">
                    @svg('heroicon-o-bell', 'h-5 w-5')
                    <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white ring-2 ring-white">3</span>
                </button>

                {{-- User dropdown --}}
                <div class="relative" x-data @click.outside="userMenu = false">
                    <button @click="userMenu = ! userMenu" class="flex items-center gap-2.5 rounded-full p-1 pr-2 transition hover:bg-slate-100">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-sm font-bold text-white">
                            {{ $accountInitials }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-xs font-bold text-slate-900">{{ $accountName }}</span>
                            <span class="block text-[10px] font-semibold text-blue-600">{{ $accountRole }}</span>
                        </span>
                        @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400')
                    </button>
                    <div x-show="userMenu" x-cloak class="absolute right-0 mt-1.5 w-48 rounded-xl border border-slate-200 bg-white py-1.5 shadow-xl">
                        <a href="{{ route('profile.settings') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Profile & Settings</a>
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Main Dashboard</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="block w-full px-3 py-2 text-left text-xs font-semibold text-rose-600 hover:bg-rose-50">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 pb-20 lg:pb-0">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="hidden border-t border-slate-200 bg-white lg:block">
            <div class="mx-auto flex w-full max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 sm:px-6 md:flex-row">
                <div class="text-xs font-medium text-slate-500">© 2026 Real Estate Corporation. All Rights Reserved.</div>
                <nav class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Home</a>
                    <a href="{{ route('marketplace.index') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Project Marketplace</a>
                    <a href="{{ url('/properties') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Properties</a>
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('support.index') }}" class="text-xs font-semibold text-slate-600 transition hover:text-blue-600">Support</a>
                </nav>
                <div class="flex items-center gap-2">
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600" title="Facebook">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21.9v-8h2.7l.4-3.1h-3.1V8.6c0-.9.25-1.5 1.55-1.5h1.65V4.4c-.29-.04-1.27-.12-2.4-.12-2.38 0-4 1.45-4 4.12v2.3H7.6v3.1h2.7v8a10 10 0 1 0 3.2 0z"/></svg>
                    </a>
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600" title="X">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.77 7.74L23.2 22h-6.23l-4.88-6.38L6.5 22H3.37l7.24-8.28L2.8 2h6.39l4.41 5.83L18.9 2zm-1.1 18h1.73L7.56 3.86H5.7L17.8 20z"/></svg>
                    </a>
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600" title="LinkedIn">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3V9zm7 0h3.8v1.64h.05c.53-1 1.83-2.06 3.77-2.06 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.5c0-1.31-.02-3-1.83-3-1.83 0-2.11 1.43-2.11 2.9V21h-4V9z"/></svg>
                    </a>
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600" title="Instagram">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.64.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92C2.21 15.58 2.2 15.2 2.2 12s.01-3.58.07-4.85C2.42 3.92 3.94 2.4 7.15 2.25 8.42 2.19 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.5.01-4.74.07-2.42.11-3.08.77-3.19 3.19C4.01 8.5 4 8.85 4 12s.01 3.5.07 4.74c.11 2.42.77 3.08 3.19 3.19 1.24.06 1.59.07 4.74.07s3.5-.01 4.74-.07c2.42-.11 3.08-.77 3.19-3.19.06-1.24.07-1.59.07-4.74s-.01-3.5-.07-4.74c-.11-2.42-.77-3.08-3.19-3.19C15.5 4.01 15.15 4 12 4zm0 3.06a4.94 4.94 0 1 1 0 9.88 4.94 4.94 0 0 1 0-9.88zm0 1.8a3.14 3.14 0 1 0 0 6.28 3.14 3.14 0 0 0 0-6.28zm5.15-3.21a1.15 1.15 0 1 1 0 2.3 1.15 1.15 0 0 1 0-2.3z"/></svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>

    {{-- Mobile bottom navigation --}}
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white pb-[env(safe-area-inset-bottom)] shadow-[0_-4px_20px_rgba(0,0,0,0.06)] lg:hidden">
        <div class="flex items-stretch justify-around" style="height:62px;">
            <a href="{{ url('/') }}" class="flex flex-1 flex-col items-center justify-center text-[#94a3b8]">
                @svg('heroicon-o-home', 'h-5 w-5')
                <small class="mt-1 font-semibold" style="font-size:0.64rem;">Home</small>
            </a>
            <a href="{{ route('portfolio.index') }}" class="flex flex-1 flex-col items-center justify-center text-[#94a3b8]">
                @svg('heroicon-o-chart-pie', 'h-5 w-5')
                <small class="mt-1 font-semibold" style="font-size:0.64rem;">Assets</small>
            </a>
            <a href="{{ route('deposit.index') }}" class="flex flex-1 flex-col items-center justify-center text-blue-600">
                @svg('heroicon-o-wallet', 'h-5 w-5')
                <small class="mt-1 font-bold" style="font-size:0.64rem;">Deposit</small>
            </a>
            <a href="{{ route('avc-marketplace.index') }}" class="flex flex-1 flex-col items-center justify-center text-[#94a3b8]">
                @svg('heroicon-o-arrows-right-left', 'h-5 w-5')
                <small class="mt-1 font-semibold" style="font-size:0.64rem;">Trade</small>
            </a>
            <a href="{{ route('finance.transactions') }}" class="flex flex-1 flex-col items-center justify-center text-[#94a3b8]">
                @svg('heroicon-o-clock', 'h-5 w-5')
                <small class="mt-1 font-semibold" style="font-size:0.64rem;">History</small>
            </a>
        </div>
    </nav>
</div>
@stack('scripts')
</body>
</html>
