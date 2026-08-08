@props([
    'icon' => 'heroicon-o-link',
    'iconColor' => 'bg-blue-100 text-blue-600',
    'title' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm']) }}>
    <div class="mb-3 flex items-center gap-2.5">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $iconColor }}">
            @svg($icon, 'h-4.5 w-4.5')
        </span>
        <h3 class="text-sm font-extrabold text-slate-900">{{ $title }}</h3>
    </div>
    <div class="flex flex-1 flex-col">
        {{ $slot }}
    </div>
</div>
