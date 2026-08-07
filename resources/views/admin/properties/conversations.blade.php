@extends('layouts.main')

@section('title', 'Admin Conversations | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
    .module-tab { padding: .45rem 1.1rem; border-radius: 50rem; font-weight: 700; font-size: .82rem; color: #475569; background: #fff; border: 1.5px solid #dbe2ec; text-decoration: none; }
    .module-tab.active { background: #0B1F3A; color: #fff; border-color: #0B1F3A; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4" style="max-width:1000px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-chat-square-text me-2" style="color:#2563eb;"></i>Admin-Managed Conversations</h4>
                <p class="text-muted small mb-0">Groups and calls created by the admin to connect verified buyers and sellers.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;">Open Inquiry</a>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#f8fafc; color:#334155; border:1px solid #dbe2ec;">Properties</a>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('admin.conversations.index') }}" class="module-tab {{ ! request('status') || request('status') === 'active' ? 'active' : '' }}">Active Chats</a>
            <a href="{{ route('admin.conversations.index', ['status' => 'closed']) }}" class="module-tab {{ request('status') === 'closed' ? 'active' : '' }}">Closed Inquiries</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Conversation</th>
                            <th>Property</th>
                            <th>Participants</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations as $convo)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold small" style="color:#0B1F3A;">{{ $convo->inquiry->inquiry_number }} — {{ $convo->inquiry->typeLabel() }}</div>
                                    <div class="small text-muted">{{ $convo->channelLabel() }} @if($convo->external_link) · <a href="{{ $convo->external_link }}" target="_blank" class="text-primary text-decoration-none">link</a> @endif</div>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ Str::limit($convo->property->title, 28) }}</div>
                                    <div class="small text-muted">{{ $convo->property->ref() }}</div>
                                </td>
                                <td>
                                    <div class="small text-muted" style="max-width:220px;">
                                        {{ $convo->participants ? implode(' · ', $convo->participants) : '—' }}
                                    </div>
                                </td>
                                <td class="small text-muted">{{ $convo->created_at->format('M d, H:i') }}</td>
                                <td><span class="status-badge" style="background:{{ $convo->status === 'active' ? '#f0fdf4' : '#f1f5f9' }}; color:{{ $convo->status === 'active' ? '#16a34a' : '#64748b' }};">{{ ucfirst($convo->status) }}</span></td>
                                <td class="px-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.inquiries.show', $convo->inquiry) }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">View Inquiry</a>
                                        @if($convo->status === 'active')
                                            <form action="{{ route('admin.conversations.close', $convo) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger fw-bold rounded-3">Close</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-dots fs-1 d-block mb-2 opacity-50"></i>
                                    No conversations. Connect parties from any inquiry to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($conversations->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $conversations->links() }}</div>
        @endif
    </div>
</section>
@endsection
