<?php

namespace Fintech;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthController
{
    private string $key;
    private string $alg = 'HS256';

    public function __construct()
    {
        $this->key = JWT_SECRET;
    }

    public function login(string $email, string $password): string
    {
        $usuario = Usuario::findByEmail($email);

        if (!$usuario || !$usuario->verifyPassword($password)) {
            throw new Exception("Credenciales incorrectas");
        }

        $payload = [
            'iss' => "fintech_api",
            'iat' => time(),
            'exp' => time() + 3600, // 1 hora
            'sub' => $usuario->getId()
        ];

        return JWT::encode($payload, $this->key, $this->alg);
    }

    public function verifyToken(string $token): ?int
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->alg));
            return (int)$decoded->sub;
        } catch (Exception $e) {
            return null;
        }
    }
}
