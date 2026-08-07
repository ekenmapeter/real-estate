@php
    $doc = $document;
    $statusColor = ['#f0fdf4', '#16a34a', 'active'];
    $statusMap = [
        'active' => '#16a34a', 'completed' => '#16a34a', 'new' => '#2563eb',
        'verified' => '#0d9488', 'pending' => '#d97706', 'archived' => '#64748b', 'rejected' => '#dc2626',
    ];
    $statusHex = $statusMap[$doc->status] ?? '#64748b';
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { margin: 0; padding: 0; color: #1e293b; font-size: 11px; line-height: 1.55; }
        .header { border-bottom: 3px solid #0B1F3A; padding-bottom: 14px; margin-bottom: 22px; }
        .header h1 { font-size: 20px; margin: 0; color: #0B1F3A; }
        .header .sub { color: #64748b; font-size: 10px; margin-top: 3px; }
        .ref-box { text-align: right; }
        .ref-box .ref { font-weight: bold; font-size: 13px; color: #2563eb; }
        .ref-box .cat { font-size: 10px; color: #64748b; }
        .title { font-size: 16px; font-weight: bold; color: #0B1F3A; margin: 0 0 4px; }
        .meta-table { width: 100%; border-collapse: collapse; margin: 14px 0; }
        .meta-table td { padding: 8px 12px; border-bottom: 1px solid #eef2f7; }
        .meta-table td.k { color: #64748b; width: 38%; font-size: 10px; }
        .meta-table td.v { font-weight: bold; color: #0f172a; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin: 12px 0; }
        .status-pill { display: inline-block; padding: 3px 12px; border-radius: 12px; font-weight: bold; font-size: 10px; color: #ffffff; background: {{ $statusHex }}; }
        .verification { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin-top: 18px; font-size: 10px; color: #166534; }
        .footer { position: fixed; bottom: -24px; left: 0; right: 0; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px; }
        .amount { font-size: 16px; font-weight: bold; color: #2563eb; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th { background: #f1f5f9; text-align: left; padding: 8px; font-size: 10px; text-transform: uppercase; color: #475569; }
        .table td { padding: 8px; border-bottom: 1px solid #eef2f7; font-size: 10px; }
        .sign { margin-top: 46px; font-size: 10px; color: #475569; }
        .sign .line { border-top: 1px solid #cbd5e1; width: 220px; padding-top: 6px; }
    </style>
</head>
<body>
    <table class="header" width="100%">
        <tr>
            <td>
                <h1>{{ site_name() }} — {{ ucwords(str_replace('_', ' ', $doc->category)) }}</h1>
                <div class="sub">{{ $doc->title }}</div>
            </td>
            <td class="ref-box">
                <div class="ref">{{ $doc->reference }}</div>
                <div class="cat">Issued: {{ \Illuminate\Support\Carbon::parse($doc->issued_at)->format('M d, Y H:i') }}</div>
            </td>
        </tr>
    </table>

    @yield('doc_content')

    <div class="verification">
        <strong>Digital Verification</strong> — This document was issued electronically by {{ site_name() }}
        and is authentic. Verify its integrity using the reference <strong>{{ $doc->reference }}</strong>.
        Status: <span class="status-pill">{{ ucfirst($doc->status) }}</span>
    </div>

    <div class="footer">
        {{ site_name() }} · Generated {{ now()->format('M d, Y H:i') }} · {{ $doc->reference }} · This document is for the account of {{ $user->name }}
    </div>
</body>
</html>
