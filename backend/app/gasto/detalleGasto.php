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
    
    $datos = $link->consulta("
select sum(importe) importe, servicio, bimestre
  from (
     select gas_nombre as servicio,
            CASE 
              WHEN MONTH(mov_fecha) IN ( 1,  2) THEN '0'
              WHEN MONTH(mov_fecha) IN ( 3,  4) THEN '1'
              WHEN MONTH(mov_fecha) IN ( 5,  6) THEN '2'
              WHEN MONTH(mov_fecha) IN ( 7,  8) THEN '3'
              WHEN MONTH(mov_fecha) IN ( 9, 10) THEN '4'
              WHEN MONTH(mov_fecha) IN (11, 12) THEN '5'
            END AS bimestre,
            mov_importe as importe
        from MOVIMIENTO,
            GASTO
      where mov_gasto = gas_gasto
        and mov_comunidad = ? 
        and mov_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
        and mov_fecha <= STR_TO_DATE('$dos', '%Y-%m-%d')
        and mov_movimiento NOT IN  (select spl_movimiento from SPLIT where spl_comunidad = ?)
        and gas_periodico = 1
      UNION 
      select gas_nombre as servicio,
            CASE 
              WHEN MONTH(spl_fecha) IN ( 1,  2) THEN '0'
              WHEN MONTH(spl_fecha) IN ( 3,  4) THEN '1'
              WHEN MONTH(spl_fecha) IN ( 5,  6) THEN '2'
              WHEN MONTH(spl_fecha) IN ( 7,  8) THEN '3'
              WHEN MONTH(spl_fecha) IN ( 9, 10) THEN '4'
              WHEN MONTH(spl_fecha) IN (11, 12) THEN '5'
            END AS bimestre,
            spl_importe as importe
        from SPLIT, GASTO
      where spl_gasto = gas_gasto  
        and spl_comunidad = ?
        and spl_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
        and spl_fecha <= STR_TO_DATE('$dos', '%Y-%m-%d')
        and gas_periodico = 1        
    ) datos
    group by servicio, bimestre
    order by servicio, bimestre  
    ",                      [0 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             1 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             2 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']]
                            ]);
    
    $reg = [];
    $dataset = [];
    for ($i = 0; $i < count($datos); $i++) {
      if ($servicio == $datos[$i]["servicio"]) {
        while (count($reg["data"]) < (int)$datos[$i]["bimestre"]) $reg["data"][count($reg["data"])] = 0;
        $reg["data"][count($reg["data"])] = $datos[$i]["importe"]*-1;
      } else {
        if ($reg != []) {
          while (count($reg["data"]) < 6) $reg["data"][count($reg["data"])] = 0;
          $dataset[count($dataset)] = $reg;
        }
        $servicio = $datos[$i]["servicio"];
        $reg["label"] = $servicio;
        $reg["borderWidth"] = 1;
        $reg["stack"] = 'Stack 0';
        $reg["data"] = [];
        while (count($reg["data"]) < (int)$datos[$i]["bimestre"]) $reg["data"][count($reg["data"])] = 0;
        $reg["data"][count($reg["data"])] = $datos[$i]["importe"]*-1;
      }
    }
    while (count($reg["data"]) < 6) $reg["data"][count($reg["data"])] = 0;
    $dataset[count($dataset)] = $reg;
    
    $datos = $link->consulta("
select sum(importe) importe, bimestre
  from (
     select CASE 
              WHEN MONTH(mov_fecha) IN ( 1,  2) THEN '0'
              WHEN MONTH(mov_fecha) IN ( 3,  4) THEN '1'
              WHEN MONTH(mov_fecha) IN ( 5,  6) THEN '2'
              WHEN MONTH(mov_fecha) IN ( 7,  8) THEN '3'
              WHEN MONTH(mov_fecha) IN ( 9, 10) THEN '4'
              WHEN MONTH(mov_fecha) IN (11, 12) THEN '5'
            END AS bimestre,
            mov_importe as importe
        from MOVIMIENTO,
            PISO
      where mov_piso = pis_piso
        and mov_comunidad = ? 
        and mov_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
        and mov_fecha <= STR_TO_DATE('$dos', '%Y-%m-%d')
        and mov_movimiento NOT IN  (select spl_movimiento from SPLIT where spl_comunidad = ?)
      UNION 
      select CASE 
              WHEN MONTH(spl_fecha) IN ( 1,  2) THEN '0'
              WHEN MONTH(spl_fecha) IN ( 3,  4) THEN '1'
              WHEN MONTH(spl_fecha) IN ( 5,  6) THEN '2'
              WHEN MONTH(spl_fecha) IN ( 7,  8) THEN '3'
              WHEN MONTH(spl_fecha) IN ( 9, 10) THEN '4'
              WHEN MONTH(spl_fecha) IN (11, 12) THEN '5'
            END AS bimestre,
            spl_importe as importe
        from SPLIT, PISO
      where spl_piso = pis_piso  
        and spl_comunidad = ?
        and spl_fecha >= STR_TO_DATE('$uno', '%Y-%m-%d')
        and spl_fecha <= STR_TO_DATE('$dos', '%Y-%m-%d')      
    ) datos
    group by bimestre
    order by bimestre  
    ",                      [0 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             1 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']],
                             2 => ['tipo' => 'i', 'dato' => $_POST['mov_comunidad']]
                            ]);

    $reg = [];
    $reg["label"] = "Ingresos";
    $reg["borderWidth"] = 1;
    $reg["stack"] = 'Stack 1';
    $reg["data"] = [];
    for ($i = 0; $i < count(); $i++) {
      while (count($reg["data"]) < (int)$datos[$i]["bimestre"]) $reg["data"][count($reg["data"])] = 0;
      $reg["data"][count($reg["data"])] = $datos[$i]["importe"];
    }
    while (count($reg["data"]) < 6) $reg["data"][count($reg["data"])] = 0;
    $dataset[count($dataset)] = $reg;

    $link->close();
    unset($link);


    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'datos' => $dataset]]);
?>