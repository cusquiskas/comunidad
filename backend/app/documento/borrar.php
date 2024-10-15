<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['doc_comunidad'] || $_POST['doc_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['doc_comunidad']);
    if ($perfil > 1) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin permisos de escritura']]]));
    
    if ($_POST['doc_documento'] == "") {
        die(json_encode(['success' => false, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'No se ha informado el documento.']]));
    }

    #buscamos si el documento está asignado a un movimiento y lo actualizamos a null
    $MOVIMIENTO = ControladorDinamicoTabla::set('MOVIMIENTO');
    $movimiento = ["mov_comunidad" => $_POST['doc_comunidad'], "mov_documento" => $_POST['doc_documento']];
    $MOVIMIENTO->give($movimiento);
    $movimiento = $MOVIMIENTO->getArray();
    if (count($movimiento) == 1) {
        $movimiento = $movimiento[0];
        $movimiento["mov_documento"] = null;
        $MOVIMIENTO->save($movimiento);
    }
    unset($MOVIMIENTO);
    
    #buscamos si el documento está asignado a un split y lo actualizamos a null
    $SPLIT = ControladorDinamicoTabla::set('SPLIT');
    $split = ["spl_comunidad" => $_POST['doc_comunidad'], "spl_documento" => $_POST['doc_documento']];
    $SPLIT->give($movimiento);
    $split = $SPLIT->getArray();
    if (count($split) == 1) {
        $split = $split[0];
        $split["spl_documento"] = null;
        $SPLIT->save($split);
    }
    unset($SPLIT);
    

    #ahora ya podemos borrar el documento, tanto físicamente del directorio como de la tabla
    $DOCUMENTO = ControladorDinamicoTabla::set('DOCUMENTO');
    $documento = ["doc_comunidad"=>$_POST['doc_comunidad'], "doc_documento"=>$_POST['doc_documento']];
    $DOCUMENTO->give($documento);
    $documento = $DOCUMENTO->getArray();
    $documento = $documento[0];
    $ubicacionDestino  = $_SESSION['data']['conf']['home'] . $_SESSION['data']['conf']['subidas'] . $documento["doc_almacenado"];
    if (file_exists($ubicacionDestino)) unlink($ubicacionDestino);
    $DOCUMENTO->delete($documento);
    unset($DOCUMENTO);

    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Se ha borrado el documento.']])
    

?>