<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use SerializesModels;

    public string $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('DigiDate – Wachtwoord resetten')
            ->markdown('emails.password-reset', [
                'url' => $this->resetUrl,
            ]);
    }
}

