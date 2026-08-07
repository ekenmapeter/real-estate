@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Ownership Certificate</h2>
    <p>This certificate confirms the shareholder's active ownership in the project below.</p>

    <table class="meta-table">
        <tr><td class="k">Owner</td><td class="v">{{ $user->name }}</td></tr>
        <tr><td class="k">Project</td><td class="v">{{ $related->project->title }} ({{ $related->project->ref() }})</td></tr>
        <tr><td class="k">Cycle Code</td><td class="v">{{ $related->cycle_code }}</td></tr>
        <tr><td class="k">Shares Owned</td><td class="v amount">{{ number_format($related->shares_owned) }}</td></tr>
        <tr><td class="k">Purchase Amount</td><td class="v">{{ number_format((float) $related->total_purchase_amount, 2) }} AVC</td></tr>
        <tr><td class="k">Activated At</td><td class="v">{{ $related->activated_at?->format('M d, Y H:i') ?? '—' }}</td></tr>
        <tr><td class="k">Completion Date</td><td class="v">{{ $related->completion_date?->format('M d, Y') ?? '—' }}</td></tr>
    </table>

    <div class="info-box">
        This certificate confirms that the shareholder holds a verified ownership position in the project.
        Ownership is managed and verified by {{ site_name() }}.
    </div>
@endsection
