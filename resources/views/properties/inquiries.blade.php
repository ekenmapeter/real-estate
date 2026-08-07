@extends('layouts.main')

@section('title', 'Property Inquiries | ' . site_name())

@section('content')
@include('partials.user-shell-sidebar')
<style>
    .status-badge { font-size: .7rem; font-weight: 700; padding: .35rem .8rem; border-radius: 50rem; }
</style>
<section class="py-4" style="background:#f8fafc; min-height:80vh;">
    <div class="container user-shell-content" style="max-width:1100px;">
        <h4 class="fw-bold mb-1" style="color:#0B1F3A;">Property Inquiries</h4>
        <p class="text-muted small mb-3">Track all your purchase interests, rental applications and inquiries.</p>


        @if(session('success'))
            <div class="alert alert-success small fw-bold">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Property</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Submitted</th>
                            <th class="px-4">Status</th>
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
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $inquiry->property->image_url ?? asset('images/property-placeholder.jpg') }}" style="width:56px; height:44px; object-fit:cover; border-radius:8px;" alt="">
                                        <div>
                                            <div class="fw-bold small" style="color:#0B1F3A;">{{ $inquiry->property->title }}</div>
                                            <div class="small text-muted">{{ $inquiry->property->ref() }} · {{ $inquiry->property->fullLocation() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small fw-bold">{{ $inquiry->typeLabel() }}</span></td>
                                <td><span class="small text-muted">{{ $inquiry->inquiry_number }}</span></td>
                                <td><span class="small text-muted">{{ $inquiry->created_at->format('M d, Y') }}</span></td>
                                <td class="px-4">
                                    <span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">{{ $inquiry->statusLabel() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-text fs-1 d-block mb-2 opacity-50"></i>
                                    No inquiries yet. Browse properties and express interest to get started.
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
