<?php

namespace app\controllers;

use yii\rest\ActiveController;

class BookController extends ActiveController
{
    public $modelClass = 'app\models\Book';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // Aquí puedes añadir o modificar comportamientos si es necesario
        // Por ejemplo, configurar CORS o autenticación

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();

        // Puedes personalizar acciones aquí si es necesario
        // Por ejemplo:
        // unset($actions['delete']);

        return $actions;
    }
}