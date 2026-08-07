@extends('layouts.dashboard-app')

@section('title', 'Documents | ' . site_name())

@section('content')
{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Documents</h1>
        <p class="mt-1 text-sm text-slate-500">Access and download all your investment, property, and account-related documents in one place.</p>
    </div>
    <div class="flex items-center gap-2">
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                @svg('heroicon-o-magnifying-glass', 'h-4 w-4 text-slate-400')
            </span>
            <input type="text" id="docSearchInput"
                   class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:w-64"
                   placeholder="Search documents..."
                   value="{{ $search ?? '' }}">
        </div>
        <button data-filters-toggle
                class="inline-flex items-center gap-2 rounded-xl bg-[#0F1E3D] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#16294f]">
            @svg('heroicon-o-funnel', 'h-4 w-4')
            Filter
        </button>
    </div>
</div>

{{-- Summary stat cards --}}
<div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
    <x-tw-stat-card href="{{ route('documents.index') }}" icon="heroicon-o-folder" color="bg-blue-500"
                     label="All Documents" :count="$stats['all']" sublabel="Total Documents"
                     :active="($category ?? 'all') === 'all'" />
    @foreach([
        ['key' => 'project_investment', 'icon' => 'heroicon-o-rocket-launch', 'color' => 'bg-emerald-500', 'label' => 'Project Investments'],
        ['key' => 'property', 'icon' => 'heroicon-o-building-office-2', 'color' => 'bg-purple-500', 'label' => 'Property Documents'],
        ['key' => 'finance', 'icon' => 'heroicon-o-wallet', 'color' => 'bg-orange-500', 'label' => 'Finance'],
        ['key' => 'marketplace', 'icon' => 'heroicon-o-arrows-right-left', 'color' => 'bg-pink-500', 'label' => 'AVC Marketplace'],
        ['key' => 'verification', 'icon' => 'heroicon-o-shield-check', 'color' => 'bg-teal-500', 'label' => 'Verification & Legal'],
    ] as $card)
        <x-tw-stat-card :href="route('documents.index', ['category' => $card['key']])" :icon="$card['icon']" :color="$card['color']"
                         :label="$card['label']" :count="$stats[$card['key']] ?? 0"
                         :active="($category ?? 'all') === $card['key']" />
    @endforeach
</div>

{{-- Horizontal scrollable tab bar --}}
<div class="mb-4 flex items-center gap-2" x-data="{ canLeft: false, canRight: false }" x-init="$nextTick(() => updateArrows())">
    <button type="button" x-show="canLeft" x-cloak @click="scrollTabs(-220)" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm hover:text-blue-600">
        @svg('heroicon-o-chevron-left', 'h-4 w-4')
    </button>
    <div id="docTabStrip" @scroll="updateArrows()" class="flex flex-1 items-center gap-1 overflow-x-auto pb-px [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <a href="{{ route('documents.index') }}"
           class="{{ ($category ?? 'all') === 'all' ? 'border-blue-600 font-bold text-blue-600' : 'border-transparent font-semibold text-slate-500 hover:border-slate-300 hover:text-slate-800' }} whitespace-nowrap border-b-2 px-3.5 py-2 text-sm transition">
            All Documents
        </a>
        @foreach(['project_investment' => 'Project Investments', 'property' => 'Properties', 'finance' => 'Finance', 'marketplace' => 'AVC Marketplace', 'verification' => 'Verification & Legal', 'statement' => 'Statements'] as $key => $label)
            <a href="{{ route('documents.index', ['category' => $key]) }}"
               class="{{ ($category ?? 'all') === $key ? 'border-blue-600 font-bold text-blue-600' : 'border-transparent font-semibold text-slate-500 hover:border-slate-300 hover:text-slate-800' }} whitespace-nowrap border-b-2 px-3.5 py-2 text-sm transition">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <button type="button" x-show="canRight" x-cloak @click="scrollTabs(220)" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm hover:text-blue-600">
        @svg('heroicon-o-chevron-right', 'h-4 w-4')
    </button>
</div>

{{-- Filter row --}}
<div id="docFilters" class="mb-6 {{ request()->hasAny(['status', 'date_range', 'from', 'to', 'search', 'per_page']) ? '' : 'hidden' }}">
    <form action="{{ route('documents.index') }}" method="GET" class="grid grid-cols-2 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4 lg:grid-cols-5">
        <input type="hidden" name="category" value="{{ $category ?? 'all' }}">
        <div>
            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">All Categories</label>
            <select name="category" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="all">All Categories</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" @selected(($category ?? 'all') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">All Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="all">All Status</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected(($status ?? 'all') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Date Range</label>
            <select name="date_range" onchange="toggleCustomDate(this.value)" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="all" @selected(($dateRange ?? 'all') === 'all')>All Time</option>
                <option value="today" @selected(($dateRange ?? '') === 'today')>Today</option>
                <option value="7d" @selected(($dateRange ?? '') === '7d')>Last 7 Days</option>
                <option value="month" @selected(($dateRange ?? '') === 'month')>Last Month</option>
                <option value="custom" @selected(($dateRange ?? '') === 'custom')>Custom</option>
            </select>
        </div>
        <div id="customDateRow" class="{{ ($dateRange ?? 'all') === 'custom' ? '' : 'hidden' }} col-span-2 md:col-span-1">
            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">From – To</label>
            <div class="flex items-center gap-1.5">
                <input type="date" name="from" value="{{ $from ?? '' }}" class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-xs text-slate-700 focus:border-blue-400 focus:outline-none">
                <span class="text-slate-400">–</span>
                <input type="date" name="to" value="{{ $to ?? '' }}" class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-xs text-slate-700 focus:border-blue-400 focus:outline-none">
            </div>
        </div>
        <div class="col-span-2 lg:col-span-2">
            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Search by document name...</label>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by document name, reference, project, property, order..."
                   class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div class="col-span-2 flex items-end gap-2 md:col-span-4 lg:col-span-5">
            <button class="inline-flex items-center gap-2 rounded-lg bg-[#0F1E3D] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#16294f]">
                @svg('heroicon-o-funnel', 'h-4 w-4') Apply Filters
            </button>
            <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>
</div>

{{-- Recent documents table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-bold text-slate-900">Recent Documents</h2>
        <p class="text-xs font-medium text-slate-500">
            @if($demo)
                Showing 1-{{ count($rows) }} of {{ count($rows) }} results
            @else
                Showing {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }} results
            @endif
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[820px] text-left">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <th class="px-5 py-3">Document Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Related To</th>
                    <th class="px-4 py-3">Date Issued</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $row)
                    <tr class="transition hover:bg-slate-50/60">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    @svg($row['icon'], 'h-5 w-5')
                                </span>
                                <span class="min-w-0">
                                    <span class="block max-w-[240px] truncate text-sm font-bold text-slate-900">{{ $row['title'] }}</span>
                                    <span class="block text-xs font-medium text-slate-400">{{ $row['reference'] }}</span>
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5"><x-tw-status-badge :label="$row['category']" :class="$row['category_class']" /></td>
                        <td class="px-4 py-3.5"><span class="block max-w-[180px] truncate text-sm font-medium text-slate-600">{{ $row['related'] }}</span></td>
                        <td class="px-4 py-3.5 text-sm font-medium text-slate-500">{{ $row['date'] }}</td>
                        <td class="px-4 py-3.5"><x-tw-status-badge :label="$row['status']" :class="$row['status_class']" /></td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($row['view_url'])
                                    <a href="{{ $row['view_url'] }}" title="View document" class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600">
                                        @svg('heroicon-o-eye', 'h-5 w-5')
                                    </a>
                                    <a href="{{ $row['download_url'] }}" title="Download" class="rounded-lg p-2 text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-600">
                                        @svg('heroicon-o-arrow-down-tray', 'h-5 w-5')
                                    </a>
                                @else
                                    <span class="rounded-lg p-2 text-slate-300" title="Demo document">
                                        @svg('heroicon-o-eye', 'h-5 w-5')
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <span class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                @svg('heroicon-o-folder-open', 'h-6 w-6')
                            </span>
                            <p class="text-sm font-bold text-slate-700">No documents found</p>
                            <p class="mt-1 text-xs text-slate-400">Documents are generated automatically as you use the platform.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer: count + pagination + per page --}}
    <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row">
        <p class="text-xs font-medium text-slate-500">
            @if($demo)
                Showing 1-{{ count($rows) }} of {{ count($rows) }} documents
            @else
                Showing {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }} documents
            @endif
        </p>

        @if(! $demo && $documents->hasPages())
            <nav class="flex items-center gap-1">
                <a href="{{ $documents->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50">
                    @svg('heroicon-o-chevron-left', 'h-4 w-4')
                </a>
                @foreach(range(1, $documents->lastPage()) as $page)
                    <a href="{{ $documents->url($page) }}"
                       class="{{ $page === $documents->currentPage() ? 'bg-[#0F1E3D] font-bold text-white' : 'text-slate-600 hover:bg-slate-100' }} flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition">
                        {{ $page }}
                    </a>
                @endforeach
                <a href="{{ $documents->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50">
                    @svg('heroicon-o-chevron-right', 'h-4 w-4')
                </a>
            </nav>
        @endif

        <form method="GET" action="{{ route('documents.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="category" value="{{ $category ?? 'all' }}">
            <input type="hidden" name="status" value="{{ $status ?? 'all' }}">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <span class="text-xs font-medium text-slate-500">Show</span>
            <select name="per_page" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs font-semibold text-slate-700 focus:border-blue-400 focus:outline-none">
                @foreach([8, 20, 50] as $n)
                    <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }}</option>
                @endforeach
            </select>
            <span class="text-xs font-medium text-slate-500">per page</span>
        </form>
    </div>
