<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset ('css/regis.css')}}">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="img">
            <img src="{{ asset('img/login.jpg') }} " alt="Login Image" style="height: 100%">
        </div>
     <div class="Form-box login">
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="rowrrr">
            <h1>Login</h1>
        <div class="input-box">
            <input type="email" id="email" name="email" required autofocus placeholder="Email" autocomplete="off">
        </div>
        <div class="input-box">
            <input type="password" id="password" name="password" required placeholder="Password" autocomplete="off">
        </div>
        <button type="submit">Login</button>
        </div>
         <p>dont have account?<a href="{{ route('register')}}">SignUp</a></p>
    </form>
</div>
</div>
</body>
</html>
