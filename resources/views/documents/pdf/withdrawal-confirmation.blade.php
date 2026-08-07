@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Withdrawal Confirmation</h2>
    <p>This confirmation certifies that your withdrawal has been processed successfully.</p>

    <table class="meta-table">
        <tr><td class="k">Withdrawal Reference</td><td class="v">{{ $related->withdrawal_code }}</td></tr>
        <tr><td class="k">Amount (AVC)</td><td class="v">{{ number_format((float) ($related->avc_amount ?? $related->amount), 2) }} AVC</td></tr>
        <tr><td class="k">Estimated Net Payout</td><td class="v amount">{{ number_format((float) $related->estimated_net_payout, 2) }} {{ $related->payout_currency ?? 'USD' }}</td></tr>
        <tr><td class="k">Platform Fee</td><td class="v">{{ number_format((float) $related->platform_fee, 2) }}</td></tr>
        <tr><td class="k">Withdrawal Method</td><td class="v">{{ $related->methodLabel() }}</td></tr>
        <tr><td class="k">Transaction Reference</td><td class="v">{{ $related->transaction_reference ?? '—' }}</td></tr>
        <tr><td class="k">Completed At</td><td class="v">{{ $related->completed_at?->format('M d, Y H:i') ?? $related->updated_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ $related->formattedStatusLabel() }}</td></tr>
    </table>
@endsection
