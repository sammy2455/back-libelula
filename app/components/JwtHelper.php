<?php

namespace app\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

class JwtHelper
{
    public static function generateToken($user, $expiration = 3600): string
    {
        $time = time();

        $token = [
            'iat' => $time,
            'exp' => $time + $expiration,
            'data' => [
                'id' => (string)$user->_id,
                'username' => $user->username
            ]
        ];

        return JWT::encode($token, Yii::$app->params['jwtSecret'], 'HS256');
    }

    public static function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(Yii::$app->params['jwtSecret'], 'HS256'));

            // Verificar si el token ha expirado
            if (time() > $decoded->exp) {
                return false;
            }

            return $decoded->data;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getTokenExpiration($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(Yii::$app->params['jwtSecret'], 'HS256'));
            return $decoded->exp;
        } catch (\Exception $e) {
            return false;
        }
    }
}