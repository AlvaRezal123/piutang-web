<?php

namespace App\Mail;

use App\Models\Agen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgenDisetujuiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agen;

    public function __construct(Agen $agen)
    {
        $this->agen = $agen;
    }

    public function build()
    {
        return $this
            ->subject('Pendaftaran Agen Disetujui')
            ->view('emails.agen-disetujui');
    }
}