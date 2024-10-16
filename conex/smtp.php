<?php    
    class SMTP {
        // Configurar los datos del servidor SMTP
        private $user = null; // El nombre de usuario SMTP
        private $pass = null; // La contraseña SMTP
        private $host = null; // El nombre del host SMTP
        private $port = null; // El puerto SMTP
        private $errstr = "";
        private $errnun = 0;
        private $result = [];
        private $debugger = false;

        private $para   = "";
        private $asunto = "";
        private $cuerpo = "";

        public function __construct($obj=null) {
            if ($this->host == null) {
                $config = json_decode(file_get_contents('/opt/lampp/htdocs/claves.json'), true);
                $config = $config["SMTP"];
                
                $this->host = $config["host"];
                $this->user = $config["user"];
                $this->pass = $config["pass"];
                $this->port = $config["port"];
            }
            
            if ($obj!=null) {
                $this->setPara   ($obj["para"]  );
                $this->setAsunto ($obj["asunto"]);
                $this->setCuerpo ($obj["cuerpo"]);
            }
        }

        public function setPara  ($para)   { $this->para   = $para; }
        public function setAsunto($asunto) { $this->asunto = $asunto; }
        public function setCuerpo($cuerpo) { $this->cuerpo = $cuerpo; }
        public function setDebugger($debugger)  { $this->debugger = $debugger; }

        public function getError() {
            return ["cod" => $this->errnum, "err" => $this->errstr, "traza" => $this->result];
        }
        
        public function sendMail($obj=null) {
            if ($obj!=null) {
                $this->setPara   ($obj["para"]);
                $this->setAsunto ($obj["asunto"]);
                $this->setCuerpo ($obj["cuerpo"]);
            }
            if ($_SERVER['SERVER_NAME'] == 'localhost') $this->para = 'cusquiskas@gmail.com';
            
            $from    = $this->user;    // La dirección de correo del remitente
            $to      = $this->para;   // La dirección de correo del destinatario
            $subject = $this->asunto; // El asunto del correo
            $body    = $this->cuerpo; // El cuerpo del correo
            $this->errnum = 0;
            $this->errstr = "";
            $this->result = [];
            
            if ($to == "" || $to == null) {
                $this->errnum = 1;
                $this->errstr = "No se ha indicado destinatario";
                return false;    
            }

            if ($subject == "" || $subject == null) {
                $this->errnum = 1;
                $this->errstr = "No se ha indicado asunto";
                return false;    
            }

            if ($body == null) $body = " "; 

            if ($this->debugger === true) {
                echo "from: $from\r\n";
                echo "to: $to\r\n";
                echo "subject: $subject\r\n";
                echo "body: $body\r\n";
            }

            
                // Crear una conexión SMTP con autenticación
                @$smtp = fsockopen($this->host, $this->port, $this->errnum, $this->errstr, 30);
                if (!$smtp) {
                    die("Error de conexión: $this->errstr ($this->errnum)");
                }
                
                // Iniciar la conexión SSL si es necesario
                // stream_socket_enable_crypto($smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT); // Omitido si ya está configurado
                
                $this->sendCommand($smtp, "EHLO $this->host\r\n");
                $this->sendCommand($smtp, "AUTH LOGIN\r\n");
                $this->sendCommand($smtp, base64_encode($this->user) . "\r\n");
                $this->sendCommand($smtp, base64_encode($this->pass) . "\r\n");
                
                $this->sendCommand($smtp, "MAIL FROM: <$from>\r\n");
                $this->sendCommand($smtp, "RCPT TO: <$to>\r\n");
                $this->sendCommand($smtp, "DATA\r\n");
                
                // Escribir las cabeceras del correo
                $this->sendCommand($smtp, "From: $from\r\n");
                $this->sendCommand($smtp, "To: $to\r\n");
                $this->sendCommand($smtp, "Subject: $subject\r\n");
                $this->sendCommand($smtp, "MIME-Version: 1.0\r\n");
                $this->sendCommand($smtp, "Content-type: text/html; charset=utf8\r\n");
                $this->sendCommand($smtp, "\r\n"); // Línea en blanco entre cabeceras y cuerpo
                
                // Escribir el cuerpo del correo
                $this->sendCommand($smtp, "$body\r\n");
                
                // Indicar el final del mensaje
                $response = $this->sendCommand($smtp, ".\r\n");
                /*if (substr($response, 0, 3) != "250") {
                    $this->errnum = 1;
                    $this->errstr = $response;
                    fclose($smtp);
                    return false;
                }*/
                
                $this->sendCommand($smtp, "QUIT\r\n");
                
                // Cerrar la conexión SMTP
                fclose($smtp);
                if ($this->debugger === true) echo "close\r\n";
    
            return true;
        }

        private function sendCommand($smtp, $command) {
            fwrite($smtp, $command);
            $response = fgets($smtp, 4096); // Leer la respuesta del servidor
            if ($this->debugger === true) echo $response;
            array_push($this->result, $response);
            return $response;
        }
    }
?>