@extends('layouts.affiliate')

@section('title', $section['label'] . ' | Affiliate Center | ' . site_name())

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8">

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $section['label'] }}</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Affiliate Center · {{ $affiliate['level'] }}</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            {{ $affiliate['status'] }}
        </span>
    </div>

    <div class="flex flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-20 text-center shadow-sm">
        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
            @svg($section['icon'], 'h-8 w-8')
        </span>
        <h2 class="mt-5 text-lg font-extrabold text-slate-900">{{ $section['label'] }} is coming soon</h2>
        <p class="mt-2 max-w-md text-sm font-medium text-slate-500">
            This section of the Affiliate Center is part of the next release. Your data will appear here automatically once it is enabled.
        </p>
        <div class="mt-6 flex items-center gap-3">
            <a href="{{ route('affiliate.center') }}" class="rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                Back to Overview
            </a>
            <a href="{{ route('affiliate.section', 'referral-link') }}" class="rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                Share Your Referral Link
            </a>
        </div>
    </div>
</div>
@endsection
