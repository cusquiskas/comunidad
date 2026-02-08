<?php
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => getenv('APP_ENV') === 'prod',
        'httponly' => true,
        'samesite' => 'Strict' // O 'Lax' o 'None'
    ]);
    
    session_start();
   #require_once ($_SESSION['data']['conf']['home'].'backend/required/initSession.php');
    require_once ('../required/initSession.php');

    if ($_POST['usu_contrasena'] == '' || $_POST['usu_correo'] == '') {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Usuario o contraseña no válido']]]));
    }

    $_POST['usu_contrasena'] = md5($_POST['usu_contrasena']);
    $_POST['usu_errorlogin'] = 7;
    $_POST['usu_errorlogin_signo'] = "<";
    $_POST['usu_fvalida'] = date('Y-m-d');
    $_POST['usu_fvalida_signo'] = "<=";
    $manJugador = ControladorDinamicoTabla::set('USUARIO', true);
    
    if ($manJugador->give($_POST) == 0) {
        #echo var_export($manJugador->getDatos(), true)."\n";
        $reg = $manJugador->getArray();
        #echo var_export($reg, true)."\n";
        if (count($reg) == 1) {

            $_POST['usu_correo'] = $reg[0]['usu_correo'];
            $_POST['usu_facceso'] = date('Y-m-d G:i:s');
            unset($_POST['usu_fvalida']);
            $_POST['usu_errorlogin'] = 0;
            $manJugador->save($_POST);
            
            #iniciamos sesión
            $manSesion = ControladorDinamicoTabla::set('SESION');
            $ses = [];
            $ses["ses_token"] = bin2hex(random_bytes(32));
            $ses["ses_correo"] = $reg[0]['usu_correo'];
            $ses["ses_primero"] = date('Y-m-d G:i:s');
            $ses["ses_ultimo"] = date('Y-m-d G:i:s');
            if ($manSesion->save($ses) != 0) {
                unset($ses);
                $ses = $manSesion->getListaErrores();
                unset($manSesion);
                die(json_encode(['success' => false, 'root' => $ses]));
            }
            $_SESSION['data']['user']['id'] = $reg[0]['usu_correo'];
            $_SESSION['data']['user']['nombre'] = $reg[0]['usu_nombre'];
            $_SESSION['data']['user']['token'] = $ses['ses_token'];
            
            echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Login realizado correctamente', 'id' => $reg[0]['usu_correo'], 'nombre' => $reg[0]['usu_nombre'], 'token' => $ses["ses_token"]]]);
        } else {
            
            if ($manJugador->give(["usu_correo" => $_POST["usu_correo"]]) == 0) {
                #echo var_export($manJugador->getDatos(), true)."\n";
                $reg = $manJugador->getArray();
                #echo var_export($reg, true)."\n";
                if (count($reg) == 1) {
                    $manJugador->save(["usu_correo" => $reg[0]["usu_correo"], "usu_errorlogin" => $reg[0]["usu_errorlogin"]+1]);
                }
            }
            echo json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Usuario o contraseña no válido']]]);

        }
    } else {
        $reg = $manJugador->getListaErrores();
        echo var_export($reg->getDatos(), true)."\n";
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    }

    unset($manJugador);
    unset($reg);
