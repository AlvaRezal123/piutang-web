<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

    <h2>Pendaftaran Agen Ditolak</h2>

    <p>
        Halo {{ $agen->username }},
    </p>

    <p>
        Mohon maaf, pendaftaran akun Anda belum dapat disetujui.
    </p>

    <p>
        <strong>Alasan Penolakan:</strong>
    </p>

    <p>
        {{ $agen->alasan_penolakan }}
    </p>

    <p>
        Silakan melakukan pendaftaran kembali dengan data yang sesuai.
    </p>

    <br>

    <p>
        Terima kasih,
        <br>
        Admin SIMPAN
    </p>

</body>
</html>