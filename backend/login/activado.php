<?php

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => getenv('APP_ENV') === 'prod',
        'httponly' => true,
        'samesite' => 'Strict' // O 'Lax' o 'None'
    ]);
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
    
    $return = ['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "El token no es válido", 'Code' => 0]];

    // comprobamos que la sesión existe y si existe la fecha de caducidad
    $manSesion = ControladorDinamicoTabla::set('SESION');
    $manSesion->give(['ses_token' => $_POST['SES_TOKEN']]);
    $ses = $manSesion->getArray();
    if (count($ses) == 1) {
        $ses = $ses[0];
        if ($ses['ses_ultimo'] == '9999-12-31 00:00:00') {
            //activar la sesión y nada más
            $manUsuario = ControladorDinamicoTabla::set('USUARIO');
            $manUsuario->give(['usu_correo' => $ses['ses_correo']]);
            $usu = $manUsuario->getArray();
            $usu = $usu[0];
            $usu['usu_fvalida'] = date('Y-m-d');
            $usu['usu_errorlogin'] = 0;
            if ($manUsuario->save($usu) == 0) {
                $manSesion->delete($ses);
                $return['success'] = true;
                $return['root']['Detalle'] = 'Usuario activado correctamente';
                $return['root']['Code']    = 1;
                
                /*require_once ($_SESSION['data']['conf']['home'].'conex/correo.php');
                $smtp = new Correo();
                $smtp->destinatario("jm.munar@cusquiskas.com");
                $smtp->asunto = "Verificación de cuenta de correo";
                $smtp->cuerpo = "Se ha verificado el correo de un nuevo usuario: " . var_export($usu, true);
                if (!$smtp->mandaMail()) {
                    $error = $smtp->error;
                    unset($smtp);
                    die(json_encode(['success' => false, 'root' => $error]));
                }
                unset($smtp);*/

            } else {
                $return['root']['Detalle'] = $manUsuario->getListaErrores();
            }
            unset($usu);
            unset($manUsuario);
            
        } elseif ($ses['ses_ultimo'] == '9999-12-30 00:00:00') {
            //desbloqueo o cambio de contraseña, resuelvo cambiando la contraseña siempre
            $return['success'] = true;
            $return['root']['Detalle'] = ', hemos detectado que cuenta bloqueada.';
            $return['root']['Code'] = 2;
        } else {
            //desbloqueo o cambio de contraseña, resuelvo cambiando la contraseña siempre
            $return['success'] = true;
            $return['root']['Detalle'] = ', vamos a cambiar la contraseña.';
            $return['root']['Code'] = 3;
        }
    }
    unset($ses);
    unset($manSesion);
    die (json_encode($return));

?>