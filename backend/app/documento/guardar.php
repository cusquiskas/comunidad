<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['doc_comunidad'] || $_POST['doc_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['doc_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    $manDocumento = ControladorDinamicoTabla::set('DOCUMENTO');
    unset($manDocumento);

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    
    if (isset($_FILES['doc_real']) && $_FILES['doc_real']['error'] == 0) {
        if (!in_array(strtolower($_FILES['doc_real']['type']), $allowedMimeTypes)) {
            die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'El tipo de archivo no está soportado.']]));
        }
        if (!in_array(pathinfo(strtolower($_FILES['doc_real']['name']), PATHINFO_EXTENSION), $allowedExtensions)) {
            die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'El tipo de archivo no está soportado.']]));
        }
        $nombreArchivo     = $_FILES['doc_real']['name'];
        $ubicacionTemporal = $_FILES['doc_real']['tmp_name'];
        $ubicacionDestino  = $_SESSION['data']['conf']['home'] . $_SESSION['data']['conf']['subidas'] . $nombreArchivo;
        
        @move_uploaded_file($ubicacionTemporal, $ubicacionDestino);
        if (file_exists($ubicacionDestino)) {
            echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Archivo subido con éxito.', 'datosArchivo' => $_FILES]]);
        } else {
            echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Error al mover el archivo.']]);
        }
    } else {
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Error al subir el archivo.']]);
    }
        

    #require_once $_SESSION['data']['conf']['home'].'tabla/MOVIMIENTO.php';
    #$manMovimiento = new Tabla_MOVIMIENTO();
    /*if (isset($_POST['mov_movimiento']) && $_POST['mov_movimiento'] == "") unset($_POST['mov_movimiento']);
    if (!isset($_POST['mov_piso'])) $_POST['mov_piso'] = null;
    if (!isset($_POST['mov_gasto'])) $_POST['mov_gasto'] = null;
    if (isset($_POST['mov_piso']) && $_POST['mov_piso'] == "-1") unset($_POST['mov_piso']);
    if (isset($_POST['mov_gasto']) && $_POST['mov_gasto'] == "-1") unset($_POST['mov_gasto']);

    if ($manMovimiento->save($_POST) == 0) {
        $reg = $manMovimiento->getArray();
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
    } else {
        $reg = $manMovimiento->getListaErrores();
        echo json_encode(['success' => false, 'root' => $reg]);
    }*/
    
?>