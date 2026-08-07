@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Share Cycle Receipt</h2>
    <p>Receipt for the share purchase cycle described below.</p>

    <table class="meta-table">
        <tr><td class="k">Receipt Number</td><td class="v">{{ $related->receipt_number }}</td></tr>
        <tr><td class="k">Cycle Code</td><td class="v">{{ $related->cycle_code }}</td></tr>
        <tr><td class="k">Project</td><td class="v">{{ $related->project->title }} ({{ $related->project->ref() }})</td></tr>
        <tr><td class="k">Duration</td><td class="v">{{ $related->duration_label }}</td></tr>
        <tr><td class="k">Shares</td><td class="v">{{ number_format($related->shares_owned) }}</td></tr>
        <tr><td class="k">Total Paid</td><td class="v amount">{{ number_format((float) $related->total_purchase_amount, 2) }} AVC</td></tr>
        <tr><td class="k">Projected Earnings</td><td class="v">{{ number_format((float) $related->projected_earnings, 2) }} AVC</td></tr>
        <tr><td class="k">Completion Value</td><td class="v">{{ number_format((float) $related->completion_value, 2) }} AVC</td></tr>
        <tr><td class="k">Purchased At</td><td class="v">{{ $related->purchased_at?->format('M d, Y H:i') }}</td></tr>
    </table>
@endsection