</div>

{{-- Document categories --}}
<div class="mt-8 mb-4 flex items-center justify-between">
    <h2 class="text-lg font-bold text-slate-900">Document Categories</h2>
    <a href="{{ route('documents.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">View All</a>
</div>
<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        ['key' => 'project_investment', 'icon' => 'heroicon-o-rocket-launch', 'color' => 'bg-emerald-500', 'footer' => 'Investment Agreements · Share Certificates · Ownership Certificates'],
        ['key' => 'property', 'icon' => 'heroicon-o-building-office-2', 'color' => 'bg-purple-500', 'footer' => 'Property Contracts · Lease Agreements'],
        ['key' => 'finance', 'icon' => 'heroicon-o-wallet', 'color' => 'bg-orange-500', 'footer' => 'Deposit Receipts · Withdrawal Confirmations'],
        ['key' => 'marketplace', 'icon' => 'heroicon-o-arrows-right-left', 'color' => 'bg-pink-500', 'footer' => 'Escrow Certificates · Buy Orders · Sell Orders'],
        ['key' => 'verification', 'icon' => 'heroicon-o-shield-check', 'color' => 'bg-teal-500', 'footer' => 'KYC · AML · Legal Documents'],
        ['key' => 'statement', 'icon' => 'heroicon-o-document-chart-bar', 'color' => 'bg-slate-500', 'footer' => 'Monthly Statements · Annual Statements · Tax Reports'],
    ] as $cat)
        <x-tw-category-card :href="route('documents.index', ['category' => $cat['key']])" :icon="$cat['icon']" :color="$cat['color']"
                            :name="$categories[$cat['key']]" :count="$stats[$cat['key']] ?? 0"
                            :footer="$cat['key'] === 'statement' ? 'View & Generate' : $cat['footer']" />
    @endforeach
