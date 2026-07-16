<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

//Mengirim berbagai notifikasi umum seperti pengajuan disetujui, pencairan saldo, pengingat jatuh tempo, dan pembayaran terlambat.
class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $judul;
    public $pesan;
    public $nama;

    public function __construct($nama, $judul, $pesan)
    {
        $this->nama = $nama;
        $this->judul = $judul;
        $this->pesan = $pesan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}