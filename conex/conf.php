<?php

class ConfiguracionSistema
{
    private $host = 'localhost';
    private $user = 'contador';
    private $pass = 'D3pàcot1ya';
    private $apli = 'comunidad';

    private $home = '/opt/lampp/htdocs/comunidad/';
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
}
