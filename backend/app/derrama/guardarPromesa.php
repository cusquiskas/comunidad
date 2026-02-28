<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['der_comunidad'] || $_POST['der_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['der_comunidad']);
    if ($perfil < 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    

header('Content-Type: application/json');

/* =====================================================
   1. VALIDACIONES BÁSICAS DE ENTRADA
===================================================== */

if (!isset($_POST['derrama']) || !isset($_POST['der_comunidad'])) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Validacion','Detalle'=>'Datos incompletos']]]);
    exit;
}

$derrama     = intval($_POST['derrama']);
$comunidad   = intval($_POST['der_comunidad']);
$fechaInicio = $_POST['fecha_inicio'] ?? null;
$numCuotas   = intval($_POST['num_cuotas'] ?? 0);
$periodo     = intval($_POST['periodicidad'] ?? 0);
$tipo        = $_POST['tipo_reparto'] ?? 'igual';

/* =====================================================
   2. CONTROL DE PERFIL
===================================================== */

$perfil = controlPerfil($comunidad);
if ($perfil < 1) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Permisos','Detalle'=>'Sin acceso a la comunidad']]]);
    exit;
}

/* =====================================================
   3. VALIDACIONES FUNCIONALES
===================================================== */

if (!$fechaInicio || $numCuotas < 1 || $periodo < 1) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Validacion','Detalle'=>'Configuración inválida']]]);
    exit;
}

/* =====================================================
   4. COMPROBAR QUE LA DERRAMA NO ESTÉ YA CONVERTIDA
===================================================== */

$psm = ControladorDinamicoTabla::set('psm');

$existe = $psm->customQuery("
    SELECT COUNT(*) as total 
    FROM psm 
    WHERE psm_derrama = {$derrama}
");

if ($existe[0]['total'] > 0) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Logica','Detalle'=>'La derrama ya está convertida en promesa']]]);
    exit;
}

/* =====================================================
   5. OBTENER DATOS REALES DE LA DERRAMA
===================================================== */

$der = ControladorDinamicoTabla::set('der');
$derData = $der->findById(['der_comunidad'=>$comunidad,'der_derrama'=>$derrama]);

if (!$derData) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Datos','Detalle'=>'Derrama no encontrada']]]);
    exit;
}

$importeTotal = floatval($derData['der_total']);

/* =====================================================
   6. OBTENER PISOS SELECCIONADOS (DESDE FRONT)
===================================================== */

if (!isset($_POST['vecino']) || !is_array($_POST['vecino'])) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Validacion','Detalle'=>'No hay vecinos seleccionados']]]);
    exit;
}

$vecinosSeleccionados = array_map('intval', $_POST['vecino']);

/* =====================================================
   7. OBTENER DATOS REALES DE PISOS
===================================================== */

$piso = ControladorDinamicoTabla::set('pis');

$listaPisos = $piso->customQuery("
    SELECT pis_piso, pis_porcentaje 
    FROM pis 
    WHERE pis_comunidad = {$comunidad}
    AND pis_piso IN (".implode(',', $vecinosSeleccionados).")
");

if (count($listaPisos) == 0) {
    echo json_encode(['success'=>false,'root'=>[['tipo'=>'Validacion','Detalle'=>'Vecinos inválidos']]]);
    exit;
}

/* =====================================================
   8. RECALCULAR IMPORTES BACKEND
===================================================== */

$grupos = [];
$sumaCoef = 0;

if ($tipo === 'coef') {
    foreach ($listaPisos as $p) {
        $sumaCoef += floatval($p['pis_porcentaje']);
    }
}

$numVecinos = count($listaPisos);

foreach ($listaPisos as $p) {

    if ($tipo === 'igual') {
        $totalVecino = $importeTotal / $numVecinos;
    } else {
        $totalVecino = $importeTotal * (floatval($p['pis_porcentaje']) / $sumaCoef);
    }

    $totalVecino = round($totalVecino, 2);
    $importeCuota = round($totalVecino / $numCuotas, 2);

    $clave = number_format($importeCuota, 2, '.', '');

    $grupos[$clave]['importe'] = $importeCuota;
    $grupos[$clave]['pisos'][] = $p['pis_piso'];
}

/* =====================================================
   9. CREAR PROMESAS AGRUPADAS
===================================================== */

$fechaDesde = new DateTime($fechaInicio);
$fechaHasta = clone $fechaDesde;
$fechaHasta->modify('+'.($periodo * ($numCuotas-1)).' month');

$prp = ControladorDinamicoTabla::set('prp');

foreach ($grupos as $grupo) {

    $psmData = [
        'psm_comunidad' => $comunidad,
        'psm_derrama'   => $derrama,
        'psm_fdesde'    => $fechaDesde->format('Y-m-d'),
        'psm_fhasta'    => $fechaHasta->format('Y-m-d'),
        'psm_importe'   => $grupo['importe'],
        'psm_periodo'   => $periodo
    ];

    $status = $psm->save($psmData);

    if ($status != 0) {
        echo json_encode(['success'=>false,'root'=>$psm->getListaErrores()]);
        exit;
    }

    $idPromesa = $psm->getArray()['psm_promesa'];

    foreach ($grupo['pisos'] as $pisoId) {

        $status = $prp->save([
            'prp_promesa' => $idPromesa,
            'prp_piso'    => $pisoId
        ]);

        if ($status != 0) {
            echo json_encode(['success'=>false,'root'=>$prp->getListaErrores()]);
            exit;
        }
    }
}

/* =====================================================
   10. RESPUESTA OK
===================================================== */

echo json_encode([
    'success'=>true,
    'root'=>['Detalle'=>'Promesa creada correctamente']
]);

?>