<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family:Arial;background:#f5f5f5;padding:40px;">

<div style="max-width:600px;background:white;margin:auto;border-radius:12px;padding:30px;">

    <h2 style="color:#5628C7;">
        SIMPAN - Partner Pulsa
    </h2>

    <hr>

    <p>
        Halo <strong>{{ $nama }}</strong>,
    </p>

    <p>
        {!! nl2br(e($pesan)) !!}
    </p>

    <br>

    <a href="http://127.0.0.1:8000/login"
       style="
       background:#5628C7;
       color:white;
       text-decoration:none;
       padding:12px 22px;
       border-radius:8px;
       display:inline-block;">

        Login ke SIMPAN

    </a>

    <br><br>

    <hr>

    <p style="font-size:13px;color:#888;">
        Email ini dikirim otomatis oleh Sistem Informasi Pengelolaan Piutang Agen (SIMPAN). Mohon untuk tidak membalas email ini.
    </p>

</div>

</body>
</html>