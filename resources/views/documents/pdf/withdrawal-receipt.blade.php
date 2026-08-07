@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Withdrawal Request Receipt</h2>
    <p>This receipt confirms that your withdrawal request has been submitted and is awaiting review.</p>

    <table class="meta-table">
        <tr><td class="k">Withdrawal Reference</td><td class="v">{{ $related->withdrawal_code }}</td></tr>
        <tr><td class="k">Amount (AVC)</td><td class="v">{{ number_format((float) ($related->avc_amount ?? $related->amount), 2) }} AVC</td></tr>
        <tr><td class="k">Gross USD Value</td><td class="v amount">{{ number_format((float) $related->gross_usd_value, 2) }} USD</td></tr>
        <tr><td class="k">Withdrawal Method</td><td class="v">{{ $related->methodLabel() }}</td></tr>
        <tr><td class="k">Submitted At</td><td class="v">{{ $related->created_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ $related->formattedStatusLabel() }}</td></tr>
    </table>
@endsection
