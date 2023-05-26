<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION['data']['user']['id']) || $_SESSION['data']['user']['id'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Sesión no válida']]]));
    }

    require_once '../../conex/conf.php';  //información crítica del sistema
    require_once '../../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../../tabla/controller.php';

    $manJugador = ControladorDinamicoTabla::set('JUGADOR');

    if ($manJugador->give(['JGD_JUGADOR'=>$_SESSION['data']['user']['id']]) == 0) {
        $reg = $manJugador->getArray();
    }
    
    if (count($reg) == 1)
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => ['JGD_JUGADOR' => $reg[0]['JGD_JUGADOR'], 'JGD_NOMBRE' => $reg[0]['JGD_NOMBRE']]]]);
    else {
        unset($_SESSION['data']['user']);
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Usuario no encontrado']]);
    }

?>

