<?php
    require_once ('../required/controlSession.php');

    function compararPorClave($clave, $orden = 'asc') {
        return function ($a, $b) use ($clave, $orden) {
            if ($orden === 'asc') {
                return $a[$clave] <=> $b[$clave];
            } else {
                return $b[$clave] <=> $a[$clave];
            }
        };
    }
    
    if (!$_POST['com_comunidad'] || $_POST['com_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['com_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $avisos = [];

    if ($perfil <= 2) { #presidente o administrador
        $manPisos = ControladorDinamicoTabla::set('PISO');
        $manPisos->give(["pis_comunidad" => $_POST["com_comunidad"]]);
        $reg = $manPisos->getArray();
        unset($manPisos);
        $sum = 0; 
        for ($i = 0; $i < count($reg); $i++) { 
            $sum += ($reg[$i]["pis_porcentaje"] * 1); 
        }
        
        if (round($sum,2) != 100.00) {
            $avisos[] = ["tipo" =>"Mensaje", "Campo"=>"Porcentaje Pisos", "Detalle" => "La suma total de los pocentajes de los pisos es $sum%"];
        }
    }

    if ($perfil >= 2) { #usuario o presidente
        require_once $_SESSION['data']['conf']['home'].'tabla/propietario_piso.php';
        $manPisos = new View_PROPIETARIO_PISO();
        $resu = $manPisos->lista_pisos($_SESSION['data']['user']['id'], $_POST['com_comunidad'], date_format(new DateTime(), 'Y-m-d'));
        unset($manPisos);
        if (count($resu) == 0) $avisos[] = ["tipo" =>"Mensaje", "Campo"=>"Propietario", "Detalle" => "No está incluido como propietario en ningún piso"];
    }

    #ejecutamos las promesas que estén pendientes, da igual con qué perfil se acceda, se tiene que recalcular para la comunidad
    $manDeuda    = ControladorDinamicoTabla::set('DEUDA');
    $manPisos    = ControladorDinamicoTabla::set('PROMESA_PISO');
    $manPromesas = ControladorDinamicoTabla::set('PROMESA');
    #-recuperamos todas las promesas activas en la comunidad
    $manPromesas->give([
                    "psm_comunidad"    => $_POST["com_comunidad"], 
                    "psm_fdesde"       => date("Y-m-d"), 
                    "psm_fhasta"       => date("Y-m-d"), 
                    "psm_fdesde_signo" => "<=", 
                    "psm_fhasta_signo" => ">="
                ]);
    $promesas = $manPromesas->getArray();
    #-recorremos las promesas
    for ($i = 0; $i < count($promesas); $i++) {
        #echo "\nPROMESAS $i\n";
        #echo var_export($promesas[$i], true);
        #--buscando sus pisos
        $manPisos->give(["prp_promesa" => $promesas[$i]["psm_promesa"]]);
        $pisos = $manPisos->getArray();
        for ($z = 0; $z < count($pisos); $z++) {
            #echo "\nPISOS $z\n";
            #echo var_export($pisos[$z], true);
            #---buscamos los registros que ya tengamos asignados de esta deuda
            $manDeuda->give([
                "dud_comunidad" => $_POST["com_comunidad"],
                "dud_piso"      => $pisos[$z]["prp_piso"],
                "dud_promesa"   => $pisos[$z]["prp_promesa"]
            ]);
            $deudas = $manDeuda->getArray();
            #---ahora tenemos que definir los registros a crear
            $dia = "";
            if (count($deudas) > 0) {
                #-- si ya hay deuda grabada, cogemos la última y empezamos a contar desde ahí
                uasort($deudas, compararPorClave('dud_fecha', 'desc'));
                $fechaDesde = new DateTime($deudas[0]["dud_fecha"]);
                $dia = ($fechaDesde->format('d') == $fechaDesde->format('t'))?"last day of this month":"+".$fechaDesde->format('d')." day";
                $fechaDesde->modify('first day of this month')
                           ->modify('+'.$promesas[$i]["psm_periodo"].' month')
                           ->modify($dia);
            } else {
                #---si no hay deuda anterior, es la fecha de inicio de la promesa
                $fechaDesde = new DateTime($promesas[$i]["psm_fdesde"]);
                $dia = ($fechaDesde->format('d') == $fechaDesde->format('t'))?"last day of this month":"+".$fechaDesde->format('d')." day";
            }
            #--- la fecha de finalización será la de hoy (sino, no estaríamos revisando la promesa)
            $fechaHasta = new DateTime();
            #--- esta será la nueva fecha a grabar, si es que ya ha llegado el día
            
            while ($fechaDesde <= $fechaHasta) {
                $manDeuda->save([
                    "dud_comunidad" => $_POST["com_comunidad"] * 1,
                    "dud_piso"      => $pisos[$z]["prp_piso"] * 1,
                    "dud_promesa"   => $pisos[$z]["prp_promesa"] * 1,
                    "dud_fecha"     => $fechaDesde->format('Y-m-d'),
                    "dud_importe"   => $promesas[$i]["psm_importe"] * 1
                ]);
                $fechaDesde->modify('first day of this month')
                           ->modify('+'.$promesas[$i]["psm_periodo"].' month')
                           ->modify($dia);
            }
        }
    }
    if (count($avisos) > 0) {
        echo json_encode(['success' => false, 'root' => ["avisos" => $avisos, "perfil" => $perfil]]);
    } else {
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "Sin avisos", "perfil" => $perfil]]);
    }

    unset($manDeuda);
    unset($manPisos);
    unset($manPromesas);
?>