<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['gas_comunidad'] || $_POST['gas_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['gas_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manGastos = ControladorDinamicoTabla::set('GASTO');
    $manGastos->give($_POST);
    $reg = $manGastos->getArray();
    unset($manGastos);

    $manGasPis = ControladorDinamicoTabla::set('GASTO_PISO');
    $manReparto = ControladorDinamicoTabla::set('REPARTO');
    $manReparto->give([]);
    $rep = $manReparto->getArray();
    $key = []; foreach($rep as $rep2) { $key[$rep2['rep_reparto']] = $rep2['rep_nombre']; }
    for ($i=0; $i<count($reg); $i++) {
        $reg[$i]['rep_nombre'] = $key[$reg[$i]['gas_reparto']];
        if ($manGasPis->give(['gpi_gasto' => $reg[$i]['gas_gasto']]) == 0) {
            $reg[$i]['pisos'] = $manGasPis->getArray();
        } else {
            $reg = $manGasPis->getListaErrores();
            die (json_encode(['success' => false, 'root' => $reg]));
        }

    }
    
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    
    unset($reg);
    unset($rep);
    unset($manReparto);
    unset($manGasPis);


?>