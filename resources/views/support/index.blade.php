@extends('layouts.account')

@section('title', 'Support & Help Center | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8"
     x-data="supportEngine({{ Js::from($requests) }})">

    {{-- 1. Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Support & Help Center</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">We're here to help with deposits, withdrawals, projects, KYC and everything else on AVC.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="#" @click.prevent="openForm()" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                @svg('heroicon-o-plus-circle', 'h-4 w-4')
                Open Support Request
            </a>
            <a href="#my-requests" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                @svg('heroicon-o-ticket', 'h-4 w-4')
                My Requests
            </a>
        </div>
    </div>

    {{-- 2. Stat cards --}}
    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <x-stat-card :icon="$stat['icon']" :color="$stat['color']" :label="$stat['label']" :value="$stat['value']" :caption="$stat['caption'] ?? null" />
        @endforeach
    </div>

    {{-- 3. Account manager --}}
    <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-lg font-extrabold text-white shadow-lg">
                    {{ $accountManager['initials'] }}
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold text-slate-900">{{ $accountManager['name'] }}</h2>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>{{ $accountManager['availability'] }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs font-semibold text-blue-600">Your Account Manager · {{ $accountManager['department'] }}</p>
                    <div class="mt-3 grid gap-x-8 gap-y-1.5 text-[11px] font-medium text-slate-500 sm:grid-cols-2">
                        <div><span class="font-bold text-slate-700">Working hours:</span> {{ $accountManager['workingHours'] }}</div>
                        <div><span class="font-bold text-slate-700">Languages:</span> {{ $accountManager['languages'] }}</div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                    @svg('heroicon-o-chat-bubble-left-right', 'h-4 w-4')
                    Message Account Manager
                </a>
                <a href="#" @click.prevent="openForm()" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    @svg('heroicon-o-plus-circle', 'h-4 w-4')
                    Open Support Request
                </a>
                <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    @svg('heroicon-o-calendar', 'h-4 w-4')
                    Schedule a Meeting
                </a>
            </div>
        </div>
    </div>

    {{-- 4. Support categories --}}
    <div class="mt-8">
        <h2 class="text-lg font-extrabold text-slate-900">How can we help you?</h2>
        <p class="text-xs font-medium text-slate-500">Choose a category and we'll route your request to the right team.</p>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($categories as $category)
                <x-category-card :label="$category['label']" :description="$category['description']"
                    :icon="$category['icon']" :color="$category['color']" @click="selectCategory('{{ $category['key'] }}', '{{ $category['label'] }}')" />
            @endforeach
        </div>
    </div>

    {{-- 5. Support request form --}}
    <div id="support-form" class="mt-8 scroll-mt-20">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm" x-show="!submitted">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">Open Support Request</h2>
                <p class="text-[11px] font-medium text-slate-400">Describe your issue and our team will get back to you.</p>
            </div>
            <div class="space-y-4 px-5 py-5">
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Category</label>
                    <select x-model="form.category" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['key'] }}">{{ $category['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Subject</label>
                    <input type="text" x-model="form.subject" placeholder="Short summary of your issue" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Description</label>
                    <textarea rows="4" x-model="form.description" placeholder="Provide as much detail as possible..." class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Related Transaction or Project</label>
                    <select x-model="form.related" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">— None selected —</option>
                        <option value="DEP-2026-000101">DEP-2026-000101 · Deposit</option>
                        <option value="WDR-2026-004182">WDR-2026-004182 · Withdrawal</option>
                        <option value="PRJ-00001">PRJ-00001 · Luxury Villas</option>
                        <option value="PRJ-00002">PRJ-00002 · Urban Living Apartments</option>
                        <option value="AFR-2026-0142">AFR-2026-0142 · Finance Request</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Attachment</label>
                    <label class="flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center transition hover:border-blue-300 hover:bg-blue-50">
                        <span class="text-blue-600">@svg('heroicon-o-cloud-arrow-up', 'h-6 w-6')</span>
                        <span class="text-xs font-bold text-slate-700">Click to upload a file</span>
                        <span class="text-[10px] font-medium text-slate-400">JPG, PNG or PDF · max 10 MB</span>
                        <input type="file" class="hidden">
                    </label>
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Preferred Contact Method</label>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach (['Email', 'WhatsApp', 'Telegram', 'Phone'] as $method)
                            <label class="flex cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2.5 text-xs font-bold text-slate-600 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700">
                                <input type="radio" name="contact_method" value="{{ strtolower($method) }}" class="sr-only" {{ $loop->first ? 'checked' : '' }}>
                                {{ $method }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <button type="button" @click="submitRequest()"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-3 text-xs font-bold text-white transition hover:bg-blue-700 sm:w-auto sm:px-8">
                    @svg('heroicon-o-paper-airplane', 'h-4 w-4')
                    Submit Request
                </button>
            </div>
        </div>

        {{-- Submission confirmation --}}
        <div x-show="submitted" x-cloak class="rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center shadow-sm">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                @svg('heroicon-o-check-circle', 'h-7 w-7')
            </span>
            <h2 class="mt-4 text-lg font-extrabold text-slate-900">Request submitted successfully</h2>
            <p class="mt-1 text-xs font-medium text-slate-500">Your reference number is</p>
            <code class="mt-2 inline-block rounded-lg bg-white px-4 py-2 text-sm font-extrabold text-blue-600 ring-1 ring-inset ring-emerald-200" x-text="reference"></code>
            <p class="mt-3 text-[11px] font-medium text-slate-500">The support team has been notified. You can track progress under My Requests.</p>
            <a href="#my-requests" class="mt-5 inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">View My Requests</a>
        </div>
    </div>

    {{-- 6. My requests --}}
    <div id="my-requests" class="mt-8 scroll-mt-20">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">My Requests</h2>
                <p class="text-xs font-medium text-slate-500">Track the status of every request you've submitted.</p>
            </div>
            <div class="no-scrollbar flex gap-1.5 overflow-x-auto rounded-lg bg-white p-1 shadow-sm">
                <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-slate-800'"
                        class="shrink-0 rounded-md px-2.5 py-1.5 text-[11px] font-bold transition">All</button>
                @foreach ([
                    ['key' => 'open', 'label' => 'Open'],
                    ['key' => 'in_progress', 'label' => 'In Progress'],
                    ['key' => 'awaiting_user', 'label' => 'Awaiting User'],
                    ['key' => 'escalated', 'label' => 'Escalated'],
                    ['key' => 'resolved', 'label' => 'Resolved'],
                    ['key' => 'closed', 'label' => 'Closed'],
                ] as $filter)
                    <button @click="statusFilter = '{{ $filter['key'] }}'" :class="statusFilter === '{{ $filter['key'] }}' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-slate-800'"
                            class="shrink-0 rounded-md px-2.5 py-1.5 text-[11px] font-bold transition">{{ $filter['label'] }}</button>
                @endforeach
            </div>
        </div>

        <div class="space-y-3">
            <template x-for="request in filteredRequests()" :key="request.reference">
                <a :href="'/support/' + request.reference" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <code class="text-[11px] font-extrabold text-blue-600" x-text="request.reference"></code>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold ring-1 ring-inset" :class="badge(request.status).classes" x-text="badge(request.status).label"></span>
                        </div>
                        <div class="mt-1 truncate text-xs font-extrabold text-slate-900" x-text="request.subject"></div>
                        <div class="mt-0.5 flex flex-wrap items-center gap-2 text-[11px] font-medium text-slate-400">
                            <span x-text="request.category"></span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>Updated <span x-text="request.lastUpdate"></span></span>
                        </div>
                    </div>
                    <span class="shrink-0 text-slate-300">
                        @svg('heroicon-o-chevron-right', 'h-5 w-5')
                    </span>
                </a>
            </template>
        </div>
        <div x-show="filteredRequests().length === 0" x-cloak class="rounded-2xl border border-slate-200 bg-white py-14 text-center shadow-sm">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                @svg('heroicon-o-inbox', 'h-6 w-6')
            </span>
            <p class="mt-3 text-xs font-bold text-slate-500">No requests match this filter.</p>
        </div>
    </div>

    {{-- 7. Live support + Articles --}}
    <div class="mt-8 grid gap-4 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-extrabold text-slate-900">Live Support</h2>
            <div class="mt-3 flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 ring-1 ring-inset ring-emerald-200">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-emerald-700">Our team is online now · {{ $contact['liveSupportHours'] }}</span>
            </div>
            <p class="mt-3 text-xs font-medium leading-relaxed text-slate-500">Prefer a quick conversation? Start a live chat with our support team for immediate assistance.</p>
            <button class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                @svg('heroicon-o-chat-bubble-left-right', 'h-4 w-4')
                Start Live Chat
            </button>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">Help Articles & FAQs</h2>
                <a href="#" @click.prevent class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View All Articles</a>
            </div>
            <ul class="divide-y divide-slate-50 px-5">
                @foreach ($articles as $article)
                    <li>
                        <a href="#" @click.prevent class="flex items-center justify-between gap-3 py-3 transition hover:bg-slate-50">
                            <span class="min-w-0">
                                <span class="block truncate text-xs font-bold text-slate-800">{{ $article['title'] }}</span>
                                <span class="block truncate text-[11px] font-medium text-slate-400">{{ $article['excerpt'] }}</span>
                            </span>
                            <span class="shrink-0">
                                <span class="mr-2 inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-slate-500">{{ $article['category'] }}</span>
                                @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-300')
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- 8. Contact + schedule --}}
    <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ $contact['whatsapp'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
            </span>
            <span class="min-w-0">
                <span class="block text-xs font-extrabold text-slate-900">WhatsApp</span>
                <span class="block truncate text-[11px] font-medium text-slate-400">Chat with support 24/7</span>
            </span>
        </a>
        <a href="{{ $contact['telegram'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-sky-300 hover:shadow-md">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11.94 2a9.94 9.94 0 1 0 0 19.88 9.94 9.94 0 0 0 0-19.88zm4.64 6.89-1.65 7.78c-.12.55-.45.68-.91.42l-2.51-1.85-1.21 1.16c-.13.13-.25.25-.5.25l.18-2.55 4.64-4.19c.2-.18-.04-.28-.31-.1l-5.73 3.6-2.47-.77c-.54-.17-.55-.54.11-.8l9.65-3.72c.45-.17.84.11.7.77z"/></svg>
            </span>
            <span class="min-w-0">
                <span class="block text-xs font-extrabold text-slate-900">Telegram</span>
                <span class="block truncate text-[11px] font-medium text-slate-400">@avc_support</span>
            </span>
        </a>
        <a href="mailto:{{ $contact['email'] }}" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                @svg('heroicon-o-envelope', 'h-5 w-5')
            </span>
            <span class="min-w-0">
                <span class="block text-xs font-extrabold text-slate-900">Email Support</span>
                <span class="block truncate text-[11px] font-medium text-slate-400">{{ $contact['email'] }}</span>
            </span>
        </a>
        <a href="tel:{{ $contact['phone'] }}" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-100 text-violet-600">
                @svg('heroicon-o-phone', 'h-5 w-5')
            </span>
            <span class="min-w-0">
                <span class="block text-xs font-extrabold text-slate-900">Support Phone</span>
                <span class="block truncate text-[11px] font-medium text-slate-400">{{ $contact['phone'] }}</span>
            </span>
        </a>
    </div>

    {{-- 9. Schedule + report --}}
    <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div class="flex flex-col items-start gap-3 rounded-2xl bg-gradient-to-br from-[#0f1e3d] to-blue-900 p-5 text-white shadow-lg sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-blue-300">
                    @svg('heroicon-o-calendar', 'h-5 w-5')
                </span>
                <div>
                    <div class="text-sm font-extrabold">Schedule a Meeting</div>
                    <p class="mt-0.5 text-[11px] font-medium text-blue-200">Book a call with your account manager or the finance team.</p>
                </div>
            </div>
            <a href="#" @click.prevent class="shrink-0 rounded-lg bg-white px-4 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-50">Choose a Time</a>
        </div>
        <div class="flex flex-col items-start gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                    @svg('heroicon-o-flag', 'h-5 w-5')
                </span>
                <div>
                    <div class="text-sm font-extrabold text-slate-900">Report an Issue</div>
                    <p class="mt-0.5 text-[11px] font-medium text-slate-500">Found a bug or suspicious activity? Let us know immediately.</p>
                </div>
            </div>
            <a href="#" @click.prevent="selectCategory('security', 'Security & Reports')" class="shrink-0 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-100">Report Now</a>
        </div>
    </div>
</div>

<script>
    function supportEngine(requests) {
        return {
            requests,
            submitted: false,
            reference: '',
            statusFilter: 'all',
            statusMap: {
                open: { label: 'Open', classes: 'bg-blue-50 text-blue-700 ring-blue-200' },
                in_progress: { label: 'In Progress', classes: 'bg-indigo-50 text-indigo-700 ring-indigo-200' },
                awaiting_user: { label: 'Awaiting User', classes: 'bg-amber-50 text-amber-700 ring-amber-200' },
                escalated: { label: 'Escalated', classes: 'bg-rose-50 text-rose-700 ring-rose-200' },
                resolved: { label: 'Resolved', classes: 'bg-emerald-50 text-emerald-700 ring-emerald-200' },
                closed: { label: 'Closed', classes: 'bg-slate-100 text-slate-600 ring-slate-200' },
            },
            form: {
                category: '',
                subject: '',
                description: '',
                related: '',
            },
            badge(status) {
                return this.statusMap[status] || this.statusMap.open;
            },
            selectCategory(key, label) {
                this.form.category = key;
                document.getElementById('support-form')?.scrollIntoView({ behavior: 'smooth' });
            },
            openForm() {
                document.getElementById('support-form')?.scrollIntoView({ behavior: 'smooth' });
            },
            submitRequest() {
                this.reference = 'AVC-SUP-' + new Date().getFullYear() + '-' + String(Math.floor(100000 + Math.random() * 900000));
                this.submitted = true;
            },
            filteredRequests() {
                if (this.statusFilter === 'all') return this.requests;
                return this.requests.filter((r) => r.status === this.statusFilter);
            },
        };
    }
</script>
@endsection
