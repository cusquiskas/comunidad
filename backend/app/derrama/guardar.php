<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['der_comunidad'] || $_POST['der_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['der_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manGastos = ControladorDinamicoTabla::set('DERRAMA');
    if ($manGastos->save($_POST) == 0) {
        $reg = $manGastos->getArray();
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    } else {
        $reg = $manGastos->getListaErrores();
        echo json_encode(['success' => false, 'root' => $reg]);
    }
    unset($manGastos);
    unset($reg);

?>