<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['gas_comunidad'] || $_POST['gas_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }
    $perfil = controlPerfil($_POST['gas_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    $manGasto = ControladorDinamicoTabla::set('GASTO_PISO');
    if (isset($_POST['gas_gasto']) && $_POST['gas_gasto'] == "") 
    die(json_encode(['success' => false, 'root' => [['tipo' => 'Respuesta', 'Detalle' => 'No se ha indicado el ID de gasto']]]));
    
    $enlace = new ConexionSistema();
    $enlace->ejecuta("delete from GASTO_PISO where gpi_gasto = ?", [0 => ['tipo' => 'i', 'dato' => $_POST["gas_gasto"]]]);
    if (!$enlace->hayError()) {
        $pisos = explode(",", $_POST['gas_piso']);
        foreach ($pisos as $valor) {
            $manGasto->save(["gpi_gasto" => $_POST["gas_gasto"], "gpi_piso" => $valor]);            
        }
    } else {
        $reg = $enlace->getListaErrores();
        echo json_encode(['success' => false, 'root' => $reg]);
    }
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => ""]]);
    unset($enlace);
    unset($manGasto);
?>