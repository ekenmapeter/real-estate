@props([
    'icon' => 'heroicon-o-folder',
    'color' => 'bg-blue-500',
    'name' => '',
    'count' => 0,
    'footer' => null,
    'href' => '#',
])

<a href="{{ $href }}" class="group flex items-center justify-between rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
    <div class="flex min-w-0 items-center gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white shadow-sm {{ $color }}">
            @svg($icon, 'h-5 w-5')
        </span>
        <span class="min-w-0">
            <span class="block truncate text-sm font-bold text-slate-900">{{ $name }}</span>
            <span class="block truncate text-xs font-medium text-slate-500">{{ $footer ?? $count . ' Documents' }}</span>
        </span>
    </div>
    @svg('heroicon-o-arrow-right', 'h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-blue-500')
</a>
