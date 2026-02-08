<?php

class ConfiguracionSistema
{
    private $host = '';
    private $user = '';
    private $pass = '';
    private $apli = '';

    private $home        = '';
    private $subidas     = '';
    private $timeSession = "";

    public function getHost       () { return $this->host;        }

    public function getUser       () { return $this->user;        }

    public function getPass       () { return $this->pass;        }
 
    public function getApli       () { return $this->apli;        }

    public function getHome       () { return $this->home;        }

    public function getTimeSession() { return $this->timeSession; }

    public function getSubidas    () { return $this->subidas;     }

    public function __construct   () {
        $this->host        = getenv('DB_HOST');
        $this->user        = getenv('DB_USER');
        $this->pass        = getenv('DB_PASS');
        $this->apli        = getenv('DB_APLI');

        $this->home        = getenv('FL_HOME');
        $this->subidas     = getEnv('FL_SUBIDAS');
        $this->timeSession = getEnv('SS_timeSession');
    
    }
}
