@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Identity Verification Report</h2>
    <p>Record of the identity documents submitted for verification by the account holder.</p>

    <table class="meta-table">
        <tr><td class="k">Account Holder</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Email</td><td class="v">{{ $user->email }}</td></tr>
        <tr><td class="k">Document Type</td><td class="v">{{ $metadata['document_label'] ?? 'Identity Document' }}</td></tr>
        <tr><td class="k">Submitted At</td><td class="v">{{ $user->kyc_submitted_at?->format('M d, Y H:i') ?? now()->format('M d, Y H:i') }}</td></tr>
        <tr><td class="k">Status</td><td class="v">{{ ucfirst($user->kyc_status ?? 'submitted') }}</td></tr>
    </table>

    <div class="info-box">
        This report references the original uploaded file. The file itself is stored securely and is
        restricted — only authorized personnel can access it.
    </div>
@endsection
