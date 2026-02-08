<?php    
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => getenv('APP_ENV') === 'prod',
        'httponly' => true,
        'samesite' => 'Strict' // O 'Lax' o 'None'
    ]);

    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_SESSION['data']['user']['id']) || $_SESSION['data']['user']['id'] == "") {
        unset($_SESSION);
        http_response_code(401);
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Sesión no válida']]]));
    }
    
    if (!isset($_POST) || $_POST == []) 
        $_POST = json_decode(file_get_contents('php://input'), true);
    
    require_once $_SESSION['data']['conf']['home'].'conex/conf.php';  //información crítica del sistema
    require_once $_SESSION['data']['conf']['home'].'conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once $_SESSION['data']['conf']['home'].'tabla/controller.php';

    $manSesion = ControladorDinamicoTabla::set('SESION');
    $fecha = new DateTime();
    $fecha->modify($_SESSION['data']['conf']['timeSession']);
    $manSesion->give(["ses_token" => $_SESSION['data']['user']['token'], "ses_ultimo_signo" => ">", "ses_ultimo" => $fecha->format('Y-m-d G:i:s')]);
    $ses = $manSesion->getArray();
    if (count($ses) == 1)  {
        $ses = $ses[0];
        $ses["ses_ultimo"] = date('Y-m-d G:i:s');
        if ($manSesion->save($ses) != 0) {
            $ses = $manSesion->getListaErrores();
            unset($manSesion);
            unset($_SESSION);
            http_response_code(401);
            die(json_encode(['success' => false, 'root' => $ses]));
        }
    } else {
        unset($manSesion);
        unset($_SESSION);
        http_response_code(401);
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Token no válido']]]));
    }
    unset($manSesion);

    $manAuditoria = ControladorDinamicoTabla::set('AUDITORIA');
    $audit = [
        "aud_token"      => $_SESSION['data']['user']['token'],
        "aud_usuario"    => $_SESSION['data']['user']['id'],
        "aud_peticion"   => strtok($_SERVER['REQUEST_URI'], '?'),
        "aud_parametros" => ($_POST == [])?"GET:\n" . var_export($_GET, true) : "POST:\n" . var_export($_POST, true),
        "aud_IP"         => $_SERVER['REMOTE_ADDR'],
        "aud_agente"     => $_SERVER['HTTP_USER_AGENT'],
        "aud_cuando"     => date('Y-m-d G:i:s')
    ];
    @$manAuditoria->save($audit);
    unset($manAuditoria);
    
    function controlPerfil($comunidad) {
        $perfil = 0;
        $manValidador = ControladorDinamicoTabla::set('USUARIO_COMUNIDAD');
        $manValidador->give([
            "uco_comunidad"  => $comunidad,
            "uco_correo"     => $_SESSION['data']['user']['id'],
            "uco_desde"      => date('Y-m-d'),
            "uco_desde_signo"=> "<=",
            "uco_hasta"      => date('Y-m-d'),
            "uco_hasta_signo"=> ">="
        ]);
        $reg = $manValidador->getArray();
        if (count($reg) == 0) { $perfil = 0; } 
        else { $perfil = $reg[0]['uco_perfil']; }
        unset($reg);
        unset($manValidador);
    
        return $perfil;
    }
?>