@props([
    'provider' => 'google',
    'label' => '',
    'connected' => false,
    'date' => '',
])

@php
    $icon = match ($provider) {
        'google' => '<span class="text-sm font-extrabold" style="background:linear-gradient(135deg,#4285F4 0%,#34A853 33%,#FBBC05 66%,#EA4335 100%); -webkit-background-clip:text; background-clip:text; color:transparent;">G</span>',
        'apple' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.05 12.54c-.03-2.9 2.37-4.3 2.48-4.37-1.35-1.98-3.46-2.25-4.2-2.28-1.8-.18-3.5 1.05-4.4 1.05-.91 0-2.31-1.03-3.8-1-1.95.03-3.76 1.14-4.77 2.89-2.04 3.54-.52 8.78 1.46 11.65.97 1.4 2.13 2.98 3.65 2.93 1.46-.06 2.02-.95 3.79-.95 1.77 0 2.27.95 3.82.92 1.58-.03 2.58-1.43 3.54-2.84 1.12-1.63 1.58-3.2 1.6-3.28-.04-.02-3.06-1.17-3.1-4.64zM14.23 4.26c.8-.97 1.34-2.32 1.2-3.66-1.15.05-2.55.77-3.38 1.73-.74.86-1.39 2.23-1.22 3.55 1.29.1 2.6-.65 3.4-1.62z"/></svg>',
        'telegram' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>',
        'whatsapp' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>',
        default => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-3 py-2.5']) }}>
    <div class="flex min-w-0 items-center gap-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600">
            {!! $icon !!}
        </span>
        <div class="min-w-0">
            <div class="text-xs font-bold text-slate-900">{{ $label }}</div>
            <div class="text-[11px] font-medium text-slate-400">{{ $date }}</div>
        </div>
    </div>
    @if ($connected)
        <span class="inline-flex shrink-0 items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
            <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Connected
        </span>
    @else
        <span class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">
            Not Connected
        </span>
    @endif
</div>
