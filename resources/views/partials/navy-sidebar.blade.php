@php
    $activeRoute = request()->route() ? request()->route()->getName() : '';
    $user = Auth::user();
@endphp

<div class="flex h-full min-h-full flex-col overflow-y-auto bg-[#0F1E3D]">
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

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm font-medium">
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

        <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Wallet</p>
        <a href="{{ route('deposit.index') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-arrow-down-tray', 'h-5 w-5 text-slate-400 group-hover:text-emerald-400')
            Deposit
        </a>
        <a href="{{ route('withdraw.index') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-arrow-up-tray', 'h-5 w-5 text-slate-400 group-hover:text-rose-400')
            Withdraw
        </a>
        <a href="{{ route('marketplace') }}" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-arrows-right-left', 'h-5 w-5 text-slate-400 group-hover:text-amber-400')
            AVC Marketplace
        </a>
        <a href="{{ route('finance.transactions') }}" class="group mb-1 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-receipt-percent', 'h-5 w-5 text-slate-400 group-hover:text-cyan-400')
            Transactions
        </a>

        <p class="mb-2 mt-5 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Account</p>
        <a href="{{ url('/dashboard') }}#notifications" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-bell', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
            Notifications
            <span class="ml-auto rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">3</span>
        </a>
        <a href="{{ url('/dashboard') }}#referrals" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-user-group', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
            Referrals
        </a>
        <a href="{{ url('/dashboard') }}#profile_kyc" class="group mb-0.5 flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-identification', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
            Profile & KYC
        </a>
        <a href="{{ route('documents.index') }}" class="mb-0.5 flex items-center gap-3 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-2.5 py-2 font-semibold text-white shadow-lg shadow-blue-900/40 {{ $activeRoute == 'documents.index' ? '' : 'opacity-90 hover:opacity-100' }}">
            @svg('heroicon-o-folder', 'h-5 w-5 text-blue-200')
            Documents
        </a>
        <a href="{{ url('/dashboard') }}#settings" class="group flex items-center gap-3 rounded-lg px-2.5 py-2 text-slate-300 transition hover:bg-white/5 hover:text-white">
            @svg('heroicon-o-cog-6-tooth', 'h-5 w-5 text-slate-400 group-hover:text-blue-400')
            Settings
        </a>
    </nav>

    {{-- Promo + language --}}
    <div class="shrink-0 p-4">
        <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-800 p-4 text-white shadow-lg">
            @svg('heroicon-o-building-office', 'mb-2 h-8 w-8 text-blue-200')
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
</div>
