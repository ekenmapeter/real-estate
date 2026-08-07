@extends('layouts.main')

@section('title', 'Review Listing ' . $property->ref() . ' | Admin | ' . site_name())

@section('content')
<style>
    .admin-shell { background: #f8fafc; min-height: calc(100vh - 70px); }
    .status-badge { font-size: .68rem; font-weight: 700; padding: .3rem .75rem; border-radius: 50rem; }
    .spec-mini { border: 1px solid #eef2f7; border-radius: 10px; padding: .6rem .4rem; text-align: center; background: #fbfdff; font-size: .72rem; }
</style>

<section class="admin-shell py-4">
    <div class="container-fluid px-4" style="max-width:1100px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.properties.index', ['tab' => 'pending']) }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;"><i class="bi bi-arrow-left me-1"></i> Back</a>
                <div>
                    <h4 class="fw-bold mb-0" style="color:#0B1F3A;">{{ $property->title }}</h4>
                    <small class="text-muted">{{ $property->ref() }} · {{ $property->fullLocation() }}</small>
                </div>
            </div>
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
            <span class="status-badge" style="background:{{ $badge[0] }}; color:{{ $badge[1] }};">{{ $property->statusLabel() }}</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <!-- Decision bar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-3">
                        <div class="fw-bold small mb-1" style="color:#0B1F3A;">Admin Decision</div>
                        <div class="small text-muted" style="font-size:.75rem;">Approve publishes the listing publicly. Reject or request more info notifies the owner.</div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('admin.properties.approve', $property) }}" method="POST">
                            @csrf
                            <button class="btn fw-bold w-100 py-2 rounded-3 text-white" style="background:#16a34a;" {{ in_array($property->status, ['published', 'approved']) ? 'disabled' : '' }}>
                                <i class="bi bi-check-circle me-2"></i> Approve & Publish
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.properties.request-info', $property) }}" method="POST" class="flex-fill">
                                @csrf
                                <input type="text" name="reason" class="form-control form-control-sm mb-1" placeholder="What information is needed?" required>
                                <button class="btn btn-sm fw-bold w-100 rounded-3" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a;">
                                    <i class="bi bi-question-circle me-1"></i> Request More Info
                                </button>
                            </form>
                            <form action="{{ route('admin.properties.reject', $property) }}" method="POST" class="flex-fill">
                                @csrf
                                <input type="text" name="reason" class="form-control form-control-sm mb-1" placeholder="Rejection reason" required>
                                <button class="btn btn-sm fw-bold w-100 rounded-3" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
                                    <i class="bi bi-x-circle me-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left: details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="fw-bold" style="color:#0B1F3A;">Listing Details</div>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="row g-2 mb-3">
                            <div class="col-md-3"><div class="spec-mini"><i class="bi bi-door-closed d-block mb-1 text-primary"></i><b>{{ $property->bedrooms ?? '—' }}</b> Beds</div></div>
                            <div class="col-md-3"><div class="spec-mini"><i class="bi bi-droplet-half d-block mb-1 text-primary"></i><b>{{ $property->bathrooms ?? '—' }}</b> Baths</div></div>
                            <div class="col-md-3"><div class="spec-mini"><i class="bi bi-rulers d-block mb-1 text-primary"></i><b>{{ $property->property_size ? number_format($property->property_size) : '—' }}</b> sqm</div></div>
                            <div class="col-md-3"><div class="spec-mini"><i class="bi bi-bounding-box d-block mb-1 text-primary"></i><b>{{ $property->land_size ? number_format($property->land_size) : '—' }}</b> land</div></div>
                        </div>
                        <div class="small mb-3" style="color:#475569; line-height:1.8;">{{ $property->description }}</div>
                        <div class="row g-2 small">
                            <div class="col-md-4"><span class="text-muted">Country:</span> <b>{{ $property->country ?? '—' }}</b></div>
                            <div class="col-md-4"><span class="text-muted">City:</span> <b>{{ $property->city ?? '—' }}</b></div>
                            <div class="col-md-4"><span class="text-muted">Address:</span> <b>{{ $property->address ?? '—' }}</b></div>
                            <div class="col-md-4"><span class="text-muted">Category:</span> <b>{{ $property->category }}</b></div>
                            <div class="col-md-4"><span class="text-muted">Ownership:</span> <b class="text-capitalize">{{ $property->ownership_type ?? '—' }}</b></div>
                            <div class="col-md-4"><span class="text-muted">Parking:</span> <b>{{ $property->parking ?? '—' }}</b></div>
                        </div>
                        @if(count($property->amenities()))
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($property->amenities() as $a)
                                    <span class="badge" style="background:#f8fafc; color:#334155; border:1px solid #dbe2ec;">{{ $a }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($property->admin_note)
                            <hr>
                            <div class="alert small mb-0 py-2" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e;">
                                <b>Admin note:</b> {{ $property->admin_note }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Media -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="fw-bold" style="color:#0B1F3A;">Media ({{ $property->images->count() + ($property->image_url ? 1 : 0) }} photos)</div>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($property->galleryUrls() as $url)
                                <a href="{{ $url }}" target="_blank">
                                    <img src="{{ $url }}" style="width:110px; height:80px; object-fit:cover; border-radius:8px;" alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="fw-bold" style="color:#0B1F3A;">Documents ({{ $property->documents->count() }})</div>
                    </div>
                    <div class="card-body p-4 pt-2">
                        @forelse($property->documents as $doc)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2 small">
                                <div>
                                    <b>{{ $doc->title ?: 'Document' }}</b>
                                    <span class="badge ms-2" style="background:#f8fafc; color:#64748b; border:1px solid #e2e8f0;">{{ $doc->document_type }}</span>
                                </div>
                                <a href="{{ route('admin.properties.document.download', [$property, $doc]) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-3"><i class="bi bi-download me-1"></i> Download</a>
                            </div>
                        @empty
                            <p class="small text-muted mb-0">No documents uploaded.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Logs -->
                @if($property->logs)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <div class="fw-bold" style="color:#0B1F3A;">Activity Log</div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="small">
                                @foreach(array_reverse($property->logs) as $log)
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

            <!-- Right: owner + actions -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;">Owner / Representative</h6>
                        @if($property->user)
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:48px; height:48px; background:#2563eb;">
                                    {{ strtoupper(mb_substr($property->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small" style="color:#0B1F3A;">{{ $property->user->name }}</div>
                                    <div class="small text-muted">{{ $property->user->email }}</div>
                                    <span class="badge" style="background:#eff6ff; color:#2563eb;">{{ $property->representativeLabel() }}</span>
                                </div>
                            </div>
                            <div class="small mb-3">
                                <span class="text-muted">Member since:</span> {{ $property->user->created_at->format('M Y') }}<br>
                                <span class="text-muted">KYC status:</span>
                                <span class="badge {{ $property->user->kyc_status === 'approved' ? 'text-bg-success' : 'text-bg-warning' }}">{{ $property->user->kyc_status ?? 'Not submitted' }}</span><br>
                                <span class="text-muted">Representative status:</span>
                                <span class="badge" style="background:{{ $property->user->rep_status === 'verified' ? '#f0fdf4' : '#fffbeb' }}; color:{{ $property->user->rep_status === 'verified' ? '#16a34a' : '#d97706' }};">{{ ucfirst($property->user->rep_status) }}</span>
                            </div>
                        @else
                            <p class="small text-muted">Listed directly by Aurevia (admin-created listing).</p>
                        @endif
                        <hr>
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.properties.feature', $property) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm fw-bold w-100 rounded-3" style="background:#fdf4ff; color:#c026d3; border:1px solid #f5d0fe;">
                                    <i class="bi bi-star me-1"></i> {{ $property->is_featured ? 'Unfeature' : 'Feature' }} Listing
                                </button>
                            </form>
                            @if(in_array($property->status, ['published', 'approved']))
                                <form action="{{ route('admin.properties.suspend', $property) }}" method="POST" onsubmit="return confirm('Suspend this listing? It will disappear from public search.');">
                                    @csrf
                                    <button class="btn btn-sm fw-bold w-100 rounded-3" style="background:#fff7ed; color:#ea580c; border:1px solid #fed7aa;">
                                        <i class="bi bi-pause-circle me-1"></i> Suspend Listing
                                    </button>
                                </form>
                            @elseif($property->status === 'suspended')
                                <form action="{{ route('admin.properties.restore', $property) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm fw-bold w-100 rounded-3" style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">
                                        <i class="bi bi-play-circle me-1"></i> Restore to Published
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Permanently remove this listing? This cannot be undone.');">
                                @csrf
                                <button class="btn btn-sm fw-bold w-100 rounded-3" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
                                    <i class="bi bi-trash me-1"></i> Remove Listing
                                </button>
                            </form>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3" style="color:#0B1F3A;">Edit Listing</h6>
                        <form action="{{ route('admin.properties.update', $property) }}" method="POST">
                            @csrf
                            <div class="row g-2 small">
                                <div class="col-12">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Title</label>
                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $property->title }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Category</label>
                                    <input type="text" name="category" class="form-control form-control-sm" value="{{ $property->category }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Price ($)</label>
                                    <input type="number" name="price" class="form-control form-control-sm" step="0.01" value="{{ $property->price }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Monthly Rent ($)</label>
                                    <input type="number" name="monthly_rent" class="form-control form-control-sm" step="0.01" value="{{ $property->monthly_rent }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Country</label>
                                    <input type="text" name="country" class="form-control form-control-sm" value="{{ $property->country }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">City</label>
                                    <input type="text" name="city" class="form-control form-control-sm" value="{{ $property->city }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Beds / Baths</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="bedrooms" class="form-control" value="{{ $property->bedrooms }}" placeholder="Beds">
                                        <input type="number" name="bathrooms" class="form-control" value="{{ $property->bathrooms }}" placeholder="Baths">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Size (sqm)</label>
                                    <input type="number" name="property_size" class="form-control form-control-sm" step="0.01" value="{{ $property->property_size }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold" style="font-size:.72rem;">Admin Note</label>
                                    <input type="text" name="admin_note" class="form-control form-control-sm" value="{{ $property->admin_note }}">
                                </div>
                                <div class="col-12 d-flex gap-3 align-items-center pt-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="fVerified" @checked($property->is_verified)>
                                        <label class="form-check-label small" for="fVerified">Verified</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="fFeatured" @checked($property->is_featured)>
                                        <label class="form-check-label small" for="fFeatured">Featured</label>
                                    </div>
                                    <button class="btn btn-sm fw-bold text-white rounded-3 ms-auto" style="background:#0B1F3A;">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
