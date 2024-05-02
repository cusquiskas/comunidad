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

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>