<?php

    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    if (!isset($_SESSION['data'])) {
        $_SESSION['data'] = [];
    }
    if (!isset($_SESSION['data']['user'])) {
        $_SESSION['data']['user'] = [];
    }

    require_once '../../conex/conf.php';  //información crítica del sistema
    require_once '../../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../../tabla/controller.php';

    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST) || $_POST == []) 
        $_POST = json_decode(file_get_contents('php://input'), true);
        
    if ($_POST['usu_contrasena'] !== $_POST['usu_contrasena2']) {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Las contraseñas no coinciden']]]));
    }

    $_POST['usu_contrasena'] = md5($_POST['usu_contrasena']);

    #$_POST['ses_primero'] = $_POST['ses_ultimo'] = date('Y-m-d G:i:s');
    
    #$_POST['ses_token'] = bin2hex(random_bytes(32));
    
    $_POST['usu_errorlogin'] = 0;

    $manUsuario = ControladorDinamicoTabla::set('USUARIO');

    #if (!isset($_POST['usu_correo']) || $_POST['usu_correo'] == '') $_POST['usu_correo'] = "-1";
    echo var_export($_POST, true);

    if ($manUsuario->save($_POST) == 0) {
        $reg = $manUsuario->getArray();
        $_SESSION['data']['user']['correo'] = $reg['usu_correo'];
        $_SESSION['data']['user']['nombre'] = $reg['usu_nombre'];
        /*
        $to      = 'cusquiskas@gmail.com';
        $subject = 'Verificación de cuenta de correo';
        $message = 'Pulsa este enlace para activar el centro de mando';
        $headers = 'From: cusquiskas@gmail.com'       . "\r\n" .
                   'Reply-To: cusquiskas@gmail.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
        */
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Registro realizado correctamente', 'id' => $reg['usu_correo'], 'nombre' => $reg['usu_nombre']]]);
        
    } else {
        $reg = $manUsuario->getListaErrores();
        $rag = [];
        foreach ($reg as $valor) { $rag[] = [$valor['error']]; }
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    }

    unset($manUsuario);
    unset($reg);
    unset($rag);
