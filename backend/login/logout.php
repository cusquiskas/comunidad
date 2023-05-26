<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

unset($_SESSION['data']);

header('Content-Type: application/json; charset=utf-8');

echo json_encode(['success' => true, 'root' => [['tipo' => 'Respuesta', 'Detalle' => 'Sesión cerrada correctamente']]]);
?> 
