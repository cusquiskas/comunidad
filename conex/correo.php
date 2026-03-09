<?php
    Class Correo
    {
        
        
        private $mail   = null;
        public  $error  = null;
        public  $asunto = null;
        public  $cuerpo = null;
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
                $this->destinatario = $correo;
        }

        public function esHTML($bol) {
            $this->esHTML = $bol;                                  //Set email format to HTML
        }

        public function destinatarioCC($correo, $nombre) {
            if (getenv('SS_ENTORNO') == 'des')  
                $this->destinatarioCC = getenv('MAIL_DEV');
            else
                $this->destinatarioCC = $correo;
        }

        public function destinaratioCO($correo, $nombre) {
            if (getenv('SS_ENTORNO') == 'des')  
                $this->destinaratioCO = getenv('MAIL_DEV');
            else
                $this->destinaratioCO = $correo;
        }

        public function adjunto ($origen, $nombre) {
            //$this->mail->addAttachment($origen, $nombre);
        }

        public function mandaMail($asunto = '', $cuerpo = '') {
            try {
                if ($asunto != '') $this->asunto = $asunto;
                if ($cuerpo != '') $this->cuerpo = $cuerpo;
                $url = "http://".getenv('MAIL_HOST')."/correo.php";   // o la ruta que uses en tu API

                $data = [
                    "to"      => $this->destinatario,
                    "cc"      => $this->destinatarioCC,
                    "co"      => $this->destinaratioCO,
                    "subject" => $this->asunto,
                    "body"    => $this->cuerpo,
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

                /*echo "url: $url\n";
                echo "payload: $payload\n";
                echo "httpcode: $httpcode\n";
                echo "response: $response\n";*/

                if ($httpcode != 200) {
                    throw new Exception($response);
                }

                return true;
            } catch (Exception $e) {
                $this->error = $e;
                return false;
            }
        }
        
        public function __destruct() {
            #unset($this->mail);
        }
    }

?>