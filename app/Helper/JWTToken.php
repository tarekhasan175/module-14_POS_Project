<?php

namespace App\Helper;

use Exception;
use App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function createToken($userEmail, $userID): string
    {
        $key = env('JWT_KEY');
        $payloader = [
            'iss' => 'laravel_token',
            'iat' => time(),
            'exp' => time() + 3600,
            'email' => $userEmail,
            'userID' => $userID
        ];
        return JWT::encode($payloader, $key, 'HS256');
    }

    public static function createTokenForSetPassword($userEmail): string
    {
        $key = env('JWT_KEY');
        $payloader = [
            'iss' => 'laravel_token',
            'iat' => time(),
            'exp' => time() + 60 * 10,
            'email' => $userEmail,
            'userID' => '0'
        ];
        return JWT::encode($payloader, $key, 'HS256');
    }

    public static function verifyToken($token): string|object
    {
        try {
            if ($token == null) {
                return 'unathorized';
            } else {
                $key = env('JWT_KEY');
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                return $decoded;
            }
        } catch (Exception $e) {
            return 'unathorized';
        }
    }

}
