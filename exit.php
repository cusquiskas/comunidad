<?php
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => getenv('APP_ENV') === 'prod',
        'httponly' => true,
        'samesite' => 'Strict' // O 'Lax' o 'None'
    ]);
    session_start  ();
    session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VecinApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <script>
        sessionStorage.setItem('id','');
        sessionStorage.setItem('nombre','');
        document.location.href = "/";    
    </script>
</body>

