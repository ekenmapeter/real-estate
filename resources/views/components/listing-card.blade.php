@props([
    'reference' => '',
    'type' => 'sell',
    'userName' => '',
    'verified' => false,
    'amount' => 0,
    'country' => '',
    'countryFlag' => '🏳️',
    'paymentMethod' => '',
    'currency' => '',
    'escrowStatus' => '',
    'age' => '',
    'status' => 'live',
    'own' => false,
    'escrowTelegram' => 'https://t.me/avc_escrow',
    'escrowWhatsapp' => 'https://wa.me/18005550134',
    'userId' => 'AVC-2300389',
])

@php
    $message = 'Hello Admin Escrow Team. I would like to start a deal for Listing #' . $reference . ' for ' . number_format($amount) . ' AVC. My User ID is ' . $userId . '.';
    $telegramHref = $escrowTelegram . '?text=' . urlencode($message);
    $whatsappHref = $escrowWhatsapp . '?text=' . urlencode($message);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md']) }}
     x-data="{ adminModal: false, approvalModal: false }">
    <div class="flex items-center justify-between">
        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset
            {{ $type === 'sell' ? 'bg-blue-50 text-blue-700 ring-blue-200' : 'bg-emerald-50 text-emerald-700 ring-emerald-200' }}">
            {{ $type === 'sell' ? 'Sell Offer' : 'Buy Request' }}
        </span>
        <code class="text-[11px] font-extrabold text-slate-400">Listing #{{ $reference }}</code>
    </div>

    <div class="mt-3 flex items-center gap-2">
        <span class="text-sm font-extrabold text-slate-900">{{ $userName }}</span>
        @if ($verified)
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                @svg('heroicon-o-check-badge', 'h-3 w-3') Verified
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">Unverified</span>
        @endif
    </div>

    <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2.5 text-xs">
        <div>
            <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">AVC Amount</div>
            <div class="mt-0.5 font-extrabold text-slate-900">{{ number_format($amount) }} AVC</div>
        </div>
        <div>
            <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Country</div>
            <div class="mt-0.5 font-bold text-slate-700">{{ $countryFlag }} {{ $country }}</div>
        </div>
        <div>
            <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Payment Method</div>
            <div class="mt-0.5 font-bold text-slate-700">{{ $paymentMethod }}</div>
        </div>
        <div>
            <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Payment Currency</div>
            <div class="mt-0.5 font-bold text-slate-700">{{ $currency }}</div>
        </div>
    </div>

    <div class="mt-3 flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-inset ring-slate-100">
        @svg('heroicon-o-lock-closed', 'h-3.5 w-3.5 text-amber-500')
        <span class="text-[10px] font-bold text-slate-600">{{ $escrowStatus }}</span>
        <span class="ml-auto text-[10px] font-medium text-slate-400">{{ $age }}</span>
    </div>

    @if ($own)
        <span class="mt-4 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-50 py-2.5 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
            @svg('heroicon-o-user-circle', 'h-4 w-4') Your Listing
        </span>
    @elseif ($status !== 'live')
        <span class="mt-4 rounded-lg bg-slate-100 py-2.5 text-center text-xs font-bold text-slate-500">
            {{ ucfirst(str_replace('_', ' ', $status)) }}
        </span>
    @else
        <div class="mt-4 grid grid-cols-2 gap-2">
            <button type="button" @click="adminModal = true"
                    class="rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                Deal via Admin
            </button>
            <button type="button" @click="approvalModal = true"
                    class="rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                {{ $type === 'sell' ? 'Buy This AVC' : 'Sell AVC to This Buyer' }}
            </button>
        </div>
    @endif

    {{-- Deal via Admin modal --}}
    <div x-show="adminModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm" @click.self="adminModal = false">
        <div class="w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-2xl">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                @svg('heroicon-o-chat-bubble-left-right', 'h-6 w-6')
            </span>
            <h3 class="mt-3 text-base font-extrabold text-slate-900">Deal via Admin</h3>
            <p class="mt-1.5 text-xs font-medium leading-relaxed text-slate-500">
                Contact the Admin Escrow Team to start a deal for Listing #{{ $reference }}. Your message will be pre-filled.
            </p>
            <div class="mt-5 grid gap-2.5">
                <a href="{{ $telegramHref }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-lg bg-sky-600 py-2.5 text-xs font-bold text-white transition hover:bg-sky-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>
                    Continue on Telegram
                </a>
                <a href="{{ $whatsappHref }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-lg bg-emerald-600 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
                    Continue on WhatsApp
                </a>
                <button type="button" @click="adminModal = false" class="rounded-lg border border-slate-200 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Admin approval required modal --}}
    <div x-show="approvalModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm" @click.self="approvalModal = false">
        <div class="w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-2xl">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                @svg('heroicon-o-shield-exclamation', 'h-6 w-6')
            </span>
            <h3 class="mt-3 text-base font-extrabold text-slate-900">Admin Approval Required</h3>
            <p class="mt-1.5 text-xs font-medium leading-relaxed text-slate-500">
                This action does not start the payment process yet. The Admin Escrow Team must first verify both parties, secure the AVC in escrow and activate the deal. Once approved, this button becomes active.
            </p>
            <div class="mt-5 grid gap-2.5">
                <button type="button" @click="approvalModal = false; adminModal = true"
                        class="w-full rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                    Contact Admin Escrow Team
                </button>
                <button type="button" @click="approvalModal = false" class="w-full rounded-lg border border-slate-200 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
            </div>
        </div>
    </div>
</div>
