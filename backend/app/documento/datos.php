<?php
    require_once ('../../required/controlSession.php');
    require_once ('../../required/utiles.php');

    if (!$_POST['doc_comunidad'] || $_POST['doc_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['doc_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    
    $manDocumento = ControladorDinamicoTabla::set('DOCUMENTO');
    $manDocumento->give(["doc_documento" => $_POST["doc_documento"], "doc_comunidad" => $_POST["doc_comunidad"]]);
    $reg = $manDocumento->getArray();
    $reg = $reg[0];
    unset($reg["doc_almacenado"]);
    unset($manDocumento);
    
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $reg]]);
?>