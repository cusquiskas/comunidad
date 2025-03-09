<?php
    require_once ('../../required/controlSession.php');
    require_once ('../../required/utiles.php');

    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    #if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    
    /*$manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
    $manMovimiento->give($_POST);
    $reg = $manMovimiento->getArray();
    unset($manMovimiento);*/
    $link = new ConexionSistema();
    $reg = $link->consulta("select mov_movimiento movimiento,
                                   null           split, 
                                   mov_detalle    detalle,
                                   mov_fecha      fecha,
                                   mov_importe    importe,
                                   mov_piso       piso,
                                   mov_gasto      gasto,
                                   mov_documento  documento
                              from MOVIMIENTO
                             where mov_comunidad = ?
                               and mov_movimiento NOT IN  (select spl_movimiento
                                                             from SPLIT
                                                            where spl_comunidad = ?)
                             UNION ALL
                            select spl_movimiento movimiento,
                                   spl_split      split,
                                   spl_detalle    detalle,
                                   spl_fecha      fecha,
                                   spl_importe    importe,
                                   spl_piso       piso,
                                   spl_gasto      gasto,
                                   spl_documento  documento
                              from SPLIT
                             where spl_comunidad = ?
                             order
                                by fecha desc
                            ", 
                            [0 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             1 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             2 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']]
                            ]);
    $link->close();
    unset($link);
    
    # vamos a recuperar la descripción del gasto
    $manGastos = ControladorDinamicoTabla::set('GASTO');
    $manGastos->give(array("gas_comunidad" => $_POST['mov_comunidad']));
    $lstGastos = $manGastos->getArray();
    unset($manGastos);
    # vamos a recuperar la descripción del piso
    $manPisos = ControladorDinamicoTabla::set('PISO');
    $manPisos->give(array("pis_comunidad" => $_POST["mov_comunidad"]));
    $lstPisos = $manPisos->getArray();
    unset($manPisos);
    # vamos a recuperar la descripción del documento
    /*
    $manDocumento = ControladorDinamicoTabla::set('DOCUMENTO');
    $manDocumento->give(array("doc_comunidad" => $_POST["mov_comunidad"]));
    $lstDocumento = $manDocumento->getArray();
    unset($manDocumento);
    */
    for ($i = 0; $i < count($reg); $i++) {
        if ($reg[$i]["gasto"] != "") {
            $reg[$i]["gastoX"] = buscarEnArray($lstGastos, $reg[$i]["gasto"], "gas_gasto", "gas_nombre");
        } else {
            $reg[$i]["gastoX"] = "";
        }
        if ($reg[$i]["piso"] != "") {
            $reg[$i]["pisoX"] = buscarEnArray($lstPisos, $reg[$i]["piso"], "pis_piso", "pis_nombre");
        } else {
            $reg[$i]["pisoX"] = "";
        }
        /*
        if ($reg[$i]["documento"] != "") {
            $reg[$i]["documentoX"] = buscarEnArray($lstPisos, $reg[$i]["documento"], "doc_documento", "doc_real");
        } else {
            $reg[$i]["documentoX"] = "";
        }
        */
    }


    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>