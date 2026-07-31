@extends('emails.layout')
@section('subject', 'You earned a referral bonus!')
@section('heading', 'Referral Bonus Credited!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $referrer->name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">Great news! Someone used your referral code to join Radiant Dream Realty. You've earned a referral bonus!</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">New Member</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $referredUser->name }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Email</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $referredUser->email }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Bonus Amount</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:16px; font-weight:700; color:#10b981;">+${{ number_format($bonusAmount, 2) }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">New Wallet Balance</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">${{ number_format($referrer->wallet_balance, 2) }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#64748b; line-height:1.5;">Share your referral code with more friends to keep earning. Your code: <strong style="color:#2563eb;">{{ $referrer->affiliate_code }}</strong></p>
    <p style="margin:12px 0 0; font-size:14px; color:#64748b; line-height:1.5;">Keep investing and growing with Radiant Dream Realty!</p>
@endsection
