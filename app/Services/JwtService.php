<?php

namespace App\Services;
use Firebase\JWT\JWT;
class JwtService
{
    public static function generateToken($user)
    {
        $key = env('JWT_SECRET_KEY');
        $payload = [
            'iss' => base_url(),
            'aud' => base_url(),
            'iat' => time(),
            'exp' => time() + 3600,
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'mobile_number' => $user['mobile_number'],
                'role' => $user['role']
            ]
        ];

        return JWT::encode(
            $payload,
            $key,
            'HS256'
        );
    }
}