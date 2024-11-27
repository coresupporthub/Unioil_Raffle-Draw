<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntryCouponDouble extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    private string $code1;
    private string $code2;
    public function __construct(string $code1, string $code2)
    {
        $this->code1 = $code1;
        $this->code2 = $code2;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Raffle Entry Coupons - 2 Entries',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Admin.email.double_coupon',
            with: ['code1'=> $this->code1, 'code2'=> $this->code2],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
