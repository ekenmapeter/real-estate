@props([
    'label' => '',
    'value' => '',
    'copy' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-3 py-2.5']) }}>
    <span class="shrink-0 text-xs font-semibold text-slate-500">{{ $label }}</span>
    <span class="flex min-w-0 items-center gap-1.5 text-right">
        <span class="truncate text-xs font-bold text-slate-900">{{ $value }}</span>
        @if ($copy)
            <button x-data="{ copied: false }"
                    @click="navigator.clipboard.writeText('{{ $copy }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="shrink-0 rounded-md p-1 text-slate-400 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Copy {{ $label }}">
                <template x-if="!copied">@svg('heroicon-o-clipboard', 'h-3.5 w-3.5')</template>
                <template x-if="copied">@svg('heroicon-o-check', 'h-3.5 w-3.5 text-emerald-500')</template>
            </button>
        @endif
    </span>
</div>
