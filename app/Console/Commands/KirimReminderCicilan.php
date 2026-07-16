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
        $cicilan = \App\Models\Cicilan::with('hutang.agen.user')
            ->where('status', 'belum')
            ->get();

        $this->info("Total cicilan berstatus 'belum': " . $cicilan->count());

        if ($cicilan->isEmpty()) {
            $this->warn('Tidak ada cicilan dengan status "belum". Command berhenti.');
            return Command::SUCCESS;
        }

        foreach ($cicilan as $c) {
            $user = $c->hutang->agen->user ?? null;

            if (!$user) {
                $this->warn("Cicilan #{$c->id}: SKIP - user tidak ditemukan (hutang/agen/user null).");
                continue;
            }

            $selisih = now()->startOfDay()->diffInDays(
                Carbon::parse($c->tanggal_jatuh_tempo)->startOfDay(),
                false
            );

            $this->info("Cicilan #{$c->id} | jatuh_tempo: {$c->tanggal_jatuh_tempo} | selisih: {$selisih} | reminder_h1: " . ($c->reminder_h1 ? 'true' : 'false') . " | reminder_telat: " . ($c->reminder_telat ? 'true' : 'false'));

            // H-1
            if ($selisih == 1 && !$c->reminder_h1) {
                $this->info(" -> Mengirim reminder H-1 ke {$user->email}");

                try {
                    Mail::to($user->email)->send(
                        new NotificationMail(
                            $user->username,
                            'Pengingat Jatuh Tempo',
                            $c->hutang->metode == 'cash'
                                ? 'Besok adalah batas akhir pembayaran hutang sebesar Rp' . number_format($c->jumlah_cicilan, 0, ',', '.')
                                : 'Besok jatuh tempo cicilan ke-' . $c->cicilan_ke . ' sebesar Rp' . number_format($c->jumlah_cicilan, 0, ',', '.')
                        )
                    );
                    $this->info("    Email berhasil dikirim.");
                } catch (\Throwable $e) {
                    $this->error("    GAGAL kirim email: " . $e->getMessage());
                }

                $pesan = $c->hutang->metode == 'cash'
                    ? 'Besok adalah batas akhir pembayaran hutang sebesar Rp' . number_format($c->jumlah_cicilan, 0, ',', '.')
                    : 'Besok jatuh tempo cicilan ke-' . $c->cicilan_ke . ' sebesar Rp' . number_format($c->jumlah_cicilan, 0, ',', '.');

                Notifikasi::create([
                    'id_user' => $user->id,
                    'judul' => 'Pengingat Jatuh Tempo',
                    'pesan' => $pesan,
                    'tipe' => 'keterlambatan',
                    'media' => 'web',
                    'tanggal' => now(),
                    'status_baca' => 'belum'
                ]);
                $this->info("    Notifikasi web berhasil disimpan.");

                $c->reminder_h1 = true;
                $c->save();
            }

            // H+1
            if ($selisih == -1 && !$c->reminder_telat) {
                $this->info(" -> Mengirim reminder TERLAMBAT ke {$user->email}");

                try {
                    Mail::to($user->email)->send(
                        new NotificationMail(
                            $user->username,
                            'Pembayaran Terlambat',
                            $c->hutang->metode == 'cash'
                                ? 'Pembayaran hutang Anda telah terlambat 1 hari.'
                                : 'Cicilan ke-' . $c->cicilan_ke . ' telah terlambat 1 hari. Mohon segera lakukan pembayaran.'
                        )
                    );
                    $this->info("    Email berhasil dikirim.");
                } catch (\Throwable $e) {
                    $this->error("    GAGAL kirim email: " . $e->getMessage());
                }

                $pesan = $c->hutang->metode == 'cash'
                    ? 'Pembayaran hutang Anda telah terlambat 1 hari.'
                    : 'Cicilan ke-' . $c->cicilan_ke . ' telah terlambat 1 hari. Mohon segera lakukan pembayaran.';

                Notifikasi::create([
                    'id_user' => $user->id,
                    'judul' => 'Pembayaran Terlambat',
                    'pesan' => $pesan,
                    'tipe' => 'keterlambatan',
                    'media' => 'web',
                    'tanggal' => now(),
                    'status_baca' => 'belum'
                ]);
                $this->info("    Notifikasi web berhasil disimpan.");

                $c->reminder_telat = true;
                $c->save();
            }
        }

        $this->info('Selesai.');
        return Command::SUCCESS;
    }
}