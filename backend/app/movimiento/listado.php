<?php
    session_start();
    require_once ($_SESSION['data']['conf']['home'].'backend/required/controlSession.php');

    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    $manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
    $manMovimiento->give($_POST);
    $reg = $manMovimiento->getArray();
    unset($manMovimiento);


    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>