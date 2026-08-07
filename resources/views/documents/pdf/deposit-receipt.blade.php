@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Deposit Receipt</h2>
    <p>This receipt confirms the deposit transaction below has been processed and your AVC balance credited.</p>

    <table class="meta-table">
        <tr><td class="k">Deposit Reference</td><td class="v">{{ $related->deposit_code }}</td></tr>
        <tr><td class="k">Deposited Amount</td><td class="v">{{ $related->deposit_currency ?? 'USD' }} {{ number_format((float) ($related->deposit_amount ?? $related->amount), 2) }}</td></tr>
        <tr><td class="k">AVC Credited</td><td class="v amount">{{ number_format((float) $related->net_avc, 2) }} AVC</td></tr>
        <tr><td class="k">Rate Applied</td><td class="v">{{ number_format((float) $related->avc_rate, 4) }} AVC per {{ $related->deposit_currency ?? 'USD' }}</td></tr>
        <tr><td class="k">Fee</td><td class="v">{{ number_format((float) $related->fee_amount, 2) }} AVC</td></tr>
        <tr><td class="k">Payment Method</td><td class="v">{{ $related->methodLabel() }}</td></tr>
        <tr><td class="k">Credited At</td><td class="v">{{ $related->credited_at?->format('M d, Y H:i') ?? $related->created_at->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ $related->formattedStatusLabel() }}</td></tr>
    </table>

    <div class="info-box">
        Account holder: <strong>{{ $user->name }}</strong> ({{ $user->email }})<br>
        Account ID: {{ $user->account_id ?? '—' }}<br>
        Balance after credit: {{ number_format((float) $user->wallet_balance, 2) }} AVC
    </div>
@endsection
