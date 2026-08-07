@extends('layouts.main')

@section('title', 'Property Reports | Admin | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4" style="max-width:1000px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-flag me-2" style="color:#dc2626;"></i>Reports & Fraud Flags</h4>
                <p class="text-muted small mb-0">Reported listings and fraud reports submitted by users.</p>
            </div>
            <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">Back to Properties</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Report</th>
                            <th>Property</th>
                            <th>Reported By</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td class="px-4">
                                    <span class="badge small fw-bold" style="background:{{ $report->report_type === 'fraud' ? '#fef2f2' : '#fffbeb' }}; color:{{ $report->report_type === 'fraud' ? '#dc2626' : '#d97706' }};">
                                        {{ $report->reportTypeLabel() }}
                                    </span>
                                    <div class="small text-muted mt-1">{{ $report->created_at->format('M d, H:i') }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.properties.review', $report->property) }}" class="small fw-bold text-decoration-none" style="color:#0B1F3A;">{{ Str::limit($report->property->title, 28) }}</a>
                                    <div class="small text-muted">{{ $report->property->ref() }}</div>
                                </td>
                                <td class="small">{{ $report->reporter ? $report->reporter->name : 'Guest' }}</td>
                                <td class="small text-muted" style="max-width:280px;">{{ Str::limit($report->reason, 90) }}</td>
                                <td>
                                    <span class="status-badge" style="background:{{ $report->status === 'open' ? '#fef2f2' : ($report->status === 'resolved' ? '#f0fdf4' : '#f1f5f9') }}; color:{{ $report->status === 'open' ? '#dc2626' : ($report->status === 'resolved' ? '#16a34a' : '#64748b') }};">{{ ucfirst($report->status) }}</span>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.properties.review', $report->property) }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">Review Listing</a>
                                        @if($report->status === 'open')
                                            <form action="{{ route('admin.reports.resolve', $report) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success fw-bold rounded-3">Resolve</button>
                                            </form>
                                            <form action="{{ route('admin.reports.dismiss', $report) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-secondary fw-bold rounded-3">Dismiss</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-flag fs-1 d-block mb-2 opacity-50"></i>
                                    No reports. All listings are clean.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($reports->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $reports->links() }}</div>
        @endif
    </div>
</section>
@endsection
