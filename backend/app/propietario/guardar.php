<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['pro_comunidad'] || $_POST['pro_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['pro_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    $manPropietario = ControladorDinamicoTabla::set('PROPIETARIO');
    $manPropietario->save($_POST);
    $propietario = $manPropietario->getListaErrores();
    if ($propietario != null and count($propietario) > 0) {
        unset($manPropietario);
        die(json_encode(['success' => false, 'root' => $propietario]));
    }
    $propietario = $manPropietario->getArray();
        
    unset($manPropietario);

    $manPropiePiso = ControladorDinamicoTabla::set('PROPIETARIO_PISO');
    $reg = ["ppi_propietario" => $propietario["pro_propietario"], "ppi_piso" => $_POST["pro_piso"], "ppi_desde" => date("Y-m-d"), "ppi_hasta" => "9999-12-31", "ppi_inquilino" => 0];
    $manPropiePiso->save($reg);
    $proPiso = $manPropiePiso->getListaErrores();
    if ($proPiso != null and count($proPiso) > 0) {
        unset($manPropiePiso);
        die(json_encode(['success' => false, 'root' => $proPiso]));
    }
    $proPiso = $manPropiePiso->getArray();
        
    unset($manPropiePiso);

    
    
    
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $propietario]]);
    
    
?>