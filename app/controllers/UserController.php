<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\Response;

class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actionCreate()
    {
        $user = new User();
        $user->load(Yii::$app->request->post(), '');

        if ($user->save()) {
            Yii::$app->response->statusCode = 201;
            return [
                'message' => 'Usuario creado exitosamente',
                'user' => [
                    'id' => (string)$user->_id,
                    'username' => $user->username,
                ],
            ];
        }

        Yii::$app->response->statusCode = 422;
        return [
            'message' => 'Error al crear el usuario',
            'errors' => $user->errors,
        ];
    }
}