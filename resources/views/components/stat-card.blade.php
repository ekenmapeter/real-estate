@props([
    'icon' => 'heroicon-o-chart-bar',
    'color' => 'bg-blue-500',
    'label' => '',
    'value' => 0,
    'caption' => null,
    'trend' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-4 shadow-sm']) }}>
    <div class="flex items-center justify-between gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-white shadow-sm {{ $color }}">
            @svg($icon, 'h-5 w-5')
        </span>
        <span class="text-right text-[11px] font-bold uppercase tracking-wide text-slate-500">{{ $label }}</span>
    </div>
    <div class="mt-3 text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">{{ is_numeric($value) ? number_format($value) : $value }}</div>
    @if ($caption)
        <div class="mt-0.5 text-[11px] font-semibold text-slate-400">{{ $caption }}</div>
    @endif
    @if ($trend !== null)
        <div class="mt-1.5 flex items-center gap-1 text-xs font-bold {{ $trend >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
            @svg($trend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down', 'h-3.5 w-3.5')
            <span>{{ $trend >= 0 ? '↑' : '↓' }} {{ number_format(abs($trend), 1) }}%</span>
            <span class="font-medium text-slate-400">vs last month</span>
        </div>
    @endif
</div>
