@props([
    'label' => '',
    'classes' => 'bg-slate-100 text-slate-600 ring-slate-200',
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset ' . $classes]) }}>
    {{ $label }}
</span>
