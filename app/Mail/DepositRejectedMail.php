<?php

namespace App\Mail;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepositRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Deposit $deposit) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit Rejected – '.$this->deposit->deposit_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit-rejected',
        );
    }
}
