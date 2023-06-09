<?php
    require_once ('../required/controlSession.php');

    if (!$_POST['pis_comunidad'] || $_POST['pis_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['pis_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manPisos = ControladorDinamicoTabla::set('PISO');
    $manPisos->give($_POST);
    $reg = $manPisos->getArray();
    
    unset($manPisos);


    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>