@extends('layouts.account')

@section('title', $transfer['id'] . ' | AVC Transfer | ' . site_name())

@php
    $statusMap = [
        'completed' => ['label' => 'Completed', 'classes' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'pending' => ['label' => 'Pending', 'classes' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'failed' => ['label' => 'Failed', 'classes' => 'bg-rose-50 text-rose-700 ring-rose-200'],
    ];
    $badge = $statusMap[$transfer['status']] ?? $statusMap['completed'];
    $isReceived = $transfer['type'] === 'received';
    $nameParts = explode(' ', $transfer['counterparty']);
    $initials = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');
@endphp

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- Back link --}}
    <a href="{{ route('transfer.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to AVC Transfer
    </a>

    {{-- Receipt card --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50 px-6 py-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">AVC Transfer Receipt</div>
                    <h1 class="mt-1 text-lg font-extrabold text-slate-900">{{ $transfer['id'] }}</h1>
                </div>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold ring-1 ring-inset {{ $badge['classes'] }}">
                    {{ $badge['label'] }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            {{-- Amount --}}
            <div class="text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full {{ $isReceived ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                    @svg($isReceived ? 'heroicon-o-arrow-down-tray' : 'heroicon-o-arrow-up-tray', 'h-6 w-6')
                </div>
                <div class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                    {{ $isReceived ? '+' : '-' }}{{ number_format($transfer['amount'], 2) }} AVC
                </div>
                <div class="mt-1 text-xs font-semibold text-slate-400">≈ ${{ number_format($transfer['amount'], 2) }} USD</div>
                <div class="mt-1 text-[11px] font-medium text-slate-400">{{ $isReceived ? 'Received from' : 'Sent to' }} <span class="font-bold text-slate-700">{{ $transfer['counterparty'] }}</span></div>
            </div>

            {{-- Details --}}
            <dl class="mt-6 divide-y divide-slate-100 rounded-xl border border-slate-100 bg-slate-50 px-5">
                <div class="flex items-center justify-between gap-3 py-3">
                    <dt class="text-xs font-semibold text-slate-500">Transfer ID</dt>
                    <dd class="text-xs font-extrabold text-blue-600">{{ $transfer['id'] }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-3">
                    <dt class="text-xs font-semibold text-slate-500">Date &amp; Time</dt>
                    <dd class="text-xs font-bold text-slate-900">{{ $transfer['date'] }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-3">
                    <dt class="text-xs font-semibold text-slate-500">{{ $isReceived ? 'Sender' : 'Recipient' }}</dt>
                    <dd class="text-right">
                        <span class="block text-xs font-bold text-slate-900">{{ $transfer['counterparty'] }}</span>
                        <span class="block text-[10px] font-medium text-slate-400">{{ $transfer['counterpartyId'] }} · {{ $transfer['counterpartyEmail'] }}</span>
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-3">
                    <dt class="text-xs font-semibold text-slate-500">Amount</dt>
                    <dd class="text-xs font-extrabold text-slate-900">{{ number_format($transfer['amount'], 2) }} AVC</dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-3">
                    <dt class="text-xs font-semibold text-slate-500">Transfer Fee</dt>
                    <dd class="text-xs font-extrabold text-emerald-600">0.00 AVC</dd>
                </div>
                <div class="flex items-center justify-between gap-3 py-3">
                    <dt class="text-xs font-semibold text-slate-500">Status</dt>
                    <dd class="text-xs font-extrabold {{ $transfer['status'] === 'failed' ? 'text-rose-600' : ($transfer['status'] === 'pending' ? 'text-amber-600' : 'text-emerald-600') }}">
                        {{ ucfirst($transfer['status']) }}
                    </dd>
                </div>
                @if ($transfer['note'])
                    <div class="flex items-center justify-between gap-3 py-3">
                        <dt class="text-xs font-semibold text-slate-500">Note</dt>
                        <dd class="text-right text-xs font-bold text-slate-900">{{ $transfer['note'] }}</dd>
                    </div>
                @endif
            </dl>

            {{-- Actions --}}
            <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5">
                <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                    @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                    Download Receipt
                </a>
                <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    @svg('heroicon-o-share', 'h-4 w-4')
                    Share Receipt
                </a>
                <a href="{{ route('transfer.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    Back to AVC Transfer
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
