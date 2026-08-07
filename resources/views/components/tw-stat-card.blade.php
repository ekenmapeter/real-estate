@props([
    'icon' => 'heroicon-o-folder',
    'color' => 'bg-blue-500',
    'label' => '',
    'count' => 0,
    'sublabel' => 'Documents',
    'href' => '#',
    'active' => false,
])

<a href="{{ $href }}" class="block rounded-xl border bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md {{ $active ? 'border-blue-300 ring-2 ring-blue-100' : 'border-slate-200' }}">
    <div class="flex items-center gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white shadow-sm {{ $color }}">
            @svg($icon, 'h-5 w-5')
        </span>
        <span class="min-w-0">
            <span class="block truncate text-[11px] font-bold uppercase tracking-wide text-slate-500">{{ $label }}</span>
            <span class="block text-xl font-extrabold text-slate-900">{{ number_format($count) }}</span>
            <span class="block text-[11px] font-medium text-slate-400">{{ $sublabel }}</span>
        </span>
    </div>
</a>
