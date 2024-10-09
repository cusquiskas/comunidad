<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['doc_comunidad'] || $_POST['doc_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['doc_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    if ($_POST['doc_movimiento'] == "") {
        die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'No se ha informado el movimiento.']]));
    }

    
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    
    if (isset($_FILES['doc_real']) && $_FILES['doc_real']['error'] == 0) {
        $nombreArchivo     = $_FILES['doc_real']['name'];
        $extension = pathinfo(strtolower($nombreArchivo), PATHINFO_EXTENSION);
        $nombre    = str_pad($_POST['doc_comunidad'], 4, "0", STR_PAD_LEFT) . "_" . str_pad($_POST['doc_movimiento'], 8, "0", STR_PAD_LEFT);# . "." . $extension;
        $ubicacionTemporal = $_FILES['doc_real']['tmp_name'];
        $ubicacionDestino  = $_SESSION['data']['conf']['home'] . $_SESSION['data']['conf']['subidas'] . $nombre;
        $mimeType          = $_FILES['doc_real']['type'];
        
        if (!in_array(strtolower($mimeType), $allowedMimeTypes)) {
            die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'El tipo de archivo no está soportado.', 'mimeType' => $_FILES['doc_real']['type']]]));
        }
        
        if (!in_array($extension, $allowedExtensions)) {
            die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'El tipo de archivo no está soportado.', 'extension' => $extension]]));
        }
    
        $manDocumento = ControladorDinamicoTabla::set('DOCUMENTO');
        $docu = ["doc_comunidad"=>$_POST['doc_comunidad'], "doc_almacenado"=>$nombre, "doc_real" => $nombreArchivo, "doc_fileType" => $mimeType];
        $manDocumento->save($docu);
        $docu = $manDocumento->getListaErrores();
        if ($docu != null and count($docu) > 0) {
            unset($manDocumento);
            die(json_encode(['success' => false, 'root' => $docu]));
        }
        $docu = $manDocumento->getArray();
        unset($manDocumento);


        $manMovimiento = ControladorDinamicoTabla::set('MOVIMIENTO');
        $movi = ["mov_movimiento"=>$_POST['doc_movimiento'], "mov_documento"=>$docu["doc_documento"]];
        $manMovimiento->save($movi);
        $movi = $manMovimiento->getListaErrores();
        if ($movi != null && count($movi) > 0 ) {
            unset($manMovimiento);
            die(json_encode(['success' => false, 'root' => $movi]));
            
        }
        unset($manMovimiento);


        @move_uploaded_file($ubicacionTemporal, $ubicacionDestino);
        if (!file_exists($ubicacionDestino)) {
            die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Error al mover el archivo.']]));
        } 
        
        
        
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Archivo subido con éxito.']]);
        
    } else {
        echo json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Error al subir el archivo.']]);
    }
    
?>