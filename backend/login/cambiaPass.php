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

    if (!$_POST['SES_TOKEN'] || $_POST['SES_TOKEN'] == '') {
        die(json_encode(['success' => false, 'root' => ['tipo' => 'Sesion', 'Detalle' => 'No se ha informado Token']]));
    }
    
    if (!$_POST['USU_PASSWORD1'] || $_POST['USU_PASSWORD1'] == '' || $_POST['USU_PASSWORD1'] != $_POST['USU_PASSWORD2']) {
        die(json_encode(['success' => false, 'root' => ['tipo' => 'Sesion', 'Detalle' => 'Las contraseñas no son idénticas']]));
    }

    $return = ['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "El token no es válido", 'Code' => 0]];

    // comprobamos que la sesión existe y si existe la fecha de caducidad
    $manSesion = ControladorDinamicoTabla::set('SESION');
    $manSesion->give(['ses_token' => $_POST['SES_TOKEN']]);
    $ses = $manSesion->getArray();
    if (count($ses) == 1) {
        $ses = $ses[0];
        $manUsuario = ControladorDinamicoTabla::set('USUARIO');
        $manUsuario->give(['usu_correo' => $ses['ses_correo']]);
        $usu = $manUsuario->getArray();
        $usu = $usu[0];
        $usu['usu_contrasena'] = md5($_POST['USU_PASSWORD1']);
        $usu['usu_errorlogin'] = 0;
        if ($manUsuario->save($usu) == 0) {
            $manSesion->delete($ses);
            $return['success'] = true;
            $return['root']['Detalle'] = 'Usuario activado correctamente';
            $return['root']['Code']    = 1;
        } else {
            $return['root']['Detalle'] = $manUsuario->getListaErrores();
        }
        unset($usu);
        unset($manUsuario);        
    }
    
    unset($ses);
    unset($manSesion);
    die (json_encode($return));

?>