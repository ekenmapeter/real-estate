@extends('layouts.account')

@section('title', 'Create Listing | AVC Marketplace | ' . site_name())

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="createListing()">

    {{-- Back link --}}
    <a href="{{ route('avc-marketplace.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to AVC Marketplace
    </a>

    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Create Listing</h1>
    <p class="mt-1 text-sm font-medium text-slate-500">List your AVC for sale or create a buy request. All listings are reviewed by the Admin Escrow Team before going live.</p>

    {{-- Step indicator --}}
    <div class="mt-6 flex items-center gap-2">
        @foreach (['Listing Type', 'Listing Details', 'Review & Submit'] as $index => $label)
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-bold"
                      :class="step === {{ $index + 1 }} ? 'bg-blue-600 text-white' : (step > {{ $index + 1 }} ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400')"
                      x-text="step > {{ $index + 1 }} ? '✓' : {{ $index + 1 }}"></span>
                <span class="text-[11px] font-bold {{ $index === 0 ? '' : '' }}" :class="step === {{ $index + 1 }} ? 'text-slate-900' : 'text-slate-400'">{{ $label }}</span>
                @if (! $loop->last)
                    <span class="h-px w-8 bg-slate-200"></span>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Step 1: type --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" x-show="step === 1">
        <h2 class="text-sm font-extrabold text-slate-900">What would you like to do?</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <button type="button" @click="form.type = 'sell'; step = 2"
                    class="rounded-2xl border-2 p-5 text-left transition"
                    :class="form.type === 'sell' ? 'border-blue-600 bg-blue-50' : 'border-slate-200 hover:border-blue-300'">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">@svg('heroicon-o-arrow-up-tray', 'h-5 w-5')</span>
                <span class="mt-3 block text-sm font-extrabold text-slate-900">Sell AVC</span>
                <span class="mt-1 block text-[11px] font-medium leading-relaxed text-slate-500">Sell AVC you already own. Your AVC is secured in platform escrow until the deal completes.</span>
            </button>
            <button type="button" @click="form.type = 'buy'; step = 2"
                    class="rounded-2xl border-2 p-5 text-left transition"
                    :class="form.type === 'buy' ? 'border-blue-600 bg-blue-50' : 'border-slate-200 hover:border-blue-300'">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">@svg('heroicon-o-arrow-down-tray', 'h-5 w-5')</span>
                <span class="mt-3 block text-sm font-extrabold text-slate-900">Buy AVC</span>
                <span class="mt-1 block text-[11px] font-medium leading-relaxed text-slate-500">Purchase AVC from another marketplace user. No AVC is locked when you create the request.</span>
            </button>
        </div>
    </div>

    {{-- Step 2: details --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" x-show="step === 2" x-cloak>
        <h2 class="text-sm font-extrabold text-slate-900">Listing Details</h2>
        <p class="text-[11px] font-medium text-slate-400">Tell buyers or sellers how you want to transact.</p>

        <div class="mt-4 space-y-4">
            <div>
                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Amount of AVC</label>
                <input type="number" min="1" x-model.number="form.amount" placeholder="e.g. 500"
                       class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm font-extrabold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Preferred Payment Method</label>
                <select x-model="form.method" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">Select a payment method</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Country</label>
                    <select x-model="form.country" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select your country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Preferred Payment Currency</label>
                    <select x-model="form.currency" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select a currency</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Notes (Optional)</label>
                <textarea rows="3" x-model="form.notes" placeholder="Anything the escrow team should know about your listing..." class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5">
                <span class="shrink-0 text-amber-500">@svg('heroicon-o-exclamation-triangle', 'h-5 w-5')</span>
                <p class="text-[11px] font-semibold leading-relaxed text-amber-800">
                    Do not include bank account details, wallet addresses or personal contact information. Payment instructions are arranged privately through the Admin Escrow Team.
                </p>
            </div>

            <div class="flex items-center justify-between gap-3">
                <button type="button" @click="step = 1" class="rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Back</button>
                <button type="button" @click="step = 3" :disabled="!canReview()"
                        :class="canReview() ? 'bg-blue-600 hover:bg-blue-700' : 'cursor-not-allowed bg-slate-200 text-slate-400'"
                        class="rounded-lg px-6 py-2.5 text-xs font-bold text-white transition">Continue to Review</button>
            </div>
        </div>
    </div>

    {{-- Step 3: review --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" x-show="step === 3" x-cloak>
        <h2 class="text-sm font-extrabold text-slate-900">Review Your Listing</h2>
        <p class="text-[11px] font-medium text-slate-400">Confirm the details below before submitting.</p>
        <dl class="mt-4 space-y-2.5 rounded-xl bg-slate-50 p-4 text-xs ring-1 ring-inset ring-slate-100">
            <div class="flex items-center justify-between">
                <dt class="font-semibold text-slate-500">Listing Type</dt>
                <dd class="font-extrabold text-slate-900" x-text="form.type === 'sell' ? 'Sell AVC' : 'Buy AVC'"></dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="font-semibold text-slate-500">Amount</dt>
                <dd class="font-extrabold text-slate-900"><span x-text="(form.amount || 0).toLocaleString()"></span> AVC</dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="font-semibold text-slate-500">Payment Method</dt>
                <dd class="font-extrabold text-slate-900" x-text="form.method"></dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="font-semibold text-slate-500">Country</dt>
                <dd class="font-extrabold text-slate-900" x-text="form.country"></dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="font-semibold text-slate-500">Payment Currency</dt>
                <dd class="font-extrabold text-slate-900" x-text="form.currency"></dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="font-semibold text-slate-500">Initial Status</dt>
                <dd class="font-extrabold text-amber-600">Pending Review</dd>
            </div>
        </dl>
        <label class="mt-4 flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 px-4 py-3.5 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50">
            <input type="checkbox" x-model="form.confirmed" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-xs font-semibold leading-relaxed text-slate-600">I confirm that the AVC and payment details in this listing are accurate, and I understand that listings are reviewed by the Admin Escrow Team before going live.</span>
        </label>
        <div class="mt-4 flex items-center justify-between gap-3">
            <button type="button" @click="step = 2" class="rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Back</button>
            <button type="button" @click="submitListing()" :disabled="!form.confirmed"
                    :class="form.confirmed ? 'bg-blue-600 hover:bg-blue-700' : 'cursor-not-allowed bg-slate-200 text-slate-400'"
                    class="inline-flex items-center gap-2 rounded-lg px-6 py-2.5 text-xs font-bold text-white transition">
                @svg('heroicon-o-paper-airplane', 'h-4 w-4')
                Submit Listing
            </button>
        </div>
    </div>

    {{-- Success --}}
    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center shadow-sm" x-show="submitted" x-cloak>
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            @svg('heroicon-o-check-circle', 'h-7 w-7')
        </span>
        <h2 class="mt-4 text-lg font-extrabold text-slate-900">Listing Submitted</h2>
        <p class="mt-1 text-xs font-medium text-slate-500">Your listing is now under review by the Admin Escrow Team.</p>
        <code class="mt-3 inline-block rounded-lg bg-white px-4 py-2 text-sm font-extrabold text-blue-600 ring-1 ring-inset ring-emerald-200" x-text="reference"></code>
        <p class="mt-3 text-[11px] font-medium text-slate-500">
            <span x-show="form.type === 'sell'">Your AVC will be secured in escrow once the listing is approved.</span>
            <span x-show="form.type !== 'sell'">AVC is locked later when a seller accepts your request.</span>
        </p>
        <div class="mt-5 flex flex-wrap items-center justify-center gap-2.5">
            <a href="{{ route('avc-marketplace.my-listings') }}" class="rounded-lg bg-blue-600 px-6 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">View My Listings</a>
            <a href="{{ route('avc-marketplace.index') }}" class="rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">Back to Marketplace</a>
        </div>
    </div>
</div>

<script>
    function createListing() {
        return {
            step: 1,
            submitted: false,
            reference: '',
            form: {
                type: 'sell',
                amount: null,
                method: '',
                country: '',
                currency: '',
                notes: '',
                confirmed: false,
            },
            canReview() {
                return this.form.amount > 0 && this.form.method && this.form.country && this.form.currency;
            },
            submitListing() {
                if (!this.form.confirmed) return;
                this.reference = 'AVC-' + Math.random().toString(36).slice(2, 10).toUpperCase();
                this.submitted = true;
            },
        };
    }
</script>
@endsection
