@props(['icon' => 'bi-folder', 'color' => '#2563eb', 'name' => '', 'count' => 0, 'href' => '#', 'footer' => null])

<a href="{{ $href }}" class="text-decoration-none d-block w-100 h-100" style="min-width:0;">
    <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden doc-cat-card" style="min-width:0;">
        <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2" style="min-width:0;">
            <div class="d-flex align-items-center gap-3" style="min-width:0;">
                <div class="d-flex align-items-center justify-content-center text-white rounded-3 flex-shrink-0" style="width:44px; height:44px; background:{{ $color }};">
                    <i class="bi {{ $icon }}" style="font-size:1.15rem;"></i>
                </div>
                <div class="overflow-hidden" style="min-width:0;">
                    <div class="fw-bold text-truncate" style="color:#0B1F3A;">{{ $name }}</div>
                    <div class="small text-muted text-truncate">{{ $footer ?? $count . ' Documents' }}</div>
                </div>
            </div>
            <i class="bi bi-arrow-right-circle text-muted flex-shrink-0" style="font-size:1.2rem;"></i>
        </div>
    </div>
</a>
