@extends('layouts.main')

@section('title', 'Inquiry ' . $inquiry->inquiry_number . ' | Admin | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4" style="max-width:1000px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.inquiries.index', ['type' => $inquiry->type]) }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;"><i class="bi bi-arrow-left me-1"></i> Back</a>
                <div>
                    <h4 class="fw-bold mb-0" style="color:#0B1F3A;">{{ $inquiry->typeLabel() }} — {{ $inquiry->inquiry_number }}</h4>
                    <small class="text-muted">{{ $inquiry->property->title }} · {{ $inquiry->property->ref() }}</small>
                </div>
            </div>
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
            <span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">{{ $inquiry->statusLabel() }}</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <!-- Requester -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-person me-2" style="color:#2563eb;"></i>Requester</h6>
                        <div class="row g-2 small">
                            <div class="col-md-6"><span class="text-muted">Full name:</span> <b>{{ $inquiry->full_name }}</b></div>
                            <div class="col-md-6"><span class="text-muted">Email:</span> <b>{{ $inquiry->email }}</b></div>
                            <div class="col-md-6"><span class="text-muted">Phone:</span> <b>{{ $inquiry->phone ?? '—' }}</b></div>
                            <div class="col-md-6"><span class="text-muted">Preferred channel:</span> <b class="text-capitalize">{{ $inquiry->preferred_channel }}</b></div>
                            @if($inquiry->user)
                                <div class="col-12">
                                    <span class="text-muted">Account:</span>
                                    <a href="{{ route('dashboard') }}" class="fw-bold">{{ $inquiry->user->name }}</a>
                                    <span class="badge ms-1 {{ $inquiry->user->kyc_status === 'approved' ? 'text-bg-success' : 'text-bg-warning' }}">KYC: {{ $inquiry->user->kyc_status ?? 'not submitted' }}</span>
                                </div>
                            @else
                                <div class="col-12"><span class="text-muted">Account:</span> <b>Guest (no account)</b></div>
                            @endif
                        </div>
                        @if($inquiry->preferred_date || $inquiry->preferred_time || $inquiry->viewing_type)
                            <hr>
                            <h6 class="fw-bold mb-2" style="color:#0B1F3A;">Viewing Preferences</h6>
                            <div class="row g-2 small">
                                @if($inquiry->preferred_date)
                                    <div class="col-md-6"><span class="text-muted">Date:</span> <b>{{ $inquiry->preferred_date->format('M d, Y') }}</b></div>
                                @endif
                                @if($inquiry->preferred_time)
                                    <div class="col-md-6"><span class="text-muted">Time:</span> <b>{{ $inquiry->preferred_time }}</b></div>
                                @endif
                                <div class="col-md-6"><span class="text-muted">Type:</span> <b class="text-capitalize">{{ $inquiry->viewing_type ?? '—' }}</b></div>
                                <div class="col-md-6"><span class="text-muted">Attendees:</span> <b>{{ $inquiry->attendees }}</b></div>
                            </div>
                        @endif
                        @if($inquiry->message)
                            <hr>
                            <div class="fw-bold small mb-1" style="color:#0B1F3A;">Message</div>
                            <p class="small mb-0" style="color:#475569; background:#f8fafc; border:1px solid #eef2f7; border-radius:10px; padding:.8rem 1rem;">{{ $inquiry->message }}</p>
                        @endif
                        @if($inquiry->admin_note)
                            <hr>
                            <div class="alert small mb-0 py-2" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e;"><b>Admin note:</b> {{ $inquiry->admin_note }}</div>
                        @endif
                    </div>
                </div>

                <!-- Conversations -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="fw-bold" style="color:#0B1F3A;">Connected Conversations ({{ $inquiry->conversations->count() }})</div>
                    </div>
                    <div class="card-body p-4 pt-2">
                        @forelse($inquiry->conversations as $convo)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2 small">
                                <div>
                                    <b>{{ $convo->channelLabel() }}</b>
                                    @if($convo->external_link)
                                        <a href="{{ $convo->external_link }}" target="_blank" class="ms-2 small text-primary text-decoration-none"><i class="bi bi-box-arrow-up-right"></i> Open link</a>
                                    @endif
                                    @if($convo->participants)
                                        <div class="text-muted mt-1">{{ implode(' · ', $convo->participants) }}</div>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-badge" style="background:{{ $convo->status === 'active' ? '#f0fdf4' : '#f1f5f9' }}; color:{{ $convo->status === 'active' ? '#16a34a' : '#64748b' }};">{{ ucfirst($convo->status) }}</span>
                                    @if($convo->status === 'active')
                                        <form action="{{ route('admin.conversations.close', $convo) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-secondary fw-bold rounded-3">Close</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="small text-muted mb-0">No conversations yet. Use the connect panel on the right.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Activity log -->
                @if($inquiry->logs)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <div class="fw-bold" style="color:#0B1F3A;">Activity Log</div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="small">
                                @foreach(array_reverse($inquiry->logs) as $log)
                                    <div class="d-flex gap-2 py-1 border-bottom" style="font-size:.78rem;">
                                        <span class="text-muted text-nowrap">{{ \Illuminate\Support\Carbon::parse($log['at'])->format('M d, H:i') }}</span>
                                        <span><b>{{ $log['actor'] }}</b> — {{ $log['action'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                <!-- Status update -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-arrow-left-right me-2" style="color:#2563eb;"></i>Update Status</h6>
                        <form action="{{ route('admin.inquiries.status', $inquiry) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select mb-2">
                                @foreach([
                                    'awaiting_admin_review' => 'Awaiting Admin Review',
                                    'representative_verification' => 'Representative Verification',
                                    'viewing_scheduled' => 'Viewing Scheduled',
                                    'purchase_discussion' => 'Purchase Discussion',
                                    'rental_review' => 'Rental Review',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ] as $k => $v)
                                    <option value="{{ $k }}" @selected($inquiry->status === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="admin_note" class="form-control mb-2" placeholder="Admin note (optional)" value="{{ $inquiry->admin_note }}">
                            <button class="btn fw-bold w-100 text-white rounded-3" style="background:#0B1F3A;">Update Status</button>
                        </form>
                    </div>
                </div>

                <!-- Connect parties -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-people me-2" style="color:#16a34a;"></i>Connect Both Parties</h6>
                        <p class="small text-muted mb-3">After verifying the requester and the representative, create the connection channel.</p>
                        <form action="{{ route('admin.inquiries.connect', $inquiry) }}" method="POST">
                            @csrf
                            <select name="channel" class="form-select mb-2" required>
                                <option value="">Select channel...</option>
                                <option value="whatsapp_group">WhatsApp Group</option>
                                <option value="telegram_group">Telegram Group</option>
                                <option value="call">Scheduled Phone Call</option>
                                <option value="meeting">Video Meeting</option>
                            </select>
                            <input type="url" name="external_link" class="form-control mb-2" placeholder="Group invite / meeting link (optional)">
                            <input type="text" name="participants[]" class="form-control mb-1" placeholder="Participant 1 — e.g. Buyer" value="{{ $inquiry->full_name }} (Requester)">
                            <input type="text" name="participants[]" class="form-control mb-1" placeholder="Participant 2 — e.g. Agent" value="{{ $inquiry->property->user ? $inquiry->property->user->name . ' (' . $inquiry->property->representativeLabel() . ')' : 'Aurevia Admin' }}">
                            <input type="text" name="participants[]" class="form-control mb-2" placeholder="Participant 3 — e.g. Admin" value="Aurevia Property Support">
                            <button class="btn fw-bold w-100 rounded-3" style="background:#16a34a; color:#fff;"><i class="bi bi-link-45deg me-2"></i> Create Connection</button>
                        </form>
                    </div>
                </div>

                <!-- Verification checklist -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;"><i class="bi bi-shield-check me-2" style="color:#2563eb;"></i>Verification Checklist</h6>
                        <div class="small" style="line-height:2;">
                            <div><i class="bi bi-check-circle text-success me-2"></i>Requester account & identity</div>
                            <div><i class="bi bi-check-circle text-success me-2"></i>Requester purpose of inquiry</div>
                            <div><i class="bi bi-check-circle text-success me-2"></i>Representative identity</div>
                            <div><i class="bi bi-check-circle text-success me-2"></i>Ownership authorization</div>
                            <div><i class="bi bi-check-circle text-success me-2"></i>Property availability</div>
                            <div><i class="bi bi-check-circle text-success me-2"></i>Agent / company verification</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
