<?php
    require_once ('../required/controlSession.php');

    $perfil = controlPerfil($_POST['pis_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manPisos = ControladorDinamicoTabla::set('PISO');
    $manPisos->give($_POST);
    $reg = $manPisos->getArray();

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>