</div>

{{-- Quick actions --}}
<h2 class="mb-4 mt-8 text-lg font-bold text-slate-900">Quick Actions</h2>
<div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
    <x-tw-quick-action form href="{{ route('documents.statement') }}" icon="heroicon-o-document-plus" color="bg-blue-500" label="Generate Statement" />
    <x-tw-quick-action href="{{ route('documents.zip') }}" icon="heroicon-o-archive-box-arrow-down" color="bg-emerald-500" label="Download All Documents" />
    <x-tw-quick-action form href="{{ route('documents.statement') }}" icon="heroicon-o-printer" color="bg-purple-500" label="Print Statement" />
    <x-tw-quick-action href="{{ $shareUrl ?? '#' }}" icon="heroicon-o-share" color="bg-pink-500" label="Share Document" />
</div>

{{-- Share link flash --}}
@if(session('share_link'))
    <div class="mt-4 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4">
        <input type="text" readonly value="{{ session('share_link') }}" class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-xs text-slate-700">
        <button onclick="navigator.clipboard.writeText('{{ session('share_link') }}').then(() => alert('Link copied!'))"
                class="shrink-0 rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">Copy</button>
    </div>
@endif

<script>
function scrollTabs(offset) {
    document.getElementById('docTabStrip')?.scrollBy({ left: offset, behavior: 'smooth' });
}
function updateArrows() {
    const strip = document.getElementById('docTabStrip');
    if (! strip || ! window.Alpine) return;
    const el = strip.closest('[x-data]');
    if (el && el._x_dataStack) {
        el._x_dataStack[0].canLeft = strip.scrollLeft > 4;
        el._x_dataStack[0].canRight = strip.scrollLeft < strip.scrollWidth - strip.clientWidth - 4;
    }
}
function toggleCustomDate(value) {
    document.getElementById('customDateRow')?.classList.toggle('hidden', value !== 'custom');
}
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('docSearchInput');
    searchInput?.addEventListener('keydown', e => {
        if (e.key !== 'Enter') return;
        const url = new URL('{{ route('documents.index') }}');
        if (searchInput.value) url.searchParams.set('search', searchInput.value);
        const cat = new URLSearchParams(location.search).get('category');
        if (cat) url.searchParams.set('category', cat);
        window.location = url.toString();
    });
    document.querySelector('[data-filters-toggle]')?.addEventListener('click', () => {
        document.getElementById('docFilters')?.classList.toggle('hidden');
    });
    toggleCustomDate(document.querySelector('[name="date_range"]')?.value || 'all');
});
</script>
@endsection
