<?php
    require_once ('../../required/controlSession.php');

    header('Content-Type: application/json');

    /* =====================================================
    VALIDACIONES BÁSICAS DE ENTRADA
    ===================================================== */
    xdebug_break();

    if (!$_POST['psm_comunidad'] || $_POST['psm_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['psm_comunidad']);
    if ($perfil < 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    
    if (!isset($_POST['derrama']) || !isset($_POST['der_comunidad'])) {
        echo json_encode(['success'=>false,'root'=>[['tipo'=>'Validacion','Detalle'=>'Datos incompletos']]]);
        exit;
    }

    $trasacConex = new ConexionSistema();
    $trasacConex->beginTrasaction();

    try {

        $manPromesa = ControladorDinamicoTabla::set('PROMESA', $trasacConex);
        $manPromesa->give([
            psm_comunidad => $_POST['psm_comunidad'],
            psm_derrama => $_POST['psm_derrama']
        ]);
        $promesa = $manPromesa->getArray();

        if (count($promesa) > 0) {
            throw new Exception(json_encode([['tipo'=>'Validacion','Detalle'=>'Esta derrama ya se ha validado para pago']]));
        }

        $comunidad   = intval($_POST['psm_comunidad']);
        $fechaInicio = $_POST['fecha_inicio'] ?? null;
        $numCuotas   = intval($_POST['num_cuotas'] ?? 0);
        $periodo     = intval($_POST['periodicidad'] ?? 0);
        $tipo        = $_POST['tipo_reparto'] ?? 'igual';

        /* =====================================================
        5. OBTENER DATOS REALES DE LA DERRAMA
        ===================================================== */

        $manDerrama = ControladorDinamicoTabla::set('DERRAMA', $trasacConex);
        $manDerrama->give([
            der_comunidad => $_POST['psm_comunidad'],
            der_derrama => $_POST['psm_derrama']
        ]);
        $derrama = $manDerrama->getArray();

        if (count($derrama) <> 1) {
            throw new Exception(json_encode([['tipo'=>'Datos','Detalle'=>'Derrama no encontrada']]));
        }

        /* =====================================================
        6. OBTENER PISOS SELECCIONADOS (DESDE FRONT)
        ===================================================== */

        if (!isset($_POST['vecino']) || !is_array($_POST['vecino'])) {
            throw new Exception(json_encode([['tipo'=>'Validacion','Detalle'=>'No hay vecinos seleccionados']]));    
        }

        $vecinosSeleccionados = array_map('intval', $_POST['vecino']);

        /* =====================================================
        7. OBTENER DATOS REALES DE PISOS
        ===================================================== */

        $piso = ControladorDinamicoTabla::set('pis', $trasacConex);

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

        $prp = ControladorDinamicoTabla::set('prp', $trasacConex);

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
        $trasacConex->commit();
        $trasacConex->close();
        echo json_encode([
            'success'=>true,
            'root'=>['Detalle'=>'Promesa creada correctamente']
        ]);
    } catch (Exception $e) {
        $trasacConex->rollback();
        $trasacConex->close();
        echo json_encode([
            'success'=>false,
            'root'=>[['tipo'=>'DB','Detalle'=>$e->getMessage()]]
        ]);

        exit;
    }

?>