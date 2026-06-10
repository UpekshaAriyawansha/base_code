<?php

namespace Src\Infrastructure\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;

    public function __construct()
    {
        $this->secret = config('jwt.secret');
    }

    public function generate(array $payload): string
    {
        $issuedAt = time();

        $expire = $issuedAt + (int) config(
                'jwt.ttl',
                3600
            );

        $tokenPayload = [

            'iat' => $issuedAt,

            'exp' => $expire,

            'data' => $payload

        ];

        return JWT::encode(
            $tokenPayload,
            $this->secret,
            'HS256'
        );
    }

    public function decode(string $token): object
    {
        return JWT::decode(
            $token,
            new Key(
                $this->secret,
                'HS256'
            )
        );
    }
}