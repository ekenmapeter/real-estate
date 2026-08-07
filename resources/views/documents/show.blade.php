@extends('layouts.main')

@section('title', $document->title . ' | Documents | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
    .doc-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
</style>

<section class="doc-shell py-4">
    <div class="container-fluid px-4 user-shell-content" style="max-width:1200px;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb bg-transparent p-0 mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}" class="text-decoration-none text-muted">Documents</a></li>
                <li class="breadcrumb-item active fw-bold text-dark text-truncate" style="max-width:320px;">{{ $document->title }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;">{{ $document->title }}</h4>
                <div class="small text-muted">{{ $document->reference }}
                    @php [$sbg, $sfg] = $document->statusBadge(); @endphp
                    <x-status-badge :label="$document->statusLabel()" :bg="$sbg" :fg="$sfg" />
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('documents.download', $document) }}" class="btn fw-bold text-white rounded-3" style="background:#0B1F3A;"><i class="bi bi-download me-1"></i> Download</a>
                <a href="{{ route('documents.print', $document) }}" target="_blank" class="btn btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;"><i class="bi bi-printer me-1"></i> Print</a>
                <button class="btn btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;" onclick="shareDoc()"><i class="bi bi-share me-1"></i> Share</button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-3" style="background:#e2e8f0;">
            <iframe src="{{ route('documents.print', $document) }}" style="width:100%; height:75vh; border:none; border-radius:8px; background:#fff;"></iframe>
        </div>
    </div>
</section>

<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="fw-bold mb-0" style="color:#0B1F3A;"><i class="bi bi-share me-2" style="color:#2563eb;"></i>Share Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted">Anyone with this link can view the document for 7 days. Links are signed and can only be created by you.</p>
                <div class="input-group">
                    <input type="text" id="shareLinkInput" class="form-control" readonly>
                    <button class="btn fw-bold text-white" style="background:#0B1F3A;" type="button" onclick="copyShareLink()">Copy</button>
                </div>
                <div class="alert alert-success small mt-3 mb-0 d-none" id="shareCopied">Link copied to clipboard.</div>
            </div>
        </div>
    </div>
</div>

<script>
function shareDoc() {
    fetch('{{ route('documents.share', $document) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: new FormData()
    }).then(r => r.json()).then(data => {
        document.getElementById('shareLinkInput').value = data.link;
        new bootstrap.Modal(document.getElementById('shareModal')).show();
    });
}
function copyShareLink() {
    const input = document.getElementById('shareLinkInput');
    input.select();
    navigator.clipboard?.writeText(input.value).then(() => {
        document.getElementById('shareCopied').classList.remove('d-none');
        setTimeout(() => document.getElementById('shareCopied').classList.add('d-none'), 2000);
    });
}
</script>
@endsection
