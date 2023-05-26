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

    if (!$_POST['JGD_TOKEN'] || $_POST['JGD_TOKEN'] == '') {
        die(json_encode(['success' => false, 'root' => ['tipo' => 'Sesion', 'Detalle' => 'No se ha encontrado Token']]));
    }
    
    if ($_POST['JGD_PASSWORD'] && $_POST['JGD_PASSWORD'] != "" && $_POST['JGD_PASSWORD'] != $_POST['JGD_PASSWORD2']) {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Las contraseñas no coinciden']]]));
    }

    if ($_POST['JGD_PASSWORD'] && $_POST['JGD_PASSWORD'] != "") $_POST['JGD_PASSWORD'] = md5($_POST['JGD_PASSWORD']);

    $objeto = [];
    $objeto["JGD_TOKEN"] = $_POST['JGD_TOKEN'];

    
    $manJugador = ControladorDinamicoTabla::set('JUGADOR');

    if ($manJugador->give($objeto) == 0) {
        $reg = $manJugador->getArray();
        if (count($reg) == 1) {
            if ($reg[0]['JGD_ERRLOGIN'] < 7) {
                $_SESSION['data']['user']['id'] = $reg[0]['JGD_JUGADOR'];
                $_SESSION['data']['user']['nombre'] = $reg[0]['JGD_NOMBRE'];
                
                $reg[0]['JGD_TOKEN'] = null;
                $reg[0]['JGD_FVALIDA'] = date('Y-m-d');
                $reg[0]['JGD_ERRLOGIN'] = 0;
                if ($manJugador->save($reg[0]) == 0) {
                    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Cuenta activada correctamente', 'id' => $reg[0]['JGD_JUGADOR'], 'nombre' => $reg[0]['JGD_NOMBRE']]]);
                } else {
                    $error = $manJugador->getListaErrores();
                    echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $error[0]['Campo'].' '.$error[0]['Detalle']]]);
                }
            } else {
                if (!$_POST['JGD_PASSWORD'] || $_POST['JGD_PASSWORD'] == "") {
                    echo json_encode(['success' => false, 'root' => ['tipo' => 'Sesión', 'Detalle' => 'Cambio de contraseña', code => -1]]);
                } else {
                    $reg[0]['JGD_TOKEN'] = null;
                    $reg[0]['JGD_FVALIDA'] = date('Y-m-d');
                    $reg[0]['JGD_ERRLOGIN'] = 0;
                    if ($manJugador->save($reg[0]) == 0) {
                        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Cuenta activada correctamente', 'id' => $reg[0]['JGD_JUGADOR'], 'nombre' => $reg[0]['JGD_NOMBRE']]]);
                    } else {
                        $error = $manJugador->getListaErrores();
                        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $error[0]['Campo'].' '.$error[0]['Detalle']]]);
                    }
                }
            }
            
        }
    }
?>