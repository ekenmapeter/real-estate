@props(['icon' => 'bi-file-earmark-arrow-down', 'color' => '#2563eb', 'label' => '', 'href' => '#', 'form' => false, 'method' => 'POST'])

@if($form)
    <form action="{{ $href }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" class="h-100">
        @if($method !== 'GET') @csrf @endif
        <button type="submit" class="card border-0 rounded-4 shadow-sm w-100 h-100 text-decoration-none border-0 text-start doc-quick-action" style="background:#fff;">
            <div class="card-body p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 mb-2" style="width:48px; height:48px; background:{{ $color }};">
                    <i class="bi {{ $icon }}" style="font-size:1.25rem;"></i>
                </div>
                <div class="fw-bold small" style="color:#0B1F3A;">{{ $label }}</div>
            </div>
        </button>
    </form>
@else
    <a href="{{ $href }}" class="card border-0 rounded-4 shadow-sm d-block h-100 text-decoration-none doc-quick-action" style="background:#fff;">
        <div class="card-body p-4 text-center">
            <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 mb-2" style="width:48px; height:48px; background:{{ $color }};">
                <i class="bi {{ $icon }}" style="font-size:1.25rem;"></i>
            </div>
            <div class="fw-bold small" style="color:#0B1F3A;">{{ $label }}</div>
        </div>
    </a>
@endif
