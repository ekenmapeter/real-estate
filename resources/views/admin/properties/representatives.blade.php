@extends('layouts.main')

@section('title', 'Property Representatives | Admin | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4" style="max-width:1000px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-person-badge me-2" style="color:#2563eb;"></i>Property Representatives</h4>
                <p class="text-muted small mb-0">Owners, agents, developers and property managers who submit listings.</p>
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
                            <th class="px-4 py-3">Representative</th>
                            <th>Type</th>
                            <th>Listings</th>
                            <th>KYC</th>
                            <th>Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $statusColor = match ($user->rep_status) {
                                    'verified' => ['#f0fdf4', '#16a34a'],
                                    'pending' => ['#fffbeb', '#d97706'],
                                    'rejected' => ['#fef2f2', '#dc2626'],
                                    default => ['#f8fafc', '#64748b'],
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:42px; height:42px; background:#2563eb;">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="fw-bold small" style="color:#0B1F3A;">{{ $user->name }}</div>
                                            <div class="small text-muted">{{ $user->email }}<br><span style="font-size:.72rem;">Member since {{ $user->created_at->format('M Y') }}</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge small" style="background:#eff6ff; color:#2563eb;">{{ $user->repLabel() }}</span></td>
                                <td><span class="badge small" style="background:#f8fafc; color:#334155; border:1px solid #dbe2ec;">{{ $user->propertyListings()->count() }}</span></td>
                                <td>
                                    <span class="badge small {{ $user->kyc_status === 'approved' ? 'text-bg-success' : 'text-bg-warning' }}">{{ $user->kyc_status ?? 'Not submitted' }}</span>
                                </td>
                                <td><span class="status-badge" style="background:{{ $statusColor[0] }}; color:{{ $statusColor[1] }};">{{ ucfirst($user->rep_status) }}</span></td>
                                <td class="px-4">
                                    @if($user->rep_status !== 'verified')
                                        <form action="{{ route('admin.representatives.verify', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm fw-bold text-white rounded-3" style="background:#16a34a;"><i class="bi bi-patch-check me-1"></i> Verify</button>
                                        </form>
                                    @endif
                                    @if($user->rep_status === 'pending' || $user->rep_status === 'verified')
                                        <form action="{{ route('admin.representatives.reject', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this representative verification?');">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger fw-bold rounded-3">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                                    No representatives yet. Users who select Owner / Agent / Developer / Property Manager when listing appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $users->links() }}</div>
        @endif
    </div>
</section>
@endsection
