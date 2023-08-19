<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['com_comunidad'] || $_POST['com_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['com_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    $manPropietario = ControladorDinamicoTabla::set('PROPIETARIO');
    $reg = [];
    $propietario = null;
    if (isset($_POST["pro_dni"]) && $_POST["pro_dni"] != "") {
        $manPropietario->give(["pro_dni" => $_POST["pro_dni"]]);
        $reg = $manPropietario->getArray();
        if (count($reg) == 1) $propietario = $reg[0]["pro_propietario"];
    }
    
    if ($propietario != null && isset($_POST["pro_correo"]) && $_POST["pro_correo"] != "") {
        $manPropietario->give(["pro_correo" => $_POST["pro_correo"]]);
        $reg = $manPropietario->getArray();
        if (count($reg) == 1) $propietario = $regDNI[0]["pro_propietario"];
    }
    
    unset($manPropietario);
    unset($reg);

    if ($propietario == null && (!isset($_POST["pro_dni"]) || $_POST["pro_dni"] == "") && (!isset($_POST["pro_correo"]) || $_POST["pro_correo"] == "")) {
        echo json_encode(['success' => false, 'root' => ['tipo' => 'error', 'Detalle' => 'No se ha indicado información del propietario']]);
    } else {
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    }

    
?>