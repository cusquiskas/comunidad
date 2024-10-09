<?php
    require_once ('../../required/controlSession.php');
    
    if (!$_GET['doc_comunidad'] || $_GET['doc_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad ']]]));
    }

    $perfil = controlPerfil($_GET['doc_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    
    $manDocumento = ControladorDinamicoTabla::set('DOCUMENTO');
    $manDocumento->give(["doc_documento" => $_GET["doc_documento"], "doc_comunidad" => $_GET["doc_comunidad"]]);
    $reg = $manDocumento->getArray();
    $reg = $reg[0];

    $nombreGuardado = $reg["doc_almacenado"];
    $nombreReal     = $reg["doc_real"];
    $mimeType       = $reg["fileType"];
    $file           = $_SESSION['data']['conf']['home'] . $_SESSION['data']['conf']['subidas'] . $nombreGuardado;
    unset($manDocumento);
    
    
    header('Content-Description: File Transfer');
    header('Content-Type: '.$mimeType);
    header('Content-Disposition: attachment; filename="' . $nombreReal . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    ob_clean();
    flush();

    readfile($file);
    
?>