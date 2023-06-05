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
    

    if ($_POST['usu_contrasena'] == '' || $_POST['usu_correo'] == '') {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Usuario o contraseña no válido']]]));
    }

    $_POST['usu_contrasena'] = md5($_POST['usu_contrasena']);
    $_POST['usu_errorlogin'] = 7;
    $_POST['usu_errorlogin_signo'] = "<";
    $_POST['usu_fvalida'] = date('Y-m-d');
    $_POST['usu_fvalida_signo'] = "<=";
    $manJugador = ControladorDinamicoTabla::set('USUARIO');
    
    if ($manJugador->give($_POST) == 0) {
        #echo var_export($manJugador->getDatos(), true)."\n";
        $reg = $manJugador->getArray();
        #echo var_export($reg, true)."\n";
        if (count($reg) == 1) {

            $_SESSION['data']['user']['id'] = $reg[0]['usu_correo'];
            $_SESSION['data']['user']['nombre'] = $reg[0]['usu_nombre'];
            
            $_POST['usu_correo'] = $reg[0]['usu_correo'];
            $_POST['usu_facceso'] = date('Y-m-d G:i:s');
            unset($_POST['usu_fvalida']);
            $_POST['usu_errorlogin'] = 0;
            $manJugador->save($_POST);
            #echo var_export($manJugador->getDatos(), true)."\n";
            echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Login realizado correctamente', 'id' => $reg[0]['usu_correo'], 'nombre' => $reg[0]['usu_nombre']]]);
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
