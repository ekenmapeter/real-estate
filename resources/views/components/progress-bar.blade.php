@props([
    'progress' => 0,
    'color' => 'from-blue-600 to-blue-500',
])

<div {{ $attributes }}>
    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
        <div class="h-full rounded-full bg-gradient-to-r {{ $color }}" style="width: {{ min(100, max(0, $progress)) }}%"></div>
    </div>
</div>
