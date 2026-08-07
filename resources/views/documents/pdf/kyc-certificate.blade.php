@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">KYC Verification Certificate</h2>
    <p>This certificate confirms that the account holder below has successfully completed the Know-Your-Customer (KYC) verification process.</p>

    <table class="meta-table">
        <tr><td class="k">Account Holder</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Email</td><td class="v">{{ $user->email }}</td></tr>
        <tr><td class="k">Account ID</td><td class="v">{{ $user->account_id ?? '—' }}</td></tr>
        <tr><td class="k">Verification Status</td><td class="v amount">Verified</td></tr>
        <tr><td class="k">Verified At</td><td class="v">{{ $user->rep_verified_at?->format('M d, Y H:i') ?? $user->kyc_submitted_at?->format('M d, Y H:i') ?? now()->format('M d, Y H:i') }}</td></tr>
    </table>

    <div class="info-box">
        This account has passed identity verification. KYC documents on file are restricted and only visible
        to authorized {{ site_name() }} personnel. Verified accounts enjoy full platform access.
    </div>
@endsection
