<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset ('css/regis.css')}}">
    <title>Regis</title>
</head>
<body>
    <div class="container">
        <div class="img">
            <img src="{{ asset('img/login.jpg') }} " alt="Login Image" style="height: 100%">
        </div>
     <div class="Form-box regis">
    <form method="POST" action="{{ route('regis.store') }}">
        @csrf
        <div class="rowrrr">
            <h1>Registrasi</h1>
        <div class="input-box2">
            <input type="email" id="email" name="email" required autofocus placeholder="Email" autocomplete="off">
        </div>
        <div class="input-box2">
            <input type="text" id="name" name="name" required autofocus placeholder="Name" autocomplete="off">
        </div>
        <div class="input-box2">
            <input type="number" id="phone" name="phone" required autofocus placeholder="phone" autocomplete="off">
        </div>
        <div class="input-box2">
            <input type="password" id="password" name="password" required placeholder="Password" autocomplete="off">
        </div>
        <button type="submit">Register</button>
        </div>
         <p>dont have account?<a href="{{ route('login')}}">SignIn</a></p>
    </form>
</div>
</div>
</body>
</html>
