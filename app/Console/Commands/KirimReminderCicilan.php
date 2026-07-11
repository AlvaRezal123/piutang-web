<?php
namespace App\Console\Commands;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;
use App\Models\Notifikasi;
use Carbon\Carbon;
    
#[Signature('app:kirim-reminder-cicilan')]
#[Description('Command description')]
class KirimReminderCicilan extends Command
{
   public function handle()
{
    $cicilan = \App\Models\Cicilan::with(
        'hutang.agen.user'
    )
    ->where('status', 'belum')
    ->get();
    foreach ($cicilan as $c) {
        $user = $c->hutang->agen->user;
        if (!$user) {
            continue;
        }
        $selisih = now()->startOfDay()->diffInDays(
            Carbon::parse(
                $c->tanggal_jatuh_tempo
            )->startOfDay(),
            false
        );
        // H-1
        if ($selisih == 1 && !$c->reminder_h1) {

            Mail::to($user->email)->send(

                new NotificationMail(

                    $user->username,

                    'Pengingat Jatuh Tempo',

                    $c->hutang->metode == 'cash'
                    ?
                    'Besok adalah batas akhir pembayaran hutang sebesar Rp' .
                    number_format(
                        $c->jumlah_cicilan,
                        0,
                        ',',
                        '.'
                    )
                    :
                    'Besok jatuh tempo cicilan ke-' .
                    $c->cicilan_ke .
                    ' sebesar Rp' .
                    number_format(
                        $c->jumlah_cicilan,
                        0,
                        ',',
                        '.'
                    )
                )
            );
            $pesan = $c->hutang->metode == 'cash'
? 'Besok adalah batas akhir pembayaran hutang sebesar Rp' .
number_format(
    $c->jumlah_cicilan,
    0,
    ',',
    '.'
)
: 'Besok jatuh tempo cicilan ke-' .
$c->cicilan_ke .
' sebesar Rp' .
number_format(
    $c->jumlah_cicilan,
    0,
    ',',
    '.'
);
Notifikasi::create([
    'id_user' => $user->id,
    'judul' => 'Pengingat Jatuh Tempo',
    'pesan' => $pesan,
    'tipe' => 'keterlambatan',
    'media' => 'web',
    'tanggal' => now(),
    'status_baca' => 'belum'
]);
$c->reminder_h1 = true;
$c->save();
        }
        // H+1
       if ($selisih == -1 && !$c->reminder_telat) {
            Mail::to($user->email)->send(
                new NotificationMail(
                    $user->username,
                    'Pembayaran Terlambat',
                    $c->hutang->metode == 'cash'
                    ?
                    'Pembayaran hutang Anda telah terlambat 1 hari.'
                    :
                 'Cicilan ke-' .
                    $c->cicilan_ke .
                    ' telah terlambat 1 hari. Mohon segera lakukan pembayaran.'
                )
            );
            $pesan = $c->hutang->metode == 'cash'
? 'Pembayaran hutang Anda telah terlambat 1 hari.'
: 'Cicilan ke-' .
$c->cicilan_ke .
' telah terlambat 1 hari. Mohon segera lakukan pembayaran.';
Notifikasi::create([
    'id_user' => $user->id,
    'judul' => 'Pembayaran Terlambat',
    'pesan' => $pesan,
    'tipe' => 'keterlambatan',
    'media' => 'web',
    'tanggal' => now(),
    'status_baca' => 'belum'
]);
$c->reminder_telat = true;
$c->save();
        }
    }
    return Command::SUCCESS;
}
}
