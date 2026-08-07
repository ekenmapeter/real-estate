@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Lease Agreement</h2>
    <p>This agreement records the rental arrangement for the property below, arranged through {{ site_name() }} Property Support.</p>

    <table class="meta-table">
        <tr><td class="k">Tenant</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Property</td><td class="v">{{ $related->property->title }} ({{ $related->property->ref() }})</td></tr>
        <tr><td class="k">Location</td><td class="v">{{ $related->property->fullLocation() }}</td></tr>
        <tr><td class="k">Monthly Rent</td><td class="v amount">{{ number_format((float) $related->property->monthly_rent, 2) }} USD</td></tr>
        <tr><td class="k">Security Deposit</td><td class="v">{{ $related->property->security_deposit ? number_format((float) $related->property->security_deposit, 2) . ' USD' : '—' }}</td></tr>
        <tr><td class="k">Inquiry Reference</td><td class="v">{{ $related->inquiry_number }}</td></tr>
        <tr><td class="k">Agreed At</td><td class="v">{{ $related->created_at->format('M d, Y H:i') }}</td></tr>
    </table>
@endsection
