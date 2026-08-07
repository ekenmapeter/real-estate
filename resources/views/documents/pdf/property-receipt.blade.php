@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Property Receipt</h2>
    <p>Official receipt for the property transaction below, issued by {{ site_name() }}.</p>

    <table class="meta-table">
        <tr><td class="k">Receipt Reference</td><td class="v">{{ $document->reference }}</td></tr>
        <tr><td class="k">Buyer</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Property</td><td class="v">{{ $related->property->title }} ({{ $related->property->ref() }})</td></tr>
        <tr><td class="k">Amount Paid</td><td class="v amount">{{ number_format((float) $related->amount, 2) }} AVC</td></tr>
        <tr><td class="k">Transaction Date</td><td class="v">{{ $related->created_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucfirst($related->status) }}</td></tr>
    </table>
@endsection
