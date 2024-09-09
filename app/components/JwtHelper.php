<?php

namespace app\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

/**
 * JwtHelper proporciona métodos estáticos para manejar tokens JWT.
 *
 * Esta clase ofrece funcionalidad para generar, validar y obtener información
 * de tokens JWT (JSON Web Tokens) utilizando la biblioteca Firebase JWT.
 */
class JwtHelper
{
    /**
     * Genera un token JWT para un usuario dado.
     *
     * @param object $user El objeto usuario para el cual se genera el token
     * @param int $expiration Tiempo de expiración del token en segundos (por defecto 1 hora)
     * @return string El token JWT generado
     */
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

    /**
     * Valida un token JWT.
     *
     * @param string $token El token JWT a validar
     * @return mixed Los datos del token si es válido, false en caso contrario
     */
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

    /**
     * Obtiene el tiempo de expiración de un token JWT.
     *
     * @param string $token El token JWT del cual obtener el tiempo de expiración
     * @return mixed El tiempo de expiración del token si es válido, false en caso contrario
     */
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