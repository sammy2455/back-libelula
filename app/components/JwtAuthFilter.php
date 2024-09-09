<?php

namespace app\components;

use app\models\User;
use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

/**
 * JwtAuthFilter es un filtro de acción para manejar la autenticación basada en JWT.
 *
 * Este filtro verifica la presencia y validez de un token JWT en el encabezado
 * de autorización de la solicitud. Si el token es válido, establece la identidad
 * del usuario en la aplicación. Si no, lanza una excepción de acceso no autorizado.
 */
class JwtAuthFilter extends ActionFilter
{
    /**
     * Realiza la autenticación JWT antes de que se ejecute una acción.
     *
     * Este método intercepta la solicitud antes de que se ejecute la acción del controlador,
     * verifica el token JWT en el encabezado de autorización y autentica al usuario si el token es válido.
     *
     * @param \yii\base\Action $action La acción que está a punto de ser ejecutada
     * @return bool Si la acción debe continuar ejecutándose
     * @throws UnauthorizedHttpException si la autenticación falla
     */
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