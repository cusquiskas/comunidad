<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['psm_comunidad'] || $_POST['psm_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }
    $perfil = controlPerfil($_POST['psm_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    if (isset($_POST['psm_promesa']) && $_POST['psm_promesa'] == "") 
    die(json_encode(['success' => false, 'root' => [['tipo' => 'Respuesta', 'Detalle' => 'No se ha indicado el ID de la promesa']]]));
    
    $manGasto = ControladorDinamicoTabla::set('PROMESA_PISO');
    $enlace = new ConexionSistema();
    $enlace->ejecuta("delete from PROMESA_PISO where prp_promesa = ?", [0 => ['tipo' => 'i', 'dato' => $_POST["psm_promesa"]]]);
    if (!$enlace->hayError()) {
        $pisos = explode(",", $_POST['psm_piso']);
        foreach ($pisos as $valor) {
            $manGasto->save(["prp_promesa" => $_POST["psm_promesa"], "prp_piso" => $valor]);            
        }
    } else {
        $reg = $enlace->getListaErrores();
        echo json_encode(['success' => false, 'root' => $reg]);
    }
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => ""]]);
    unset($enlace);
    unset($manGasto);
?>