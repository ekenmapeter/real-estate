@extends('layouts.account')

@section('title', 'Profile & Settings | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8" x-data="{ activeTab: 'profile' }">

    {{-- 1. Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Profile & Settings</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Manage your account, security and identity verification.</p>
        </div>
        <a href="#" @click.prevent class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            @svg('heroicon-o-cog-6-tooth', 'h-4 w-4')
            Account Settings
        </a>
    </div>

    {{-- 2. Profile summary card --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex flex-col items-start gap-4 sm:flex-row">
                <div class="relative shrink-0">
                    <span class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-2xl font-extrabold text-white shadow-lg">
                        {{ $profile['initials'] }}
                    </span>
                    <button @click.prevent class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-blue-600 text-white shadow transition hover:bg-blue-700" title="Upload photo">
                        @svg('heroicon-o-camera', 'h-3.5 w-3.5')
                    </button>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-extrabold text-slate-900">{{ $profile['name'] }}</h2>
                        <span class="flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            @svg('heroicon-o-check-circle', 'h-3 w-3') Verified
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $profile['email'] }}</p>
                    <div class="mt-2.5 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-200">
                            Account ID: {{ $profile['accountId'] }}
                            <button x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $profile['accountId'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="text-slate-400 transition hover:text-blue-600" aria-label="Copy account ID">
                                <template x-if="!copied">@svg('heroicon-o-clipboard', 'h-3 w-3')</template>
                                <template x-if="copied">@svg('heroicon-o-check', 'h-3 w-3 text-emerald-500')</template>
                            </button>
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 ring-1 ring-inset ring-amber-200">
                            @svg('heroicon-o-trophy', 'h-3 w-3') Gold Member
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
                            @svg('heroicon-o-shield-check', 'h-3 w-3') Investor
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full shrink-0 lg:w-auto">
                <div class="flex justify-end">
                    <a href="#" @click.prevent class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        @svg('heroicon-o-pencil', 'h-3.5 w-3.5')
                        Edit Profile
                    </a>
                </div>
                <dl class="mt-4 grid w-full grid-cols-2 gap-x-8 gap-y-3 rounded-xl bg-slate-50 p-4 lg:w-96">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Member Since</dt>
                        <dd class="mt-0.5 text-xs font-bold text-slate-900">{{ $profile['memberSince'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Country</dt>
                        <dd class="mt-0.5 text-xs font-bold text-slate-900">{{ $profile['country'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Preferred Currency</dt>
                        <dd class="mt-0.5 text-xs font-bold text-slate-900">{{ $profile['preferredCurrency'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Language</dt>
                        <dd class="mt-0.5 text-xs font-bold text-slate-900">{{ $profile['language'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- 3. Stat cards --}}
    <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-5">
        @foreach ($stats as $stat)
            <x-stat-card :icon="$stat['icon']" :color="$stat['color']" :label="$stat['label']" :value="$stat['value']" :caption="$stat['caption'] ?? null" />
        @endforeach
    </div>

    {{-- 4. Pending request summary --}}
    <div class="mt-4 grid grid-cols-1 gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 shadow-sm sm:grid-cols-3">
        @foreach ($pending as $item)
            @if ($item['href'])
                <a href="{{ $item['href'] }}" class="flex items-center justify-between gap-3 bg-white px-5 py-4 transition hover:bg-slate-50">
            @else
                <div class="flex items-center justify-between gap-3 bg-white px-5 py-4">
            @endif
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $item['label'] }}</div>
                <div class="flex items-center gap-2">
                    @if ($loop->last)
                        <span class="text-sm font-extrabold text-amber-600">{{ $item['value'] }}</span>
                    @else
                        <span class="text-sm font-extrabold text-slate-900">{{ $item['value'] }}</span>
                    @endif
                    @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-300')
                </div>
            @if ($item['href'])
                </a>
            @else
                </div>
            @endif
        @endforeach
    </div>

    {{-- 5. Tab bar --}}
    <div class="no-scrollbar mt-6 flex gap-1 overflow-x-auto border-b border-slate-200 bg-white px-2 shadow-sm sm:rounded-t-2xl">
        @foreach ([
            ['key' => 'profile', 'label' => 'Profile', 'icon' => 'heroicon-o-user'],
            ['key' => 'security', 'label' => 'Security', 'icon' => 'heroicon-o-shield-check'],
            ['key' => 'kyc', 'label' => 'KYC Verification', 'icon' => 'heroicon-o-identification'],
            ['key' => 'notifications', 'label' => 'Notifications', 'icon' => 'heroicon-o-bell'],
            ['key' => 'preferences', 'label' => 'Preferences', 'icon' => 'heroicon-o-adjustments-horizontal'],
            ['key' => 'linked', 'label' => 'Linked Accounts', 'icon' => 'heroicon-o-link'],
        ] as $tab)
            <button @click="activeTab = '{{ $tab['key'] }}'"
                    :class="activeTab === '{{ $tab['key'] }}' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="flex shrink-0 items-center gap-1.5 border-b-2 px-3 py-3 text-xs font-bold transition">
                @svg($tab['icon'], 'h-4 w-4')
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- 6. Profile tab --}}
    <div x-show="activeTab === 'profile'" x-transition class="mt-4 grid gap-4 lg:grid-cols-3">

        {{-- Column 1 --}}
        <div class="space-y-4">
            {{-- Personal Information --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="{ editing: false }">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Personal Information</h3>
                    <button @click="editing = !editing" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">
                        <span x-show="!editing">Edit</span><span x-show="editing" x-cloak>Cancel</span>
                    </button>
                </div>
                <div class="divide-y divide-slate-50 px-5 py-1">
                    <template x-if="!editing">
                        <div>
                            <x-info-row label="Full Name" :value="$profile['name']" />
                            <x-info-row label="Email Address" :value="$profile['email']" />
                            <x-info-row label="Phone Number" value="Not provided" />
                            <x-info-row label="Date of Birth" value="Not provided" />
                            <x-info-row label="Gender" value="Not provided" />
                            <x-info-row label="Nationality" value="Not provided" />
                            <x-info-row label="Country" :value="$profile['country']" />
                            <x-info-row label="Preferred Currency" :value="$profile['preferredCurrency']" />
                            <x-info-row label="Language" :value="$profile['language']" />
                            <x-info-row label="Timezone" :value="$profile['timezone']" />
                        </div>
                    </template>
                    <template x-if="editing" x-cloak>
                        <div class="space-y-3 py-3">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Full Name</label>
                                <input type="text" value="{{ $profile['name'] }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Phone Number</label>
                                <input type="text" placeholder="+1 674 575 4637" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Country</label>
                                    <input type="text" value="{{ $profile['country'] }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Preferred Currency</label>
                                    <select class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option {{ $profile['preferredCurrency'] === 'USD' ? 'selected' : '' }}>USD</option>
                                        <option>EUR</option>
                                        <option>GBP</option>
                                        <option>PHP</option>
                                        <option>NGN</option>
                                        <option>AED</option>
                                    </select>
                                </div>
                            </div>
                            <button @click="editing = false" class="w-full rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                                Save Changes
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="{ editing: false }">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Contact Information</h3>
                    <button @click="editing = !editing" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">
                        <span x-show="!editing">Edit</span><span x-show="editing" x-cloak>Cancel</span>
                    </button>
                </div>
                <div class="divide-y divide-slate-50 px-5 py-1">
                    <template x-if="!editing">
                        <div>
                            <x-info-row label="Telegram Username" value="Not provided" />
                            <x-info-row label="WhatsApp Number" value="Not provided" />
                            <x-info-row label="Alternative Email" value="Not provided" />
                            <x-info-row label="Preferred Contact Method" value="Email" />
                        </div>
                    </template>
                    <template x-if="editing" x-cloak>
                        <div class="space-y-3 py-3">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Telegram Username</label>
                                <input type="text" placeholder="@username" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">WhatsApp Number</label>
                                <input type="text" placeholder="+1 674 575 4637" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Preferred Contact Method</label>
                                <select class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option selected>Email</option>
                                    <option>WhatsApp</option>
                                    <option>Telegram</option>
                                    <option>Phone</option>
                                </select>
                            </div>
                            <button @click="editing = false" class="w-full rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                                Save Changes
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Account Information --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Account Information</h3>
                    <a href="#" @click.prevent class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View All</a>
                </div>
                <div class="divide-y divide-slate-50 px-5 py-1">
                    <x-info-row label="Account Type" value="Standard" />
                    <x-info-row label="Investor ID" :value="$profile['accountId']" :copy="$profile['accountId']" />
                    <x-info-row label="Referral Code" :value="$profile['referralCode']" :copy="$profile['referralCode']" />
                    <x-info-row label="Referral Link" :value="$profile['referralLink']" :copy="$profile['referralLink']" />
                    <x-info-row label="Last Login" :value="$profile['lastLogin']" />
                    <x-info-row label="Last Password Change" :value="$profile['lastPasswordChange']" />
                </div>
            </div>
        </div>

        {{-- Column 2 --}}
        <div class="space-y-4">
            {{-- KYC Verification --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            @svg('heroicon-o-check-circle', 'h-4 w-4')
                        </span>
                        KYC Verification
                    </h3>
                    <button @click="activeTab = 'kyc'" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">View Details</button>
                </div>
                <div class="px-5 py-4">
                    <div class="flex items-start gap-3 rounded-xl bg-amber-50 p-3.5 ring-1 ring-inset ring-amber-200">
                        <span class="shrink-0 text-amber-500">
                            @svg('heroicon-o-exclamation-triangle', 'h-5 w-5')
                        </span>
                        <p class="text-[11px] font-medium leading-relaxed text-amber-800">
                            Complete identity verification to access higher transaction limits, withdrawals, project investments, marketplace services and other protected platform features.
                        </p>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700">Verification Progress</span>
                        <span class="text-xs font-extrabold text-blue-600">{{ $kyc['progress'] }}%</span>
                    </div>
                    <div class="mt-2">
                        <x-progress-bar :progress="$kyc['progress']" />
                    </div>

                    <ul class="mt-4 space-y-2.5">
                        @foreach ($kyc['steps'] as $index => $step)
                            <li class="flex items-center gap-3">
                                @if ($step['status'] === 'done')
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                        @svg('heroicon-o-check', 'h-3.5 w-3.5')
                                    </span>
                                    <span class="text-xs font-bold text-slate-800">{{ $index + 1 }}. {{ $step['title'] }}</span>
                                    <span class="ml-auto text-[10px] font-bold text-emerald-600">{{ $step['note'] }}</span>
                                @elseif ($step['status'] === 'optional')
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-400">{{ $index + 1 }}</span>
                                    <span class="text-xs font-bold text-slate-500">{{ $index + 1 }}. {{ $step['title'] }}</span>
                                    <span class="ml-auto text-[10px] font-bold text-slate-400">{{ $step['note'] }}</span>
                                @else
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-[11px] font-bold text-amber-700">{{ $index + 1 }}</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $index + 1 }}. {{ $step['title'] }}</span>
                                    <span class="ml-auto text-[10px] font-bold text-amber-600">{{ $step['note'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <button @click="activeTab = 'kyc'" class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                        @svg('heroicon-o-cloud-arrow-up', 'h-4 w-4')
                        Submit KYC Documents
                    </button>
                </div>
            </div>

            {{-- Linked Accounts --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Linked Accounts</h3>
                    <button @click="activeTab = 'linked'" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">Manage</button>
                </div>
                <div class="divide-y divide-slate-50 px-5 py-1">
                    @foreach ($linkedAccounts as $account)
                        <x-linked-account-row :provider="$account['provider']" :label="$account['label']" :connected="$account['connected']" :date="$account['date']" />
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Column 3 --}}
        <div class="space-y-4">
            {{-- Security Overview --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Security Overview</h3>
                    <button @click="activeTab = 'security'" class="text-xs font-bold text-blue-600 transition hover:text-blue-800">Manage</button>
                </div>
                <div class="divide-y divide-slate-50 px-5 py-1">
                    @foreach ($security as $item)
                        <a href="#" @click.prevent="activeTab = 'security'" class="flex items-center justify-between gap-3 py-3 transition hover:bg-slate-50">
                            <span class="flex min-w-0 items-center gap-3">
                                <span class="shrink-0 {{ $item['color'] }}">
                                    @svg($item['icon'], 'h-5 w-5')
                                </span>
                                <span class="min-w-0">
                                    <span class="block truncate text-xs font-bold text-slate-800">{{ $item['label'] }}</span>
                                    <span class="block truncate text-[11px] font-medium text-slate-400">{{ $item['note'] }}</span>
                                </span>
                            </span>
                            @svg('heroicon-o-chevron-right', 'h-4 w-4 shrink-0 text-slate-300')
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Quick Actions</h3>
                </div>
                <div class="divide-y divide-slate-50 px-5 py-1">
                    @foreach ($quickActions as $action)
                        <x-quick-action-row :icon="$action['icon']" :label="$action['label']" :icon-color="$action['color']" :destructive="$action['destructive']" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 7. Placeholder tabs --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-3" x-show="activeTab !== 'profile'" x-transition>
        <div class="flex flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm lg:col-span-3">
            <template x-if="activeTab === 'security'">
                <div class="flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">@svg('heroicon-o-shield-check', 'h-8 w-8')</span>
                    <h2 class="mt-5 text-lg font-extrabold text-slate-900">Security</h2>
                    <p class="mt-2 max-w-md text-sm font-medium text-slate-500">Password management, two-factor authentication, active sessions and login activity are part of the next release.</p>
                </div>
            </template>
            <template x-if="activeTab === 'kyc'">
                <div class="flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">@svg('heroicon-o-identification', 'h-8 w-8')</span>
                    <h2 class="mt-5 text-lg font-extrabold text-slate-900">KYC Verification</h2>
                    <p class="mt-2 max-w-md text-sm font-medium text-slate-500">The step-based identity verification flow is part of the next release. Your current verification status is shown in the Profile tab.</p>
                </div>
            </template>
            <template x-if="activeTab === 'notifications'">
                <div class="flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">@svg('heroicon-o-bell', 'h-8 w-8')</span>
                    <h2 class="mt-5 text-lg font-extrabold text-slate-900">Notifications</h2>
                    <p class="mt-2 max-w-md text-sm font-medium text-slate-500">Control which platform notifications you receive and through which channels.</p>
                </div>
            </template>
            <template x-if="activeTab === 'preferences'">
                <div class="flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">@svg('heroicon-o-adjustments-horizontal', 'h-8 w-8')</span>
                    <h2 class="mt-5 text-lg font-extrabold text-slate-900">Preferences</h2>
                    <p class="mt-2 max-w-md text-sm font-medium text-slate-500">Display currency, language, timezone, date and time formats and theme preferences are part of the next release.</p>
                </div>
            </template>
            <template x-if="activeTab === 'linked'">
                <div class="flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">@svg('heroicon-o-link', 'h-8 w-8')</span>
                    <h2 class="mt-5 text-lg font-extrabold text-slate-900">Linked Accounts</h2>
                    <p class="mt-2 max-w-md text-sm font-medium text-slate-500">Connect and manage external accounts, wallets and login providers.</p>
                </div>
            </template>
            <a href="#" @click.prevent="activeTab = 'profile'" class="mt-6 rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                Back to Profile
            </a>
        </div>
    </div>
</div>
@endsection
