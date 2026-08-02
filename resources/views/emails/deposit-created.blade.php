@extends('emails.layout')
@section('subject', 'Deposit Request Received')
@section('heading', 'Deposit Request Received')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $deposit->user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Your deposit request has been received and is being processed.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Reference</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $deposit->deposit_code }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Amount</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#2563eb;">${{ number_format($deposit->amount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Method</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ ucfirst($deposit->payment_method) }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#f59e0b;">Pending</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">You will receive another notification once your deposit has been reviewed.</p>
    <p style="margin:12px 0 0; font-size:14px; color:#64748b; line-height:1.5;">Thank you for choosing radiantdreamrealty.</p>
@endsection
