<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family:Arial,sans-serif;line-height:1.8">

<h2>Permohonan Fasilitas Cicilan Ditolak</h2>

<p>Halo <b>{{ $agen->username }}</b>,</p>

<p>
Mohon maaf, permohonan fasilitas pembayaran cicilan Anda belum dapat disetujui.
</p>

<p>
<b>Alasan Penolakan :</b>
</p>

<div style="padding:15px;background:#f5f5f5;border-left:5px solid red;">
{{ $agen->alasan_penolakan_cicilan }}
</div>

<p>
Silakan ajukan kembali apabila telah memenuhi persyaratan.
</p>

<br>

<p>
Terima kasih.
</p>

</body>
</html>