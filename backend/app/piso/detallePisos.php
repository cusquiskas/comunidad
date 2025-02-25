<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['pis_comunidad'] || $_POST['pis_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['pis_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $link = new ConexionSistema();
    $datos = $link->consulta("
    select importe, fecha, detalle, movimiento
      from (
         select mov_detalle as detalle,
                mov_fecha   as fecha,
                mov_importe as importe,
                'mov'       as movimiento
           from MOVIMIENTO
          where mov_comunidad = ? 
            and mov_piso = ?
            and mov_movimiento NOT IN  (select spl_movimiento from SPLIT where spl_comunidad = ?)
          UNION ALL
         select spl_detalle as detalle,
                spl_fecha   as fecha,
                spl_importe as importe,
                'mov'       as movimiento
           from SPLIT
          where spl_comunidad = ?
            and spl_piso = ?
          UNION ALL
         select psm_detalle as detalle,
                dud_fecha   as fecha,
                dud_importe as importe,
                'dud'       as movimiento 
           from PROMESA, DEUDA
          where psm_promesa = dud_promesa
            and psm_comunidad = dud_comunidad
            and dud_comunidad = ? 
            and dud_piso = ?
        ) datos
        order by fecha asc
        ",                      [0 => ['tipo' => 'i', 'dato' => $_POST['pis_comunidad']],
                                 1 => ['tipo' => 'i', 'dato' => $_POST['pis_piso']],
                                 2 => ['tipo' => 'i', 'dato' => $_POST['pis_comunidad']],
                                 3 => ['tipo' => 'i', 'dato' => $_POST['pis_comunidad']],
                                 4 => ['tipo' => 'i', 'dato' => $_POST['pis_piso']],
                                 5 => ['tipo' => 'i', 'dato' => $_POST['pis_comunidad']],
                                 6 => ['tipo' => 'i', 'dato' => $_POST['pis_piso']]
                                ]);
    unset($link);
    
    $total = 0;
    for ($i = 0; $i < count($datos); $i++) {
        $datos[$i]["sumatorio"] = ($datos[$i]["importe"] * 1) + $total;
        $total += $datos[$i]["importe"] * 1;
    }                                

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $datos]]);
    
    unset($datos);
?>