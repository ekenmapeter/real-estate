@extends('layouts.account')

@section('title', 'My Deals | AVC Marketplace | ' . site_name())

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- Back link --}}
    <a href="{{ route('avc-marketplace.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to AVC Marketplace
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">My Deals</h1>
        <p class="mt-1 text-sm font-medium text-slate-500">All marketplace transactions you participated in, even when the listing belongs to another user.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="px-5 py-3">Deal</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Listing</th>
                        <th class="px-5 py-3 text-right">AVC Amount</th>
                        <th class="px-5 py-3">Payment Method</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deals as $deal)
                        <tr class="border-b border-slate-50 last:border-0">
                            <td class="px-5 py-4">
                                <code class="text-[11px] font-extrabold text-blue-600">{{ $deal['reference'] }}</code>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset
                                    {{ $deal['role'] === 'Buyer' ? 'bg-indigo-50 text-indigo-700 ring-indigo-200' : 'bg-violet-50 text-violet-700 ring-violet-200' }}">
                                    {{ $deal['role'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4"><code class="text-[11px] font-bold text-slate-500">#{{ $deal['listing'] }}</code></td>
                            <td class="px-5 py-4 text-right text-xs font-extrabold text-slate-900">{{ number_format($deal['amount']) }} AVC</td>
                            <td class="px-5 py-4 text-xs font-bold text-slate-700">{{ $deal['method'] }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $deal['statusColor'] }}">{{ $deal['status'] }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('avc-marketplace.deal', $deal['reference']) }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">{{ $deal['action'] }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
