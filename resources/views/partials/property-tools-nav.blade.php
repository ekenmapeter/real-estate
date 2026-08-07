@php
    $items = [
        ['route' => 'properties.mine', 'icon' => 'bi-house-gear', 'label' => 'My Property Listings'],
        ['route' => 'properties.saved', 'icon' => 'bi-bookmark-heart', 'label' => 'Saved Properties'],
        ['route' => 'properties.inquiries', 'icon' => 'bi-chat-dots', 'label' => 'Property Inquiries'],
        ['route' => 'properties.viewing-requests', 'icon' => 'bi-calendar-check', 'label' => 'Viewing Requests'],
    ];
@endphp
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap gap-2">
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}" class="btn btn-sm fw-bold rounded-3 {{ request()->routeIs($item['route']) ? 'text-white' : 'btn-light text-muted' }}" style="{{ request()->routeIs($item['route']) ? 'background:#0B1F3A;' : 'border:1px solid #dbe2ec;' }}">
                    <i class="bi {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ route('properties.create') }}" class="btn btn-sm fw-bold text-white rounded-3 ms-auto" style="background:#16a34a;">
                <i class="bi bi-plus-lg me-1"></i> List New Property
            </a>
        </div>
    </div>
</div>
