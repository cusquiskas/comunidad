<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
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
                                   mov_gasto      gasto
                              from MOVIMIENTO
                             where mov_comunidad = ?
                               and mov_movimiento NOT IN  (select spl_movimiento
                                                             from SPLIT
                                                            where spl_comunidad = ?)
                             UNION
                            select spl_movimiento movimiento,
                                   spl_split      split,
                                   spl_detalle    detalle,
                                   spl_fecha      fecha,
                                   spl_importe    importe,
                                   spl_piso       piso,
                                   spl_gasto      gasto
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
            

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>