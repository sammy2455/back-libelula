<?php

namespace app\components;

use Yii;
use yii\web\ErrorHandler;
use yii\web\HttpException;

class CustomErrorHandler extends ErrorHandler
{
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

    protected function convertExceptionToJson($exception)
    {
        return json_encode($this->convertExceptionToArray($exception), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}