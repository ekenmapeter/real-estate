@extends('layouts.affiliate')

@section('title', 'Affiliate Center | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8" x-data="affiliateCarousel()">

    {{-- 1. Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Affiliate Center</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Grow the AVC community, manage your referrals, earn commissions, and access exclusive tools.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            {{ $affiliate['status'] }}
        </span>
    </div>

    {{-- 2. Hero row: Affiliate level + Commission wallet --}}
    <div class="grid gap-4 lg:grid-cols-2">

        {{-- Gold Partner card --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-[#16224a] to-[#0f1e3d] p-6 text-white shadow-xl shadow-slate-900/20">
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-blue-500/20 blur-2xl"></div>
            <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-lg shadow-amber-500/30">
                    @svg('heroicon-o-star', 'h-6 w-6')
                </span>
                <div>
                    <h2 class="text-lg font-extrabold">{{ $affiliate['level'] }}</h2>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-blue-300">Affiliate Level</p>
                </div>
            </div>
            <div class="mt-5 flex flex-wrap gap-4">
                <div class="rounded-xl bg-white/10 px-4 py-2.5 backdrop-blur">
                    <div class="text-sm font-extrabold">{{ $affiliate['commissionRate'] }}%</div>
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">Commission Rate</div>
                </div>
                <div class="rounded-xl bg-white/10 px-4 py-2.5 backdrop-blur">
                    <div class="text-sm font-extrabold">{{ $affiliate['memberSince'] }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">Member Since</div>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="flex gap-0.5">
                    @for ($i = 0; $i < 5; $i++)
                        @svg('heroicon-o-star', 'h-4 w-4 text-amber-400')
                    @endfor
                </div>
                <span class="text-xs font-bold">{{ $affiliate['rating'] }} Performance Rating</span>
            </div>
            <a href="{{ route('affiliate.section', 'profile-settings') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg border border-white/30 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/10">
                @svg('heroicon-o-sparkles', 'h-4 w-4')
                View Benefits
            </a>
        </div>

        {{-- Commission Wallet card --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 p-6 text-white shadow-xl shadow-blue-700/25">
            <div class="absolute -bottom-12 -right-12 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
            <h2 class="text-sm font-extrabold uppercase tracking-widest text-blue-100">Commission Wallet</h2>
            <div class="mt-4 grid grid-cols-3 divide-x divide-white/15">
                <div class="pr-3">
                    <div class="text-xl font-extrabold sm:text-2xl">${{ number_format($commissionStats['available'], 2) }}</div>
                    <div class="mt-1 text-[10px] font-bold uppercase tracking-wider text-blue-200">Available Commission</div>
                </div>
                <div class="px-3">
                    <div class="text-xl font-extrabold sm:text-2xl">${{ number_format($commissionStats['pending'], 2) }}</div>
                    <div class="mt-1 text-[10px] font-bold uppercase tracking-wider text-blue-200">Pending Commission</div>
                </div>
                <div class="pl-3">
                    <div class="text-xl font-extrabold sm:text-2xl">${{ number_format($commissionStats['lifetime'], 2) }}</div>
                    <div class="mt-1 text-[10px] font-bold uppercase tracking-wider text-blue-200">Lifetime Earnings</div>
                </div>
            </div>
            <div class="mt-5 flex flex-wrap gap-2.5">
                <a href="{{ route('affiliate.section', 'withdrawals') }}" class="rounded-lg border border-white/40 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/15">Withdraw</a>
                <a href="{{ route('affiliate.section', 'commission-wallet') }}" class="rounded-lg border border-white/40 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/15">Transfer to AVC Credits</a>
                <a href="{{ route('affiliate.section', 'commission-history') }}" class="rounded-lg border border-white/40 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/15">View Statement</a>
            </div>
        </div>
    </div>

    {{-- 3. Performance statistics --}}
    <div class="mt-4 grid grid-cols-2 gap-4 xl:grid-cols-4">
        @foreach ($performanceStats as $stat)
            <x-stat-card :icon="$stat['icon']" :color="$stat['color']" :label="$stat['label']" :value="$stat['value']" :trend="$stat['trend']" />
        @endforeach
    </div>

    {{-- 4. Analytics row: funnel + monthly chart --}}
    <div class="mt-4 grid gap-4 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <h2 class="text-sm font-extrabold text-slate-900">Conversion Funnel</h2>
            <p class="text-[11px] font-medium text-slate-400">Your referral journey at a glance</p>
            <div class="mt-4 grid grid-cols-1 gap-1 md:grid-cols-5">
                @foreach ($funnel['stages'] as $index => $stage)
                    <x-funnel-stage :label="$stage['label']" :value="$stage['value']" :icon="$stage['icon']" :last="$loop->last" />
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-between rounded-xl bg-emerald-50 px-4 py-3 ring-1 ring-inset ring-emerald-200">
                <div>
                    <div class="text-lg font-extrabold text-emerald-700">{{ $funnel['conversionRate'] }}%</div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Conversion Rate</div>
                </div>
                <div class="flex items-center gap-1 text-xs font-bold text-emerald-600">
                    @svg('heroicon-o-arrow-trending-up', 'h-3.5 w-3.5')
                    ↑ {{ number_format($funnel['conversionTrend'], 1) }}% vs last month
                </div>
            </div>
        </div>

        {{-- Monthly earnings chart --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-3" x-data="affiliateCharts({{ Js::from($monthlyEarnings['ranges']) }})">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Monthly Earnings</h2>
                    <p class="text-[11px] font-medium text-slate-400">Commission and deposits generated over time</p>
                </div>
                <div class="flex items-center gap-1 rounded-lg bg-slate-100 p-1">
                    @foreach ($monthlyEarnings['ranges'] as $key => $range)
                        <button @click="switchRange('{{ $key }}')" :class="range === '{{ $key }}' ? 'bg-white font-bold text-blue-700 shadow-sm' : 'font-semibold text-slate-500 hover:text-slate-800'"
                                class="rounded-md px-2.5 py-1 text-[11px] transition">{{ $key }}</button>
                    @endforeach
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-600"></span> Commission Earned
                </div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Deposits Generated
                </div>
                <span class="ml-auto flex items-center gap-1 text-xs font-bold" :class="trend >= 0 ? 'text-emerald-600' : 'text-rose-500'">
                    <template x-if="trend >= 0">
                        <span>↑ <span x-text="Number(trend).toFixed(1)"></span>%</span>
                    </template>
                    <template x-if="trend < 0">
                        <span>↓ <span x-text="Math.abs(trend).toFixed(1)"></span>%</span>
                    </template>
                    <span class="font-medium text-slate-400">vs last month</span>
                </span>
            </div>
            <div class="mt-3 h-56 sm:h-64">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    </div>

    {{-- 5. Assigned Projects --}}
    <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Assigned Projects</h2>
                <p class="text-xs font-medium text-slate-500">Campaigns you are promoting this month</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="scrollProjects(-1)" class="hidden rounded-lg border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 sm:block" aria-label="Scroll left">
                    @svg('heroicon-o-chevron-left', 'h-4 w-4')
                </button>
                <button @click="scrollProjects(1)" class="hidden rounded-lg border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 sm:block" aria-label="Scroll right">
                    @svg('heroicon-o-chevron-right', 'h-4 w-4')
                </button>
                <a href="{{ route('affiliate.section', 'assigned-projects') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View All Projects</a>
            </div>
        </div>
        <div class="no-scrollbar -mx-4 flex snap-x gap-4 overflow-x-auto px-4 pb-2 sm:-mx-6 sm:px-6" x-ref="projectTrack">
            @foreach ($assignedProjects as $project)
                <x-project-card :title="$project['title']" :location="$project['location']" :flag="$project['flag']" :status="$project['status']"
                    :image-url="$project['image_url']" :gradient="$project['gradient']" :progress="$project['progress']"
                    :investors="$project['investors']" :generated="$project['generated']" :commission="$project['commission']"
                    :deadline="$project['deadline']" :campaign="$project['campaign']"
                    :href="route('marketplace.index')" />
            @endforeach
        </div>
    </div>

    {{-- 6. Marketing Center --}}
    <div class="mt-8">
        <div class="mb-4">
            <h2 class="text-lg font-extrabold text-slate-900">Marketing Center</h2>
            <p class="text-xs font-medium text-slate-500">Your personal promotion toolkit</p>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

            {{-- Referral link --}}
            <x-marketing-card icon="heroicon-o-link" icon-color="bg-blue-100 text-blue-600" title="Your Referral Link"
                x-data="{ copied: false }">
                <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5">
                    <input type="text" readonly value="{{ $referralLink }}" class="w-full min-w-0 truncate bg-transparent text-xs font-semibold text-slate-600 focus:outline-none">
                    <button @click="navigator.clipboard.writeText('{{ $referralLink }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="shrink-0 rounded-md p-1.5 text-slate-500 transition hover:bg-white hover:text-blue-600" aria-label="Copy referral link">
                        <template x-if="!copied">@svg('heroicon-o-clipboard', 'h-4 w-4')</template>
                        <template x-if="copied">@svg('heroicon-o-check', 'h-4 w-4 text-emerald-500')</template>
                    </button>
                </div>
                <div class="mt-4">
                    <div class="mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Share via</div>
                    <div class="flex items-center gap-2">
                        @foreach ([
                            ['name' => 'WhatsApp', 'href' => 'https://wa.me/?text=' . urlencode('Join AVC Real Estate with my referral link: ' . $referralLink), 'svg' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>'],
                            ['name' => 'Telegram', 'href' => 'https://t.me/share/url?url=' . urlencode($referralLink) . '&text=' . urlencode('Join AVC Real Estate'), 'svg' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>'],
                            ['name' => 'Facebook', 'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($referralLink), 'svg' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21.9v-8h2.7l.4-3.1h-3.1V8.6c0-.9.25-1.5 1.55-1.5h1.65V4.4c-.29-.04-1.27-.12-2.4-.12-2.38 0-4 1.45-4 4.12v2.3H7.6v3.1h2.7v8a10 10 0 1 0 3.2 0z"/></svg>'],
                            ['name' => 'X', 'href' => 'https://twitter.com/intent/tweet?url=' . urlencode($referralLink) . '&text=' . urlencode('Join AVC Real Estate'), 'svg' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.77 7.74L23.2 22h-6.23l-4.88-6.38L6.5 22H3.37l7.24-8.28L2.8 2h6.39l4.41 5.83L18.9 2zm-1.1 18h1.73L7.56 3.86H5.7L17.8 20z"/></svg>'],
                        ] as $share)
                            <a href="{{ $share['href'] }}" target="_blank" rel="noopener" title="{{ $share['name'] }}"
                               class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                                {!! $share['svg'] !!}
                            </a>
                        @endforeach
                        <a href="mailto:?subject=Join AVC Real Estate&body={{ urlencode('Join AVC Real Estate with my referral link: ' . $referralLink) }}"
                           class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600" title="Email">
                            @svg('heroicon-o-envelope', 'h-4 w-4')
                        </a>
                    </div>
                </div>
                <div class="mt-auto pt-4">
                    <a href="#" @click.prevent="navigator.clipboard.writeText('{{ $referralLink }}'); copied = true; setTimeout(() => copied = false, 2000)"
                       class="text-xs font-bold text-blue-600 hover:text-blue-800">Copy Link</a>
                </div>
            </x-marketing-card>

            {{-- Referral code + QR --}}
            <x-marketing-card icon="heroicon-o-key" icon-color="bg-violet-100 text-violet-600" title="Referral Code"
                x-data="{ copied: false }">
                <div class="flex items-center justify-between rounded-lg bg-slate-900 px-4 py-3 text-white">
                    <code class="text-sm font-extrabold tracking-widest">{{ $referralCode }}</code>
                    <button @click="navigator.clipboard.writeText('{{ $referralCode }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="rounded-md p-1.5 text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Copy referral code">
                        <template x-if="!copied">@svg('heroicon-o-clipboard', 'h-4 w-4')</template>
                        <template x-if="copied">@svg('heroicon-o-check', 'h-4 w-4 text-emerald-400')</template>
                    </button>
                </div>
                <div class="mt-3 text-center">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Scan &amp; Share</div>
                    <div class="mt-2 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                        @if ($qrSvg)
                            {!! $qrSvg !!}
                        @else
                            <span class="flex h-[150px] w-[150px] items-center justify-center bg-slate-100 text-slate-300">@svg('heroicon-o-qr-code', 'h-16 w-16')</span>
                        @endif
                    </div>
                    <p class="mt-3 text-[11px] font-medium text-slate-500">Scan this QR code to register and become my referral</p>
                </div>
            </x-marketing-card>

            {{-- Promo builder --}}
            <x-marketing-card icon="heroicon-o-swatch" icon-color="bg-orange-100 text-orange-600" title="Promo Builder" class="md:col-span-2 xl:col-span-1">
                <p class="text-xs leading-relaxed text-slate-500">Create custom promotional images in seconds.</p>
                <div class="mt-3 flex h-32 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-blue-700 via-indigo-700 to-blue-900 text-white/30">
                    @svg('heroicon-o-photo', 'h-12 w-12')
                </div>
                <a href="{{ route('affiliate.section', 'promo-builder') }}" class="mt-4 block rounded-lg bg-blue-600 py-2.5 text-center text-xs font-bold text-white transition hover:bg-blue-700">
                    Create Promo
                </a>
            </x-marketing-card>
        </div>
    </div>

    {{-- 7. Recent Referrals + Finance Support --}}
    <div class="mt-8 grid gap-4 xl:grid-cols-5">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-3">
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Recent Referrals</h2>
                    <p class="text-[11px] font-medium text-slate-400">Your latest referred investors</p>
                </div>
                <a href="{{ route('affiliate.section', 'my-referrals') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left">
                    <thead>
                        <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                            <th class="px-4 py-2.5">Investor</th>
                            <th class="px-4 py-2.5">Status</th>
                            <th class="px-4 py-2.5 text-right">Investment</th>
                            <th class="px-4 py-2.5 text-right">Commission</th>
                            <th class="px-4 py-2.5 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentReferrals as $referral)
                            <x-referral-row :name="$referral['name']" :country="$referral['country']" :flag="$referral['flag']"
                                :status="$referral['status']" :investment="$referral['investment']"
                                :commission="$referral['commission']" :date="$referral['date']" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Finance Support</h2>
                    <p class="text-[11px] font-medium text-slate-400">Assist your referred investors</p>
                </div>
                <a href="{{ route('affiliate.section', 'finance-requests') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">View All Requests</a>
            </div>
            <div class="px-5">
                <div class="rounded-xl bg-slate-900 p-4 text-white">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-500/20 text-blue-300">
                            @svg('heroicon-o-lifebuoy', 'h-4.5 w-4.5')
                        </span>
                        <div>
                            <div class="text-xs font-bold">Open Finance Request</div>
                            <p class="mt-1 text-[11px] leading-relaxed text-slate-400">Help a referred investor fund their deposit through the AVC Finance Team.</p>
                        </div>
                    </div>
                    <a href="{{ route('affiliate.section', 'finance-requests') }}" class="mt-3 block rounded-lg bg-blue-600 py-2 text-center text-xs font-bold text-white transition hover:bg-blue-700">
                        Open New Request
                    </a>
                </div>
            </div>
            <div class="flex-1 px-2 py-3">
                <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Active Requests</div>
                <ul class="divide-y divide-slate-100 px-3">
                    @foreach ($financeRequests as $request)
                        <li class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate text-xs font-bold text-slate-900">{{ $request['id'] }}</div>
                                <div class="text-[11px] font-medium text-slate-500">{{ $request['investor'] }} · ${{ number_format($request['amount'], 2) }}</div>
                            </div>
                            @if ($request['status'] === 'payment_details_active')
                                <div x-data="{ seconds: {{ $request['seconds'] }}, timer: null, init() { this.timer = setInterval(() => { if (this.seconds > 0) { this.seconds--; } else { clearInterval(this.timer); } }, 1000) } }"
                                     class="flex shrink-0 items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-600 ring-1 ring-inset ring-rose-200">
                                    @svg('heroicon-o-clock', 'h-3 w-3')
                                    <span x-show="seconds > 0">
                                        <span x-text="String(Math.floor(seconds / 60)).padStart(2, '0')"></span>:<span x-text="String(seconds % 60).padStart(2, '0')"></span>
                                    </span>
                                    <span x-show="seconds <= 0">Expired</span>
                                </div>
                            @elseif ($request['status'] === 'completed')
                                <span class="flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                    @svg('heroicon-o-check-circle', 'h-3 w-3') Completed
                                </span>
                            @else
                                <span class="flex shrink-0 items-center gap-1.5 rounded-full bg-violet-50 px-2.5 py-1 text-[10px] font-bold text-violet-700 ring-1 ring-inset ring-violet-200">
                                    @svg('heroicon-o-clock', 'h-3 w-3') Waiting Proof
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- 8. Benefits banner --}}
    <div class="mt-8 grid grid-cols-1 gap-6 rounded-2xl bg-gradient-to-r from-[#0f1e3d] via-blue-900 to-[#0f1e3d] px-6 py-7 text-white sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($benefits as $benefit)
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-blue-300">
                    @svg($benefit['icon'], 'h-5 w-5')
                </span>
                <div>
                    <div class="text-sm font-extrabold">{{ $benefit['title'] }}</div>
                    <p class="mt-0.5 text-[11px] font-medium text-blue-200">{{ $benefit['caption'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function affiliateCharts(ranges) {
        return {
            ranges,
            range: '1M',
            trend: ranges['1M'].trend,
            chart: null,
            init() {
                const r = this.ranges['1M'];
                this.chart = new Chart(this.$refs.canvas, {
                    type: 'line',
                    data: {
                        labels: r.labels,
                        datasets: [
                            { label: 'Commission Earned', data: r.commission, borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.08)', fill: true, tension: 0.35, borderWidth: 2, pointRadius: 3 },
                            { label: 'Deposits Generated', data: r.deposits, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.08)', fill: true, tension: 0.35, borderWidth: 2, pointRadius: 3 },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y: { beginAtZero: true, max: 10000, grid: { color: '#f1f5f9' }, ticks: { callback: (v) => '$' + v.toLocaleString(), font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                        },
                        plugins: {
                            legend: { display: false },
                        },
                    },
                });
            },
            switchRange(key) {
                const r = this.ranges[key];
                if (!r) return;
                this.range = key;
                this.trend = r.trend;
                this.chart.data.labels = r.labels;
                this.chart.data.datasets[0].data = r.commission;
                this.chart.data.datasets[1].data = r.deposits;
                this.chart.update();
            },
        };
    }

    function affiliateCarousel() {
        return {
            scrollProjects(dir) {
                const track = this.$refs.projectTrack;
                if (track) {
                    track.scrollBy({ left: dir * track.clientWidth * 0.8, behavior: 'smooth' });
                }
            },
        };
    }
</script>
@endsection
