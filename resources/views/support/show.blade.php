@extends('layouts.account')

@section('title', $supportRequest['reference'] . ' | Support & Help Center | ' . site_name())

@php
    $statusMap = [
        'open' => ['label' => 'Open', 'classes' => 'bg-blue-50 text-blue-700 ring-blue-200'],
        'in_progress' => ['label' => 'In Progress', 'classes' => 'bg-indigo-50 text-indigo-700 ring-indigo-200'],
        'awaiting_user' => ['label' => 'Awaiting User', 'classes' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'escalated' => ['label' => 'Escalated', 'classes' => 'bg-rose-50 text-rose-700 ring-rose-200'],
        'resolved' => ['label' => 'Resolved', 'classes' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'closed' => ['label' => 'Closed', 'classes' => 'bg-slate-100 text-slate-600 ring-slate-200'],
    ];
    $badge = $statusMap[$supportRequest['status']] ?? $statusMap['open'];
@endphp

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- Back link + header --}}
    <a href="{{ route('support.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition hover:text-blue-600">
        @svg('heroicon-o-arrow-left', 'h-4 w-4')
        Back to Support Center
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <code class="text-sm font-extrabold text-blue-600">{{ $supportRequest['reference'] }}</code>
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $badge['classes'] }}">
                    {{ $badge['label'] }}
                </span>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500 ring-1 ring-inset ring-slate-200">
                    Priority: {{ $supportRequest['priority'] }}
                </span>
            </div>
            <h1 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900">{{ $supportRequest['subject'] }}</h1>
            <p class="mt-1 text-xs font-medium text-slate-500">{{ $supportRequest['category'] }} · Updated {{ $supportRequest['lastUpdate'] }}</p>
        </div>
        <a href="#" @click.prevent class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            @svg('heroicon-o-paper-clip', 'h-4 w-4')
            Add Attachment
        </a>
    </div>

    <div class="grid gap-4 xl:grid-cols-3">

        {{-- Conversation --}}
        <div class="flex flex-col rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2" x-data="{ reply: '', sent: [] }">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-extrabold text-slate-900">Conversation</h2>
                <p class="text-[11px] font-medium text-slate-400">Your account manager replies within this thread.</p>
            </div>

            <div class="flex-1 space-y-4 px-5 py-5">
                @foreach ($supportRequest['messages'] as $message)
                    @if ($message['from'] === 'user')
                        <div class="flex justify-end">
                            <div class="max-w-[85%] rounded-2xl rounded-br-md bg-blue-600 px-4 py-3 text-white shadow-sm sm:max-w-[75%]">
                                <p class="text-xs font-medium leading-relaxed">{{ $message['body'] }}</p>
                                <p class="mt-1.5 text-right text-[10px] font-semibold text-blue-200">{{ $message['at'] }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-[10px] font-bold text-white">
                                {{ $accountManager['initials'] }}
                            </span>
                            <div class="max-w-[85%] rounded-2xl rounded-bl-md border border-slate-200 bg-slate-50 px-4 py-3 shadow-sm sm:max-w-[75%]">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">{{ $accountManager['name'] }} · Support</p>
                                <p class="mt-1 text-xs font-medium leading-relaxed text-slate-700">{{ $message['body'] }}</p>
                                <p class="mt-1.5 text-[10px] font-semibold text-slate-400">{{ $message['at'] }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach

                <template x-for="(msg, index) in sent" :key="index">
                    <div class="flex justify-end">
                        <div class="max-w-[85%] rounded-2xl rounded-br-md bg-blue-600 px-4 py-3 text-white shadow-sm sm:max-w-[75%]">
                            <p class="text-xs font-medium leading-relaxed" x-text="msg.body"></p>
                            <p class="mt-1.5 text-right text-[10px] font-semibold text-blue-200" x-text="msg.at"></p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Reply box --}}
            <div class="border-t border-slate-100 px-5 py-4">
                <textarea x-model="reply" rows="3" placeholder="Write a reply..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                <div class="mt-3 flex items-center justify-between gap-3">
                    <p class="text-[10px] font-medium text-slate-400">Support team replies within 24 hours on business days.</p>
                    <button @click="if (reply.trim()) { sent.push({ at: 'Just now', body: reply.trim() }); reply = ''; }"
                            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                        @svg('heroicon-o-paper-airplane', 'h-4 w-4')
                        Send Reply
                    </button>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Status card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900">Status History</h2>
                <ol class="mt-4 space-y-0">
                    @foreach ($supportRequest['history'] as $index => $entry)
                        <li class="relative flex gap-3 pb-5 last:pb-0">
                            @if (! $loop->last)
                                <span class="absolute left-[9px] top-5 h-full w-0.5 bg-slate-100"></span>
                            @endif
                            <span class="relative mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full ring-4 ring-white
                                {{ $loop->first ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                                @if ($loop->first)
                                    @svg('heroicon-o-check', 'h-3 w-3')
                                @else
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                @endif
                            </span>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-800">{{ $entry['label'] }}</div>
                                <div class="text-[10px] font-semibold text-slate-400">{{ $entry['at'] }} · {{ $entry['status'] }}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Attachments --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900">Attachments</h2>
                @if (count($supportRequest['attachments']) > 0)
                    <ul class="mt-3 space-y-2">
                        @foreach ($supportRequest['attachments'] as $attachment)
                            <li class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-blue-600 shadow-sm">
                                    @svg('heroicon-o-document-text', 'h-4 w-4')
                                </span>
                                <span class="min-w-0 flex-1 truncate text-xs font-bold text-slate-700">{{ $attachment }}</span>
                                <a href="#" @click.prevent class="shrink-0 text-slate-400 transition hover:text-blue-600" title="Download">
                                    @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-3 text-[11px] font-medium text-slate-400">No attachments on this request.</p>
                @endif
            </div>

            {{-- Account manager --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900">Your Account Manager</h2>
                <div class="mt-3 flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-xs font-bold text-white shadow">
                        {{ $accountManager['initials'] }}
                    </span>
                    <div class="min-w-0">
                        <div class="text-xs font-extrabold text-slate-900">{{ $accountManager['name'] }}</div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>{{ $accountManager['availability'] }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('support.index') }}" class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700">
                    @svg('heroicon-o-chat-bubble-left-right', 'h-4 w-4')
                    Message Account Manager
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
