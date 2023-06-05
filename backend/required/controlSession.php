<?php    
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_SESSION['data']['user']['id']) || $_SESSION['data']['user']['id'] == "") {
        unset($_SESSION);
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Sesión no válida']]]));
    }
    
    if (!isset($_POST) || $_POST == []) 
        $_POST = json_decode(file_get_contents('php://input'), true);

    require_once '../../conex/conf.php';  //información crítica del sistema
    require_once '../../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../../tabla/controller.php';

    $manSesion = ControladorDinamicoTabla::set('SESION');
    $conf = new ConfiguracionSistema();
    $fecha = new DateTime();
    $fecha->modify($conf->getTimeSession());
    unset($conf);
    $manSesion->give(["ses_token" => $_SESSION['data']['user']['token'], "ses_ultimo_signo" => ">", "ses_ultimo" => $fecha->format('Y-m-d G:i:s')]);
    $ses = $manSesion->getArray();
    if (count($ses) == 1)  {
        $ses = $ses[0];
        $ses["ses_ultimo"] = date('Y-m-d G:i:s');
        if ($manSesion->save($ses) != 0) {
            $ses = $manSesion->getListaErrores();
            unset($manSesion);
            unset($_SESSION);
            die(json_encode(['success' => false, 'root' => $ses]));
        }
    } else {
        unset($manSesion);
        unset($_SESSION);
        die(json_encode(['success' => false, 'root' => [['tipo' => 'Sesion', 'Detalle' => 'Token no válido']]]));
    }
    unset($manSesion);
?>