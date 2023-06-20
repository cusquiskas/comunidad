<?php
    require_once ('../required/controlSession.php');

    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
    $manMovimiento->give($_POST);
    $reg = $manMovimiento->getArray();
    $suma  = 0;
    $fondo = 0;
    for ($i=0; $i<count($reg); $i++) {
        $suma += $reg[$i]['mov_importe'];
        $fondo+= $reg[$i]['mov_importe'];
    }
    unset($reg);
    unset($manMovimiento);

    require_once $_SESSION['data']['conf']['home'].'tabla/movimiento_piso.php';
    $manMovPiso = new View_MOVIMIENTO_PISO();
    $saldo = $manMovPiso->saldo_pisos($_POST['mov_comunidad']);

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => ['saldoCuenta' => round($suma,2), 'fondoCuenta' => round($fondo,2), 'saldoPiso' => $saldo]]]);

    unset($saldo);
    unset($manMovimiento);
?>