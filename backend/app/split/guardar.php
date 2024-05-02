<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['spl_comunidad'] || $_POST['spl_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['spl_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    $manMovimiento = ControladorDinamicoTabla::set('SPLIT');
    #require_once $_SESSION['data']['conf']['home'].'tabla/MOVIMIENTO.php';
    #$manMovimiento = new Tabla_MOVIMIENTO();
    if (isset($_POST['spl_split']) && $_POST['spl_split'] == "") unset($_POST['spl_split']);
    if (!isset($_POST['spl_piso'])) $_POST['spl_piso'] = null;
    if (!isset($_POST['spl_gasto'])) $_POST['spl_gasto'] = null;
    if (isset($_POST['spl_piso']) && $_POST['splpiso'] == "-1") unset($_POST['spl_piso']);
    if (isset($_POST['spl_gasto']) && $_POST['spl_gasto'] == "-1") unset($_POST['spl_gasto']);

    if ($manMovimiento->save($_POST) == 0) {
        $reg = $manMovimiento->getArray();
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    } else {
        $reg = $manMovimiento->getListaErrores();
        echo json_encode(['success' => false, 'root' => $reg]);
    }
    unset($manMovimiento);
?>