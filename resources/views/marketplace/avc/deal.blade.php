@extends('layouts.account')

@section('title', $deal['reference'] . ' | AVC Marketplace | ' . site_name())

@php
    $stages = [
        'Deal requested', 'Admin reviewing', 'Both parties confirmed', 'AVC secured in escrow',
        'Payment details issued', 'Buyer payment pending', 'Seller confirmation pending',
        'AVC release pending', 'Deal completed',
    ];
    $currentStage = $deal['currentStage']; // 1-based
    $statusColors = [
        'awaiting_payment' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'seller_confirmed' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    ];
    $statusColor = $statusColors[$deal['status']] ?? $statusColors['awaiting_payment'];
@endphp

@section('content')
<div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="dealEngine({{ $deal['deadlineSeconds'] ?? 0 }})">

    {{-- Back link --}}
    <a href="{{ route('avc-marketplace.my-deals') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to My Deals
    </a>

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <code class="text-sm font-extrabold text-blue-600">{{ $deal['reference'] }}</code>
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $statusColor }}">{{ $deal['statusLabel'] }}</span>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">Listing #{{ $deal['listingReference'] }}</span>
            </div>
            <h1 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900">
                {{ number_format($deal['avcAmount']) }} AVC · {{ $deal['paymentMethod'] }} · {{ $deal['currency'] }}
            </h1>
            <p class="mt-1 text-xs font-medium text-slate-500">Your role: <span class="font-bold text-slate-700">{{ ucfirst($deal['role']) }}</span> · {{ $deal['requiredAction'] }}</p>
        </div>
        <button type="button" @click="reportModal = true"
                class="inline-flex w-fit items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-600 transition hover:bg-rose-100">
            @svg('heroicon-o-flag', 'h-4 w-4')
            Report a Problem
        </button>
    </div>

    <div class="grid gap-4 xl:grid-cols-3">

        {{-- Progress tracker --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <h2 class="text-sm font-extrabold text-slate-900">Deal Progress</h2>
            <ol class="mt-4">
                @foreach ($stages as $index => $stage)
                    @php
                        $stageNumber = $index + 1;
                        $isDone = $stageNumber <= $currentStage;
                        $isCurrent = $stageNumber === $currentStage;
                    @endphp
                    <li class="relative flex gap-3 pb-4 last:pb-0">
                        @if (! $loop->last)
                            <span class="absolute left-[9px] top-5 h-full w-0.5 {{ $stageNumber < $currentStage ? 'bg-emerald-200' : 'bg-slate-100' }}"></span>
                        @endif
                        <span class="relative mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full ring-4 ring-white
                            {{ $isDone ? ($isCurrent ? 'bg-blue-600 text-white' : 'bg-emerald-500 text-white') : 'bg-slate-100 text-slate-400' }}">
                            @if ($isDone && ! $isCurrent)
                                @svg('heroicon-o-check', 'h-3 w-3')
                            @else
                                <span class="text-[9px] font-bold">{{ $stageNumber }}</span>
                            @endif
                        </span>
                        <div class="min-w-0">
                            <div class="text-xs font-bold {{ $isCurrent ? 'text-blue-700' : ($isDone ? 'text-slate-800' : 'text-slate-400') }}">{{ $stage }}</div>
                            @if ($isCurrent)
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-500">Current Stage</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- Right column --}}
        <div class="space-y-4">
            {{-- Deal info --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900">Deal Information</h2>
                <dl class="mt-3 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Listing Reference</dt>
                        <dd class="font-extrabold text-slate-900">#{{ $deal['listingReference'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">{{ $deal['role'] === 'buyer' ? 'Seller' : 'Buyer' }}</dt>
                        <dd class="font-extrabold text-slate-900">{{ $deal['counterparty'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">AVC Amount</dt>
                        <dd class="font-extrabold text-slate-900">{{ number_format($deal['avcAmount']) }} AVC</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Payment Method</dt>
                        <dd class="font-bold text-slate-900">{{ $deal['paymentMethod'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Currency</dt>
                        <dd class="font-bold text-slate-900">{{ $deal['currency'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Payment Amount</dt>
                        <dd class="font-extrabold text-slate-900">{{ $deal['currency'] }} {{ number_format($deal['paymentAmount'], 2) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Escrow Status</dt>
                        <dd class="inline-flex items-center gap-1.5 font-bold text-amber-600">
                            @svg('heroicon-o-lock-closed', 'h-3.5 w-3.5'){{ $deal['escrowStatus'] }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Countdown --}}
            @if ($deal['deadlineSeconds'])
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                    <h2 class="text-xs font-extrabold text-rose-700">Payment Deadline</h2>
                    <div class="mt-2 flex items-center gap-2 text-2xl font-extrabold tracking-tight text-rose-600">
                        @svg('heroicon-o-clock', 'h-6 w-6')
                        <span x-show="seconds > 0">
                            <span x-text="String(Math.floor(seconds / 60)).padStart(2, '0')"></span>:<span x-text="String(seconds % 60).padStart(2, '0')"></span>
                        </span>
                        <span x-show="seconds <= 0" x-cloak>Expired</span>
                    </div>
                    <p class="mt-2 text-[11px] font-medium leading-relaxed text-rose-700">
                        Complete the payment within the displayed time. If the timer expires, the deal is paused for admin review and the AVC stays locked in escrow.
                    </p>
                </div>
            @endif

            {{-- Payment instructions (buyer, active) --}}
            @if ($deal['status'] === 'awaiting_payment' && $deal['role'] === 'buyer')
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-extrabold text-slate-900">Payment Instructions</h2>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Issued by Admin
                        </span>
                    </div>
                    <dl class="mt-3 space-y-2.5 text-xs">
                        @foreach ($deal['paymentInstructions'] as $instruction)
                            <div class="flex items-center justify-between">
                                <dt class="font-semibold text-slate-500">{{ $instruction['label'] }}</dt>
                                <dd class="font-extrabold text-slate-900">{{ $instruction['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <p class="mt-3 rounded-lg bg-slate-50 px-3 py-2 text-[10px] font-medium text-slate-500 ring-1 ring-inset ring-slate-100">
                        These details are visible only to the approved buyer and will be hidden once the deal is completed, cancelled or expired.
                    </p>
                </div>

                {{-- Buyer actions --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <template x-if="!paidFormOpen">
                        <button type="button" @click="paidFormOpen = true"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-3 text-xs font-bold text-white transition hover:bg-blue-700">
                            @svg('heroicon-o-banknotes', 'h-4 w-4')
                            I Have Made Payment
                        </button>
                    </template>
                    <template x-if="paidFormOpen" x-cloak>
                        <div>
                            <h3 class="text-xs font-extrabold text-slate-900">Confirm Your Payment</h3>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Amount</label>
                                    <input type="text" value="{{ $deal['currency'] }} {{ number_format($deal['paymentAmount'], 2) }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Date</label>
                                    <input type="date" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Time</label>
                                    <input type="time" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Transaction Reference</label>
                                    <input type="text" placeholder="Bank reference or transfer ID" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Sender Name</label>
                                    <input type="text" placeholder="Name on the sending account" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Receipt</label>
                                    <input type="file" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none">
                                </div>
                            </div>
                            <button type="button" @click="markPaid()"
                                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 py-3 text-xs font-bold text-white transition hover:bg-emerald-700">
                                @svg('heroicon-o-check-circle', 'h-4 w-4')
                                Submit Payment Confirmation
                            </button>
                        </div>
                    </template>
                    <template x-if="markedPaid" x-cloak>
                        <div class="rounded-xl bg-emerald-50 p-4 text-center ring-1 ring-inset ring-emerald-200">
                            <span class="text-sm font-extrabold text-emerald-700">Payment Confirmation Submitted</span>
                            <p class="mt-1 text-[11px] font-medium text-emerald-600">The seller and Admin Escrow Team have been notified. AVC remains secured in escrow until the seller confirms receipt.</p>
                        </div>
                    </template>
                </div>
            @endif

            {{-- Seller actions --}}
            @if ($deal['status'] === 'seller_confirmed' && $deal['role'] === 'seller')
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <h2 class="text-xs font-extrabold text-amber-800">The buyer has marked this deal as paid</h2>
                    <p class="mt-1.5 text-[11px] font-medium leading-relaxed text-amber-700">
                        Check your receiving account before confirming. Do not confirm based only on the uploaded receipt. Only confirm after receiving the full payment — this action authorizes the release of AVC held in escrow.
                    </p>
                    <div class="mt-4 grid gap-2">
                        <button type="button" @click="pinStep = true"
                                class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                            @svg('heroicon-o-lock-closed', 'h-4 w-4')
                            Confirm Payment and Authorize AVC Release
                        </button>
                        <button type="button" @click="reportModal = true"
                                class="rounded-lg border border-amber-300 bg-white py-2.5 text-xs font-bold text-amber-700 transition hover:bg-amber-100">
                            Payment Not Received
                        </button>
                    </div>
                    <template x-if="pinStep" x-cloak>
                        <div class="mt-4 rounded-xl border border-amber-200 bg-white p-4">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Enter Transaction PIN to Authorize Release</div>
                            <div class="mt-3 flex max-w-[200px] justify-center gap-2">
                                <template x-for="i in 4" :key="i">
                                    <input type="password" maxlength="1" inputmode="numeric" x-model="pin[i - 1]"
                                           class="h-11 w-11 rounded-xl border border-slate-200 text-center text-base font-extrabold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </template>
                            </div>
                            <p x-show="pinError" x-cloak class="mt-2 text-[10px] font-bold text-rose-600" x-text="pinError"></p>
                            <button type="button" @click="authorizeRelease()" class="mt-3 w-full rounded-lg bg-emerald-600 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700">
                                Authorize AVC Release
                            </button>
                        </div>
                    </template>
                    <template x-if="authorized" x-cloak>
                        <div class="mt-4 rounded-xl bg-emerald-50 p-4 text-center ring-1 ring-inset ring-emerald-200">
                            <span class="text-sm font-extrabold text-emerald-700">Seller Confirmed — Awaiting Admin Release</span>
                            <p class="mt-1 text-[11px] font-medium text-emerald-600">The Admin Escrow Team will perform a final review and release the AVC to the buyer.</p>
                        </div>
                    </template>
                </div>
            @endif

            {{-- Completed --}}
            @if ($deal['status'] === 'completed')
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            @svg('heroicon-o-check-circle', 'h-5 w-5')
                        </span>
                        <div>
                            <div class="text-sm font-extrabold text-emerald-700">Deal Completed</div>
                            <p class="text-[11px] font-medium text-emerald-600">{{ number_format($deal['avcAmount']) }} AVC released from escrow.</p>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-2">
                        <a href="#" @click.prevent class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                            @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                            Download Receipt
                        </a>
                        <a href="#" @click.prevent class="flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                            @svg('heroicon-o-share', 'h-4 w-4')
                            Share Receipt
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Report a problem modal --}}
    <div x-show="reportModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm" @click.self="reportModal = false">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <h3 class="text-base font-extrabold text-slate-900">Report a Problem</h3>
            <p class="mt-1 text-xs font-medium text-slate-500">Choose the reason for reporting this deal. The deal will be frozen and AVC stays locked until the Admin Escrow Team resolves it.</p>
            <div class="mt-4 space-y-2">
                @foreach (['Payment not received', 'Incorrect amount received', 'Payment still pending', 'Buyer marked paid without paying', 'Seller refusing to confirm', 'Wrong payment details', 'Suspected fraud', 'Other issue'] as $reason)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 transition has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50">
                        <input type="radio" name="dispute_reason" class="h-3.5 w-3.5 text-rose-600 focus:ring-rose-500">
                        {{ $reason }}
                    </label>
                @endforeach
            </div>
            <div class="mt-4 grid gap-2">
                <button type="button" @click="reportModal = false" class="w-full rounded-lg bg-rose-600 py-2.5 text-xs font-bold text-white transition hover:bg-rose-700">Submit Report</button>
                <button type="button" @click="reportModal = false" class="w-full rounded-lg border border-slate-200 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function dealEngine(initialSeconds) {
        return {
            seconds: initialSeconds,
            timer: null,
            paidFormOpen: false,
            markedPaid: false,
            pinStep: false,
            pin: ['', '', '', ''],
            pinError: false,
            authorized: false,
            reportModal: false,
            init() {
                if (this.seconds > 0) {
                    this.timer = setInterval(() => {
                        if (this.seconds > 0) {
                            this.seconds--;
                        } else {
                            clearInterval(this.timer);
                        }
                    }, 1000);
                }
            },
            markPaid() {
                this.paidFormOpen = false;
                this.markedPaid = true;
            },
            authorizeRelease() {
                const self = this;
                fetch('{{ route('transfer.pin.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ pin: this.pin.join('') }),
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.valid) {
                            self.authorized = true;
                            self.pinStep = false;
                        } else {
                            self.pinError = data.message || 'Incorrect PIN. Please try again.';
                            self.pin = ['', '', '', ''];
                        }
                    })
                    .catch(() => {
                        self.pinError = 'Could not verify your PIN. Please try again.';
                        self.pin = ['', '', '', ''];
                    });
            },
        };
    }
</script>
@endsection
