<?php
    session_start();
    if (!isset($_SESSION['data'])) {
        $_SESSION['data'] = [];
    }
    if (!isset($_SESSION['data']['conf'])) {
        $_SESSION['data']['conf'] = [];
    }
    require_once 'conex/conf.php';
    $conf = new ConfiguracionSistema();
    $_SESSION['data']['conf']['home']        = $conf->getHome();
    $_SESSION['data']['conf']['timeSession'] = $conf->getTimeSession();
    $_SESSION['data']['conf']['subidas']     = $conf->getSubidas();
    unset($conf);
    require_once 'js/function.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VecinApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon"       href="css/img/imagen.gif" type="image/png" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Cache::ruta('css/switch.css'); ?>">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Cache::ruta('css/style.css'); ?>"> 
</head>
<body>
    <template id="alertBox"></template>
    <template id="header"></template>
    <template id="body"></template>
    <template id="footer"></template>
    <template id="modal"></template>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script>let session = "<?php echo session_id(); ?>";</script>
    <script src="<?php echo Cache::ruta('js/funciones.js'); ?>"></script>
    <script src="<?php echo Cache::ruta('js/peticionAjax.js'); ?>"></script>
    <script src="<?php echo Cache::ruta('js/controller.js'); ?>"></script>
    <script src="<?php echo Cache::ruta('js/scripts.js'); ?>"></script>
</body>
</html>