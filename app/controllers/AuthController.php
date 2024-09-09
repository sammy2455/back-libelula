<?php
namespace app\controllers;

use app\components\JwtHelper;
use Yii;
use yii\rest\Controller;
use app\models\User;

class AuthController extends Controller
{
    public function actionLogin(): array
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        $user = User::findByUsername($username);

        if ($user && $user->validatePassword($password)) {
            $token = JwtHelper::generateToken($user, 300);
            return ['token' => $token];
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Nombre de usuario o contraseña incorrectos'];
        }
    }
}