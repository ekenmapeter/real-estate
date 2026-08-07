@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Escrow Completion Certificate</h2>
    <p>This certificate confirms that the escrow transaction below has been completed and released.</p>

    <table class="meta-table">
        <tr><td class="k">Order Reference</td><td class="v">{{ $related->listing_number ?: $related->reference }}</td></tr>
        <tr><td class="k">Seller</td><td class="v">{{ $related->seller?->name ?? '—' }}</td></tr>
        <tr><td class="k">Buyer</td><td class="v">{{ $related->buyer?->name ?? '—' }}</td></tr>
        <tr><td class="k">Amount Released</td><td class="v amount">{{ number_format((float) $related->amount, 2) }} AVC</td></tr>
        <tr><td class="k">Completed At</td><td class="v">{{ $related->updated_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->status)) }}</td></tr>
    </table>

    <div class="sign">
        <div class="line">{{ site_name() }} — Escrow Supervision</div>
    </div>
@endsection
