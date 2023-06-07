<?php
    require_once ('../required/controlSession.php');

    if (!$_POST['pis_comunidad'] || $_POST['pis_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['pis_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    if ($perfil == 3) {
        $manPisos = ControladorDinamicoTabla::set('PISO');
        $manPisos->give($_POST);
        $reg = $manPisos->getArray();
    } else {
        require_once '../../tabla/propietario_piso.php';
        $manPisos = new View_PROPIETARIO_PISO();
        $reg = $manPisos->lista_pisos($_SESSION['data']['user']['id'], $_POST['pis_comunidad'], date_format(new DateTime(), 'Y-m-d'));
    }
    unset($manPisos);


    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>