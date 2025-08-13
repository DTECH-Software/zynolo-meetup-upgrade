<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;



class ActivationEmail extends Mailable
{

    use Queueable, SerializesModels;

    public $activationCode;
    public $subscription;

    /**
     * Create a new message instance.
     */
    public function __construct($activationCode, $subscription)
    {
        $this->activationCode = $activationCode;
        $this->subscription = $subscription;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activation Email',
        );
    }

    public function build()
    {
        return $this->view('emails.activation')
            ->subject('Activate Your Subscription')
            ->with([
                'activationCode' => $this->activationCode,
                'subscriptionId' => $this->subscription->id,
                'activationUrl' => route('subscriptions.activate', ['code' => $this->activationCode, 'subscription' => $this->subscription->id]),
            ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.subscription.subscription_activation',
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
