<?php

namespace App\Mail;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyListingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Property $property, public string $statusMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Listing ' . $this->property->ref() . ': ' . $this->property->statusLabel(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.property-listing-status',
        );
    }
}
