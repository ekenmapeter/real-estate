@extends('layouts.account')

@section('title', 'AVC Transfer | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="transferHub({{ Js::from($transfers) }})">

    {{-- 1. Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">AVC Transfer</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Send and receive AVC Credits between verified AVC members.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('transfer.send') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                @svg('heroicon-o-paper-airplane', 'h-4 w-4')
                Send AVC
            </a>
            <a href="{{ route('transfer.receive') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                @svg('heroicon-o-qr-code', 'h-4 w-4')
                Receive AVC
            </a>
        </div>
    </div>

    {{-- 2. Summary cards --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-5">
        @foreach ($summary as $stat)
            <x-stat-card :icon="$stat['icon']" :color="$stat['color']" :label="$stat['label']" :value="$stat['value']" :caption="$stat['caption'] ?? null" />
        @endforeach
    </div>

    {{-- 3. Action cards --}}
    <div class="mt-4 grid gap-4 md:grid-cols-2">
        <a href="{{ route('transfer.send') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-6 text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
            <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15 backdrop-blur">
                    @svg('heroicon-o-paper-airplane', 'h-6 w-6')
                </span>
                <div>
                    <h2 class="text-lg font-extrabold">Send AVC</h2>
                    <p class="mt-1 text-xs font-medium leading-relaxed text-blue-100">Transfer AVC Credits to another verified AVC account using their AVC ID, email or username.</p>
                </div>
            </div>
            <span class="mt-5 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-blue-700 transition group-hover:bg-blue-50">
                Send AVC Credits
                @svg('heroicon-o-arrow-right', 'h-4 w-4')
            </span>
        </a>

        <a href="{{ route('transfer.receive') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-[#16224a] to-[#0f1e3d] p-6 text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
            <div class="absolute -bottom-10 -right-10 h-40 w-40 rounded-full bg-blue-500/20 blur-2xl"></div>
            <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur">
                    @svg('heroicon-o-qr-code', 'h-6 w-6')
                </span>
                <div>
                    <h2 class="text-lg font-extrabold">Receive AVC</h2>
                    <p class="mt-1 text-xs font-medium leading-relaxed text-slate-300">Share your personal QR code, AVC ID or email and receive AVC Credits securely.</p>
                </div>
            </div>
            <span class="mt-5 inline-flex items-center gap-2 rounded-lg border border-white/30 px-4 py-2.5 text-xs font-bold text-white transition group-hover:bg-white/10">
                Receive AVC Credits
                @svg('heroicon-o-arrow-right', 'h-4 w-4')
            </span>
        </a>
    </div>

    {{-- 4. Transfer history --}}
    <div id="transfer-history" class="mt-8 scroll-mt-20">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Transfer History</h2>
                <p class="text-xs font-medium text-slate-500">Every AVC Credit movement between users, in one place.</p>
            </div>
            <div class="no-scrollbar flex gap-1.5 overflow-x-auto rounded-lg bg-white p-1 shadow-sm">
                <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-slate-800'"
                        class="shrink-0 rounded-md px-2.5 py-1.5 text-[11px] font-bold transition">All</button>
                @foreach ([
                    ['key' => 'sent', 'label' => 'Sent'],
                    ['key' => 'received', 'label' => 'Received'],
                    ['key' => 'pending', 'label' => 'Pending'],
                    ['key' => 'failed', 'label' => 'Failed'],
                ] as $filter)
                    <button @click="statusFilter = '{{ $filter['key'] }}'" :class="statusFilter === '{{ $filter['key'] }}' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-slate-800'"
                            class="shrink-0 rounded-md px-2.5 py-1.5 text-[11px] font-bold transition">{{ $filter['label'] }}</button>
                @endforeach
            </div>
        </div>

        <div class="space-y-3">
            <template x-for="transfer in filteredTransfers()" :key="transfer.id">
                <a :href="'/transfer/history/' + transfer.id" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                              :class="transfer.type === 'received' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'">
                            <template x-if="transfer.type === 'received'">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3v11.25m9 1.5v-6a2.25 2.25 0 0 0-2.25-2.25H15"/></svg>
                            </template>
                            <template x-if="transfer.type !== 'received'">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15.75H17.25a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 5.75 6v2.25M21 3 3 21m0-15.75h2.25"/></svg>
                            </template>
                        </span>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <code class="text-[11px] font-extrabold text-blue-600" x-text="transfer.id"></code>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold ring-1 ring-inset" :class="statusBadge(transfer.status).classes" x-text="statusBadge(transfer.status).label"></span>
                            </div>
                            <div class="mt-0.5 truncate text-xs font-extrabold text-slate-900">
                                <span x-text="transfer.type === 'received' ? 'Received from' : 'Sent to'"></span>
                                <span x-text="transfer.counterparty"></span>
                            </div>
                            <div class="mt-0.5 flex flex-wrap items-center gap-2 text-[11px] font-medium text-slate-400">
                                <span x-text="transfer.date"></span>
                                <template x-if="transfer.note">
                                    <span class="truncate" x-text="'· ' + transfer.note"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <div class="text-sm font-extrabold" :class="transfer.type === 'received' ? 'text-emerald-600' : 'text-slate-900'">
                            <span x-text="transfer.type === 'received' ? '+' : '-'"></span><span x-text="Number(transfer.amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> AVC
                        </div>
                        <div class="text-[10px] font-medium text-slate-400">≈ $<span x-text="Number(transfer.amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> USD</div>
                    </div>
                </a>
            </template>
        </div>
        <div x-show="filteredTransfers().length === 0" x-cloak class="rounded-2xl border border-slate-200 bg-white py-14 text-center shadow-sm">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                @svg('heroicon-o-inbox', 'h-6 w-6')
            </span>
            <p class="mt-3 text-xs font-bold text-slate-500">No transfers match this filter.</p>
        </div>
    </div>
</div>

<script>
    function transferHub(transfers) {
        return {
            transfers,
            statusFilter: 'all',
            statusMap: {
                completed: { label: 'Completed', classes: 'bg-emerald-50 text-emerald-700 ring-emerald-200' },
                pending: { label: 'Pending', classes: 'bg-amber-50 text-amber-700 ring-amber-200' },
                failed: { label: 'Failed', classes: 'bg-rose-50 text-rose-700 ring-rose-200' },
            },
            statusBadge(status) {
                return this.statusMap[status] || { label: status, classes: 'bg-slate-100 text-slate-600 ring-slate-200' };
            },
            filteredTransfers() {
                if (this.statusFilter === 'all') return this.transfers;
                return this.transfers.filter((t) => t.status === this.statusFilter || t.type === this.statusFilter);
            },
        };
    }
</script>
@endsection
