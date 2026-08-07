@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">{{ $related->offer_type === 'buy' ? 'AVC Buy Order Receipt' : 'AVC Sell Order Receipt' }}</h2>
    <p>This receipt confirms the {{ $related->offer_type === 'buy' ? 'buy' : 'sell' }} order below on the AVC Marketplace.</p>

    <table class="meta-table">
        <tr><td class="k">Order Reference</td><td class="v">{{ $related->listing_number ?: $related->reference }}</td></tr>
        <tr><td class="k">Order Type</td><td class="v">{{ ucfirst($related->offer_type) }} Offer</td></tr>
        <tr><td class="k">Amount</td><td class="v amount">{{ number_format((float) $related->amount, 2) }} AVC</td></tr>
        <tr><td class="k">Payment Method</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->payment_method)) }}</td></tr>
        <tr><td class="k">Country</td><td class="v">{{ $related->country ?? '—' }}</td></tr>
        <tr><td class="k">Placed By</td><td class="v">{{ $related->seller?->name ?? $user->name }}</td></tr>
        <tr><td class="k">Placed At</td><td class="v">{{ $related->created_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->status)) }}</td></tr>
    </table>
@endsection
