@extends('emails.layout')
@section('subject', 'KYC Verification Approved')
@section('heading', 'KYC Verified!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Your identity verification (KYC) has been approved. You now have full access to all features including higher deposit limits.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#16a34a;"><i class="bi bi-patch-check-fill"></i> Verified</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding-top:24px;"><a href="{{ route('profile.settings') }}" style="display:inline-block; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; text-decoration:none;">View Profile</a></td></tr>
    </table>
@endsection
