<?php

namespace App\Mail;

use App\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CardApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Card $card) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Crypto Card Has Been Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.card-approved',
        );
    }
}
