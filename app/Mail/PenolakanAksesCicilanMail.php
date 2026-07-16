<?php

namespace App\Mail;

use App\Models\Agen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PenolakanAksesCicilanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agen;

    public function __construct($agen)
    {
        $this->agen = $agen;
    }

    public function build()
    {
        return $this->subject('Permohonan Fasilitas Cicilan Ditolak')
            ->view('emails.penolakan-akses-cicilan');
    }
}