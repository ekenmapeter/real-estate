@props([
    'icon' => 'heroicon-o-arrow-down-tray',
    'label' => '',
    'iconColor' => 'text-blue-600',
    'destructive' => false,
])

<a {{ $attributes->merge(['class' => 'flex items-center justify-between gap-3 rounded-lg px-1 py-3 transition hover:bg-slate-50' . ($destructive ? ' text-rose-600' : ' text-slate-700')]) }}>
    <span class="flex min-w-0 items-center gap-3">
        <span class="shrink-0">
            @svg($icon, 'h-4.5 w-4.5 ' . $iconColor)
        </span>
        <span class="truncate text-xs font-bold {{ $destructive ? 'text-rose-600' : 'text-slate-700' }}">{{ $label }}</span>
    </span>
    @svg('heroicon-o-chevron-right', 'h-4 w-4 shrink-0 text-slate-300')
</a>
