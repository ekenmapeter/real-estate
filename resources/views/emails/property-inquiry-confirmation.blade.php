@extends('emails.layout')
@section('subject', 'Inquiry Submitted — ' . $inquiry->property->title)
@section('heading', 'Inquiry Submitted!')
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $inquiry->full_name }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">
        Your <strong>{{ $inquiry->typeLabel() }}</strong> for <strong style="color:#0f172a;">{{ $inquiry->property->title }}</strong> has been submitted to Aurevia Property Support and is now awaiting admin review.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Request ID</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $inquiry->inquiry_number }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Property</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $inquiry->property->title }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Reference</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $inquiry->property->ref() }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#d97706;">Awaiting Admin Review</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Preferred Channel</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:600; color:#0f172a; text-transform:capitalize;">{{ $inquiry->preferred_channel }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; color:#475569; line-height:1.6;">
        Our support team verifies both parties before connecting you. We will contact you shortly on {{ $inquiry->preferred_channel === 'whatsapp' ? 'WhatsApp' : 'Telegram' }} with updates.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding-top:24px;"><a href="{{ url('/properties') }}" style="display:inline-block; background:linear-gradient(135deg,#0B1F3A,#1e4a8a); color:#fff; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; text-decoration:none;">Browse More Properties</a></td></tr>
    </table>
@endsection
