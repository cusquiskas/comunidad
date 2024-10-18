<?php

class ConfiguracionSistema
{
    private $host = null;
    private $user = null;
    private $pass = null;
    private $apli = null;

    private $home = null;
    private $subidas     = null;
    private $timeSession = null;

    public function getHost       () { return $this->host;        }

    public function getUser       () { return $this->user;        }

    public function getPass       () { return $this->pass;        }
 
    public function getApli       () { return $this->apli;        }

    public function getHome       () { return $this->home;        }

    public function getTimeSession() { return $this->timeSession; }

    public function getSubidas    () { return $this->subidas;     }

    public function __construct   () {
        if (file_exists('/opt/lampp/htdocs/claves.json')) {
            $config = json_decode(file_get_contents('/opt/lampp/htdocs/claves.json'), true);
            
            $config = $config["comunidad"];
            
            $this->host        = $config["host"       ];
            $this->user        = $config["user"       ];
            $this->pass        = $config["pass"       ];
            $this->apli        = $config["apli"       ];

            $this->home        = $config["home"       ];
            $this->subidas     = $config["subidas"    ];
            $this->timeSession = $config["timeSession"];
        }
    
    }
}
