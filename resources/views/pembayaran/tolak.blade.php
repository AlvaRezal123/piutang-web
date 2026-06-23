<!DOCTYPE html>

<html>
<head>
    <title>Alasan Penolakan Pembayaran</title>
</head>
<body>

<h1>Alasan Penolakan Pembayaran</h1>

@if($errors->any())

```
<ul style="color:red;">

    @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

    @endforeach

</ul>
```

@endif

<form
    action="/admin/pembayaran/simpan-tolak/{{ $pembayaran->id }}"
    method="POST">

```
@csrf

<label>
    Alasan Penolakan
</label>

<br>

<textarea
    name="alasan_penolakan"
    rows="5"
    cols="50"></textarea>

<br><br>

<button type="submit">
    Simpan
</button>
```

</form>

</body>
</html>
