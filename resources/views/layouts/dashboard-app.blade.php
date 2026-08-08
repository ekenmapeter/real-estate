@php
    $currentUser = Auth::user();
    $sidebarOpen = false;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Documents | ' . site_name())</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-100 antialiased">
<div x-data="{ sidebarOpen: false, langMenu: false, userMenu: false, lang: 'en' }" class="min-h-screen">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    {{-- Dark navy sidebar (fixed, full height) --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-[#0F1E3D] transition-transform duration-300 ease-in-out lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg">
                @svg('heroicon-o-home-modern', 'h-5 w-5')
            </div>
            <div>
                <div class="text-sm font-extrabold tracking-wide text-white">{{ site_name() }}</div>
                <div class="text-[10px] font-medium uppercase tracking-widest text-slate-400">Invest · Own · Earn</div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm font-medium">
            {{-- Overview --}}
            <p class="mb-2 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Overview</p>
            <a href="{{ url('/dashboard') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-squares-2x2', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Dashboard
            </a>
            <a href="{{ route('marketplace.index') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-shopping-bag', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Project Marketplace
            </a>
            <a href="{{ url('/properties') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-building-office-2', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Browse Properties
            </a>
            <a href="{{ route('portfolio.index') }}" class="group mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-briefcase', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                My Portfolio
            </a>

            {{-- WALLET --}}
            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Wallet</p>
            <a href="{{ route('deposit.index') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-arrow-down-tray', 'h-5 w-5 text-slate-400 group-hover:text-emerald-400')
                Deposit
            </a>
            <a href="{{ route('withdraw.index') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-arrow-up-tray', 'h-5 w-5 text-slate-400 group-hover:text-rose-400')
                Withdraw
            </a>
            <a href="{{ route('avc-marketplace.index') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-arrows-right-left', 'h-5 w-5 text-slate-400 group-hover:text-amber-400')
                AVC Marketplace
            </a>
            <a href="{{ route('finance.transactions') }}" class="group mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-receipt-percent', 'h-5 w-5 text-slate-400 group-hover:text-cyan-400')
                Transactions
            </a>

            {{-- ACCOUNT --}}
            <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Account</p>
            <a href="{{ route('dashboard') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-bell', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Notifications
                <span class="ml-auto rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">3</span>
            </a>
            <a href="{{ route('affiliate.center') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-user-group', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Affiliate Center
            </a>
            <a href="{{ route('profile.settings') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-identification', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Profile & Settings
            </a>
            <a href="{{ route('documents.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-2.5 py-2 font-semibold text-white shadow-lg shadow-blue-900/40">
                @svg('heroicon-o-folder', 'h-5 w-5 text-blue-200')
                Documents
            </a>
            <a href="{{ route('profile.settings') }}" class="group flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
                @svg('heroicon-o-cog-6-tooth', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
                Settings
            </a>
        </nav>

        {{-- Promo card --}}
        <div class="shrink-0 p-4">
            <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-800 p-4 text-white shadow-lg">
                @svg('heroicon-o-building-office', 'h-8 w-8 mb-2 text-blue-200')
                <div class="text-sm font-bold">Grow your wealth with AVC</div>
                <p class="mt-1 text-xs leading-relaxed text-blue-100">Invest in verified projects and earn predictable returns every cycle.</p>
                <a href="{{ route('marketplace.index') }}" class="mt-3 block rounded-lg bg-white/15 py-2 text-center text-xs font-bold text-white backdrop-blur transition hover:bg-white/25">
                    Explore Opportunities
                </a>
            </div>
            <div class="mt-3 flex items-center justify-between px-1">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Language</span>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = ! open" class="flex items-center gap-1.5 rounded-md bg-white/5 px-2 py-1 text-xs font-semibold text-slate-300 hover:bg-white/10">
                        <span class="h-3 w-4 rounded-sm bg-gradient-to-br from-amber-400 to-rose-500"></span> EN
                        @svg('heroicon-o-chevron-down', 'h-3 w-3')
                    </button>
                    <div x-show="open" x-cloak class="absolute bottom-full right-0 mb-1 w-32 rounded-xl border border-slate-200 bg-white py-1 shadow-xl">
                        <button class="flex w-full items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"><span class="h-3 w-4 rounded-sm bg-gradient-to-br from-amber-400 to-rose-500"></span> English</button>
                        <button class="flex w-full items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"><span class="h-3 w-4 rounded-sm bg-red-600"></span> Español</button>
                    </div>
                </div>
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
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 text-white">
                    @svg('heroicon-o-folder', 'h-4 w-4')
                </div>
                <nav class="hidden items-center gap-1.5 text-sm font-semibold text-slate-500 sm:flex">
                    <span>Documents</span>
                    @svg('heroicon-o-chevron-right', 'h-3.5 w-3.5 text-slate-300')
                    <span>Dashboard</span>
                    @svg('heroicon-o-chevron-right', 'h-3.5 w-3.5 text-slate-300')
                    <span class="text-slate-900">Documents</span>
                </nav>
            </div>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                {{-- Bell --}}
                <button class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100">
                    @svg('heroicon-o-bell', 'h-5 w-5')
                    <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white">3</span>
                </button>
                {{-- Chat --}}
                <button class="hidden rounded-full p-2 text-slate-500 hover:bg-slate-100 sm:block">
                    @svg('heroicon-o-chat-bubble-left-right', 'h-5 w-5')
                </button>
                {{-- Language --}}
                <div class="relative hidden md:block" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = ! open" class="flex items-center gap-1.5 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50">
                        EN @svg('heroicon-o-chevron-down', 'h-3.5 w-3.5')
                    </button>
                    <div x-show="open" x-cloak class="absolute right-0 mt-1 w-32 rounded-xl border border-slate-200 bg-white py-1 shadow-xl">
                        <button class="flex w-full items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">English</button>
                        <button class="flex w-full items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Español</button>
                    </div>
                </div>
                {{-- Avatar --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = ! open" class="flex items-center gap-2.5 rounded-full p-1 pr-2 hover:bg-slate-100">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white">
                            {{ strtoupper(substr($currentUser->name ?? 'Juan Dela Cruz', 0, 2)) }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-xs font-bold text-slate-900">{{ $currentUser->name ?? 'Juan Dela Cruz' }}</span>
                            <span class="block text-[10px] font-medium text-slate-500">Member</span>
                        </span>
                        @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400')
                    </button>
                    <div x-show="open" x-cloak class="absolute right-0 mt-1.5 w-48 rounded-xl border border-slate-200 bg-white py-1.5 shadow-xl">
                        <a href="{{ route('profile.settings') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Profile & Settings</a>
                        <a href="{{ route('documents.index') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Documents</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="block w-full px-3 py-2 text-left text-xs font-semibold text-rose-600 hover:bg-rose-50">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
