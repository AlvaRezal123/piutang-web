<!DOCTYPE html>
<html>
<head>
    <title>Login Agen</title>
</head>
<body>

<h1>Login Agen</h1>

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

<form action="/login-proses" method="POST">
    @csrf

    <input type="email" name="email" placeholder="Email"><br><br>

    <input type="password" name="password" placeholder="Password"><br><br>
    

    <button type="submit">Login</button>
</form>

</body>
</html>