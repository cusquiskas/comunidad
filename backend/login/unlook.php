<?php
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '\/comunidad\/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict' // O 'Lax' o 'None'
    ]);
    
    session_start();
    require_once ($_SESSION['data']['conf']['home'].'backend/required/initSession.php');
        
    $manUsuario = ControladorDinamicoTabla::set('USUARIO');

    $manUsuario->give(["usu_correo" => $_POST["usu_correo"], "usu_correo_case" => "N"]);
    $control = $manUsuario->getArray();
    unset($manUsuario);
    if (count($control) == 1) {
        $manSesion = ControladorDinamicoTabla::set('SESION');
        $ses = [];
        $ses["ses_token"] = bin2hex(random_bytes(32));
        $ses["ses_correo"] = $_POST['usu_correo'];
        $ses["ses_primero"] = date('Y-m-d G:i:s');
        if ($control[0]["usu_errorlogin"] > 7)
            $ses["ses_ultimo"] = '9999-12-30';
        else 
            $ses["ses_ultimo"] = '9999-12-29';

        if ($manSesion->save($ses) == 0) {
            require_once ($_SESSION['data']['conf']['home'].'conex/correo.php');
            $smtp = new Correo();
            $smtp->destinatario($ses["ses_correo"], null);
            $smtp->asunto = "Desbloqueo de cuenta de correo";
            $smtp->cuerpo = "Sigue el enlace indicado a continuación para desbloquear tu usuario y cambiar la contraseña.<br><a href='".$_SERVER['HTTP_ORIGIN']."/comunidad/confirmarUsuario.html?s=".$ses["ses_token"]."'>Desbloquear cuenta</a>";
            if (!$smtp->mandaMail()) {
                $error = $smtp->error;
                unset($smtp);
                die(json_encode(['success' => false, 'root' => $error]));
            }
            unset($smtp);
        } else {
            unset($ses);
            $ses = $manSesion->getListaErrores();
            unset($manSesion);
            die(json_encode(['success' => false, 'root' => $ses]));
        }
    }

    die(json_encode(['success' => true, 'root' => [['tipo' => 'Respuesta', 'Detalle' => 'Se ha enviado un correo de recuperación a la cuenta de correo, sigue las instrucciones para cambiar la contraseña y desbloquear la cuenta.']]]));

?>