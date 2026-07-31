<?php

namespace App\Mail;

use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Investment $investment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Investment Confirmed – '.$this->investment->property->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.investment-confirmed',
        );
    }
}
