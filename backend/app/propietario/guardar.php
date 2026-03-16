<?php
    require_once ('../../required/controlSession.php');

    if (!$_POST['pro_comunidad'] || $_POST['pro_comunidad'] == "") {
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'No se ha especificado una comunidad']]]));
    }

    $perfil = controlPerfil($_POST['pro_comunidad']);
    if ($perfil > 2) die(json_encode(['success' => false, 'root' => [['tipo' => 'Permisos', 'Detalle' => 'Sin acceso a esta pantalla']]]));
    
    # creamos o actualizamos el propietario
    $manPropietario = ControladorDinamicoTabla::set('PROPIETARIO');
    if ($manPropietario->save($_POST)){
        $errores = $manPropietario->getListaErrores();
        unset($manPropietario);
        die(json_encode(['success' => false, 'root' => $errores]));
    }
    $propietario = $manPropietario->getArray();
    unset($manPropietario);

    # vinculamos el propietario con el piso
    $manPropiePiso = ControladorDinamicoTabla::set('PROPIETARIO_PISO');
    $reg = ["ppi_propietario" => $propietario["pro_propietario"], "ppi_piso" => $_POST["pro_piso"], "ppi_desde" => date("Y-m-d"), "ppi_hasta" => "9999-12-31", "ppi_inquilino" => 0];
    if ($manPropiePiso->save($reg)) {
        $errores = $manPropiePiso->getListaErrores();
        unset($manPropiePiso);
        die(json_encode(['success' => false, 'root' => $errores]));
    }
    #$proPiso = $manPropiePiso->getArray();
    unset($manPropiePiso);

    # miramos si hay que enviar una invitación al propietario
    if ($_POST["invitacion"] == "[\"S\"]" && $_POST["pro_correo"] != "") {
        $manComunidad = ControladorDinamicoTabla::set('COMUNIDAD');
        $manComunidad->give(["com_comunidad" => $_POST['pro_comunidad']]);
        $nombreComunidad = $manComunidad->getArray()[0]["com_nombre"];
        unset($manComunidad);
        
        $textoInvitacion = "";
        # hay que revisar si el email existe en la tabla USUARIOS
        $manUsuario = ControladorDinamicoTabla::set('USUARIO');
        $manUsuarioComunidad = ControladorDinamicoTabla::set('USUARIO_COMUNIDAD');
        if ($manUsuario->give(["usu_correo" => $_POST["pro_correo"]])) {
            $errores = $manUsuario->getListaErrores();
            unset($manUsuario);
            unset($manUsuarioComunidad);
            die (json_encode(['success' => false, 'root' => $errores]));
        }
        $usuario = $manUsuario->getArray();
        
        if (count($usuario) == 0) {
            # el email no existe, hay que crear el usuario
            $reg = [
                "usu_correo" => $_POST["pro_correo"], 
                "usu_nombre" => $_POST["pro_nombre"], 
                "usu_contrasena" => md5("1234567890"),
                "usu_errorlogin" => 0,
                "usu_facceso" => date("Y-m-d H:i:s"),
                "usu_fvalida" => "9999-12-31"
            ];
            if ($manUsuario->save($reg)) {
                $errores = $manUsuario->getListaErrores();
                unset($manUsuario);
                unset($manUsuarioComunidad);
                die(json_encode(['success' => false, 'root' => $errores]));
            }
            $usuario = $manUsuario->getArray();
            # y vincularlo con la comunidad
            $reg = [
                "uco_correo" => $usuario["usu_correo"], 
                "uco_comunidad" => $_POST['pro_comunidad'], 
                "uco_perfil" => 3, 
                "uco_desde" => date("Y-m-d"), 
                "uco_hasta" => "9999-12-31"
            ];
            if ($manUsuarioComunidad->save($reg)) {
                $errores = $manUsuarioComunidad->getListaErrores();
                unset($manUsuario);
                unset($manUsuarioComunidad);
                die(json_encode(['success' => false, 'root' => $errores]));
            }
            $textoInvitacion = "<img src=\"https://comunidad.cusquiskas.com/css/img/imagen.gif\" width=\"45px\" height=\"45px\">".
                               "<h3>Se le ha añadido a la comunidad \"$nombreComunidad\".</h3>".
                               "<p>Para poder consultar el estado de la comunidad, deberá activar su cuenta utilizando la opción de 'Recuperar cuenta' en la pantalla de login.</p>".
                               "<p>Su usuario es este mismo correo.</p>".
                               "<p>Se le enviará un email con las instrucciones para activar su cuenta.</p>".
                               "<p>Puede acceder a su cuenta usando el siguiente <a href=\"https://comunidad.cusquiskas.com\" target=\"blank\">enlace</a></p>".
                               "<p style=\"color: red\"><b><i>Si tiene dudas o desconoce por qué le ha llegado este correo, por favor, contacte con su administrador o presidente de la comunidad</i><b></p>".
                               "<br/><br/><p>Bienvenido a VecinApp.</p>";

        } else {
            $usuario = $usuario[0];
            # el email existe, hay que revisar si el usuario tiene acceso a la comunidad
            if ($manUsuarioComunidad->give(["uco_correo" => $usuario["usu_correo"], "uco_comunidad" => $_POST['pro_comunidad']])) {
                $errores = $manUsuarioComunidad->getListaErrores();
                unset($manUsuario);
                unset($manUsuarioComunidad);
                die (json_encode(['success' => false, 'root' => $errores]));
            }
            $usuarioComunidad = $manUsuarioComunidad->getArray();
            if (count($usuarioComunidad) == 0) {
                # el usuario no tiene acceso a la comunidad, hay que darle acceso
                $reg = [
                    "uco_correo" => $usuario["usu_correo"], 
                    "uco_comunidad" => $_POST['pro_comunidad'], 
                    "uco_perfil" => 3, 
                    "uco_desde" => date("Y-m-d"), 
                    "uco_hasta" => "9999-12-31"
                ];
                if ($manUsuarioComunidad->save($reg)) {
                    $errores = $manUsuarioComunidad->getListaErrores();
                    unset($manUsuario);
                    unset($manUsuarioComunidad);
                    die(json_encode(['success' => false, 'root' => $errores]));
                }
                
                $textoInvitacion =  "<img src=\"https://comunidad.cusquiskas.com/css/img/imagen.gif\" width=\"45px\" height=\"45px\">".
                                    "<h3>Se le ha añadido a la comunidad \"$nombreComunidad\".</h3>".
                                    "<p>Para poder consultar el estado de la comunidad, puede continuar accediendo con su usuario y contraseña habitual y seleccionar la comunidad desde el desplegable de la cabecera.</p>".
                                    "<p>Si no recuerda su contraseña, puede reiniciarla utilizando la opción de 'Recuperar cuenta' de la pantalla de login y se le enviará un email con las instrucciones para cambiar la contraseña.</p>".
                                    "<p>Puede acceder a la aplicación usando el siguiente <a href=\"https://comunidad.cusquiskas.com\" target=\"blank\">enlace</a></p>".
                                    "<p style=\"color: red\"><b><i>Si tiene dudas o desconoce por qué le ha llegado este correo, por favor, contacte con su administrador o presidente de la comunidad</i><b></p>".
                                    "<br/><br/><p>Gracias por usar VecinApp.</p>";
            } else {
                # el usuario ya tiene acceso a la comunidad, no hay que hacer nada
                $textoInvitacion = "<img src=\"https://comunidad.cusquiskas.com/css/img/imagen.gif\" width=\"45px\" height=\"45px\">".
                                   "<p>Un administrador de la comunidad \"$nombreComunidad\" le ha enviado un recordario para que acceda a consultar su estado en la comunidad.</p>".
                                   "<p>Si no recuerda su contraseña, puede reiniciarla utilizando la opción de 'Recuperar cuenta' de la pantalla de login y se le enviará un email con las instrucciones para cambiar la contraseña.</p>".
                                   "<p>Puede acceder a la aplicación usando el siguiente <a href=\"https://comunidad.cusquiskas.com\" target=\"blank\">enlace</a></p>".
                                   "<p style=\"color: red\"><b><i>Si tiene dudas o desconoce por qué le ha llegado este correo, por favor, contacte con su administrador o presidente de la comunidad</i><b></p>".
                                   "<br/><br/><p>Gracias por usar VecinApp.</p>"; 
            }
        }
    
        require_once ($_SESSION['data']['conf']['home'].'conex/correo.php');
        $smtp = new Correo();
        $smtp->destinatario($usuario["usu_correo"]);
        $smtp->asunto = "VecinApp - Activación cuenta de correo";
        $smtp->cuerpo = $textoInvitacion;
        if (!$smtp->mandaMail()) {
            $error = $smtp->error;
            unset($manUsuario);
            unset($manUsuarioComunidad);
            unset($smtp);
            die(json_encode(['success' => false, 'root' => $error]));
        }
        unset($smtp);
        unset($manUsuario);
        unset($manUsuarioComunidad);
    
    }
    
    
    
    echo json_encode(['success' => true, 'root' => ['tipo' => 'Respuesta', 'Detalle' => $propietario]]);
    
