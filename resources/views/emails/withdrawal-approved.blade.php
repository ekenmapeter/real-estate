@extends('emails.layout')
@section('subject', 'Withdrawal Approved')
@section('heading', 'Withdrawal Approved!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $withdrawal->user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Your withdrawal has been approved and is being processed. Funds will be sent to your account shortly.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Amount</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:16px; font-weight:700; color:#16a34a;">${{ number_format($withdrawal->amount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Method</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ ucfirst(str_replace('_', ' ', $withdrawal->withdrawal_method)) }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#16a34a;">Approved</span></td></tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
