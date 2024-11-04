<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Code</title>
</head>

<body>
    <h3>Please click on the button below to reset password.</h3>
    <a href="{{env('VUE_APP_BASE_URL')}}reset_password/{{$data['token']}}/{{$data['email']}}">
        <button style="padding: 10px; border-radius: 10px; background-color: blue; border: none; color:white;">Reset Password</button>
    </a>
</body>
</html>