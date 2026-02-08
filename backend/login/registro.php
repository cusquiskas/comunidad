<?php
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => getenv('APP_ENV') === 'prod',
        'httponly' => true,
        'samesite' => 'Strict' // O 'Lax' o 'None'
    ]);
    
    session_start();
    require_once ($_SESSION['data']['conf']['home'].'backend/required/initSession.php');
        
    if ($_POST['usu_contrasena'] !== $_POST['usu_contrasena2']) {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Las contraseñas no coinciden']]]));
    }
    
    $_POST['usu_contrasena'] = md5($_POST['usu_contrasena']);

    $_POST['usu_facceso'] = date('Y-m-d G:i:s');
    
    $_POST['usu_fvalida'] = '9999-12-31';
    
    $_POST['usu_errorlogin'] = 0;

    $manUsuario = ControladorDinamicoTabla::set('USUARIO');

    $manUsuario->give(["usu_correo" => $_POST["usu_correo"], "usu_correo_case" => "N"]);
    $control = $manUsuario->getArray();
    if (count($control) == 1) {
        unset($manUsuario);
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Respuesta', 'Detalle' => 'El correo ya está registrado']]]));
    }

    if ($manUsuario->save($_POST) == 0) {
        $reg = $manUsuario->getArray(); #despues de un save... no es un array, es un objeto
        #no podrá acceder hasta validar la cuenta de correo
        #$_SESSION['data']['user']['id'] = $reg['usu_correo'];
        #$_SESSION['data']['user']['nombre'] = $reg['usu_nombre'];
        
        $manSesion = ControladorDinamicoTabla::set('SESION');
        $ses = [];
        $ses["ses_token"] = bin2hex(random_bytes(32));
        $ses["ses_correo"] = $reg['usu_correo'];
        $ses["ses_primero"] = date('Y-m-d G:i:s');
        $ses["ses_ultimo"] = '9999-12-31';

        if ($manSesion->save($ses) == 0) {
            require_once ($_SESSION['data']['conf']['home'].'conex/correo.php');
            $smtp = new Correo();
            $smtp->destinatario($ses["ses_correo"]);
            $smtp->asunto = "Verificación de cuenta de correo";
            $smtp->cuerpo = "Sigue el enlace indicado a continuación para activar tu usuario y contraseña.<br><a href='".$_SERVER['HTTP_ORIGIN']."/confirmarUsuario.html?s=".$ses["ses_token"]."'>Verificación cuenta de correo</a>";
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
            unset($manUsuario);
            die(json_encode(['success' => false, 'root' => $ses]));
        }
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Registro realizado correctamente, acceda a su buzón de correo antes de hacer login', 'id' => $reg['usu_correo'], 'nombre' => $reg['usu_nombre']]]);
        
    } else {
        $reg = $manUsuario->getListaErrores();
        $rag = [];
        foreach ($reg as $valor) { 
            if (isset($valor["tipo"]) && $valor["tipo"] == "Validacion")
                $rag[] = $valor;
            else
                $rag[] = [$valor['error']]; 
        }
        echo json_encode(['success' => false, 'root' => $rag]);
    }

    unset($manUsuario);
    unset($reg);
    unset($rag);
