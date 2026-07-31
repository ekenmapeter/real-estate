<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FundReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $sender,
        public User $recipient,
        public float $amount,
        public string $reference,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Funds Received – $'.number_format($this->amount, 2),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fund-received',
        );
    }
}
