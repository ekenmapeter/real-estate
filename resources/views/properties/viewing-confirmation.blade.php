@extends('layouts.main')

@section('title', 'Viewing Request Submitted | ' . site_name())

@section('content')
<style>
    .success-ring { width: 96px; height: 96px; border-radius: 50%; background: #f0fdf4; border: 3px solid #bbf7d0; display: flex; align-items: center; justify-content: center; }
    .check-step { display: flex; gap: .75rem; padding: .6rem 0; }
    .check-step .bi-check-circle-fill { color: #16a34a; }
</style>

<section class="py-5" style="background:#f8fafc; min-height:80vh;">
    <div class="container" style="max-width:760px;">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="success-ring mx-auto mb-3">
                    <i class="bi bi-check-lg text-success" style="font-size:2.6rem;"></i>
                </div>
                <h3 class="fw-bold mb-2" style="color:#0B1F3A;">Viewing Request Submitted!</h3>
                <p class="text-muted mb-4">
                    Your request has been received by Aurevia Property Support. Our team will verify both parties and contact you shortly.
                </p>

                <div class="text-start bg-light rounded-3 p-3 d-flex align-items-center gap-3 mb-4" style="border:1px solid #eef2f7;">
                    @php $img = $inquiry->property->image_url ?? asset('images/property-placeholder.jpg'); @endphp
                    <img src="{{ $img }}" class="rounded-3" style="width:86px; height:70px; object-fit:cover;" alt="{{ $inquiry->property->title }}">
                    <div>
                        <div class="fw-bold" style="color:#0B1F3A;">{{ $inquiry->property->title }}</div>
                        <div class="small text-muted">{{ $inquiry->property->fullLocation() }}</div>
                        <div class="small text-muted mt-1">
                            <span class="badge bg-dark bg-opacity-10 text-dark me-2">{{ $inquiry->property->ref() }}</span>
                            <span class="badge" style="background:#eff6ff; color:#2563eb;">Request ID: {{ $inquiry->inquiry_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-start bg-white rounded-3 p-4 mb-4" style="border:1px dashed #dbe2ec;">
                    <div class="fw-bold small mb-2 text-uppercase" style="letter-spacing:.05em; color:#475569;">Our Property Support team will:</div>
                    <div class="check-step"><i class="bi bi-check-circle-fill"></i><span class="small">Verify your account and identity</span></div>
                    <div class="check-step"><i class="bi bi-check-circle-fill"></i><span class="small">Verify the listing representative and ownership authorization</span></div>
                    <div class="check-step"><i class="bi bi-check-circle-fill"></i><span class="small">Confirm the property is available on {{ $inquiry->preferred_date ? $inquiry->preferred_date->format('M d, Y') : 'your preferred date' }}@if($inquiry->preferred_time) at {{ $inquiry->preferred_time }}@endif</span></div>
                    <div class="check-step"><i class="bi bi-check-circle-fill"></i><span class="small">Arrange the {{ $inquiry->viewing_type ? ucfirst($inquiry->viewing_type) : '' }} viewing with both parties</span></div>
                    <div class="check-step"><i class="bi bi-check-circle-fill"></i><span class="small">Contact you shortly on {{ $inquiry->preferred_channel === 'whatsapp' ? 'WhatsApp' : 'Telegram' }}</span></div>
                </div>

                <div class="d-grid d-sm-flex gap-2 justify-content-sm-center">
                    @php
                        $msg = 'Hello Aurevia Property Support, I submitted a viewing request (' . $inquiry->inquiry_number . ') for ' . $inquiry->property->title . ' (' . $inquiry->property->ref() . ').';
                    @endphp
                    <a href="{{ whatsapp_url($msg) }}" target="_blank" class="btn fw-bold py-2 px-4 rounded-3" style="background:#25D366; color:#fff;">
                        <i class="bi bi-whatsapp me-2"></i> Chat on WhatsApp
                    </a>
                    <a href="{{ telegram_url($msg) }}" target="_blank" class="btn fw-bold py-2 px-4 rounded-3" style="background:#229ED9; color:#fff;">
                        <i class="bi bi-telegram me-2"></i> Open Telegram
                    </a>
                </div>
                <div class="mt-3">
                    <a href="{{ url('/properties') }}" class="small fw-bold text-decoration-none" style="color:#2563eb;"><i class="bi bi-arrow-left me-1"></i> Back to Properties</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
