<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    $manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
    unset($_POST['mov_movimiento']);
    
    if ($manMovimiento->save($_POST) == 0) {
        $reg = $manMovimiento->getArray();
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    } else {
        $reg = $manMovimiento->getListaErrores();
        echo json_encode(['success' => false, 'root' => $reg]);
    }
    unset($manMovimiento);
?>