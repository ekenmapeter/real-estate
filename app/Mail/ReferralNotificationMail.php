<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $referrer,
        public User $referredUser,
        public float $bonusAmount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Referral: ' . $this->referredUser->name . ' joined via ' . $this->referrer->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-notification',
        );
    }
}
