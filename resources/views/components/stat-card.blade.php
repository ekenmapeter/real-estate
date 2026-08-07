@props(['icon' => 'bi-folder2-open', 'color' => '#2563eb', 'label' => '', 'count' => 0, 'sublabel' => 'Documents', 'active' => false])

<a href="{{ $attributes->get('href', '#') }}" class="text-decoration-none d-block w-100" style="min-width:0;">
    <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden {{ $active ? '' : 'doc-stat-card' }}" style="{{ $active ? 'border:2px solid #2563eb; background:#f0f6ff;' : 'background:#fff;' }} min-width:0;">
        <div class="card-body p-3 d-flex align-items-center gap-3" style="min-width:0;">
            <div class="d-flex align-items-center justify-content-center text-white rounded-3 flex-shrink-0" style="width:44px; height:44px; background:{{ $color }};">
                <i class="bi {{ $icon }}" style="font-size:1.15rem;"></i>
            </div>
            <div class="overflow-hidden" style="min-width:0;">
                <div class="small text-muted fw-bold text-truncate" style="font-size:.72rem; letter-spacing:.02em;">{{ $label }}</div>
                <div class="fw-bold text-truncate" style="color:#0B1F3A; font-size:1.35rem; line-height:1.2;">{{ number_format($count) }}</div>
                <div class="text-muted text-truncate" style="font-size:.68rem;">{{ $sublabel }}</div>
            </div>
        </div>
    </div>
</a>
