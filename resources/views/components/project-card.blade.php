@props([
    'title' => '',
    'location' => '',
    'flag' => '🏳️',
    'status' => 'Sale',
    'imageUrl' => null,
    'gradient' => 'from-blue-700 via-indigo-700 to-blue-900',
    'progress' => 0,
    'investors' => 0,
    'generated' => 0.00,
    'commission' => 0.00,
    'deadline' => '',
    'campaign' => 'Active',
    'href' => null,
])

<div {{ $attributes->merge(['class' => 'flex w-72 shrink-0 snap-start flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg sm:w-80']) }}>
    <div class="relative h-36 bg-gradient-to-br {{ $gradient }}">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $title }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center text-white/25">
                @svg('heroicon-o-building-office-2', 'h-16 w-16')
            </div>
        @endif
        <span class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white text-base shadow">
            {{ $flag }}
        </span>
        <span class="absolute right-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-800 shadow">
            {{ $status }}
        </span>
        @if ($campaign)
            <span class="absolute bottom-3 right-3 flex items-center gap-1.5 rounded-full bg-emerald-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>{{ $campaign }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="text-sm font-extrabold text-slate-900">{{ $title }}</h3>
        <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $location }}</p>

        <div class="mt-3">
            <div class="flex items-center justify-between text-[11px] font-bold">
                <span class="text-slate-500">Funding Progress</span>
                <span class="text-blue-600">{{ $progress }}%</span>
            </div>
            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-500" style="width: {{ min(100, max(0, $progress)) }}%"></div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-3 divide-x divide-slate-100 rounded-xl border border-slate-100 bg-slate-50 py-2.5 text-center">
            <div>
                <div class="text-sm font-extrabold text-slate-900">{{ $investors }}</div>
                <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Investors</div>
            </div>
            <div>
                <div class="text-sm font-extrabold text-slate-900">${{ number_format($generated) }}</div>
                <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Generated</div>
            </div>
            <div>
                <div class="text-sm font-extrabold text-emerald-600">${{ number_format($commission) }}</div>
                <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Commission</div>
            </div>
        </div>

        <div class="mt-3 flex items-center justify-between text-[11px] font-semibold text-slate-500">
            <span>{{ $deadline }}</span>
        </div>

        @if ($href)
            <a href="{{ $href }}" class="mt-3 block rounded-lg bg-blue-600 py-2 text-center text-xs font-bold text-white transition hover:bg-blue-700">
                View Project
            </a>
        @else
            <span class="mt-3 block cursor-not-allowed rounded-lg bg-slate-100 py-2 text-center text-xs font-bold text-slate-400">
                View Project
            </span>
        @endif
    </div>
</div>
