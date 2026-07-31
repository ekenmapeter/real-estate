@extends('emails.layout')
@section('subject', 'Withdrawal Request Received')
@section('heading', 'Withdrawal Request Received')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $withdrawal->user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Your withdrawal request has been received and is pending review.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Reference</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $withdrawal->withdrawal_code ?? 'WID-'.str_pad($withdrawal->id,6,'0',STR_PAD_LEFT) }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Amount</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#2563eb;">${{ number_format($withdrawal->amount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Method</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ ucfirst(str_replace('_', ' ', $withdrawal->withdrawal_method)) }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#f59e0b;">Pending</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">We will notify you once your withdrawal has been processed. This typically takes 1–3 business days.</p>
@endsection
