<?php
    Class Correo
    {
        
        
        private $mail   = null;
        public  $error  = null;
        private $destinatario = null;
        private $destinatarioCC = null;
        private $esHTML = false;
        private $destinaratioCO = null;

        public function __construct()
        {
            
        }
        public function destinatario($correo, $nombre=null) {
            if (getenv('SS_ENTORNO') == 'des') 
                $this->destinatario = getenv('MAIL_DEV');
            else
                $this->destinatario = "$nombre <$correo>";
        }

        public function esHTML($bol) {
            $this->esHTML = $bol;                                  //Set email format to HTML
        }

        public function destinatarioCC($correo, $nombre) {
            if (getenv('SS_ENTORNO') == 'des')  
                $this->destinatarioCC = getenv('MAIL_DEV');
            else
                $this->destinatarioCC = "$nombre <$correo>";
        }

        public function destinaratioCO($correo, $nombre) {
            if (getenv('SS_ENTORNO') == 'des')  
                $this->destinaratioCO = getenv('MAIL_DEV');
            else
                $this->destinaratioCO = "$nombre <$correo>";
        }

        public function adjunto ($origen, $nombre) {
            //$this->mail->addAttachment($origen, $nombre);
        }

        public function mandaMail($asunto = '', $cuerpo = '') {
            try {
                
                $url = "http://".getenv('MAIL_DEV')."/correo.php";   // o la ruta que uses en tu API

                $data = [
                    "to"      => $this->destinatario,
                    "cc"      => $this->destinatarioCC,
                    "co"      => $this->destinaratioCO,
                    "subject" => $asunto,
                    "body"    => $cuerpo,
                    "app"     => "app-Comunidad"
                ];

                $payload = json_encode($data);

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/json",
                    "Content-Length: " . strlen($payload)
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                echo "Código HTTP: $httpcode\n";
                echo "Respuesta: $response\n";

                return true;
            } catch (Exception $e) {
                $this->error = $e;
                return false;
            }
        }
        
        public function __destruct() {
            unset($this->mail);
        }
    }

/*
    $correo = new SMTP();
    $correo->destinatario('cusquiskas@gmail.com', 'José Miguel');
    $correo->asunto = 'Pruebas final PHPMailer';
    $correo->cuerpo = '<h1>SUCCESS</h1><hr/><p>El contenido del correo, ahora le estoy diciendo que lo que mando <b>es HTML</b></p><p>Ahora me paso a la librería PHPMailer, que va como un cañón.</p>';
    #$nuevo->verbose();
    if ($correo->mandaMail()) {
        echo "Correo enviado";
    } else {
        echo "Error: ". var_export($nuevo->error, true);
    }
*/
?>