@extends('layouts.account')

@section('title', 'My Listings | AVC Marketplace | ' . site_name())

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- Back link --}}
    <a href="{{ route('avc-marketplace.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to AVC Marketplace
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">My Listings</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Manage your marketplace listings and their escrow status.</p>
        </div>
        <a href="{{ route('avc-marketplace.create') }}" class="inline-flex w-fit items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
            @svg('heroicon-o-plus-circle', 'h-4 w-4')
            Create Listing
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="px-5 py-3">Listing</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3 text-right">Amount</th>
                        <th class="px-5 py-3">Payment Method</th>
                        <th class="px-5 py-3">Escrow Status</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Created</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listings as $listing)
                        <tr class="border-b border-slate-50 last:border-0">
                            <td class="px-5 py-3.5">
                                <code class="text-[11px] font-extrabold text-blue-600">#{{ $listing['reference'] }}</code>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset
                                    {{ $listing['type'] === 'sell' ? 'bg-blue-50 text-blue-700 ring-blue-200' : 'bg-emerald-50 text-emerald-700 ring-emerald-200' }}">
                                    {{ $listing['type'] === 'sell' ? 'Sell' : 'Buy' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-xs font-extrabold text-slate-900">{{ number_format($listing['amount']) }} AVC</span>
                                <span class="block text-[10px] font-medium text-slate-400">{{ number_format($listing['remaining']) }} remaining</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-bold text-slate-700">{{ $listing['paymentMethod'] }}</td>
                            <td class="px-5 py-3.5 text-[11px] font-bold text-slate-600">{{ $listing['escrowStatus'] }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $listing['statusColor'] }}">
                                    {{ ucfirst(str_replace('_', ' ', $listing['status'])) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-[11px] font-medium text-slate-500">{{ $listing['createdAt'] }}</td>
                            <td class="px-5 py-3.5 text-right">
                                @if (in_array($listing['status'], ['pending_review', 'changes_required']))
                                    <a href="#" @click.prevent class="text-[11px] font-bold text-blue-600 hover:text-blue-800">Edit</a>
                                    <span class="mx-1.5 text-slate-200">|</span>
                                    <a href="#" @click.prevent class="text-[11px] font-bold text-rose-600 hover:text-rose-800">Cancel</a>
                                @elseif ($listing['status'] === 'live')
                                    <a href="#" @click.prevent class="text-[11px] font-bold text-amber-600 hover:text-amber-800">Pause</a>
                                    <span class="mx-1.5 text-slate-200">|</span>
                                    <a href="#" @click.prevent class="text-[11px] font-bold text-rose-600 hover:text-rose-800">Cancel</a>
                                @elseif ($listing['status'] === 'completed')
                                    <span class="text-[11px] font-bold text-slate-400">View Receipt</span>
                                @else
                                    <span class="text-[11px] font-bold text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
