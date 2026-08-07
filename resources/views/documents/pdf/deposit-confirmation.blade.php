@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Deposit Confirmation</h2>
    <p>Your deposit request has been received. Payment instructions have been assigned — complete the payment within the window shown below.</p>

    <table class="meta-table">
        <tr><td class="k">Deposit Reference</td><td class="v">{{ $related->deposit_code }}</td></tr>
        <tr><td class="k">Amount</td><td class="v">{{ $related->deposit_currency ?? 'USD' }} {{ number_format((float) ($related->deposit_amount ?? $related->amount), 2) }}</td></tr>
        <tr><td class="k">AVC Expected</td><td class="v amount">{{ number_format((float) $related->gross_avc, 2) }} AVC</td></tr>
        <tr><td class="k">Payment Method</td><td class="v">{{ $related->methodLabel() }}</td></tr>
        <tr><td class="k">Instructions Expire</td><td class="v">{{ $related->expires_at?->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ $related->formattedStatusLabel() }}</td></tr>
    </table>

    <div class="info-box">
        <strong>Payment instructions:</strong><br>
        {!! nl2br(e(is_array($related->admin_instructions) ? implode("\n", $related->admin_instructions) : ($related->admin_instructions ?? 'See your deposit page.'))) !!}
    </div>
@endsection
