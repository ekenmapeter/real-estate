@extends('layouts.account')

@section('title', 'AVC Marketplace | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="avcMarketplace({{ Js::from($listings) }})">

    {{-- 1. Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">AVC Marketplace</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Peer-to-peer AVC trading protected by platform escrow and coordinated by the Admin Escrow Team.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('avc-marketplace.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                @svg('heroicon-o-plus-circle', 'h-4 w-4')
                Create Listing
            </a>
            <a href="{{ route('avc-marketplace.my-listings') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                @svg('heroicon-o-rectangle-stack', 'h-4 w-4')
                My Listings
            </a>
            <a href="{{ route('avc-marketplace.my-deals') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                @svg('heroicon-o-arrows-right-left', 'h-4 w-4')
                My Deals
            </a>
        </div>
    </div>

    {{-- 2. Sub-navigation --}}
    <div class="no-scrollbar flex gap-1 overflow-x-auto border-b border-slate-200 bg-white px-2 shadow-sm sm:rounded-t-2xl">
        @foreach ([
            ['label' => 'Browse Listings', 'href' => route('avc-marketplace.index'), 'active' => true],
            ['label' => 'Create Listing', 'href' => route('avc-marketplace.create'), 'active' => false],
            ['label' => 'My Listings', 'href' => route('avc-marketplace.my-listings'), 'active' => false],
            ['label' => 'My Deals', 'href' => route('avc-marketplace.my-deals'), 'active' => false],
        ] as $tab)
            <a href="{{ $tab['href'] }}"
               class="shrink-0 border-b-2 px-3 py-3 text-xs font-bold transition
               {{ $tab['active'] ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    {{-- 3. Balance summary --}}
    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
        @foreach ($balanceSummary as $stat)
            <x-stat-card :icon="$stat['icon']" :color="$stat['color']" :label="$stat['label']" :value="$stat['value']" :caption="$stat['caption'] ?? null" />
        @endforeach
    </div>

    {{-- 4. Filters --}}
    <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-40">
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Listing Type</label>
                <select x-model="filters.type" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none">
                    <option value="">All Types</option>
                    <option value="sell">Sell Offers</option>
                    <option value="buy">Buy Requests</option>
                </select>
            </div>
            <div class="w-full sm:w-44">
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Country</label>
                <select x-model="filters.country" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none">
                    <option value="">All Countries</option>
                    @foreach ($filters['countries'] as $country)
                        <option value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-28">
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Min AVC</label>
                <input type="number" min="0" x-model.number="filters.minAmount" placeholder="0" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="w-28">
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Max AVC</label>
                <input type="number" min="0" x-model.number="filters.maxAmount" placeholder="No limit" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="w-full sm:w-44">
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Method</label>
                <select x-model="filters.method" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none">
                    <option value="">All Methods</option>
                    @foreach ($filters['methods'] as $method)
                        <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-36">
                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Currency</label>
                <select x-model="filters.currency" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none">
                    <option value="">All Currencies</option>
                    @foreach ($filters['currencies'] as $currency)
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" @click="clearFilters()" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-500 transition hover:bg-slate-50">Clear Filters</button>
            <span class="ml-auto text-[11px] font-bold text-slate-400"><span x-text="filteredListings().length"></span> matching listings</span>
        </div>
    </div>

    {{-- 5. Listing grid --}}
    <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <template x-for="listing in filteredListings()" :key="listing.reference">
            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md"
                 x-data="{ adminModal: false, approvalModal: false }">
                <div class="flex items-center justify-between">
                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset"
                          :class="listing.type === 'sell' ? 'bg-blue-50 text-blue-700 ring-blue-200' : 'bg-emerald-50 text-emerald-700 ring-emerald-200'"
                          x-text="listing.type === 'sell' ? 'Sell Offer' : 'Buy Request'"></span>
                    <code class="text-[11px] font-extrabold text-slate-400" x-text="'Listing #' + listing.reference"></code>
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <span class="text-sm font-extrabold text-slate-900" x-text="listing.userName"></span>
                    <template x-if="listing.verified">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Verified
                        </span>
                    </template>
                    <template x-if="!listing.verified">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">Unverified</span>
                    </template>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2.5 text-xs">
                    <div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">AVC Amount</div>
                        <div class="mt-0.5 font-extrabold text-slate-900"><span x-text="Number(listing.amount).toLocaleString()"></span> AVC</div>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Country</div>
                        <div class="mt-0.5 font-bold text-slate-700"><span x-text="listing.countryFlag"></span> <span x-text="listing.country"></span></div>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Payment Method</div>
                        <div class="mt-0.5 font-bold text-slate-700" x-text="listing.paymentMethod"></div>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Payment Currency</div>
                        <div class="mt-0.5 font-bold text-slate-700" x-text="listing.currency"></div>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-inset ring-slate-100">
                    <svg class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    <span class="text-[10px] font-bold text-slate-600" x-text="listing.escrowStatus"></span>
                    <span class="ml-auto text-[10px] font-medium text-slate-400" x-text="listing.age"></span>
                </div>

                <template x-if="listing.own">
                    <span class="mt-4 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-50 py-2.5 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        Your Listing
                    </span>
                </template>
                <template x-if="!listing.own">
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <button type="button" @click="adminModal = true"
                                class="rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                            Deal via Admin
                        </button>
                        <button type="button" @click="approvalModal = true"
                                class="rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50"
                                x-text="listing.type === 'sell' ? 'Buy This AVC' : 'Sell AVC to This Buyer'"></button>
                    </div>
                </template>

                {{-- Deal via Admin modal --}}
                <div x-show="adminModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm" @click.self="adminModal = false">
                    <div class="w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-2xl">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/></svg>
                        </span>
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">Deal via Admin</h3>
                        <p class="mt-1.5 text-xs font-medium leading-relaxed text-slate-500">
                            Contact the Admin Escrow Team to start a deal for Listing #<span x-text="listing.reference"></span>. Your message will be pre-filled.
                        </p>
                        <div class="mt-5 grid gap-2.5">
                            <a :href="dealHref(listing, 'telegram')" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-lg bg-sky-600 py-2.5 text-xs font-bold text-white transition hover:bg-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>
                                Continue on Telegram
                            </a>
                            <a :href="dealHref(listing, 'whatsapp')" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-lg bg-emerald-600 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700">
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
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                        </span>
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">Admin Approval Required</h3>
                        <p class="mt-1.5 text-xs font-medium leading-relaxed text-slate-500">
                            This action does not start the payment process yet. The Admin Escrow Team must first verify both parties, secure the AVC in escrow and activate the deal. Once approved, this button becomes active.
                        </p>
                        <div class="mt-5 grid gap-2.5">
                            <button type="button" @click="approvalModal = false; adminModal = true" class="w-full rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                                Contact Admin Escrow Team
                            </button>
                            <button type="button" @click="approvalModal = false" class="w-full rounded-lg border border-slate-200 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- 6. How it works --}}
    <div class="mt-10">
        <h2 class="text-lg font-extrabold text-slate-900">How It Works</h2>
        <p class="text-xs font-medium text-slate-500">Every trade is coordinated through the official Admin Escrow Team.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($howItWorks as $step)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        @svg($step['icon'], 'h-5 w-5')
                    </span>
                    <h3 class="mt-3 text-sm font-extrabold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-[11px] font-medium leading-relaxed text-slate-500">{{ $step['detail'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 7. Admin escrow contact --}}
    <div class="mt-6 rounded-2xl bg-gradient-to-br from-[#0f1e3d] to-blue-900 p-6 text-white shadow-lg">
        <div class="flex flex-col items-start gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-blue-300">
                    @svg('heroicon-o-shield-check', 'h-6 w-6')
                </span>
                <div>
                    <h2 class="text-base font-extrabold">Admin Escrow Team</h2>
                    <p class="mt-1 max-w-xl text-xs font-medium leading-relaxed text-blue-200">
                        All deals are verified and coordinated by our official escrow team. Use the official Telegram or WhatsApp channels below to start a deal.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ $escrow['telegram'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-sky-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>
                    Continue on Telegram
                </a>
                <a href="{{ $escrow['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
                    Continue on WhatsApp
                </a>
            </div>
        </div>
    </div>

    {{-- 8. Safety notice --}}
    <div class="mt-4 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-5">
        <span class="shrink-0 text-amber-500">
            @svg('heroicon-o-exclamation-triangle', 'h-5 w-5')
        </span>
        <div class="text-[11px] font-medium leading-relaxed text-amber-800">
            <strong>Marketplace Safety Notice:</strong> Never communicate directly with buyers or sellers outside the official Admin Escrow Team. Never share your bank details, wallet addresses, phone numbers or personal contact information on the marketplace. All payments are arranged privately through the official escrow channels. AVC is only released from platform escrow after the buyer's payment is confirmed by the seller and reviewed by the Admin Escrow Team.
        </div>
    </div>
</div>

<script>
    function avcMarketplace(listings) {
        return {
            listings,
            escrowTelegram: '{{ $escrow['telegram'] }}',
            escrowWhatsapp: '{{ $escrow['whatsapp'] }}',
            userId: 'AVC-2300389',
            filters: {
                type: '',
                country: '',
                minAmount: null,
                maxAmount: null,
                method: '',
                currency: '',
            },
            clearFilters() {
                this.filters = { type: '', country: '', minAmount: null, maxAmount: null, method: '', currency: '' };
            },
            dealMessage(listing) {
                return 'Hello Admin Escrow Team. I would like to start a deal for Listing #' + listing.reference +
                    ' for ' + Number(listing.amount).toLocaleString() + ' AVC. My User ID is ' + this.userId + '.';
            },
            dealHref(listing, channel) {
                const base = channel === 'telegram' ? this.escrowTelegram : this.escrowWhatsapp;
                return base + '?text=' + encodeURIComponent(this.dealMessage(listing));
            },
            filteredListings() {
                const f = this.filters;
                return this.listings.filter((l) => {
                    if (l.status !== 'live') return false;
                    if (f.type && l.type !== f.type) return false;
                    if (f.country && l.country !== f.country) return false;
                    if (f.method && l.paymentMethod !== f.method) return false;
                    if (f.currency && l.currency !== f.currency) return false;
                    if (f.minAmount !== null && f.minAmount !== undefined && l.amount < f.minAmount) return false;
                    if (f.maxAmount !== null && f.maxAmount !== undefined && l.amount > f.maxAmount) return false;
                    return true;
                });
            },
        };
    }
</script>
@endsection
