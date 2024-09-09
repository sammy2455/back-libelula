<?php

namespace app\controllers;

use app\components\JwtHelper;
use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * AuthController maneja la autenticación de usuarios.
 *
 * Esta clase extiende de yii\rest\Controller y proporciona
 * funcionalidad para la autenticación de usuarios mediante
 * nombre de usuario y contraseña, devolviendo un token JWT.
 */
class AuthController extends Controller
{
    /**
     * Acción para manejar el inicio de sesión de usuarios.
     *
     * Esta acción recibe el nombre de usuario y la contraseña por POST,
     * valida las credenciales y, si son correctas, genera y devuelve
     * un token JWT. Si las credenciales son incorrectas, devuelve un
     * error con código de estado 401.
     *
     * @return array Respuesta con el token JWT o mensaje de error
     */
    public function actionLogin(Request $request, Response $response): array
    {
        try {
            $username = $request->post('username');
            $password = $request->post('password');

            $user = User::findByUsername($username);

            if (!$user || !$user->validatePassword($password)) {
                throw new UnauthorizedHttpException('Nombre de usuario o contraseña incorrectos');
            }

            $token = JwtHelper::generateToken($user, 300);

            return [
                'message' => 'Inicio de sesión exitoso',
                'token' => $token
            ];
        } catch (UnauthorizedHttpException $e) {
            $response->setStatusCode(401);
            return [
                'error' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            return [
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ];
        }
    }
}