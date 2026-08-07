@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Property Purchase Contract</h2>
    <p>This contract records the purchase of the property below, administered and supervised by {{ site_name() }} Property Support.</p>

    <table class="meta-table">
        <tr><td class="k">Buyer</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Property</td><td class="v">{{ $related->property->title }}</td></tr>
        <tr><td class="k">Property Reference</td><td class="v">{{ $related->property->ref() }}</td></tr>
        <tr><td class="k">Location</td><td class="v">{{ $related->property->fullLocation() }}</td></tr>
        <tr><td class="k">Purchase Price</td><td class="v amount">{{ number_format((float) $related->amount, 2) }} AVC</td></tr>
        <tr><td class="k">Purchased At</td><td class="v">{{ $related->created_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucfirst($related->status) }}</td></tr>
    </table>

    <div class="info-box">
        Ownership transfers to the buyer upon completion. All transactions are secured and supervised by
        {{ site_name() }}. The property representative has been verified.
    </div>
@endsection
