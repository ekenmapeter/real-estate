@extends('layouts.account')

@section('title', 'Dashboard | ' . site_name())

@section('content')
<div x-data="guidedTour({{ $showTourAuto ? 'true' : 'false' }}, {{ request()->query('tour') ? 'true' : 'false' }})" @resize.window="position()">
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="dashboardOverview({{ Js::from($earningsChart['ranges']) }})">

    {{-- 1. Dashboard header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-3.5">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-sm font-bold text-white shadow-lg">
                {{ $profile['initials'] }}
            </span>
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">{{ $header['greeting'] }}, {{ $profile['name'] }}</h1>
                <p class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-slate-500">
                    <span>Investor ID: <span class="font-bold text-slate-700">{{ $header['investorId'] }}</span></span>
                    @if ($header['kycVerified'])
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Verified
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 ring-1 ring-inset ring-amber-200">
                            Verification Required
                        </span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="relative rounded-full p-2 text-slate-500 transition hover:bg-slate-100" title="Notifications">
                @svg('heroicon-o-bell', 'h-5 w-5')
                <span class="absolute right-0.5 top-0.5 h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
            </button>
            <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
                @svg('heroicon-o-currency-dollar', 'h-4 w-4')
                {{ $header['avcPrice'] }}
            </span>
        </div>
    </div>

    {{-- 2. AVC Credits Balance hero card --}}
    <div id="tour-balance" class="relative scroll-mt-20 overflow-hidden rounded-2xl bg-gradient-to-br from-[#0f1e3d] via-[#16224a] to-blue-900 p-6 text-white shadow-xl shadow-slate-900/20 sm:p-8">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-indigo-500/15 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-blue-300">AVC Credits Balance</span>
                <div class="mt-2 flex flex-wrap items-end gap-3">
                    <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl">{{ number_format($balance['total']) }} <span class="text-xl font-bold text-blue-300">AVC</span></h2>
                    <span class="mb-1.5 text-sm font-bold text-blue-200">≈ ${{ number_format($balance['usd'], 2) }} USD</span>
                </div>
                <div class="mt-2 flex items-center gap-2 text-[11px] font-semibold text-blue-300">
                    <span>Conversion rate: 1 AVC = $1.00</span>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                        <div class="text-sm font-extrabold">${{ number_format($balance['totalDeposited'], 2) }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">Total Deposited</div>
                    </div>
                    <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                        <div class="text-sm font-extrabold">${{ number_format($balance['totalWithdrawn'], 2) }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">Total Withdrawn</div>
                    </div>
                    <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                        <div class="text-sm font-extrabold">{{ number_format($balance['available']) }} AVC</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">Available Balance</div>
                    </div>
                    <div class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                        <div class="text-sm font-extrabold">{{ number_format($balance['pending'], 2) }} AVC</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-blue-200">Pending Balance</div>
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 flex-col gap-2.5 lg:w-56">
                <a href="{{ route('deposit.index') }}" class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-900/40 transition hover:bg-blue-500">
                    @svg('heroicon-o-arrow-down-tray', 'h-4 w-4') Deposit AVC
                </a>
                <a href="{{ route('withdraw.index') }}" class="flex items-center justify-center gap-2 rounded-lg border border-white/30 py-2.5 text-xs font-bold text-white transition hover:bg-white/10">
                    @svg('heroicon-o-arrow-up-tray', 'h-4 w-4') Withdraw AVC
                </a>
                <a href="{{ route('finance.team.index') }}" class="flex items-center justify-center gap-2 rounded-lg border border-white/30 py-2.5 text-xs font-bold text-white transition hover:bg-white/10">
                    @svg('heroicon-o-banknotes', 'h-4 w-4') Finance Team
                </a>
            </div>
        </div>
    </div>

    {{-- 3. Quick Actions --}}
    <div class="mt-6">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-extrabold text-slate-900">Quick Actions</h2>
            <span class="text-[11px] font-medium text-slate-400">One-tap access to everything</span>
        </div>
        <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 xl:grid-cols-6">
            @foreach ($quickActions as $action)
                <a href="{{ $action['href'] }}" {{ isset($action['id']) ? 'id=' . $action['id'] : '' }} class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $action['color'] }}">
                        @svg($action['icon'], 'h-5 w-5')
                    </span>
                    <span class="text-[10px] font-bold leading-tight text-slate-600">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- 4 + 5. Portfolio summary + earnings chart --}}
    <div class="mt-6 grid gap-4 xl:grid-cols-5">
        <div id="tour-portfolio" class="scroll-mt-20 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-slate-900">Portfolio Summary</h2>
                <a href="{{ route('portfolio.index') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View My Portfolio</a>
            </div>
            <div class="mt-4 flex items-end gap-2">
                <div class="text-3xl font-extrabold tracking-tight text-slate-900">${{ number_format($portfolio['totalValue'], 2) }}</div>
                <div class="mb-1 flex items-center gap-1 text-xs font-bold text-emerald-600">
                    @svg('heroicon-o-arrow-trending-up', 'h-3.5 w-3.5') {{ $portfolio['portfolioRoi'] }}%
                </div>
            </div>
            <div class="mt-1 text-[11px] font-medium text-slate-400">Combined value of all active investments</div>

            <div class="mt-5 grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-inset ring-slate-100">
                    <div class="text-lg font-extrabold text-slate-900">{{ $portfolio['activeProjects'] }}</div>
                    <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Active Projects</div>
                </div>
                <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-inset ring-slate-100">
                    <div class="text-lg font-extrabold text-slate-900">{{ $portfolio['completedProjects'] }}</div>
                    <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Completed</div>
                </div>
                <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-inset ring-slate-100">
                    <div class="text-lg font-extrabold text-slate-900">{{ $portfolio['totalShares'] }}</div>
                    <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Shares Owned</div>
                </div>
            </div>

            <dl class="mt-4 space-y-2.5">
                <div class="flex items-center justify-between">
                    <dt class="text-xs font-semibold text-slate-500">Total ROI Earned</dt>
                    <dd class="text-xs font-extrabold text-emerald-600">${{ number_format($portfolio['totalRoiEarned'], 2) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-xs font-semibold text-slate-500">Dividend Earnings</dt>
                    <dd class="text-xs font-extrabold text-slate-900">${{ number_format($portfolio['dividendEarnings'], 2) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-xs font-semibold text-slate-500">Rental / Investment Earnings</dt>
                    <dd class="text-xs font-extrabold text-slate-900">${{ number_format($portfolio['rentalEarnings'], 2) }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Earnings Overview</h2>
                    <p class="text-[11px] font-medium text-slate-400">ROI, dividends and rental earnings over time</p>
                </div>
                <div class="flex items-center gap-2">
                    <select x-model="currency" @change="updateCurrency()" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-bold text-slate-700 focus:border-blue-500 focus:outline-none">
                        <option value="USD">USD $</option>
                        <option value="PHP">PHP ₱</option>
                        <option value="EUR">EUR €</option>
                        <option value="GBP">GBP £</option>
                    </select>
                    <div class="flex items-center gap-1 rounded-lg bg-slate-100 p-1">
                        @foreach ($earningsChart['ranges'] as $key => $range)
                            <button @click="switchRange('{{ $key }}')" :class="range === '{{ $key }}' ? 'bg-white font-bold text-blue-700 shadow-sm' : 'font-semibold text-slate-500 hover:text-slate-800'"
                                    class="rounded-md px-2.5 py-1 text-[11px] transition">{{ $key === '1M' ? '1 Month' : ($key === '3M' ? '3 Months' : ($key === '6M' ? '6 Months' : ($key === '1Y' ? '1 Year' : 'All Time'))) }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-3 flex flex-wrap items-center gap-4 text-[11px] font-bold text-slate-500">
                <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-600"></span> ROI Growth</span>
                <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Dividends</span>
                <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-500"></span> Rental / Investment</span>
            </div>
            <div class="mt-3 h-56 sm:h-64">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    </div>

    {{-- 6. Active investments --}}
    <div id="tour-investments" class="mt-8 scroll-mt-20">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Active Investments</h2>
                <p class="text-xs font-medium text-slate-500">Your current project holdings</p>
            </div>
            <a href="{{ route('portfolio.index') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View All Active Investments</a>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($activeInvestments as $investment)
                <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="relative h-32 bg-gradient-to-br {{ $investment['gradient'] }}">
                        <div class="flex h-full w-full items-center justify-center text-white/25">
                            @svg('heroicon-o-building-office-2', 'h-14 w-14')
                        </div>
                        <span class="absolute left-3 top-3 flex h-7 w-7 items-center justify-center rounded-full bg-white text-sm shadow">{{ $investment['flag'] }}</span>
                        <span class="absolute right-3 top-3 rounded-full bg-emerald-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow">{{ $investment['status'] }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="text-sm font-extrabold text-slate-900">{{ $investment['title'] }}</h3>
                        <p class="text-[11px] font-medium text-slate-500">{{ $investment['location'] }}</p>
                        <div class="mt-3 flex items-center justify-between text-[11px] font-bold">
                            <span class="text-slate-500">Funding Progress</span>
                            <span class="text-blue-600">{{ $investment['progress'] }}%</span>
                        </div>
                        <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-500" style="width: {{ $investment['progress'] }}%"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-3 divide-x divide-slate-100 rounded-xl border border-slate-100 bg-slate-50 py-2 text-center">
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">${{ number_format($investment['amount']) }}</div>
                                <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Invested</div>
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">{{ $investment['shares'] }}</div>
                                <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Shares</div>
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-emerald-600">{{ $investment['roi'] }}%</div>
                                <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Current ROI</div>
                            </div>
                        </div>
                        <a href="{{ $investment['href'] }}" class="mt-4 block rounded-lg bg-blue-600 py-2 text-center text-xs font-bold text-white transition hover:bg-blue-700">View Project</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 7 + 13. AVC card + documents --}}
    <div class="mt-8 grid gap-4 xl:grid-cols-3">
        <div id="avc-card" class="scroll-mt-20 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">My AVC Card</h2>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">Not Applied</span>
            </div>
            <div class="grid gap-5 p-5 lg:grid-cols-2">
                {{-- Card preview --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-blue-900 to-blue-700 p-5 text-white shadow-xl">
                    <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-blue-300">AVC Card</div>
                            <div class="mt-4 text-lg font-extrabold tracking-widest">•••• •••• •••• 4821</div>
                        </div>
                        <span class="text-white/40">@svg('heroicon-o-credit-card', 'h-8 w-8')</span>
                    </div>
                    <div class="mt-6 flex items-end justify-between">
                        <div>
                            <div class="text-[9px] font-semibold uppercase tracking-wider text-blue-300">Card Balance</div>
                            <div class="text-xl font-extrabold">$1,250.00</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[9px] font-semibold uppercase tracking-wider text-blue-300">Daily Limit</div>
                            <div class="text-sm font-extrabold">$500.00</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-sm font-extrabold text-slate-900">Get the official AVC Card</h3>
                    <p class="mt-2 text-xs font-medium leading-relaxed text-slate-500">Spend your AVC Credits worldwide with the premium AVC Card. Available for verified members.</p>
                    <a href="{{ route('dashboard') }}#avc-card" class="mt-4 inline-flex w-fit items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                        @svg('heroicon-o-credit-card', 'h-4 w-4')
                        Apply for AVC Card
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-slate-900">Documents &amp; Verification</h2>
                <a href="{{ route('documents.index') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">Manage Documents</a>
            </div>
            <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3.5 py-2.5 ring-1 ring-inset ring-slate-100">
                    <span class="text-xs font-semibold text-slate-600">KYC Verification</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $documents['kycStatusColor'] }}">{{ $documents['kycStatus'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3.5 py-2.5 ring-1 ring-inset ring-slate-100">
                    <span class="text-xs font-semibold text-slate-600">AML Verification</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $documents['amlStatusColor'] }}">{{ $documents['amlStatus'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3.5 py-2.5 ring-1 ring-inset ring-slate-100">
                    <span class="text-xs font-semibold text-slate-600">Uploaded Documents</span>
                    <span class="text-xs font-extrabold text-slate-900">{{ $documents['uploadedDocuments'] }} files</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3.5 py-2.5 ring-1 ring-inset ring-slate-100">
                    <span class="text-xs font-semibold text-slate-600">Verification Level</span>
                    <span class="text-xs font-extrabold text-slate-900">Level {{ $documents['verificationLevel'] }}</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between text-[11px] font-bold">
                    <span class="text-slate-500">Verification Progress</span>
                    <span class="text-blue-600">{{ $documents['progress'] }}%</span>
                </div>
                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-500" style="width: {{ $documents['progress'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- 8 + 9. Affiliate + finance requests --}}
    <div class="mt-8 grid gap-4 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-slate-900">Affiliate Summary</h2>
                <a href="{{ route('affiliate.center') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">Go to Affiliate Dashboard</a>
            </div>
            <div class="mt-4 flex items-end gap-2">
                <div class="text-3xl font-extrabold tracking-tight text-slate-900">${{ number_format($affiliate['commissionBalance'], 2) }}</div>
                <div class="mb-1 text-xs font-bold text-slate-400">Commission Balance</div>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ($affiliateStats = [
                    ['label' => 'Total Referrals', 'value' => $affiliate['totalReferrals']],
                    ['label' => 'Qualified Leads', 'value' => $affiliate['qualifiedLeads']],
                    ['label' => 'Total Visitors', 'value' => $affiliate['totalVisitors']],
                    ['label' => 'Conversion Rate', 'value' => $affiliate['conversionRate'] . '%'],
                    ['label' => 'Affiliate Rating', 'value' => $affiliate['rating'] . ' / 5'],
                ] as $stat)
                    <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-inset ring-slate-100">
                        <div class="text-lg font-extrabold text-slate-900">{{ is_numeric($stat['value']) ? number_format($stat['value']) : $stat['value'] }}</div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">Finance Requests</h2>
                <a href="{{ route('finance.team.create') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">Create New Finance Request</a>
            </div>
            <ul class="divide-y divide-slate-50 px-5">
                @foreach ($financeRequests as $request)
                    <li class="flex items-center justify-between gap-3 py-3.5">
                        <div class="min-w-0">
                            <div class="truncate text-xs font-bold text-slate-900">{{ $request['type'] }} · ${{ number_format($request['amount'], 2) }}</div>
                            <div class="text-[11px] font-medium text-slate-400">{{ $request['id'] }} · {{ $request['date'] }}</div>
                        </div>
                        <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $request['statusColor'] }}">{{ $request['status'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- 10 + 11. Recent activity + transactions --}}
    <div class="mt-8 grid gap-4 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">Recent Activity</h2>
                <a href="{{ route('support.index') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View All</a>
            </div>
            <ul class="divide-y divide-slate-50 px-5">
                @foreach ($recentActivity as $activity)
                    <li class="flex items-center gap-3 py-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $activity['color'] }}">
                            @svg($activity['icon'], 'h-4.5 w-4.5')
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-xs font-bold text-slate-800">{{ $activity['label'] }}</div>
                            <div class="truncate text-[11px] font-medium text-slate-400">{{ $activity['detail'] }}</div>
                        </div>
                        <span class="shrink-0 text-[10px] font-semibold text-slate-400">{{ $activity['date'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">Recent Transactions</h2>
                <a href="{{ route('finance.transactions') }}" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View All Transactions</a>
            </div>
            <ul class="divide-y divide-slate-50 px-5">
                @foreach ($recentTransactions as $txn)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <div class="truncate text-xs font-bold text-slate-900">{{ $txn['label'] }}</div>
                            <div class="truncate text-[11px] font-medium text-slate-400">{{ $txn['description'] }} · {{ $txn['date'] }}</div>
                            <span class="mt-1 inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold ring-1 ring-inset {{ $txn['statusColor'] }}">{{ $txn['status'] }}</span>
                        </div>
                        <div class="shrink-0 text-right">
                            <div class="text-xs font-extrabold {{ $txn['type'] === 'credit' ? 'text-emerald-600' : 'text-slate-900' }}">
                                {{ $txn['type'] === 'credit' ? '+' : '-' }}${{ number_format($txn['amount'], 2) }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- 12 + 14. Market highlights + support --}}
    <div class="mt-8 grid gap-4 xl:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <h2 class="text-sm font-extrabold text-slate-900">Market Highlights</h2>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ([
                    ['label' => 'Current AVC Price', 'value' => '$' . $marketHighlights['avcPrice']],
                    ['label' => 'Available Projects', 'value' => $marketHighlights['totalProjects']],
                    ['label' => 'Property Listings', 'value' => $marketHighlights['totalProperties']],
                    ['label' => 'AVC Marketplace Listings', 'value' => $marketHighlights['marketplaceListings']],
                    ['label' => 'Newly Added Projects', 'value' => $marketHighlights['newProjects']],
                    ['label' => 'Featured Project', 'value' => 'Luxury Villas', 'featured' => true],
                ] as $stat)
                    <div class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-inset ring-slate-100">
                        <div class="text-sm font-extrabold {{ isset($stat['featured']) ? 'text-blue-600' : 'text-slate-900' }}">{{ $stat['value'] }}</div>
                        <div class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex flex-col items-start gap-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 p-4 ring-1 ring-inset ring-blue-100 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-xs font-extrabold text-slate-900">{{ $marketHighlights['featuredProject'] }}</div>
                    <div class="text-[11px] font-medium text-slate-500">Trending · 82% funded · 45 days remaining</div>
                </div>
                <a href="{{ $marketHighlights['featuredHref'] }}" class="shrink-0 rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-blue-700">View Project</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900">Support Center</h2>
            <div class="mt-4 space-y-2.5">
                <a href="{{ route('support.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 transition hover:border-blue-300 hover:bg-blue-50">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">@svg('heroicon-o-chat-bubble-left-right', 'h-4 w-4')</span>
                    <span class="text-xs font-bold text-slate-700">Live Chat</span>
                </a>
                <a href="https://wa.me/18005550134" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 transition hover:border-emerald-300 hover:bg-emerald-50">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
                    </span>
                    <span class="text-xs font-bold text-slate-700">WhatsApp</span>
                </a>
                <a href="https://t.me/avc_support" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 transition hover:border-sky-300 hover:bg-sky-50">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>
                    </span>
                    <span class="text-xs font-bold text-slate-700">Telegram</span>
                </a>
                <a href="{{ route('support.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 transition hover:border-blue-300 hover:bg-blue-50">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-600">@svg('heroicon-o-calendar', 'h-4 w-4')</span>
                    <span class="text-xs font-bold text-slate-700">Book a Meeting</span>
                </a>
                <a href="{{ route('support.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 transition hover:border-blue-300 hover:bg-blue-50">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600">@svg('heroicon-o-question-mark-circle', 'h-4 w-4')</span>
                    <span class="text-xs font-bold text-slate-700">FAQs</span>
                </a>
            </div>
            <a href="{{ route('support.index') }}" class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                @svg('heroicon-o-ticket', 'h-4 w-4')
                Submit Ticket
            </a>
        </div>
    </div>

    {{-- 15. Dashboard statistics footer --}}
    <div class="mt-8 grid grid-cols-2 gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-4 xl:grid-cols-8">
        @foreach ($statsFooter as $stat)
            <div class="text-center">
                <div class="text-sm font-extrabold text-slate-900">{{ $stat['value'] }}</div>
                <div class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>
</div>

    {{-- Guided tour UI --}}
    <div x-cloak>
        {{-- Welcome modal --}}
        <div x-show="state === 'welcome'" class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-3xl bg-white p-8 text-center shadow-2xl">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-lg">
                    @svg('heroicon-o-rocket-launch', 'h-8 w-8')
                </span>
                <h2 class="mt-5 text-xl font-extrabold text-slate-900">Welcome to AVC</h2>
                <p class="mt-2 text-sm font-medium leading-relaxed text-slate-500">Learn how AVC works in a few simple steps.</p>
                <div class="mt-6 grid gap-2.5">
                    <button type="button" @click="startTour()" class="w-full rounded-lg bg-blue-600 py-3 text-xs font-bold text-white transition hover:bg-blue-700">
                        Start Tour
                    </button>
                    <button type="button" @click="skipTour()" class="w-full rounded-lg border border-slate-200 py-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                        Skip for Now
                    </button>
                </div>
            </div>
        </div>

        {{-- Spotlight overlay + tooltip --}}
        <template x-if="state === 'step'">
            <div>
                <div class="pointer-events-none fixed z-[70] rounded-2xl shadow-[0_0_0_4px_#2563eb,0_0_0_9999px_rgba(15,23,42,0.72)] transition-all duration-300"
                     :style="spotStyle()"></div>
                <div class="fixed z-[75] w-[340px] max-w-[90vw] rounded-2xl bg-white p-5 shadow-2xl transition-all duration-300"
                     :style="tooltipStyle()">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Step <span x-text="stepIndex + 1"></span> of <span x-text="steps.length"></span></span>
                        <button type="button" @click="skipTour()" class="text-[11px] font-bold text-slate-400 transition hover:text-slate-600">Skip</button>
                    </div>
                    <h3 class="mt-2 text-base font-extrabold text-slate-900" x-text="currentStep().title"></h3>
                    <p class="mt-1.5 text-xs font-medium leading-relaxed text-slate-500" x-text="currentStep().message"></p>
                    <div x-show="currentStep().example" class="mt-3 rounded-xl bg-slate-50 px-3.5 py-2.5 text-[11px] font-semibold text-slate-600 ring-1 ring-inset ring-slate-100" x-text="currentStep().example"></div>
                    <div class="mt-4 flex items-center justify-between gap-2">
                        <button type="button" @click="prevStep()" x-show="stepIndex > 0"
                                class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Back</button>
                        <button type="button" @click="nextStep()"
                                class="ml-auto inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-5 py-2 text-xs font-bold text-white transition hover:bg-blue-700">
                            <span x-text="stepIndex === steps.length - 1 ? 'Finish' : 'Next'"></span>
                            @svg('heroicon-o-arrow-right', 'h-3.5 w-3.5')
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- Completion modal --}}
        <div x-show="state === 'complete'" class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-3xl bg-white p-8 text-center shadow-2xl">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    @svg('heroicon-o-check-circle', 'h-8 w-8')
                </span>
                <h2 class="mt-5 text-xl font-extrabold text-slate-900">Congratulations!</h2>
                <p class="mt-2 text-sm font-medium leading-relaxed text-slate-500">
                    You have successfully completed the AVC Guided Tour. You now understand how the platform works from adding funds to purchasing shares, receiving earnings, selling AVC, and withdrawing your funds.
                </p>
                <div class="mt-6 grid gap-2.5">
                    <button type="button" @click="closeTour()" class="w-full rounded-lg bg-blue-600 py-3 text-xs font-bold text-white transition hover:bg-blue-700">
                        Go to Dashboard
                    </button>
                    <button type="button" @click="restartTour()" class="w-full rounded-lg border border-slate-200 py-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                        Restart Tour
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    function guidedTour(autoShow, forced) {
        return {
            state: 'hidden',
            stepIndex: 0,
            spot: { top: 0, left: 0, width: 0, height: 0 },
            tooltip: { top: 0, left: 0 },
            steps: [
                {
                    selector: '#tour-balance',
                    title: 'Add Funds',
                    message: 'Add funds to your AVC Wallet using any available deposit method or through the Finance Team. Once completed, your balance will appear in your AVC Wallet.',
                    example: 'Deposit: $10 → Wallet Balance: 10 AVC',
                },
                {
                    selector: '#tour-marketplace',
                    title: 'Browse Projects',
                    message: 'Browse available projects, review their details, progress, expected returns, duration, and other information before choosing where to purchase shares.',
                },
                {
                    selector: '#tour-investments',
                    title: 'Purchase Shares',
                    message: 'Use the available AVC in your wallet to purchase shares in your selected project.',
                    example: 'Wallet: 10 AVC → Purchase Shares → Shares Successfully Added',
                },
                {
                    selector: '#tour-portfolio',
                    title: 'Track Your Portfolio',
                    message: 'Your purchased shares are displayed here. You can monitor project progress, share ownership, and earnings as they are distributed.',
                },
                {
                    selector: '#tour-balance',
                    title: 'Earnings',
                    message: 'As projects generate returns, your earnings are credited back to your AVC Wallet according to the project\u2019s distribution schedule.',
                    example: 'Starting Balance: 10 AVC + Earnings Added: 10 AVC = New Wallet Balance: 20 AVC',
                },
                {
                    selector: '#tour-sell',
                    title: 'Sell AVC',
                    message: 'When you want to convert your AVC balance into cash, list or sell your AVC through the AVC Marketplace using the platform\u2019s escrow process.',
                    example: 'Wallet: 20 AVC → Sell through AVC Marketplace → Funds Received',
                },
                {
                    selector: '#tour-withdraw',
                    title: 'Withdraw Funds',
                    message: 'After selling your AVC, you can request a withdrawal using the Finance Team or any other supported withdrawal method available on the platform.',
                },
            ],
            init() {
                if (forced || autoShow) {
                    this.state = 'welcome';
                }
            },
            currentStep() {
                return this.steps[this.stepIndex];
            },
            startTour() {
                this.stepIndex = 0;
                this.state = 'step';
                this.$nextTick(() => this.position());
            },
            restartTour() {
                this.startTour();
            },
            nextStep() {
                if (this.stepIndex >= this.steps.length - 1) {
                    this.finishTour();
                    return;
                }
                this.stepIndex++;
                this.position();
            },
            prevStep() {
                if (this.stepIndex > 0) {
                    this.stepIndex--;
                    this.position();
                }
            },
            position() {
                const el = document.querySelector(this.currentStep().selector);
                if (!el) return;
                el.scrollIntoView({ block: 'center', behavior: 'smooth' });
                clearTimeout(this._posTimer);
                this._posTimer = setTimeout(() => {
                    const rect = el.getBoundingClientRect();
                    this.spot = { top: rect.top - 6, left: rect.left - 6, width: rect.width + 12, height: rect.height + 12 };
                    const tooltipH = 260;
                    const below = rect.bottom + 12 + tooltipH < window.innerHeight;
                    this.tooltip = {
                        top: below ? rect.bottom + 12 : Math.max(12, rect.top - tooltipH - 12),
                        left: Math.min(Math.max(12, rect.left + rect.width / 2 - 170), Math.max(12, window.innerWidth - 352)),
                    };
                }, 400);
            },
            spotStyle() {
                return 'top:' + this.spot.top + 'px;left:' + this.spot.left + 'px;width:' + this.spot.width + 'px;height:' + this.spot.height + 'px;';
            },
            tooltipStyle() {
                return 'top:' + this.tooltip.top + 'px;left:' + this.tooltip.left + 'px;';
            },
            finishTour() {
                fetch('{{ route('dashboard.tour.complete') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                });
                this.state = 'complete';
            },
            skipTour() {
                fetch('{{ route('dashboard.tour.skip') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                });
                this.state = 'hidden';
            },
            closeTour() {
                this.state = 'hidden';
            },
        };
    }

    function dashboardOverview(ranges) {
        return {
            ranges,
            range: '1M',
            currency: 'USD',
            rates: { USD: 1, PHP: 57, EUR: 0.92, GBP: 0.79 },
            symbols: { USD: '$', PHP: '₱', EUR: '€', GBP: '£' },
            chart: null,
            init() {
                this.renderChart();
            },
            renderChart() {
                const r = this.ranges[this.range];
                const rate = this.rates[this.currency];
                const symbol = this.symbols[this.currency];
                this.chart = new Chart(this.$refs.canvas, {
                    type: 'line',
                    data: {
                        labels: r.labels,
                        datasets: [
                            { label: 'ROI Growth', data: r.roi.map((v) => v * rate), borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.08)', fill: true, tension: 0.35, borderWidth: 2, pointRadius: 3 },
                            { label: 'Dividends', data: r.dividends.map((v) => v * rate), borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.06)', fill: true, tension: 0.35, borderWidth: 2, pointRadius: 3 },
                            { label: 'Rental / Investment', data: r.rental.map((v) => v * rate), borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.06)', fill: true, tension: 0.35, borderWidth: 2, pointRadius: 3 },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: (v) => symbol + Number(v).toLocaleString(), font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                        },
                        plugins: { legend: { display: false } },
                    },
                });
            },
            switchRange(key) {
                const r = this.ranges[key];
                if (!r) return;
                this.range = key;
                const rate = this.rates[this.currency];
                this.chart.data.labels = r.labels;
                this.chart.data.datasets[0].data = r.roi.map((v) => v * rate);
                this.chart.data.datasets[1].data = r.dividends.map((v) => v * rate);
                this.chart.data.datasets[2].data = r.rental.map((v) => v * rate);
                this.chart.update();
            },
            updateCurrency() {
                if (!this.chart) return;
                const r = this.ranges[this.range];
                const rate = this.rates[this.currency];
                const symbol = this.symbols[this.currency];
                this.chart.data.datasets[0].data = r.roi.map((v) => v * rate);
                this.chart.data.datasets[1].data = r.dividends.map((v) => v * rate);
                this.chart.data.datasets[2].data = r.rental.map((v) => v * rate);
                this.chart.options.scales.y.ticks.callback = (v) => symbol + Number(v).toLocaleString();
                this.chart.update();
            },
        };
    }
</script>
@endpush
@endsection
