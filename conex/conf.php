<?php

class ConfiguracionSistema
{
    private $host = 'localhost';
    private $user = 'contador';
    private $pass = 'D3pàcot1ya';
    private $apli = 'comunidad';

    private $home = '/opt/lampp/htdocs/comunidad/';
    private $subidas = 'backend/documentosSubidos/';
    private $timeSession = "-30 minute";

    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getApli()
    {
        return $this->apli;
    }

    public function getHome()
    {
        return $this->home;
    }

    public function getTimeSession() {
        return $this->timeSession;
    }

    public function getSubidas() {
        return $this->subidas;
    }

    public function __construct() {
        $clave = json_decode(file_get_contents('/opt/lampp/claves.json'), true);
        $clave = $clave["comunidad"];
        
        $url = "https://cusquiskas.com/secretos";
        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "Authorization: Bearer $clave\r\n" .
                "Content-Type: application/json\r\n"
            )
        );
        $context = stream_context_create($options);
        
        $config  = json_decode(file_get_contents($url, false, $context), true);
    
    }
}
