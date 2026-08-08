@props([
    'label' => '',
    'description' => '',
    'icon' => 'heroicon-o-chat-bubble-left-right',
    'color' => 'bg-blue-50 text-blue-600',
])

<button {{ $attributes->merge(['class' => 'flex flex-col items-start gap-2.5 rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md']) }}>
    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $color }}">
        @svg($icon, 'h-5 w-5')
    </span>
    <span>
        <span class="block text-xs font-extrabold text-slate-900">{{ $label }}</span>
        <span class="mt-0.5 block text-[10px] font-medium leading-relaxed text-slate-400">{{ $description }}</span>
    </span>
</button>
