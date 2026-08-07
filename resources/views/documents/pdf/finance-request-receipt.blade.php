@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Finance Request Receipt</h2>
    <p>This receipt confirms the finance request below.</p>

    <table class="meta-table">
        <tr><td class="k">Request Reference</td><td class="v">{{ $related->request_id }}</td></tr>
        <tr><td class="k">Type</td><td class="v">{{ ucwords(str_replace('_', ' ', $related->type)) }}</td></tr>
        <tr><td class="k">Amount</td><td class="v amount">{{ number_format((float) $related->amount, 2) }} {{ $related->currency ?? 'USD' }}</td></tr>
        <tr><td class="k">Country</td><td class="v">{{ $related->country }}</td></tr>
        <tr><td class="k">Payment Method</td><td class="v">{{ $related->payment_method ? ucwords(str_replace('_', ' ', $related->payment_method)) : '—' }}</td></tr>
        <tr><td class="k">Sender</td><td class="v">{{ $related->sender_name }} ({{ $related->sender_email }})</td></tr>
        <tr><td class="k">Completed At</td><td class="v">{{ $related->completed_at?->format('M d, Y H:i') ?? $related->updated_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ $related->formattedStatusLabel() }}</td></tr>
    </table>
@endsection
