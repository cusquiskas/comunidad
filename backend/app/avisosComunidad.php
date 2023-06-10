<?php
    require_once ('../required/controlSession.php');

    if (!$_POST['com_comunidad'] || $_POST['com_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['com_comunidad']);
    if ($perfil == 0) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta comunidad']]]));
    
    $avisos = [];

    if ($perfil <= 2) { #presidente o administrador
        $manPisos = ControladorDinamicoTabla::set('PISO');
        $manPisos->give($_POST);
        $reg = $manPisos->getArray();
        unset($manPisos);
        $sum = 0; for ($i = 0; $i < count($reg); $i++) { $sum += ($reg[0]["pis_porcentaje"] * 1); }
        if ($sum != 100) $avisos[] = ["tipo" =>"Mensaje", "Campo"=>"Porcentaje Pisos", "Detalle" => "La suma total de los pocentajes de los pisos es ".($suma*1)."%"];
    }

    if ($perfil >= 2) { #usuario o presidente
        require_once '../../tabla/propietario_piso.php';
        $manPisos = new View_PROPIETARIO_PISO();
        $resu = $manPisos->lista_pisos($_SESSION['data']['user']['id'], $_POST['com_comunidad'], date_format(new DateTime(), 'Y-m-d'));
        unset($manPisos);
        if (count($resu) == 0) $avisos[] = ["tipo" =>"Mensaje", "Campo"=>"Propietario", "Detalle" => "No está incluido como propietario en ningún piso"];
    }


    if (count($avisos) > 0) {
        echo json_encode(['success' => false, 'root' => ["avisos" => $avisos, "perfil" => $perfil]]);
    } else {
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => "Sin avisos", "perfil" => $perfil]]);
    }
?>