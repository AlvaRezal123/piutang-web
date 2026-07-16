<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Pendaftaran Agen Disetujui</h2>

<p>
    Halo {{ $agen->username }},
</p>

<p>
    Selamat! Pendaftaran akun Anda telah berhasil diverifikasi dan disetujui.
</p>

<p>
    Anda sekarang dapat login ke sistem SIMPAN menggunakan akun berikut:
</p>

<table style="background-color:#F5F3FE; border:1px solid #5628C7; border-radius:8px; padding:16px; margin:16px 0; width:100%; max-width:400px; border-collapse:collapse;">
    <tr>
        <td style="padding:6px 10px; color:#5628C7; font-weight:bold; width:110px;">Email</td>
        <td style="padding:6px 10px;">: {{ $user->email }}</td>
    </tr>
    <tr>
        <td style="padding:6px 10px; color:#5628C7; font-weight:bold;">Password</td>
        <td style="padding:6px 10px;">: {{ $agen->password_plain }}</td>
    </tr>
</table>

<p>
    Terima kasih telah bergabung sebagai Agen Partner Pulsa.
</p>

<br>

<p>
    Hormat Kami,
    <br>
    Admin SIMPAN
</p>

</body>
</html>