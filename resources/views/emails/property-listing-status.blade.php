@extends('emails.layout')
@section('subject', 'Listing ' . $property->ref() . ': ' . $property->statusLabel())
@section('heading', 'Listing Update: ' . $property->statusLabel())
@section('content')
    <p style="margin:0 0 16px; font-size:15px; color:#1e293b; line-height:1.6;">Hello <strong style="color:#0f172a;">{{ $property->owner?->name ?? 'there' }}</strong>,</p>
    <p style="margin:0 0 20px; font-size:15px; color:#475569; line-height:1.6;">{{ $statusMessage }}</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:20px;">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Listing Reference</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $property->ref() }}</span></td></tr>
                    <tr><td style="padding-bottom:10px;"><span style="font-size:13px; color:#64748b;">Property</span></td><td style="padding-bottom:10px; text-align:right;"><span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $property->title }}</span></td></tr>
                    <tr><td style="padding-bottom:0;"><span style="font-size:13px; color:#64748b;">Status</span></td><td style="padding-bottom:0; text-align:right;"><span style="font-size:14px; font-weight:700; color:#16a34a;">{{ $property->statusLabel() }}</span></td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if($property->admin_note)
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px; background:#fffbeb; border-radius:12px; padding:16px;">
            <tr><td style="font-size:14px; color:#92400e; line-height:1.6;"><strong>Admin note:</strong> {{ $property->admin_note }}</td></tr>
        </table>
    @endif

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding-top:24px;"><a href="{{ url('/my-properties') }}" style="display:inline-block; background:linear-gradient(135deg,#0B1F3A,#1e4a8a); color:#fff; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; text-decoration:none;">View My Listings</a></td></tr>
    </table>
@endsection
