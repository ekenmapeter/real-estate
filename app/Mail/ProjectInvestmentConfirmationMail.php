<?php

namespace App\Mail;

use App\Models\ProjectInvestment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectInvestmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProjectInvestment $investment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Project Investment Confirmed – '.$this->investment->project->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-investment-confirmed',
        );
    }
}
