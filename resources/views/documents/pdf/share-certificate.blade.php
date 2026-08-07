@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Share Certificate</h2>
    <p>This certificate certifies that the shareholder named below owns the shares described, issued by {{ site_name() }}.</p>

    <table class="meta-table">
        <tr><td class="k">Shareholder</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Account ID</td><td class="v">{{ $user->account_id ?? '—' }}</td></tr>
        <tr><td class="k">Project</td><td class="v">{{ $related->project->title }} ({{ $related->project->ref() }})</td></tr>
        <tr><td class="k">Cycle Code</td><td class="v">{{ $related->cycle_code }}</td></tr>
        <tr><td class="k">Number of Shares</td><td class="v amount">{{ number_format($related->shares_owned) }}</td></tr>
        <tr><td class="k">Share Price</td><td class="v">{{ number_format((float) $related->share_price, 2) }} AVC</td></tr>
        <tr><td class="k">Total Value</td><td class="v">{{ number_format((float) $related->total_purchase_amount, 2) }} AVC</td></tr>
        <tr><td class="k">Issued At</td><td class="v">{{ $related->purchased_at?->format('M d, Y') }}</td></tr>
    </table>

    <div class="sign">
        <div class="line">{{ site_name() }} — Authorized Signature</div>
    </div>
@endsection
