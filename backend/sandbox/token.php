<?php
    /*$client_id = '2ae7619f-651c-4d89-a8f3-0d0fb909adca';
    $client_secret = 'qN5dE3wG4nO2fJ7kD5cQ6jA8bC6xM6tD3wP1cL2uG0nJ7lX8sL';
    $token_url = 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/bancamarch/token/';

    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    
    curl_close($ch);
    
    echo var_export($response, true);
    
    $response_data = json_decode($response, true);
    
    if (isset($response_data['error'])) {
        echo 'Error: ' . $response_data['error'];
        if (isset($response_data['error_description'])) {
            echo ' - ' . $response_data['error_description'];
        }
    }*/

    function generate_code_verifier($length = 128) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-._~';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    $code_verifier = generate_code_verifier(128);
    
    // Paso 2: Generar el code_challenge utilizando SHA-256 y codificación base64 URL-safe
    function generate_code_challenge($code_verifier) {
        $hash = hash('sha256', $code_verifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }
    
    $code_challenge = generate_code_challenge($code_verifier);

    $aspsp = 'bancamarch'; // Nombre del banco
    $response_type = 'code';
    $client_id = '2ae7619f-651c-4d89-a8f3-0d0fb909adca';
    $scope = 'AIS';
    $state = bin2hex(random_bytes(8)); // Genera un valor aleatorio para el estado
    $redirect_uri = 'https://cusquiskas.com/comunidad/backend/sandbox/retoken.php'; // Asegúrate de que esta URL esté registrada en tu aplicación
    #$code_challenge = 'YOUR_CODE_CHALLENGE'; // Debes generar un código de desafío según RFC 7636
    $code_challenge_method = 'S256';

    $authorization_url = "https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/$aspsp/authorize";
    $authorization_url .= "?response_type=$response_type&client_id=$client_id&scope=$scope&state=$state&redirect_uri=$redirect_uri&code_challenge=$code_challenge&code_challenge_method=$code_challenge_method";

    header('Location: ' . $authorization_url);
    exit;
       
?>