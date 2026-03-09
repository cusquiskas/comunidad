<?php
    require_once ('../../required/controlSession.php');

    header('Content-Type: application/json');

    /* =====================================================
    VALIDACIONES BÁSICAS DE ENTRADA
    ===================================================== */
    if (!$_POST['psm_comunidad'] || $_POST['psm_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => ['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]));
    }

    $perfil = controlPerfil($_POST['psm_comunidad']);
    if ($perfil < 1) die(json_encode(['success' => false, 'root' => ['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]));
    
    
    if (!isset($_POST['psm_derrama']) || !isset($_POST['psm_comunidad'])) {
        echo json_encode(['success'=>false,'root'=>['tipo'=>'Validacion','Detalle'=>'Datos incompletos']]);
        exit;
    }

    $transacConex = new ConexionSistema();
    
    try {

        $transacConex->begin();

        $manPromesa = ControladorDinamicoTabla::set('PROMESA', $transacConex);
        $manPromesa->give([
            "psm_comunidad" => $_POST['psm_comunidad'],
            "psm_derrama" => $_POST['psm_derrama']
        ]);
        $promesa = $manPromesa->getArray();

        if (count($promesa) > 0) {
            throw new Exception(json_encode(['tipo'=>'Validacion','Detalle'=>'Esta derrama ya se ha validado para pago']));
        }

        $comunidad   = intval($_POST['psm_comunidad']);
        $fechaInicio = $_POST['psm_fdesde'] ?? null;
        $numCuotas   = intval($_POST['num_cuotas'] ?? 0);
        $periodo     = intval($_POST['psm_periodo'] ?? 0);
        $tipo        = $_POST['tipo_reparto'] ?? 'igual';

        /* =====================================================
        5. OBTENER DATOS REALES DE LA DERRAMA
        ===================================================== */

        $manDerrama = ControladorDinamicoTabla::set('DERRAMA', $transacConex);
        $manDerrama->give([
            "der_comunidad" => $_POST['psm_comunidad'],
            "der_derrama" => $_POST['psm_derrama']
        ]);
        $derrama = $manDerrama->getArray();

        if (count($derrama) <> 1) {
            throw new Exception(json_encode(['tipo'=>'Validacion','Detalle'=>'Derrama no encontrada']));
        }

        $detalleDerrama = $derrama[0]['der_nombre'];
        $importeTotal = floatval($derrama[0]['der_total']);

        /* =====================================================
        6. OBTENER PISOS SELECCIONADOS (DESDE FRONT)
        ===================================================== */

        $vecinosSeleccionados = json_decode($_POST['psm_vecinos'] ?? null, true);  
        $promesaTotal = 0.0;
        foreach ($vecinosSeleccionados as $v) {
            $promesaTotal += floatval(str_replace(',', '.', str_replace('.', '', str_replace('.', '', $v['imp_cuota'])))) * $numCuotas;
        }
        
        if ($importeTotal > $promesaTotal) {
            throw new Exception(json_encode(['tipo'=>'Validacion','Detalle'=>'El importe total de las promesas no coincide con el importe de la derrama']));
        }
        
        /* =====================================================
        7. AGRUPAR PROMESAS POR CUOTA
        ===================================================== */
        $pisos_promesa = [];
        foreach ($vecinosSeleccionados as $item) {
            if (empty($item['checked'])) { continue; }

            $cuota = $item['imp_cuota'];
            $pisos_promesa[$cuota][] = $item['pis_piso'];
        }

        $manPromesaPiso = ControladorDinamicoTabla::set('PROMESA_PISO', $transacConex);
        /* =====================================================
        8. GRABAR PROMESAS
        ===================================================== */
        
        $fechaDesde = new DateTime($fechaInicio);
        $fechaHasta = clone $fechaDesde;
        $fechaHasta->modify('+'.($periodo * ($numCuotas-1)).' month');

        foreach ($pisos_promesa as $importe => $pisos) {
            $psm_promesa = $transacConex->consulta("SELECT psm_promesa
                                    FROM PROMESA
                                    WHERE psm_comunidad = ? 
                                    ORDER BY psm_promesa DESC
                                    LIMIT 1
                                    FOR UPDATE", 
                                    [
                                        0 => ['tipo'=>'i','dato'=>$comunidad]
                                    ]);    
            $psm_promesa = ($psm_promesa[0]['psm_promesa'] ?? 0) + 1;
            $psmData = [
                'psm_promesa'   => $psm_promesa,
                'psm_comunidad' => $comunidad,
                'psm_detalle'   => $detalleDerrama,
                'psm_derrama'   => $derrama[0]['der_derrama'],
                'psm_fdesde'    => $fechaDesde->format('Y-m-d'),
                'psm_fhasta'    => $fechaHasta->format('Y-m-d'),
                'psm_importe'   => floatval(str_replace(',', '.', str_replace('.', '', $importe))) * -1,
                'psm_periodo'   => $periodo
            ];
            
            if ($manPromesa->save($psmData) > 0) {
                throw new Exception(json_encode($manPromesa->getListaErrores()));
            }            
        
            /* =====================================================
            9. ASIGNAR PROMESAS A LOS PISOS
            ===================================================== */
            foreach ($pisos as $pisoId) {
                $prpData = [
                    'prp_promesa' => $psm_promesa,
                    'prp_piso'    => $pisoId
                ];
                if ($manPromesaPiso->save($prpData) > 0) {
                    throw new Exception(json_encode($manPromesaPiso->getListaErrores()));
                }
            }

            
        }
        
        /* =====================================================
        10. RESPUESTA OK
        ===================================================== */
        $transacConex->commit();
        $transacConex->close();
        echo json_encode([
            'success'=>true,
            'root'=>['tipo'=>'Respuesta', 'Detalle'=>'Promesa creada correctamente']
        ]);
    } catch (Exception $e) {
        $transacConex->rollback();
        $transacConex->close();
        echo json_encode([
            'success'=>false,
            'root'=>json_decode($e->getMessage(), true)
        ]);

        exit;
    }

?>