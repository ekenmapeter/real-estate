@extends('documents.pdf.layout')
@section('doc_content')
    <h2 class="title">Project Update</h2>
    <p>Official project update issued by {{ site_name() }} for project shareholders.</p>

    <table class="meta-table">
        <tr><td class="k">Project</td><td class="v">{{ $related->project->title }} ({{ $related->project->ref() }})</td></tr>
        <tr><td class="k">Update Title</td><td class="v">{{ $related->title }}</td></tr>
        <tr><td class="k">Category</td><td class="v">{{ $related->category }}</td></tr>
        <tr><td class="k">Published At</td><td class="v">{{ $related->published_at?->format('M d, Y H:i') }}</td></tr>
    </table>

    <div class="info-box">
        {!! nl2br(e($related->content)) !!}
    </div>
@endsection
