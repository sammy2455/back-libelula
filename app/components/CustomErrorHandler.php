<?php

namespace app\components;

use Yii;
use yii\web\ErrorHandler;
use yii\web\HttpException;

/**
 * CustomErrorHandler es un manejador de errores personalizado para la aplicación.
 *
 * Esta clase extiende el ErrorHandler predeterminado de Yii2 para proporcionar
 * respuestas de error en formato JSON y mensajes de error personalizados.
 */
class CustomErrorHandler extends ErrorHandler
{
    /**
     * Renderiza la excepción como una respuesta JSON.
     *
     * @param \Exception $exception La excepción a renderizar
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            $response->format = \yii\web\Response::FORMAT_JSON;

            if ($exception instanceof HttpException) {
                $response->setStatusCode($exception->statusCode);
            } else {
                $response->setStatusCode(500);
            }

            $errorData = $this->convertExceptionToArray($exception);

            $response->data = $errorData;
            $response->send();
        } else {
            echo $this->convertExceptionToJson($exception);
        }
    }

    /**
     * Convierte una excepción en un array con información del error.
     *
     * @param \Exception $exception La excepción a convertir
     * @return array Información del error en formato de array
     */
    protected function convertExceptionToArray($exception)
    {
        $statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;

        return [
            'name' => $this->getErrorName($exception),
            'message' => $this->getErrorMessage($exception),
            'code' => 0, // Mantenemos el código en 0 como en tu ejemplo
            'status' => $statusCode,
        ];
    }

    /**
     * Obtiene un nombre descriptivo para el error basado en el código de estado HTTP.
     *
     * @param \Exception $exception La excepción
     * @return string Nombre descriptivo del error
     */
    protected function getErrorName($exception)
    {
        if ($exception instanceof HttpException) {
            switch ($exception->statusCode) {
                case 400: return 'Bad Request';
                case 401: return 'Unauthorized';
                case 403: return 'Forbidden';
                case 404: return 'Not Found';
                case 500: return 'Internal Server Error';
                default: return 'HTTP Error';
            }
        }
        return 'Error';
    }

    /**
     * Obtiene un mensaje de error personalizado basado en el código de estado HTTP.
     *
     * @param \Exception $exception La excepción
     * @return string Mensaje de error personalizado
     */
    protected function getErrorMessage($exception)
    {
        if ($exception instanceof HttpException) {
            switch ($exception->statusCode) {
                case 401: return 'Acceso no autorizado';
                case 403: return 'Acceso prohibido';
                case 404: return 'Recurso no encontrado';
                default: return $exception->getMessage() ?: 'Ha ocurrido un error';
            }
        }
        return 'Ha ocurrido un error en el servidor';
    }

    /**
     * Convierte una excepción en una cadena JSON formateada.
     *
     * @param \Exception $exception La excepción a convertir
     * @return string Representación JSON de la excepción
     */
    protected function convertExceptionToJson($exception)
    {
        return json_encode($this->convertExceptionToArray($exception), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}