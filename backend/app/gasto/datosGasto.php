<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    
    $link = new ConexionSistema();
    
    $uno = new DateTime();
    $dos = new DateTime();
    $uno = $uno->modify('first day of January last year')->format('Y-m-d');
    $dos = $dos->modify('last day of December last year')->format('Y-m-d');
    
    $anterior = $link->consulta("select sum(mov_importe)  importe
                              from MOVIMIENTO
                             where mov_comunidad = ?
                               and mov_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
                               and mov_fecha <= STR_TO_DATE('$dos', '%Y-%m-%d')
                               and mov_gasto          IN  (select gas_gasto
                                                             from GASTO
                                                            where gas_comunidad = ?
                                                              and gas_periodico = 1)
                               and mov_movimiento NOT IN  (select spl_movimiento
                                                             from SPLIT
                                                            where spl_comunidad = ?)
                             UNION
                            select sum(spl_importe) importe
                              from SPLIT
                             where spl_comunidad = ?
                               and spl_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
                               and spl_fecha <= STR_TO_DATE('$dos', '%Y-%m-%d')
                               and spl_gasto          IN  (select gas_gasto
                                                             from GASTO
                                                            where gas_comunidad = ?
                                                              and gas_periodico = 1)
                            ", 
                            [0 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             1 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             2 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             3 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             4 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']]
                            ]);
    
    $uno = new DateTime();
    $uno = $uno->modify('first day of January this year')->format('Y-m-d');
    $actual = $link->consulta("select sum(mov_importe)  importe
                              from MOVIMIENTO
                             where mov_comunidad = ?
                               and mov_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
                               and mov_gasto          IN  (select gas_gasto
                                                             from GASTO
                                                            where gas_comunidad = ?
                                                              and gas_periodico = 1)
                               and mov_movimiento NOT IN  (select spl_movimiento
                                                             from SPLIT
                                                            where spl_comunidad = ?)
                             UNION
                            select sum(spl_importe) importe
                              from SPLIT
                             where spl_comunidad = ?
                               and spl_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
                               and spl_gasto          IN  (select gas_gasto
                                                             from GASTO
                                                            where gas_comunidad = ?
                                                              and gas_periodico = 1)
                            ", 
                            [0 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             1 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             2 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             3 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             4 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']]
                            ]);
    
    $sumaAnterior = array_reduce($anterior, function($carry, $item) { return $carry + $item["importe"]; }, 0);
    $sumaActual   = array_reduce($actual,   function($carry, $item) { return $carry + $item["importe"]; }, 0);
    
    $link->close();
    unset($link);

    $reg = ["ejercicio" => ($sumaActual * (-1)), "anterior" => ($sumaAnterior * (-1))];

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>