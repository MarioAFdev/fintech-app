<?php

namespace Fintech;

class ResponseHelper
{
    /**
     * Envía una respuesta JSON estandarizada.
     * @param mixed $data Datos a enviar (array u objeto)
     * @param int $code Código de estado HTTP (default 200)
     */
    public static function jsonResponse($data, int $code = 200)
    {
        // Limpiamos cualquier salida previa para evitar errores de headers
        if (ob_get_length()) ob_clean();

        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($data);
        exit; // Cortamos la ejecución para asegurar que no se envíe nada más
    }

    /**
     * Envía un error estandarizado.
     */
    public static function error(string $message, int $code = 500)
    {
        self::jsonResponse([
            'status' => 'error',
            'message' => $message
        ], $code);
    }
}
