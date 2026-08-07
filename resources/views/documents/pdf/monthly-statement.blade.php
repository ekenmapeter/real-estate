@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">{{ $document->title }}</h2>
    <p>Period: <strong>{{ $metadata['period_label'] ?? '—' }}</strong></p>

    <table class="meta-table">
        <tr><td class="k">Transaction Count</td><td class="v">{{ $metadata['transaction_count'] ?? 0 }}</td></tr>
        <tr><td class="k">Deposits (approved)</td><td class="v">{{ $metadata['deposit_count'] ?? 0 }}</td></tr>
        <tr><td class="k">Withdrawals (approved)</td><td class="v">{{ $metadata['withdrawal_count'] ?? 0 }}</td></tr>
        <tr><td class="k">Closing Balance</td><td class="v amount">{{ number_format((float) ($metadata['closing_balance'] ?? 0), 2) }} AVC</td></tr>
    </table>

    @if(! empty($metadata['transactions']))
        <table class="table">
            <tr>
                <th>Date</th><th>Type</th><th>Description</th><th>Reference</th><th>Amount</th><th>Status</th>
            </tr>
            @foreach($metadata['transactions'] as $txn)
                <tr>
                    <td>{{ $txn['date'] }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $txn['type'])) }}</td>
                    <td>{{ $txn['description'] }}</td>
                    <td>{{ $txn['reference'] ?? '—' }}</td>
                    <td>{{ number_format((float) $txn['amount'], 2) }}</td>
                    <td>{{ ucfirst($txn['status']) }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="info-box">No transactions were recorded for this period.</div>
    @endif
@endsection
