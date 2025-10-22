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
    $filtroPromesa = [];
    $filtroPromesa["psm_comunidad"] = $_POST["der_comunidad"];
    
    $manDeuda = ControladorDinamicoTabla::set('DEUDA');
    $filtroDeuda = [];
    $filtroDeuda["dud_comunidad"] = $_POST["der_comunidad"];
    
    for ($i=0; $i<count($reg); $i++) {
        # primero hay que localizar qué ID de promesa tiene esta derrama (puede ser que no haya ninguna)
        $filtroPromesa["psm_derrama"] = $reg[$i]["der_derrama"];
        $manPromesa->give($filtroPromesa);
        $psm = $manPromesa->getArray();
        
        if (count($psm) == 1) {
            $reg[$i]["der_promesa"] = $psm[0]["psm_promesa"];

            $filtroDeuda["dud_promesa"] = $psm[0]["psm_promesa"];
            $manDeuda->give($filtroDeuda);
            $dud = $manDeuda->getArray();
            
            $reg[$i]["der_parcial"] = 0;
            for ($z=0; $z<count($dud); $z++) {
                $reg[$i]["der_parcial"] += ($dud[$z]["dud_importe"] * -1);
            }
            # luego sumamos la deuda que haya para esa promesa
            $reg[$i]["x100"] = intval(($reg[$i]["der_parcial"]*100)/$reg[$i]["der_total"]);
        } else {
            $reg[$i]["psm_promesa"] = 0;
            $reg[$i]["der_parcial"] = 0;
            $reg[$i]["x100"]        = 0;
        }
    }
    
    unset($manDeuda);
    unset($manPromesa);

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    
    unset($reg);

?>