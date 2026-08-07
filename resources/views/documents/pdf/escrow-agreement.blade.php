@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Escrow Agreement</h2>
    <p>This agreement records that the AVC Marketplace order below is held in escrow, supervised by {{ site_name() }}.</p>

    <table class="meta-table">
        <tr><td class="k">Order Reference</td><td class="v">{{ $related->listing_number ?: $related->reference }}</td></tr>
        <tr><td class="k">Seller</td><td class="v">{{ $related->seller?->name ?? '—' }}</td></tr>
        <tr><td class="k">Buyer</td><td class="v">{{ $related->buyer?->name ?? '—' }}</td></tr>
        <tr><td class="k">Amount in Escrow</td><td class="v amount">{{ number_format((float) $related->amount, 2) }} AVC</td></tr>
        <tr><td class="k">Payment Method</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->payment_method)) }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->status)) }}</td></tr>
    </table>

    <div class="info-box">
        Escrowed funds are released only when both parties confirm the transaction, or per the decision of
        {{ site_name() }} after review. All AVC Marketplace deals are Telegram-mediated and admin-supervised.
    </div>
@endsection
