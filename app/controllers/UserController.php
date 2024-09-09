<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\User;
use yii\web\Request;
use yii\web\Response;

/**
 * UserController maneja las operaciones relacionadas con los usuarios.
 *
 * Esta clase extiende de yii\rest\Controller y proporciona
 * funcionalidad para la creación de nuevos usuarios.
 */
class UserController extends Controller
{
    /**
     * Define los comportamientos del controlador.
     *
     * Este método configura el formato de respuesta para asegurar
     * que las respuestas sean en formato JSON.
     *
     * @return array Los comportamientos configurados para este controlador
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Acción para crear un nuevo usuario.
     *
     * Este método maneja la solicitud POST para crear un nuevo usuario.
     * Si la creación es exitosa, devuelve un mensaje de éxito y los datos del usuario.
     * Si hay errores de validación, devuelve un mensaje de error y los detalles de los errores.
     * Si ocurre una excepción, devuelve un mensaje de error interno del servidor.
     *
     * @param Request $request La solicitud HTTP
     * @param Response $response La respuesta HTTP
     * @return array La respuesta de la operación de creación
     */
    public function actionCreate(Request $request, Response $response): array
    {
        try {
            $user = new User();

            $dataRequest = $request->getBodyParams();
            $user->load($dataRequest, '');

            if (!$user->save()) {
                $response->statusCode = 422;
                return [
                    'message' => 'Error al crear el usuario',
                    'errors' => $user->errors,
                ];
            }

            $response->statusCode = 201;
            return [
                'message' => 'Usuario creado exitosamente',
                'user' => [
                    'id' => (string)$user->_id,
                    'username' => $user->username,
                ],
            ];
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            return [
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ];
        }
    }
}