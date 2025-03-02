<?php
    require_once ('../../required/controlSession.php');
    require_once $_SESSION['data']['conf']['home'].'js/function.php';
    if (!$_POST['mov_comunidad'] || $_POST['mov_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['mov_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    setlocale(LC_TIME, 'es_ES.UTF-8');

    $link = new ConexionSistema();
    
    
    $uno = new DateTime();
    $dos = new DateTime();
    $tm  = ($uno->format('m')*1 % 2 == 0?'13':'12');
    $td  = ($uno->format('m')*1 % 2 == 0? '1': '0');
    $unt = $uno->modify('first day of this month')->modify("-$tm month")->format('Y-m-d');
    $dot = $dos->modify('first day of this month')->modify("-$td month")->modify('-1 day')->format('Y-m-d');
    $bimestres = [];
    $control   = [];
    while ($uno < $dos) {
      switch ($uno->format('m')) {
        case "01": $control[] = $uno->format("Y")."0"; break;
        case "03": $control[] = $uno->format("Y")."1"; break;
        case "05": $control[] = $uno->format("Y")."2"; break;
        case "07": $control[] = $uno->format("Y")."3"; break;
        case "09": $control[] = $uno->format("Y")."4"; break;
        case "11": $control[] = $uno->format("Y")."5";
      }
      $cad = Fecha::nombreMesCorto($uno->format('m')) . '. ';
      $uno->modify('+1 month');
      $cad .= Fecha::nombreMesCorto($uno->format('m')) . '. ';
      $cad .= $uno->format("'y");
      $bimestres[] = $cad;
      $uno->modify('+1 month');
  }
    
  $datos = $link->consulta("
select sum(importe) importe, servicio, bimestre
  from (
     select gas_nombre as servicio,
            CASE 
              WHEN MONTH(mov_fecha) IN ( 1,  2) THEN CONCAT(YEAR(mov_fecha),'0')
              WHEN MONTH(mov_fecha) IN ( 3,  4) THEN CONCAT(YEAR(mov_fecha),'1')
              WHEN MONTH(mov_fecha) IN ( 5,  6) THEN CONCAT(YEAR(mov_fecha),'2')
              WHEN MONTH(mov_fecha) IN ( 7,  8) THEN CONCAT(YEAR(mov_fecha),'3')
              WHEN MONTH(mov_fecha) IN ( 9, 10) THEN CONCAT(YEAR(mov_fecha),'4')
              WHEN MONTH(mov_fecha) IN (11, 12) THEN CONCAT(YEAR(mov_fecha),'5')
            END AS bimestre,
            mov_importe as importe
        from MOVIMIENTO,
            GASTO
      where mov_gasto = gas_gasto
        and mov_comunidad = ? 
        and mov_fecha >= STR_TO_DATE('$unt', '%Y-%m-%d')
        and mov_fecha <= STR_TO_DATE('$dot', '%Y-%m-%d')
        and mov_movimiento NOT IN  (select spl_movimiento from SPLIT where spl_comunidad = ?)
        and gas_periodico = 1
      UNION ALL
      select gas_nombre as servicio,
            CASE 
              WHEN MONTH(spl_fecha) IN ( 1,  2) THEN CONCAT(YEAR(spl_fecha),'0')
              WHEN MONTH(spl_fecha) IN ( 3,  4) THEN CONCAT(YEAR(spl_fecha),'1')
              WHEN MONTH(spl_fecha) IN ( 5,  6) THEN CONCAT(YEAR(spl_fecha),'2')
              WHEN MONTH(spl_fecha) IN ( 7,  8) THEN CONCAT(YEAR(spl_fecha),'3')
              WHEN MONTH(spl_fecha) IN ( 9, 10) THEN CONCAT(YEAR(spl_fecha),'4')
              WHEN MONTH(spl_fecha) IN (11, 12) THEN CONCAT(YEAR(spl_fecha),'5')
            END AS bimestre,
            spl_importe as importe
        from SPLIT, GASTO
      where spl_gasto = gas_gasto  
        and spl_comunidad = ?
        and spl_fecha >= STR_TO_DATE('$unt', '%Y-%m-%d')
        and spl_fecha <= STR_TO_DATE('$dot', '%Y-%m-%d')
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
    $servicio = "";
    $iControl = 0;
    for ($i = 0; $i < count($datos); $i++) {
      if ($servicio == $datos[$i]["servicio"]) {
        while ($control[$iControl] != $datos[$i]["bimestre"] && $iControl < 6) { $reg["data"][count($reg["data"])] = 0; $iControl++; }
        $reg["data"][count($reg["data"])] = $datos[$i]["importe"]*-1;
        $iControl++;
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
        $iControl = 0;
        while ($control[$iControl] != $datos[$i]["bimestre"] && $iControl < 6) { $reg["data"][count($reg["data"])] = 0; $iControl++; }
        $reg["data"][count($reg["data"])] = $datos[$i]["importe"]*-1;
        $iControl++;
      }
    }
    while (count($reg["data"]) < 6) $reg["data"][count($reg["data"])] = 0;
    $dataset[count($dataset)] = $reg;
   

    $datos = $link->consulta("
select sum(importe) importe, bimestre
  from (
     select CASE 
              WHEN MONTH(mov_fecha) IN ( 1,  2) THEN CONCAT(YEAR(mov_fecha),'0')
              WHEN MONTH(mov_fecha) IN ( 3,  4) THEN CONCAT(YEAR(mov_fecha),'1')
              WHEN MONTH(mov_fecha) IN ( 5,  6) THEN CONCAT(YEAR(mov_fecha),'2')
              WHEN MONTH(mov_fecha) IN ( 7,  8) THEN CONCAT(YEAR(mov_fecha),'3')
              WHEN MONTH(mov_fecha) IN ( 9, 10) THEN CONCAT(YEAR(mov_fecha),'4')
              WHEN MONTH(mov_fecha) IN (11, 12) THEN CONCAT(YEAR(mov_fecha),'5')
            END AS bimestre,
            mov_importe as importe
        from MOVIMIENTO,
            PISO
      where mov_piso = pis_piso
        and mov_comunidad = ? 
        and mov_fecha >= STR_TO_DATE('$unt', '%Y-%m-%d')
        and mov_fecha <= STR_TO_DATE('$dot', '%Y-%m-%d')
        and mov_movimiento NOT IN  (select spl_movimiento from SPLIT where spl_comunidad = ?)
      UNION ALL
      select CASE 
              WHEN MONTH(spl_fecha) IN ( 1,  2) THEN CONCAT(YEAR(spl_fecha),'0')
              WHEN MONTH(spl_fecha) IN ( 3,  4) THEN CONCAT(YEAR(spl_fecha),'1')
              WHEN MONTH(spl_fecha) IN ( 5,  6) THEN CONCAT(YEAR(spl_fecha),'2')
              WHEN MONTH(spl_fecha) IN ( 7,  8) THEN CONCAT(YEAR(spl_fecha),'3')
              WHEN MONTH(spl_fecha) IN ( 9, 10) THEN CONCAT(YEAR(spl_fecha),'4')
              WHEN MONTH(spl_fecha) IN (11, 12) THEN CONCAT(YEAR(spl_fecha),'5')
            END AS bimestre,
            spl_importe as importe
        from SPLIT, PISO
      where spl_piso = pis_piso  
        and spl_comunidad = ?
        and spl_fecha >= STR_TO_DATE('$unt', '%Y-%m-%d')
        and spl_fecha <= STR_TO_DATE('$dot', '%Y-%m-%d')      
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
    $iControl = 0;
    for ($i = 0; $i < count($datos); $i++) {
      while ($control[$iControl] != $datos[$i]["bimestre"] && $iControl < 6) { $reg["data"][count($reg["data"])] = 0; $iControl++; }
      $reg["data"][count($reg["data"])] = $datos[$i]["importe"];
      $iControl++;
    }
    while (count($reg["data"]) < 6) $reg["data"][count($reg["data"])] = 0;
    $dataset[count($dataset)] = $reg;

    $link->close();
    unset($link);


    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'control' => ['desde' => $unt, 'hasta' => $dot, 'bimestres' => $bimestres], 'datos' => $dataset]]);
?>