<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['spl_comunidad'] || $_POST['spl_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['spl_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manSplit = ControladorDinamicoTabla::set('SPLIT');
    $manSplit->give($_POST);
    $reg = $manSplit->getArray();
    unset($manSplit);

    if (count($reg) == 0) {
        $rog = ["mov_comunidad" => $_POST['spl_comunidad'], "mov_movimiento" => $_POST['spl_movimiento']];
        $manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
        $manMovimiento->give($rog);
        $rog = $manMovimiento->getArray();
        unset($manMovimiento);
        
        $reg[] = ["spl_detalle" =>$rog[0]['mov_detalle'], "spl_importe" => $rog[0]['mov_importe'], "spl_movimiento" => $rog[0]['mov_movimiento']];
    }
    

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>