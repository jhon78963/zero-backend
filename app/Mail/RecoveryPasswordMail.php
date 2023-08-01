<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoveryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($password_default)
    {
        $this->password_default = $password_default;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Restablezca la contraseña solicitada de su cuenta',
        );
    }

    public function content()
    {
        return new Content(
            view: 'email.forgot-password',
        );
    }

    public function attachments()
    {
        return [];
    }
}
