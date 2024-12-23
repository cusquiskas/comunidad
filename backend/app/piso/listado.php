<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['pis_comunidad'] || $_POST['pis_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['pis_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manPisos = ControladorDinamicoTabla::set('PISO');
    $manPisos->give($_POST);
    $reg = $manPisos->getArray();
    
    unset($manPisos);

    
    $manPropiePiso = ControladorDinamicoTabla::set('PROPIETARIO_PISO');
    $manPropietario = ControladorDinamicoTabla::set('PROPIETARIO');
    
    for ($i=0; $i<count($reg); $i++) {
        $mag = ["ppi_piso" => $reg[$i]["pis_piso"], "ppi_hasta_signo" => ">=", "ppi_hasta" => date("Y-m-d"), "ppi_desde_signo" => "<=", "ppi_desde" => date("Y-m-d")];
        $manPropiePiso->give($mag);
        $mog = $manPropiePiso->getArray();
        if (count($mog) > 0) {
            $rog = ["pro_propietario" => $mog[0]["ppi_propietario"]];
            $manPropietario->give($rog);
            $meg = $manPropietario->getArray();
            
            $reg[$i]["pro_propietario"]        = $mog[0]["ppi_propietario"];
            $reg[$i]['pis_propietario_nombre'] = $meg[0]["pro_nombre"] . " " . $meg[0]["pro_apellidos"];
        } else {
            $reg[$i]["pro_propietario"]        = "";
            $reg[$i]['pis_propietario_nombre'] = "Sin Especificar";
            
        }
    }
    
    unset($manPropietario);
    unset($manPropiePiso);

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>