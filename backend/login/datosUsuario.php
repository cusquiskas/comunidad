<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_POST) || $_POST == []) 
        $_POST = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_SESSION['data']['user']['id']) || $_SESSION['data']['user']['id'] == "" ||
        !isset($_POST['usu_correo']) || $_POST['usu_correo'] == "" || $_POST['usu_correo'] != $_SESSION['data']['user']['id']
    ) {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Sesión no válida']]]));
    }

    require_once '../../conex/conf.php';  //información crítica del sistema
    require_once '../../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../../tabla/controller.php';

    $manUsuario = ControladorDinamicoTabla::set('USUARIO');

    if ($manUsuario->give($_POST) == 0) {
        $reg = $manUsuario->getArray();
    }
    
    if (count($reg) == 1) {
        $filtro = [];
        $filtro['uco_correo'] = $_POST['usu_correo'];
        $filtro['uco_desde'] = date('Y-m-d');
        $filtro['uco_desde_signo'] = '<=';
        $filtro['uco_hasta'] = date('Y-m-d');
        $filtro['uco_hasta_signo'] = '>=';

        $manComunidad = ControladorDinamicoTabla::set('COMUNIDAD');
        $manComuUsu = ControladorDinamicoTabla::set('USUARIO_COMUNIDAD');
        $manComuUsu->give($filtro);
        $comus = $manComuUsu->getArray();
        $ret = [];
        for ($i = 0; $i < count($comus); $i++) {
            $manComunidad->give(["com_comunidad" => $comus[$i]['uco_comunidad']]);
            $rog = $manComunidad->getArray();
            $ret[$i]['com_comunidad'] = $rog[0]['com_comunidad'];
            $ret[$i]['com_nombre'] = $rog[0]['com_nombre'];
        }
        unset($manComunidad);
        unset($manComuUsu);
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => ['usu_correo' => $reg[0]['usu_correo'], 'usu_nombre' => $reg[0]['usu_nombre']], "Comunidades" => $ret]]);
    } else {
        unset($_SESSION['data']['user']);
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Usuario no encontrado']]);
    }

    unset($manUsuario);
?>

