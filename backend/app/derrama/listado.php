<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['der_comunidad'] || $_POST['der_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['der_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manGastos = ControladorDinamicoTabla::set('DERRAMA');
    $manGastos->give($_POST);
    $reg = $manGastos->getArray();
    unset($manGastos);

    $manPromesa = ControladorDinamicoTabla::set('PROMESA');
    foreach ($reg as $i => $derrama) {
        $manPromesa->give(['psm_derrama' => $derrama['der_derrama'], 'psm_comunidad' => $derrama['der_comunidad']]);
        $promesas = $manPromesa->getArray();
        $reg[$i]['der_promesa'] = !empty($promesas);
    }
    unset($manPromesa);
    
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    
    unset($reg);

?>