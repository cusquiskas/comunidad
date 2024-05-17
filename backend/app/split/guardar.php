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
    } elseif () {
    
        $manMovimiento = ControladorDinamicoTabla::set('SPLIT');
        $manMovimiento->give(["mov_movimiento" => $_POST["spl_movimiento"]]);
        $movi = $manMovimiento->getArray();
        unset($manMovimiento);
        $movi = $movi[0];

        $_POST["spl_fecha"] = $movi["mov_fecha"];
        $_POST

        $manSplit = ControladorDinamicoTabla::set('SPLIT');
        #require_once $_SESSION['data']['conf']['home'].'tabla/MOVIMIENTO.php';
        #$manMovimiento = new Tabla_MOVIMIENTO();
        if (isset($_POST['spl_split']) && $_POST['spl_split'] == "") unset($_POST['spl_split']);
        if (!isset($_POST['spl_piso'])) $_POST['spl_piso'] = null;
        if (!isset($_POST['spl_gasto'])) $_POST['spl_gasto'] = null;
        if (isset($_POST['spl_piso']) && $_POST['splpiso'] == "-1") unset($_POST['spl_piso']);
        if (isset($_POST['spl_gasto']) && $_POST['spl_gasto'] == "-1") unset($_POST['spl_gasto']);

        if ($manMmanSplitovimiento->save($_POST) == 0) {
            $reg = $manSplit->getArray();
            echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
        } else {
            $reg = $manSplit->getListaErrores();
            echo json_encode(['success' => false, 'root' => $reg]);
        }
        unset($manSplit);
    }
?>