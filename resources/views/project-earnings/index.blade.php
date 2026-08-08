@extends('layouts.account')

@section('title', 'Project Earnings | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- 1. Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Project Earnings</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Earnings generated from your active project cycles, credited directly to your AVC Wallet.</p>
        </div>
        <a href="{{ route('portfolio.index') }}" class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            @svg('heroicon-o-briefcase', 'h-4 w-4')
            Back to My Portfolio
        </a>
    </div>

    @if ($filteredTitle)
        <div class="mb-4 flex items-center justify-between gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
            <div class="flex items-center gap-2 text-xs font-bold text-blue-800">
                @svg('heroicon-o-funnel', 'h-4 w-4')
                Showing earnings for <span class="font-extrabold">{{ $filteredTitle }}</span>
            </div>
            <a href="{{ route('project-earnings.index') }}" class="shrink-0 text-[11px] font-bold text-blue-600 hover:text-blue-800">View All Earnings</a>
        </div>
    @endif

    {{-- 2. Summary cards --}}
    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
        @foreach ($summary as $stat)
            <x-stat-card :icon="$stat['icon']" :color="$stat['color']" :label="$stat['label']" :value="$stat['value']" :caption="$stat['caption'] ?? null" />
        @endforeach
    </div>

    {{-- 3. Today's earnings --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-extrabold text-slate-900">Today's Earnings</h2>
            <p class="text-[11px] font-medium text-slate-400">Earnings credited today from your active project cycles.</p>
        </div>
        <ul class="divide-y divide-slate-50 px-5">
            @foreach ($todayEarnings as $earning)
                <li class="flex items-center gap-3.5 py-3.5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $earning['gradient'] }} text-white/30">
                        @svg('heroicon-o-building-office-2', 'h-5 w-5')
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-xs font-extrabold text-slate-900">{{ $earning['title'] }}</div>
                        <div class="text-[11px] font-medium text-slate-400">{{ $earning['time'] }} · AVC Wallet</div>
                    </div>
                    <div class="shrink-0 text-right">
                        <div class="text-sm font-extrabold text-emerald-600">+{{ number_format($earning['amount'], 2) }} AVC</div>
                        <div class="text-[10px] font-medium text-slate-400">≈ ${{ number_format($earning['amount'], 2) }} USD</div>
                    </div>
                    <span class="inline-flex shrink-0 items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                        {{ $earning['status'] }}
                    </span>
                </li>
            @endforeach
        </ul>
        <div class="flex items-center justify-between bg-slate-50 px-5 py-3.5">
            <span class="text-xs font-bold text-slate-500">Total Today's Earnings</span>
            <span class="text-sm font-extrabold text-emerald-600">+{{ number_format($todayTotal, 2) }} AVC <span class="text-[10px] font-medium text-slate-400">(≈ ${{ number_format($todayTotal, 2) }} USD)</span></span>
        </div>
    </div>

    {{-- 4. Earnings by active cycle --}}
    <div class="mt-8">
        <div class="mb-4">
            <h2 class="text-lg font-extrabold text-slate-900">Earnings by Active Cycle</h2>
            <p class="text-xs font-medium text-slate-500">Each active project card shows the earnings it has generated.</p>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($cycles as $cycle)
                <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="relative h-28 bg-gradient-to-br {{ $cycle['gradient'] }}">
                        <div class="flex h-full w-full items-center justify-center text-white/25">
                            @svg('heroicon-o-building-office-2', 'h-12 w-12')
                        </div>
                        <span class="absolute left-3 top-3 flex h-7 w-7 items-center justify-center rounded-full bg-white text-sm shadow">{{ $cycle['flag'] }}</span>
                        <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>Active
                        </span>
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="text-sm font-extrabold text-slate-900">{{ $cycle['title'] }}</h3>
                        <p class="text-[11px] font-medium text-slate-500">{{ $cycle['location'] }}</p>
                        <dl class="mt-4 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <dt class="font-semibold text-slate-500">Shares Owned</dt>
                                <dd class="font-extrabold text-slate-900">{{ $cycle['shares'] }} Shares</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="font-semibold text-slate-500">Today's Earnings</dt>
                                <dd class="font-extrabold text-emerald-600">+{{ number_format($cycle['today'], 2) }} AVC</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="font-semibold text-slate-500">Total Earnings</dt>
                                <dd class="font-extrabold text-slate-900">{{ number_format($cycle['total'], 2) }} AVC</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="font-semibold text-slate-500">Last Credited</dt>
                                <dd class="font-bold text-slate-700">{{ $cycle['lastCredited'] }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('project-earnings.index', ['project' => $cycle['key']]) }}"
                           class="mt-4 block rounded-lg bg-blue-600 py-2.5 text-center text-xs font-bold text-white transition hover:bg-blue-700">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 5. Earnings history --}}
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="text-sm font-extrabold text-slate-900">Earnings History</h2>
                <p class="text-[11px] font-medium text-slate-400">Every earning that has been credited to your AVC Wallet.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left">
                <thead>
                    <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="px-5 py-2.5">Date &amp; Time</th>
                        <th class="px-5 py-2.5">Project</th>
                        <th class="px-5 py-2.5 text-right">Amount</th>
                        <th class="px-5 py-2.5">Destination</th>
                        <th class="px-5 py-2.5">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $entry)
                        <tr class="border-b border-slate-50 last:border-0">
                            <td class="px-5 py-3 text-[11px] font-semibold text-slate-500">{{ $entry['date'] }}</td>
                            <td class="px-5 py-3 text-xs font-extrabold text-slate-900">{{ $entry['project'] }}</td>
                            <td class="px-5 py-3 text-right text-xs font-extrabold text-emerald-600">+{{ number_format($entry['amount'], 2) }} AVC</td>
                            <td class="px-5 py-3 text-[11px] font-bold text-slate-600">{{ $entry['destination'] }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">{{ $entry['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">@svg('heroicon-o-inbox', 'h-6 w-6')</span>
                                <p class="mt-3 text-xs font-bold text-slate-500">No credited earnings for this project yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50/50 px-5 py-4 text-center">
            <a href="{{ route('finance.transactions') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                @svg('heroicon-o-arrow-right', 'h-4 w-4')
                View All Transactions
            </a>
        </div>
    </div>

    {{-- 6. About project earnings --}}
    <div class="mt-6 flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
            @svg('heroicon-o-information-circle', 'h-5 w-5')
        </span>
        <div>
            <div class="text-sm font-extrabold text-slate-900">About Project Earnings</div>
            <p class="mt-1 text-xs font-medium leading-relaxed text-slate-500">
                Project Earnings displays earnings generated from your active project cycles. All credited earnings are automatically transferred to your AVC Wallet according to each project's earnings schedule.
            </p>
        </div>
    </div>
</div>
@endsection
