<?php

namespace app\components;

use app\models\User;
use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class JwtAuthFilter extends ActionFilter
{
    public function beforeAction($action): bool
    {
        $request = Yii::$app->request;
        $authHeader = $request->getHeaders()->get('Authorization');

        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $token = $matches[1];
            $data = JwtHelper::validateToken($token);

            if ($data) {
                Yii::$app->user->setIdentity(User::findIdentity($data->id));
                return parent::beforeAction($action);
            }
        }

        throw new UnauthorizedHttpException('Acceso no autorizado');
    }
}