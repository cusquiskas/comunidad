<?php

    require_once ('../required/initSession.php');
        
    if ($_POST['usu_contrasena'] !== $_POST['usu_contrasena2']) {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Las contraseñas no coinciden']]]));
    }

    $_POST['usu_contrasena'] = md5($_POST['usu_contrasena']);

    $_POST['usu_facceso'] = date('Y-m-d G:i:s');
    
    $_POST['usu_fvalida'] = '9999-12-31';
    
    $_POST['usu_errorlogin'] = 0;

    $manUsuario = ControladorDinamicoTabla::set('USUARIO');

    $manUsuario->give(["usu_correo" => $_POST["usu_correo"], "usu_correo_case" => "N"]);
    $control = $manUsuario->getArray();
    if (count($control) == 1) {
        unset($manUsuario);
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Respuesta', 'Detalle' => 'El correo ya está registrado']]]));
    }

    if ($manUsuario->save($_POST) == 0) {
        $reg = $manUsuario->getArray(); #despues de un save... no es un array, es un objeto
        #no podrá acceder hasta validar la cuenta de correo
        #$_SESSION['data']['user']['id'] = $reg['usu_correo'];
        #$_SESSION['data']['user']['nombre'] = $reg['usu_nombre'];
        
        $manSesion = ControladorDinamicoTabla::set('SESION');
        $ses = [];
        $ses["ses_token"] = bin2hex(random_bytes(32));
        $ses["ses_correo"] = $reg['usu_correo'];
        $ses["ses_primero"] = date('Y-m-d G:i:s');
        $ses["ses_ultimo"] = '9999-12-31';
        if ($manSesion->save($ses) == 0) {
            /*
            $to      = 'cusquiskas@gmail.com';
            $subject = 'Verificación de cuenta de correo';
            $message = 'Pulsa este enlace para activar el centro de mando';
            $headers = 'From: cusquiskas@gmail.com'       . "\r\n" .
                       'Reply-To: cusquiskas@gmail.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();
    
            mail($to, $subject, $message, $headers);
            */
        } else {
            unset($ses);
            $ses = $manSesion->getListaErrores();
            unset($manSesion);
            unset($manUsuario);
            die(json_encode(['success' => false, 'root' => $ses]));
        }
        echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => 'Registro realizado correctamente', 'id' => $reg['usu_correo'], 'nombre' => $reg['usu_nombre']]]);
        
    } else {
        $reg = $manUsuario->getListaErrores();
        $rag = [];
        foreach ($reg as $valor) { 
            if (isset($valor["tipo"]) && $valor["tipo"] == "Validacion")
                $rag[] = $valor;
            else
                $rag[] = [$valor['error']]; 
        }
        echo json_encode(['success' => false, 'root' => $rag]);
    }

    unset($manUsuario);
    unset($reg);
    unset($rag);
