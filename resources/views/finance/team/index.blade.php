@extends('layouts.main')

@section('title', 'Finance Team Requests | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
.nav-pill-tab {
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.86rem;
        color: #64748b;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #f1f5f9;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .nav-pill-tab:hover, .nav-pill-tab.active {
        background-color: #2563eb;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .nav-pill-tab .badge {
        font-size: 0.72rem;
    }
</style>

<div class="container-fluid px-0 user-shell-content" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-xl py-4 px-3 px-md-4">

        <!-- Header Row -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('finance.overview') }}" class="text-decoration-none">Finance Center</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Finance Team Requests</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Finance Team Requests Desk</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Assisted local currency deposits & withdrawals handled directly with official platform finance operators.</p>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('finance.team.create') }}" class="btn btn-primary fw-bold rounded-3 px-4 py-2 shadow-sm" style="background: #2563eb; border:none;">
                    <i class="bi bi-plus-circle-fill me-1.5"></i> Create Finance Request
                </a>
            </div>
        </div>

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Tabs Bar (Matching Image 1 & Image 2) -->
        <div class="d-flex align-items-center gap-2 overflow-x-auto pb-2 mb-4">
            <a href="{{ route('finance.team.index', ['tab' => 'all']) }}" class="nav-pill-tab {{ $tab === 'all' ? 'active' : '' }}">
                All Requests <span class="badge rounded-pill bg-white text-dark bg-opacity-25">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('finance.team.index', ['tab' => 'open']) }}" class="nav-pill-tab {{ $tab === 'open' ? 'active' : '' }}">
                Open Requests <span class="badge rounded-pill bg-white text-dark bg-opacity-25">{{ $counts['open'] }}</span>
            </a>
            <a href="{{ route('finance.team.index', ['tab' => 'pending']) }}" class="nav-pill-tab {{ $tab === 'pending' ? 'active' : '' }}">
                Under Review <span class="badge rounded-pill bg-white text-dark bg-opacity-25">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('finance.team.index', ['tab' => 'action_required']) }}" class="nav-pill-tab {{ $tab === 'action_required' ? 'active' : '' }}">
                Awaiting Action <span class="badge rounded-pill bg-warning text-dark">{{ $counts['action_required'] }}</span>
            </a>
            <a href="{{ route('finance.team.index', ['tab' => 'completed']) }}" class="nav-pill-tab {{ $tab === 'completed' ? 'active' : '' }}">
                Completed <span class="badge rounded-pill bg-white text-dark bg-opacity-25">{{ $counts['completed'] }}</span>
            </a>
            <a href="{{ route('finance.team.index', ['tab' => 'cancelled']) }}" class="nav-pill-tab {{ $tab === 'cancelled' ? 'active' : '' }}">
                Cancelled <span class="badge rounded-pill bg-white text-dark bg-opacity-25">{{ $counts['cancelled'] }}</span>
            </a>
        </div>

        <!-- Requests List Card -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
            @if($requests->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-headset display-4 text-muted d-block mb-3"></i>
                    <h5 class="fw-bold text-dark mb-1">No finance requests found</h5>
                    <p class="text-muted small mb-3">Need assistance depositing or withdrawing funds in local currency?</p>
                    <a href="{{ route('finance.team.create') }}" class="btn btn-primary fw-bold rounded-3 px-4 py-2" style="background:#2563eb;">
                        Submit Finance Request
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Request ID</th>
                                <th>Type & Payment Method</th>
                                <th>Country & Currency</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $fr)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark font-mono">{{ $fr->request_id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($fr->type === 'deposit')
                                                <div class="rounded-circle bg-success bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                    <i class="bi bi-arrow-down-left text-success fw-bold"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">Deposit Request</span>
                                                    <small class="text-muted">{{ $fr->payment_method }}</small>
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-danger bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                    <i class="bi bi-arrow-up-right text-danger fw-bold"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">Withdrawal Request</span>
                                                    <small class="text-muted">{{ $fr->payment_method }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $fr->country }}</span>
                                        <small class="text-muted">{{ $fr->currency }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-6 text-dark">
                                            {{ number_format($fr->amount, 2) }}
                                        </span>
                                        <small class="text-muted d-block">{{ $fr->currency }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $fr->statusBadgeClass() }} px-3 py-1.5 rounded-2">
                                            {{ $fr->formattedStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $fr->created_at->format('M d, Y') }}<br>
                                        <small>{{ $fr->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('finance.team.show', $fr->request_id) }}" class="btn btn-sm btn-light border rounded-2 text-primary fw-bold">
                                            View Details →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <small class="text-muted">Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} requests</small>
                    <div>
                        {{ $requests->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
