<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $passwordBaru;

    public function __construct($passwordBaru)
    {
        $this->passwordBaru = $passwordBaru;
    }

    public function build()
    {
        return $this
            ->subject('Reset Password SIMPAN')
            ->view('emails.reset-password');
    }
}