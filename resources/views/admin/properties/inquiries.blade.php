@extends('layouts.main')

@section('title', 'Property Inquiries | Admin | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
    .module-tab { padding: .45rem 1.1rem; border-radius: 50rem; font-weight: 700; font-size: .82rem; color: #475569; background: #fff; border: 1.5px solid #dbe2ec; text-decoration: none; }
    .module-tab.active { background: #0B1F3A; color: #fff; border-color: #0B1F3A; }
    .module-tab:hover:not(.active) { border-color: #2563eb; color: #2563eb; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-chat-dots me-2" style="color:#2563eb;"></i>Property Inquiries & Requests</h4>
                <p class="text-muted small mb-0">Viewing requests, purchase interests and rental applications — all admin-managed.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">Conversations</a>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;">Properties</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="d-flex flex-wrap gap-2 mb-3">
            @php
                $typeTabs = ['all' => 'All', 'viewing' => 'Viewing Requests', 'purchase' => 'Purchase Interests', 'rental' => 'Rental Applications', 'general' => 'General'];
            @endphp
            @foreach($typeTabs as $key => $label)
                <a href="{{ route('admin.inquiries.index', ['type' => $key]) }}" class="module-tab {{ (request('type') ?: 'all') === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th>Property</th>
                            <th>Requester</th>
                            <th>Type</th>
                            <th>Channel</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inquiry)
                            @php
                                $badge = match ($inquiry->status) {
                                    'awaiting_admin_review' => ['#eff6ff', '#2563eb'],
                                    'representative_verification' => ['#fdf4ff', '#c026d3'],
                                    'viewing_scheduled', 'purchase_discussion', 'rental_review' => ['#fffbeb', '#d97706'],
                                    'completed' => ['#f0fdf4', '#16a34a'],
                                    'cancelled' => ['#fef2f2', '#dc2626'],
                                    default => ['#f8fafc', '#64748b'],
                                };
                            @endphp
                            <tr>
                                <td class="px-4 small fw-bold" style="color:#0B1F3A;">{{ $inquiry->inquiry_number }}</td>
                                <td>
                                    <div class="small fw-bold" style="color:#0B1F3A;">{{ Str::limit($inquiry->property->title, 30) }}</div>
                                    <div class="small text-muted">{{ $inquiry->property->ref() }}</div>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $inquiry->full_name }}</div>
                                    <div class="small text-muted">{{ $inquiry->email }}</div>
                                </td>
                                <td><span class="badge small" style="background:#f8fafc; color:#334155; border:1px solid #dbe2ec;">{{ $inquiry->typeLabel() }}</span></td>
                                <td>
                                    <span class="badge small" style="background:{{ $inquiry->preferred_channel === 'whatsapp' ? '#f0fdf4' : '#eff6ff' }}; color:{{ $inquiry->preferred_channel === 'whatsapp' ? '#16a34a' : '#2563eb' }};">
                                        <i class="bi {{ $inquiry->preferred_channel === 'whatsapp' ? 'bi-whatsapp' : 'bi-telegram' }} me-1"></i>{{ ucfirst($inquiry->preferred_channel) }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $inquiry->created_at->format('M d, H:i') }}</td>
                                <td><span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">{{ $inquiry->statusLabel() }}</span></td>
                                <td class="px-4">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn btn-sm fw-bold text-white rounded-3" style="background:#0B1F3A;">Manage</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    No inquiries in this view.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($inquiries->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $inquiries->links() }}</div>
        @endif
    </div>
</section>
@endsection
