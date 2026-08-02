@extends('emails.layout')
@section('subject', 'Crypto Card Application Update')
@section('heading', 'Crypto Card Application')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $card->user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Unfortunately, your Crypto Card application could not be approved at this time.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:8px;"><span style="font-size:13px; color:#dc2626; font-weight:700;">Reason:</span></td></tr>
                    <tr><td><span style="font-size:14px; color:#7f1d1d; line-height:1.6;">{{ $reason ?: 'Your application did not meet our verification requirements. Please contact support for more information.' }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">You are welcome to submit a new application once the issue is resolved.</p>
@endsection
