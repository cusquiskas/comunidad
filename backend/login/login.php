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

    if ($_POST['JGD_PASSWORD'] == '' || $_POST['JGD_CORREO'] == '') {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Usuario o contraseña no válido']]]));
    }

    $_POST['JGD_PASSWORD'] = md5($_POST['JGD_PASSWORD']);
    $_POST['JGD_ERRLOGIN'] = 7;
    $_POST['JGD_ERRLOGIN_signo'] = "<";
    $_POST['JGD_FVALIDA'] = date('Y-m-d');
    $_POST['JGD_FVALIDA_signo'] = "<=";
    $manJugador = ControladorDinamicoTabla::set('JUGADOR');

    if ($manJugador->give($_POST) == 0) {
        #echo var_export($manJugador->getDatos(), true)."\n";
        $reg = $manJugador->getArray();
        #echo var_export($reg, true)."\n";
        if (count($reg) == 1) {

            $_SESSION['data']['user']['id'] = $reg[0]['JGD_JUGADOR'];
            $_SESSION['data']['user']['nombre'] = $reg[0]['JGD_NOMBRE'];
            
            $_POST['JGD_JUGADOR'] = $reg[0]['JGD_JUGADOR'];
            $_POST['JGD_FACCESO'] = date('Y-m-d'); #date('Y-m-d G:i:s');
            $_POST['JGD_ERRLOGIN'] = 0;
            $manJugador->save($_POST);
            #echo var_export($manJugador->getDatos(), true)."\n";
            echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Login realizado correctamente', 'id' => $reg[0]['JGD_JUGADOR'], 'nombre' => $reg[0]['JGD_NOMBRE']]]);
        } else {
            if ($manJugador->give(["JGD_CORREO" => $_POST["JGD_CORREO"]]) == 0) {
                #echo var_export($manJugador->getDatos(), true)."\n";
                $reg = $manJugador->getArray();
                #echo var_export($reg, true)."\n";
                if (count($reg) == 1) {
                    $manJugador->save(["JGD_JUGADOR" => $reg[0]["JGD_JUGADOR"], "JGD_ERRLOGIN" => $reg[0]["JGD_ERRLOGIN"]+1]);
                }
            }
            echo json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Usuario o contraseña no válido']]]);

        }
    } else {
        $reg = $manJugador->getListaErrores();
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    }

    unset($manJugador);
    unset($reg);
