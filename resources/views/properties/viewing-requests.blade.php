@extends('layouts.main')

@section('title', 'Viewing Requests | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
    .status-badge { font-size: .7rem; font-weight: 700; padding: .35rem .8rem; border-radius: 50rem; }
</style>
<section class="py-4" style="background:#f8fafc; min-height:80vh;">
    <div class="container user-shell-content" style="max-width:1100px;">
        <h4 class="fw-bold mb-1" style="color:#0B1F3A;">Viewing Requests</h4>
        <p class="text-muted small mb-3">All property viewing requests submitted through Aurevia Property Support.</p>


        @if(session('success'))
            <div class="alert alert-success small fw-bold">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Property</th>
                            <th>Preferred Date & Time</th>
                            <th>Type</th>
                            <th>Request ID</th>
                            <th class="px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inquiry)
                            @php
                                $badge = match ($inquiry->status) {
                                    'awaiting_admin_review' => ['#fffbeb', '#d97706'],
                                    'viewing_scheduled' => ['#eff6ff', '#2563eb'],
                                    'completed' => ['#f0fdf4', '#16a34a'],
                                    'cancelled' => ['#fef2f2', '#dc2626'],
                                    default => ['#f8fafc', '#64748b'],
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $inquiry->property->image_url ?? asset('images/property-placeholder.jpg') }}" style="width:56px; height:44px; object-fit:cover; border-radius:8px;" alt="">
                                        <div>
                                            <div class="fw-bold small" style="color:#0B1F3A;">{{ $inquiry->property->title }}</div>
                                            <div class="small text-muted">{{ $inquiry->property->ref() }} · {{ $inquiry->property->fullLocation() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small">
                                    <div class="fw-bold">{{ $inquiry->preferred_date?->format('M d, Y') ?? 'TBD' }}</div>
                                    <div class="text-muted">{{ $inquiry->preferred_time ?? '' }}</div>
                                </td>
                                <td><span class="badge small text-capitalize" style="background:{{ $inquiry->viewing_type === 'virtual' ? '#fdf4ff' : '#eff6ff' }}; color:{{ $inquiry->viewing_type === 'virtual' ? '#c026d3' : '#2563eb' }};">{{ $inquiry->viewing_type ?? 'Pending' }}</span></td>
                                <td><span class="small text-muted">{{ $inquiry->inquiry_number }}</span></td>
                                <td class="px-4">
                                    <span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">
                                        {{ $inquiry->status === 'awaiting_admin_review' ? 'Pending Confirmation' : $inquiry->statusLabel() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
                                    No viewing requests yet. Request a viewing from any property page.
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
