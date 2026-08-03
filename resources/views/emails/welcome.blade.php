@extends('emails.layout')
@section('subject', 'Welcome to ' . site_name())
@section('heading', 'Welcome to ' . site_name() . '!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,</p>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.6;">Thank you for joining {{ site_name() }}! Your account has been created successfully and you're now ready to start investing in premium real estate opportunities.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px; margin:0 0 20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:12px;"><span style="font-size:13px; color:#64748b;">Account ID</span></td><td style="padding-bottom:12px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $user->account_id ?? 'RDR-'.str_pad($user->id,6,'0',STR_PAD_LEFT) }}</span></td></tr>
                    <tr><td style="padding-bottom:12px;"><span style="font-size:13px; color:#64748b;">Email</span></td><td style="padding-bottom:12px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $user->email }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">AVC Balance</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#2563eb;">{{ format_avc($user->wallet_balance ?? 0) }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 24px; font-size:15px; color:#475569; line-height:1.6;">Browse our available properties and start building your real estate portfolio today.</p>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center"><a href="{{ url('/dashboard') }}" style="display:inline-block; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; text-decoration:none;">Go to Dashboard</a></td></tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">Happy investing!<br><strong style="color:#0f172a;">The {{ site_name() }} Team</strong></p>
@endsection
