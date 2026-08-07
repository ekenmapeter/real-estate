<?php

namespace App\Mail;

use App\Models\PropertyInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PropertyInquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Inquiry ' . $this->inquiry->inquiry_number . ' Submitted — ' . $this->inquiry->property->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.property-inquiry-confirmation',
        );
    }
}
