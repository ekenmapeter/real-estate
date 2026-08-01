@extends('emails.layout')
@section('subject', 'Project Investment Confirmed')
@section('heading', 'Project Investment Confirmed!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $investment->user->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Congratulations! Your investment in the project below has been placed successfully.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Project</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $investment->project->title }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Location</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $investment->project->location }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Amount Invested</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:16px; font-weight:700; color:#2563eb;">${{ number_format($investment->amount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Expected Return ({{ $investment->project->expected_return_percentage }}%)</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#16a34a;">${{ number_format($investment->expected_roi_amount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#16a34a;">Active</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding-top:24px;"><a href="{{ url('/dashboard') }}#my_investments" style="display:inline-block; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; text-decoration:none;">View My Investments</a></td></tr>
    </table>
@endsection
