<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['spl_comunidad'] || $_POST['spl_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['spl_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    if (isset($_POST['spl_detalle']) && $_POST['spl_detalle'] == 'DEL') {
        $link = new ConexionSistema();
        $link->ejecuta("delete
                          from SPLIT
                         where spl_comunidad  = ?
                           and spl_movimiento = ?", 
                    [0 => ['tipo' => 'i', 'dato' => $_POST['spl_comunidad']],
                     1 => ['tipo' => 'i', 'dato' => $_POST['spl_movimiento']]
                    ]);
        $link->close();
        unset($link);

        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Split borrado']]);
    } else  {
    
        $manSplit = ControladorDinamicoTabla::set('SPLIT');
        #require_once $_SESSION['data']['conf']['home'].'tabla/SPLIT.php';
        #$manSplit = new Tabla_SPLIT();
        if (isset($_POST['spl_split']) && $_POST['spl_split'] == "") unset($_POST['spl_split']);
        if (!isset($_POST['spl_piso'])) $_POST['spl_piso'] = null;
        if (!isset($_POST['spl_gasto'])) $_POST['spl_gasto'] = null;
        if (isset($_POST['spl_piso']) && $_POST['spl_piso'] == "-1") unset($_POST['spl_piso']);
        if (isset($_POST['spl_gasto']) && $_POST['spl_gasto'] == "-1") unset($_POST['spl_gasto']);

        if (isset($_POST['spl_detalle']) && isset($_POST['spl_detalle'])) {
            #se están guardando los datos de un split nuevo (hay detalle)
            #y tendremos que recuperar información del movimiento
            $manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
            $manMovimiento->give(["mov_movimiento" => $_POST["spl_movimiento"]]);
            $movi = $manMovimiento->getArray();
            unset($manMovimiento);
            $movi = $movi[0];

            $_POST["spl_fecha"] = $movi["mov_fecha"];
        
            $datSplit = [];
            $tmpSplit = json_decode($_POST['spl_detalle']);
            $importe = 0;
            if (count($tmpSplit) < 3) {
                die (json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "En un Split tiene que haber más de un elemento"]]));
            }
            
            for ($i = 0; $i < count($tmpSplit); $i+=2) {
                $importe+= $tmpSplit[$i+1]*1;
                array_push($datSplit, ["spl_split" => null, "spl_comunidad" => $_POST["spl_comunidad"], "spl_movimiento" => $_POST["spl_movimiento"], "spl_fecha" => $_POST["spl_fecha"], "spl_detalle" => $tmpSplit[$i], "spl_importe" => $tmpSplit[$i+1]*1]);
            }
            if ($importe != $movi["mov_importe"]*1) {
                unset($manSplit);
                die (json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "La suma del Split no coincide con la del movimiento"]]));
            }
        } else {
            if (isset($_POST['spl_split']) && $_POST['spl_split'] != "") {
                #estamos modificando el split
                if ($manSplit->save($_POST) == 0) {
                    $reg = $manSplit->getArray();
                    unset($manSplit);
                    die (json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]));
                } else {
                    $reg = $manSplit->getListaErrores();
                    unset($manSplit);
                    die(json_encode(['success' => false, 'root' => $reg]));
                }
            } else {
                unset($manSplit);
                die (json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "información de Split no válida"]]));
            }
            
        }
        
        #borramos los splits del movimiento, por si hubiese alguno
        $link = new ConexionSistema();
        $link->ejecuta("delete
                          from SPLIT
                         where spl_comunidad  = ?
                           and spl_movimiento = ?", 
                    [0 => ['tipo' => 'i', 'dato' => $_POST['spl_comunidad']],
                     1 => ['tipo' => 'i', 'dato' => $_POST['spl_movimiento']]
                    ]);
        $link->close();
        unset($link);

        #y luego creamos los nuevos
        $error = 0;
        for ($i = 0; $i < count($datSplit); $i++) {
            $tmpSplit = $datSplit[$i];
            unset($tmpSplit["spl_split"]);
            $error+= $manSplit->save($tmpSplit);
        }
        
        if ($error == 0) {
            echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Recarga el listado de movimientos']]);
        } else {
            echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Se han producido errores al guardar el Split']]);
        }
        unset($manSplit);
    }
?>