@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Investment Agreement</h2>
    <p>This agreement records the share investment made by the investor in the project below, subject to the platform's terms and conditions.</p>

    <table class="meta-table">
        <tr><td class="k">Project</td><td class="v">{{ $related->project->title }}</td></tr>
        <tr><td class="k">Project Reference</td><td class="v">{{ $related->project->ref() }}</td></tr>
        <tr><td class="k">Cycle Code</td><td class="v">{{ $related->cycle_code }}</td></tr>
        <tr><td class="k">Shares Owned</td><td class="v">{{ number_format($related->shares_owned) }} shares</td></tr>
        <tr><td class="k">Share Price</td><td class="v">{{ number_format((float) $related->share_price, 2) }} AVC</td></tr>
        <tr><td class="k">Total Purchase Amount</td><td class="v amount">{{ number_format((float) $related->total_purchase_amount, 2) }} AVC</td></tr>
        <tr><td class="k">Duration</td><td class="v">{{ $related->duration_label }}</td></tr>
        <tr><td class="k">Target Earnings</td><td class="v">{{ number_format((float) $related->target_earnings_pct, 2) }}% ({{ number_format((float) $related->projected_earnings, 2) }} AVC)</td></tr>
        <tr><td class="k">Purchased At</td><td class="v">{{ $related->purchased_at?->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->status)) }}</td></tr>
    </table>

    <div class="info-box">
        <strong>Investor:</strong> {{ $user->name }} ({{ $user->email }})<br>
        <strong>Receipt number:</strong> {{ $related->receipt_number }}
    </div>
@endsection
