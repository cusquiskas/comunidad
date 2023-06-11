<?php
    error_reporting(E_ALL & ~E_NOTICE);
    
    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_SESSION['data'])) {
        $_SESSION['data'] = [];
    }
    if (!isset($_SESSION['data']['user'])) {
        $_SESSION['data']['user'] = [];
    }
    if (!isset($_SESSION['data']['conf'])) {
        $_SESSION['data']['conf'] = [];
    }
    
    require_once '../../conex/conf.php';  //información crítica del sistema
    require_once '../../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../../tabla/controller.php';
    
    $conf = new ConfiguracionSistema();
    $_SESSION['data']['conf']['home']        = $conf->getHome();
    $_SESSION['data']['conf']['timeSession'] = $conf->getTimeSession();
    unset($conf);

    if (!isset($_POST) || $_POST == []) 
        $_POST = json_decode(file_get_contents('php://input'), true);
?>