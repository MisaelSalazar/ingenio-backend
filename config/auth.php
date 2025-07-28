<?php
function isAdmin() {
    $token = null;

    // Intenta con getallheaders() (Apache)
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
        }
    }

    // Alternativa para Nginx y otros entornos
    if (!$token && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
    }

    // Validación del token simple
    return $token === 'Bearer token_admin';
}
