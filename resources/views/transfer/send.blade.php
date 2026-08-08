@extends('layouts.account')

@section('title', 'Send AVC | AVC Transfer | ' . site_name())

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="sendAvc({{ Js::from($recipients) }})">

    {{-- Back link --}}
    <a href="{{ route('transfer.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to AVC Transfer
    </a>

    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Send AVC</h1>
    <p class="mt-1 text-sm font-medium text-slate-500">Transfer AVC Credits to another verified AVC account.</p>

    {{-- Step 1: Details --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm" x-show="step === 'details'">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-extrabold text-slate-900">Transfer Details</h2>
            <p class="text-[11px] font-medium text-slate-400">Enter the amount and search for your recipient.</p>
        </div>
        <div class="space-y-5 px-5 py-5">
            <div>
                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Transfer Amount (AVC)</label>
                <div class="relative">
                    <input type="number" min="1" x-model.number="form.amount" placeholder="0.00"
                           class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm font-extrabold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <span class="absolute inset-y-0 right-4 flex items-center text-xs font-bold text-slate-400">AVC</span>
                </div>
                <p class="mt-1.5 text-[11px] font-semibold text-slate-400">
                    ≈ $<span x-text="(form.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> USD
                </p>
            </div>

            <div>
                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Search Recipient</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                        @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                    </span>
                    <input type="text" x-model="form.query" placeholder="Search by AVC ID, registered email or username"
                           class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-4 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                {{-- Search results --}}
                <div x-show="form.query.length > 0 && !selectedRecipient && filteredRecipients().length > 0" x-cloak
                     class="mt-2 overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                    <template x-for="recipient in filteredRecipients()" :key="recipient.avcId">
                        <button type="button" @click="selectRecipient(recipient)"
                                class="flex w-full items-center gap-3 border-b border-slate-100 bg-white px-3.5 py-3 text-left transition last:border-0 hover:bg-slate-50">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-xs font-bold text-white"
                                  x-text="initials(recipient.name)"></span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-xs font-bold text-slate-900" x-text="recipient.name"></span>
                                <span class="block truncate text-[11px] font-medium text-slate-400">
                                    <span x-text="recipient.avcId"></span> · <span x-text="recipient.email"></span>
                                </span>
                            </span>
                            <span class="shrink-0">
                                <template x-if="recipient.verified">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">Verified</span>
                                </template>
                                <template x-if="!recipient.verified">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">Unverified</span>
                                </template>
                            </span>
                        </button>
                    </template>
                </div>
                <p x-show="form.query.length > 0 && filteredRecipients().length === 0 && !selectedRecipient" x-cloak
                   class="mt-2 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-[11px] font-semibold text-amber-700">
                    No verified AVC account found for this search. Check the AVC ID or email and try again.
                </p>

                {{-- Selected recipient --}}
                <div x-show="selectedRecipient" x-cloak
                     class="mt-3 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-sm font-bold text-white"
                          x-text="initials(selectedRecipient.name)"></span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="truncate text-sm font-extrabold text-slate-900" x-text="selectedRecipient.name"></span>
                            <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[9px] font-bold text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Verified
                            </span>
                        </div>
                        <div class="truncate text-[11px] font-medium text-slate-500">
                            <span x-text="selectedRecipient.avcId"></span> · <span x-text="selectedRecipient.email"></span>
                        </div>
                    </div>
                    <button type="button" @click="selectedRecipient = null; form.query = ''" class="shrink-0 rounded-md p-1.5 text-slate-400 transition hover:bg-emerald-100 hover:text-rose-600" title="Remove recipient">
                        @svg('heroicon-o-x-mark', 'h-4 w-4')
                    </button>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Transfer Note (Optional)</label>
                <input type="text" x-model="form.note" placeholder="Add a note for the recipient"
                       class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Transaction summary --}}
            <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-100">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Transaction Summary</h3>
                <dl class="mt-3 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Amount</dt>
                        <dd class="font-extrabold text-slate-900"><span x-text="(form.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> AVC</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-semibold text-slate-500">Transfer Fee</dt>
                        <dd class="font-extrabold text-emerald-600">0.00 AVC</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-200 pt-2">
                        <dt class="font-bold text-slate-700">Recipient receives</dt>
                        <dd class="font-extrabold text-blue-600"><span x-text="(form.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> AVC</dd>
                    </div>
                </dl>
            </div>

            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 px-4 py-3.5 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50">
                <input type="checkbox" x-model="form.confirmed" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span class="text-xs font-semibold leading-relaxed text-slate-600">I confirm that I want to send this amount of AVC Credits to the verified recipient shown above. This action cannot be undone.</span>
            </label>

            <button type="button" @click="goToPin()" :disabled="!canContinue()"
                    :class="canContinue() ? 'bg-blue-600 hover:bg-blue-700' : 'cursor-not-allowed bg-slate-200 text-slate-400'"
                    class="flex w-full items-center justify-center gap-2 rounded-lg py-3 text-xs font-bold text-white transition sm:w-auto sm:px-8">
                @svg('heroicon-o-lock-closed', 'h-4 w-4')
                Continue to PIN Confirmation
            </button>
        </div>
    </div>

    {{-- Step 2: PIN --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm" x-show="step === 'pin'" x-cloak>
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
            @svg('heroicon-o-lock-closed', 'h-7 w-7')
        </span>
        <h2 class="mt-4 text-lg font-extrabold text-slate-900">Enter your transfer PIN</h2>
        <p class="mt-1 text-xs font-medium text-slate-500">Confirm this transfer of <span class="font-extrabold text-slate-900" x-text="(form.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> AVC to <span class="font-extrabold text-slate-900" x-text="selectedRecipient?.name"></span>.</p>
        <div class="mx-auto mt-6 flex max-w-[260px] justify-center gap-3">
            <template x-for="i in 4" :key="i">
                <input type="password" maxlength="1" inputmode="numeric" x-model="pin[i - 1]"
                       class="h-12 w-12 rounded-xl border border-slate-200 text-center text-lg font-extrabold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </template>
        </div>
        <p x-show="pinError" x-cloak class="mt-3 text-[11px] font-bold text-rose-600" x-text="pinError"></p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
            <button type="button" @click="step = 'details'" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">Back</button>
            <button type="button" @click="confirmPin()" class="rounded-lg bg-blue-600 px-8 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                Confirm Transfer
            </button>
        </div>
    </div>

    {{-- Step 3: Success --}}
    <div class="mt-6 rounded-2xl border border-emerald-200 bg-white p-8 text-center shadow-sm" x-show="step === 'success'" x-cloak>
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            @svg('heroicon-o-check-circle', 'h-7 w-7')
        </span>
        <h2 class="mt-4 text-lg font-extrabold text-slate-900">Transfer Completed</h2>
        <p class="mt-1 text-xs font-medium text-slate-500">Your AVC Credits are on their way.</p>

        <div class="mx-auto mt-6 max-w-sm rounded-2xl border border-slate-100 bg-slate-50 p-5 text-left">
            <dl class="space-y-2.5 text-xs">
                <div class="flex items-center justify-between">
                    <dt class="font-semibold text-slate-500">Transfer ID</dt>
                    <dd class="font-extrabold text-blue-600" x-text="reference"></dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="font-semibold text-slate-500">Date &amp; Time</dt>
                    <dd class="font-bold text-slate-900" x-text="completedAt"></dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="font-semibold text-slate-500">Recipient</dt>
                    <dd class="font-bold text-slate-900" x-text="selectedRecipient?.name"></dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="font-semibold text-slate-500">Amount</dt>
                    <dd class="font-extrabold text-slate-900"><span x-text="(form.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> AVC</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="font-semibold text-slate-500">Status</dt>
                    <dd class="font-bold text-emerald-600">Completed</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5">
            <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                @svg('heroicon-o-arrow-down-tray', 'h-4 w-4') Download Receipt
            </a>
            <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                @svg('heroicon-o-share', 'h-4 w-4') Share Receipt
            </a>
            <a href="{{ route('transfer.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                Back to AVC Transfer
            </a>
        </div>
    </div>
</div>

<script>
    function sendAvc(recipients) {
        return {
            recipients,
            step: 'details',
            pin: ['', '', '', ''],
            pinError: false,            reference: '',
            completedAt: '',
            form: {
                amount: null,
                query: '',
                note: '',
                confirmed: false,
            },
            selectedRecipient: null,
            initials(name) {
                const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
                return ((parts[0]?.[0] || '') + (parts[1]?.[0] || '')).toUpperCase() || '?';
            },
            filteredRecipients() {
                const q = String(this.form.query || '').trim().toLowerCase();
                if (!q) return [];
                return this.recipients.filter((r) =>
                    r.avcId.toLowerCase().includes(q) ||
                    r.email.toLowerCase().includes(q) ||
                    r.name.toLowerCase().includes(q)
                );
            },
            selectRecipient(recipient) {
                this.selectedRecipient = recipient;
                this.form.query = '';
            },
            canContinue() {
                return this.form.amount > 0 && this.selectedRecipient && this.form.confirmed;
            },
            goToPin() {
                if (!this.canContinue()) return;
                this.pin = ['', '', '', ''];
                this.pinError = false;
                this.step = 'pin';
                setTimeout(() => {
                    const first = document.querySelector('[x-data="sendAvc"] input');
                }, 50);
            },
            confirmPin() {
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
                            self.reference = 'TRF-' + new Date().getFullYear() + '-' + String(Math.floor(100000 + Math.random() * 900000));
                            self.completedAt = new Date().toLocaleString('en-US', {
                                month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit',
                            });
                            self.step = 'success';
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
