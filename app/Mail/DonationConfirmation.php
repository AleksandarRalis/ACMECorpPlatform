<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $donation;
    public $campaign;
    public $donor;

    /**
     * Create a new message instance.
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
        $this->campaign = $donation->campaign;
        $this->donor = $donation->createdBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Donation - ' . $this->campaign->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-confirmation',
            with: [
                'donation' => $this->donation,
                'campaign' => $this->campaign,
                'donor' => $this->donor,
            ],
        );
    }
} 