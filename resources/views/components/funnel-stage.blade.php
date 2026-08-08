@props([
    'label' => '',
    'value' => 0,
    'icon' => 'heroicon-o-check-circle',
    'last' => false,
])

<div {{ $attributes->merge(['class' => 'relative flex flex-1 flex-col items-center gap-2 px-2 py-4 text-center']) }}>
    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600 ring-4 ring-blue-50">
        @svg($icon, 'h-6 w-6')
    </span>
    <div class="text-lg font-extrabold text-slate-900">{{ number_format($value) }}</div>
    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $label }}</div>
    @if (! $last)
        <span class="absolute -right-3 top-1/2 z-10 hidden -translate-y-1/2 text-slate-300 md:block">
            @svg('heroicon-o-arrow-right', 'h-4 w-4')
        </span>
        <span class="text-slate-300 md:hidden">
            @svg('heroicon-o-arrow-down', 'h-4 w-4')
        </span>
    @endif
</div>
