@extends('emails.layout')
@section('subject', 'KYC Documents Submitted Successfully')
@section('heading', 'KYC Documents Received')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">We have received your KYC documents. Our team will review your submission and update you once the verification is complete.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Account ID</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $user->account_id }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#f59e0b;">Under Review</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">This process usually takes 1-2 business days. You will receive an email once your identity has been verified.</p>
    <p style="margin:12px 0 0; font-size:14px; color:#64748b; line-height:1.5;">Thank you for choosing Radiant Dream Realty.</p>
@endsection
