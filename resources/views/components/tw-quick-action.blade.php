@props([
    'icon' => 'heroicon-o-document-arrow-down',
    'color' => 'bg-blue-500',
    'label' => '',
    'href' => '#',
    'form' => false,
    'method' => 'POST',
])

@if($form)
    <form action="{{ $href }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" class="h-full">
        @if($method !== 'GET') @csrf @endif
        <button type="submit" class="flex h-full w-full flex-col items-center gap-2.5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <span class="flex h-11 w-11 items-center justify-center rounded-lg text-white shadow-sm {{ $color }}">
                @svg($icon, 'h-5 w-5')
            </span>
            <span class="text-center text-xs font-bold text-slate-700">{{ $label }}</span>
        </button>
    </form>
@else
    <a href="{{ $href }}" class="flex h-full flex-col items-center gap-2.5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg text-white shadow-sm {{ $color }}">
            @svg($icon, 'h-5 w-5')
        </span>
        <span class="text-center text-xs font-bold text-slate-700">{{ $label }}</span>
    </a>
@endif
