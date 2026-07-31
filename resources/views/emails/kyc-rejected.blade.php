@extends('emails.layout')
@section('subject', 'KYC Verification Update')
@section('heading', 'KYC Verification Update')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Unfortunately, your KYC verification could not be approved at this time.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#fef2f2; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#ef4444;">Rejected</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Reason</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $reason }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">You can resubmit your documents from your profile page. Make sure all details are clear and match your registered information.</p>
    <p style="margin:12px 0 0; font-size:14px; color:#64748b; line-height:1.5;">If you need assistance, please contact our support team.</p>
@endsection
