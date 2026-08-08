@props([
    'name' => '',
    'country' => '',
    'flag' => '🏳️',
    'status' => 'registered',
    'investment' => 0.00,
    'commission' => 0.00,
    'date' => '',
])

@php
    $statusMap = [
        'visited' => ['label' => 'Visited', 'class' => 'bg-slate-100 text-slate-600 ring-slate-200'],
        'qualified_lead' => ['label' => 'Qualified Lead', 'class' => 'bg-indigo-50 text-indigo-700 ring-indigo-200'],
        'registered' => ['label' => 'Registered', 'class' => 'bg-slate-100 text-slate-600 ring-slate-200'],
        'email_verified' => ['label' => 'Email Verified', 'class' => 'bg-cyan-50 text-cyan-700 ring-cyan-200'],
        'pending_kyc' => ['label' => 'Pending KYC', 'class' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'kyc_approved' => ['label' => 'KYC Approved', 'class' => 'bg-teal-50 text-teal-700 ring-teal-200'],
        'verified' => ['label' => 'Verified', 'class' => 'bg-teal-50 text-teal-700 ring-teal-200'],
        'deposit_pending' => ['label' => 'Deposit Pending', 'class' => 'bg-orange-50 text-orange-700 ring-orange-200'],
        'deposit_confirmed' => ['label' => 'Deposit Confirmed', 'class' => 'bg-blue-50 text-blue-700 ring-blue-200'],
        'investment_completed' => ['label' => 'Investment Completed', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'commission_pending' => ['label' => 'Commission Pending', 'class' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'commission_approved' => ['label' => 'Commission Approved', 'class' => 'bg-blue-50 text-blue-700 ring-blue-200'],
        'commission_paid' => ['label' => 'Commission Paid', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-rose-50 text-rose-600 ring-rose-200'],
        'disqualified' => ['label' => 'Disqualified', 'class' => 'bg-slate-100 text-slate-500 ring-slate-200'],
    ];
    $badge = $statusMap[$status] ?? $statusMap['registered'];
    $parts = array_values(array_filter(preg_split('/\s+/', trim($name))));
    $initials = strtoupper(substr($parts[0] ?? '?', 0, 1)) . (isset($parts[1]) ? strtoupper(substr($parts[1], 0, 1)) : '');
@endphp

<tr {{ $attributes->merge(['class' => 'border-b border-slate-100 last:border-0']) }}>
    <td class="px-4 py-3">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-xs font-bold text-white">
                {{ $initials }}
            </span>
            <div class="min-w-0">
                <div class="truncate text-xs font-bold text-slate-900">{{ $name }}</div>
                <div class="flex items-center gap-1 truncate text-[11px] font-medium text-slate-500">
                    <span>{{ $flag }}</span>{{ $country }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-4 py-3">
        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $badge['class'] }}">
            {{ $badge['label'] }}
        </span>
    </td>
    <td class="px-4 py-3 text-right text-xs font-bold text-slate-900">${{ number_format($investment, 2) }}</td>
    <td class="px-4 py-3 text-right text-xs font-bold text-emerald-600">${{ number_format($commission, 2) }}</td>
    <td class="px-4 py-3 text-right text-[11px] font-medium text-slate-500">{{ $date }}</td>
</tr>
