<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['psm_comunidad'] || $_POST['psm_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['psm_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manPromesas = ControladorDinamicoTabla::set('PROMESA');
    $manPromesas = new Tabla_PROMESA();
    $manPromesas->give($_POST);
    $reg = $manPromesas->getArray();
    unset($manPromesas);

    $manPsmPis = ControladorDinamicoTabla::set('PROMESA_PISO');
    for ($i=0; $i<count($reg); $i++) {
        if ($manPsmPis->give(['prp_promesa' => $reg[$i]['psm_promesa']]) == 0) {
            $reg[$i]['pisos'] = $manPsmPis->getArray();
        } else {
            $reg = $manPsmPis->getListaErrores();
            die (json_encode(['success' => false, 'root' => $reg]));
        }

    }
    
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    
    unset($reg);
    unset($rep);
    unset($manPsmPis);


?>