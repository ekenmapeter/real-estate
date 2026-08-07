@extends('layouts.main')

@section('title', 'Properties Management | Admin | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .sidebar-admin { background: #0B1F3A; min-height: calc(100vh - 70px); }
    .sidebar-admin .nav-link-admin { display: flex; align-items: center; gap: .65rem; padding: .62rem .9rem; border-radius: .6rem; color: #cbd5e1; font-weight: 600; font-size: .88rem; margin-bottom: .2rem; }
    .sidebar-admin .nav-link-admin:hover { color: #fff; background: rgba(255,255,255,.06); }
    .sidebar-admin .nav-link-admin.active { color: #fff; background: linear-gradient(135deg, #2563eb, #7c3aed); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
    .module-tab { padding: .45rem 1.1rem; border-radius: 50rem; font-weight: 700; font-size: .82rem; color: #475569; background: #fff; border: 1.5px solid #dbe2ec; text-decoration: none; }
    .module-tab.active { background: #0B1F3A; color: #fff; border-color: #0B1F3A; }
    .module-tab:hover:not(.active) { border-color: #2563eb; color: #2563eb; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-house-check me-2" style="color:#2563eb;"></i>Properties Management</h4>
                <p class="text-muted small mb-0">Review, approve and manage all marketplace listings.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;">Inquiries</a>
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">Conversations</a>
                <a href="{{ route('admin.properties.categories') }}" class="btn btn-sm fw-bold rounded-3" style="background:#fdf4ff; color:#c026d3; border:1px solid #f5d0fe;">Categories</a>
                <a href="{{ route('admin.representatives.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a;">Representatives</a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm fw-bold rounded-3" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">Reports</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="d-flex flex-wrap gap-2 mb-3">
            @php
                $tabs = ['pending' => 'Pending Listings', 'approved' => 'Approved Listings', 'rejected' => 'Rejected Listings', 'suspended' => 'Suspended Listings', 'all' => 'All Properties'];
            @endphp
            @foreach($tabs as $key => $label)
                <a href="{{ route('admin.properties.index', ['tab' => $key]) }}" class="module-tab {{ $tab === $key ? 'active' : '' }}">
                    {{ $label }}
                    @if(in_array($key, ['pending', 'approved', 'rejected', 'suspended']))
                        <span class="badge bg-secondary bg-opacity-25 rounded-pill ms-1" style="{{ $tab === $key ? 'background:rgba(255,255,255,.2) !important;' : '' }}">{{ $counts[$key] }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="fw-bold" style="color:#0B1F3A;">{{ $tab === 'pending' ? 'Pending Property Listings' : ucfirst($tab) . ' Listings' }}</div>
                <form class="d-flex gap-2" method="GET">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="text" name="search" class="form-control form-control-sm" style="width:260px;" value="{{ request('search') }}" placeholder="Search title, reference, location...">
                    <button class="btn btn-sm fw-bold text-white rounded-3" style="background:#0B1F3A;"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Listing</th>
                            <th>Owner / Agent</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties as $property)
                            @php
                                $badge = match ($property->status) {
                                    'published', 'approved' => ['#f0fdf4', '#16a34a'],
                                    'submitted', 'under_review' => ['#eff6ff', '#2563eb'],
                                    'more_info_required' => ['#fffbeb', '#d97706'],
                                    'rejected' => ['#fef2f2', '#dc2626'],
                                    'suspended' => ['#fff7ed', '#ea580c'],
                                    default => ['#f8fafc', '#64748b'],
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $property->image_url ?? asset('images/property-placeholder.jpg') }}" style="width:64px; height:48px; object-fit:cover; border-radius:8px;" alt="">
                                        <div>
                                            <div class="fw-bold small" style="color:#0B1F3A;">{{ $property->title }}</div>
                                            <div class="small text-muted">{{ $property->ref() }} · {{ $property->fullLocation() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $property->user ? masked_name($property->user->name) : 'Aurevia' }}</div>
                                    <span class="badge small fw-semibold" style="background:#eff6ff; color:#2563eb;">{{ $property->representativeLabel() }}</span>
                                    @if($property->representative_verified)
                                        <i class="bi bi-patch-check-fill text-success ms-1" title="Representative verified"></i>
                                    @endif
                                </td>
                                <td><span class="small fw-bold {{ $property->isForSale() ? 'text-danger' : 'text-primary' }}">{{ $property->isForSale() ? 'Sale' : 'Rent' }}</span></td>
                                <td class="small fw-bold" style="color:#2563eb;">{{ $property->isForRent() ? format_usd($property->monthly_rent) . '/mo' : format_usd($property->purchasePrice()) }}</td>
                                <td class="small text-muted">{{ $property->listed_at?->format('M d, Y') ?? $property->created_at->format('M d, Y') }}</td>
                                <td><span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">{{ $property->statusLabel() }}</span></td>
                                <td class="px-4">
                                    <a href="{{ route('admin.properties.review', $property) }}" class="btn btn-sm fw-bold text-white rounded-3" style="background:#0B1F3A;">
                                        <i class="bi bi-eye me-1"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    No listings in this view.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($properties->hasPages())
            <div class="mt-4 d-flex justify-content-center">{{ $properties->links() }}</div>
        @endif
    </div>
</section>
@endsection
