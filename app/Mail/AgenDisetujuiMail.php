<?php

namespace App\Mail;

use App\Models\Agen;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgenDisetujuiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agen;
    public $user;

    public function __construct(Agen $agen, User $user)
    {
        $this->agen = $agen;
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject('Pendaftaran Agen Disetujui')
            ->view('emails.agen-disetujui');
    }
}