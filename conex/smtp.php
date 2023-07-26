<?php    
    class SMTP {
        // Configurar los datos del servidor SMTP
        private $user = "noreply@cusquiskas.com";       // El nombre de usuario SMTP
        private $pass = "";                             // La contraseña SMTP
        private $host = "ssl://smtp.hostinger.com";     // El nombre del host SMTP
        private $port = 465;                            // El puerto SMTP
        private $errstr = "";
        private $errnun = 0;
        private $result = [];
        private $debugger = false;

        private $para   = "";
        private $asunto = "";
        private $cuerpo = "";

        public function __construct($obj=null) {
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
            @$smtp = fsockopen($this->host, $this->port, $this->errnun, $this->errstr); // Abrir un socket TCP al servidor SMTP
            if ($this->errstr != "") return false;
            fwrite($smtp, "EHLO $this->host\r\n"); // Enviar el comando HELO al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "AUTH LOGIN\r\n"); // Enviar el comando AUTH LOGIN al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            if (substr($tempo, 0, 3) != "250") {
                $this->errnum = substr($tempo, 0, 3) * 1;
                $this->errstr = $tempo;
                fclose($smtp);
                return false;
            }
            fwrite($smtp, base64_encode($this->user) . "\r\n"); // Enviar el nombre de usuario codificado en base64 al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, base64_encode($this->pass) . "\r\n"); // Enviar la contraseña codificada en base64 al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            
            // Enviar el correo electrónico
            fwrite($smtp, "MAIL FROM: <$from>\r\n"); // Enviar el comando MAIL FROM al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "RCPT TO: <$to>\r\n"); // Enviar el comando RCPT TO al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "DATA\r\n"); // Enviar el comando DATA al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "From: $from\r\n"); // Escribir la cabecera From del correo
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "To: $to\r\n"); // Escribir la cabecera To del correo
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "Subject: $subject\r\n"); // Escribir la cabecera Subject del correo
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "MIME-Version: 1.0\r\n");
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "Content-type: text/html; charset=utf8\r\n"); // Escribir en la cabecera que el correo será HTML y en codificación UTF8
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, "\r\n"); // Escribir una línea en blanco para separar las cabeceras del cuerpo
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            if (substr($tempo, 0, 3) != "235") {
                $this->errnum = substr($tempo, 0, 3) * 1;
                $this->errstr = $tempo;
                fclose($smtp);
                return false;
            }
            fwrite($smtp, "$body\r\n"); // Escribir el cuerpo del correo
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            fwrite($smtp, ".\r\n"); // Enviar el punto final para indicar el fin del correo
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);
            if (substr($tempo, 0, 3) != "250") {
                $this->errnum = substr($tempo, 0, 3) * 1;
                $this->errstr = $tempo;
                fclose($smtp);
                return false;
            }@fwrite($smtp, "QUIT\r\n"); // Enviar el comando QUIT al servidor SMTP
            $tempo = fgets($smtp);
            if ($this->debugger === true) echo $tempo;
            array_push($this->result, $tempo);

            // Cerrar la conexión SMTP
            fclose($smtp); // Cerrar el socket TCP
            if ($this->debugger === true) echo "close\r\n";
            return true;
        }

    }
?>