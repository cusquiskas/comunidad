<?php
    session_start();
    require_once ($_SESSION['data']['conf']['home'].'backend/required/controlSession.php');

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
            $ret[$i]['com_nombre']    = $rog[0]['com_nombre'];
        }
        unset($manComunidad);
        unset($manComuUsu);
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => ['usu_correo' => $reg[0]['usu_correo'], 'usu_nombre' => $reg[0]['usu_nombre']], "Comunidades" => $ret]]);
    } else {
        unset($_SESSION['data']['user']);
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Errores' => 'Usuario no encontrado']]);
    }

    unset($manUsuario);
?>

