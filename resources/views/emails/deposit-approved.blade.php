@extends('emails.layout')
@section('subject', 'Deposit Approved')
@section('heading', 'Deposit Approved!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $deposit->user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Your deposit has been approved and the funds have been credited to your wallet.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Reference</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $deposit->deposit_code }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Amount Credited</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:16px; font-weight:700; color:#16a34a;">+${{ number_format($deposit->amount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#16a34a;">Completed</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding-top:24px;"><a href="{{ url('/dashboard') }}" style="display:inline-block; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; text-decoration:none;">View Wallet</a></td></tr>
    </table>
@endsection
