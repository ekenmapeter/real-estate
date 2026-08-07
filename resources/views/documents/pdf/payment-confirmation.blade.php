@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Payment Confirmation</h2>
    <p>This document confirms a payment associated with your account.</p>

    <table class="meta-table">
        @foreach(($metadata['rows'] ?? []) as $row)
            <tr><td class="k">{{ $row['label'] }}</td><td class="v">{{ $row['value'] }}</td></tr>
        @endforeach
        @if(empty($metadata['rows'] ?? []))
            <tr><td class="k">Reference</td><td class="v">{{ $document->reference }}</td></tr>
            <tr><td class="k">Amount</td><td class="v">{{ isset($metadata['amount']) ? number_format((float) $metadata['amount'], 2) : '—' }}</td></tr>
        @endif
    </table>
@endsection
