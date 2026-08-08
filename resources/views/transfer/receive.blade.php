@extends('layouts.account')

@section('title', 'Receive AVC | AVC Transfer | ' . site_name())

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- Back link --}}
    <a href="{{ route('transfer.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to AVC Transfer
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Receive AVC</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Share your details and receive AVC Credits securely from verified members.</p>
        </div>
        <a href="{{ route('transfer.send') }}" class="inline-flex w-fit items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
            @svg('heroicon-o-paper-airplane', 'h-4 w-4')
            Send AVC Instead
        </a>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">

        {{-- QR code card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900">Personal AVC QR Code</h2>
            <p class="text-[11px] font-medium text-slate-400">Ask the sender to scan this code to reach your account.</p>
            <div class="mt-5 inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                @if ($qrSvg)
                    {!! $qrSvg !!}
                @else
                    <span class="flex h-[180px] w-[180px] items-center justify-center bg-slate-100 text-slate-300">@svg('heroicon-o-qr-code', 'h-16 w-16')</span>
                @endif
            </div>
            <p class="mt-4 text-[11px] font-medium text-slate-400">Scanning this QR code opens the AVC Transfer receive page with your account pre-filled.</p>
            <a href="#" @click.prevent class="mt-3 inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                Download QR Code
            </a>
        </div>

        {{-- Account details card --}}
        <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900">Your Receive Details</h2>
            <p class="text-[11px] font-medium text-slate-400">Share any of these details with the sender.</p>

            <div class="mt-4 space-y-3">
                @foreach ([
                    ['label' => 'AVC ID', 'value' => $accountId],
                    ['label' => 'Registered Email', 'value' => $email],
                    ['label' => 'Username', 'value' => $username],
                ] as $detail)
                    <div x-data="{ copied: false }" class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                        <div class="min-w-0">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $detail['label'] }}</div>
                            <div class="truncate text-xs font-extrabold text-slate-900">{{ $detail['value'] }}</div>
                        </div>
                        <button @click="navigator.clipboard.writeText('{{ $detail['value'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="shrink-0 rounded-md p-1.5 text-slate-400 transition hover:bg-white hover:text-blue-600" aria-label="Copy {{ $detail['label'] }}">
                            <template x-if="!copied">@svg('heroicon-o-clipboard', 'h-4 w-4')</template>
                            <template x-if="copied">@svg('heroicon-o-check', 'h-4 w-4 text-emerald-500')</template>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                <div class="mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Share Details</div>
                @php
                    $shareText = urlencode('Send me AVC Credits on AVC Real Estate. My AVC ID is ' . $accountId . '.');
                    $shareLink = urlencode(url('/transfer/receive?to=' . $accountId));
                @endphp
                <div class="flex flex-wrap items-center gap-2">
                    <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener" class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 transition hover:bg-emerald-200" title="Share on WhatsApp">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
                    </a>
                    <a href="https://t.me/share/url?url={{ $shareLink }}&text={{ $shareText }}" target="_blank" rel="noopener" class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-600 transition hover:bg-sky-200" title="Share on Telegram">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>
                    </a>
                    <a href="mailto:?subject=Send me AVC Credits&body={{ $shareText }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 transition hover:bg-blue-200" title="Share by Email">
                        @svg('heroicon-o-envelope', 'h-4.5 w-4.5')
                    </a>
                    <button x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ url('/transfer/receive?to=' . $accountId) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-violet-100 text-violet-600 transition hover:bg-violet-200" title="Copy link">
                        <template x-if="!copied">@svg('heroicon-o-link', 'h-4.5 w-4.5')</template>
                        <template x-if="copied">@svg('heroicon-o-check', 'h-4.5 w-4.5 text-emerald-600')</template>
                    </button>
                </div>
            </div>

            <div class="mt-auto pt-5">
                <div class="flex items-start gap-3 rounded-xl bg-blue-50 px-4 py-3.5 ring-1 ring-inset ring-blue-200">
                    <span class="shrink-0 text-blue-600">
                        @svg('heroicon-o-shield-check', 'h-5 w-5')
                    </span>
                    <p class="text-[11px] font-medium leading-relaxed text-blue-800">
                        Receive AVC Credits only from <strong>verified AVC members</strong>. Never share your password, PIN or recovery codes with anyone. The AVC team will never ask for them.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
