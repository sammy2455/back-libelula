<?php

namespace app\controllers;

use app\components\JwtAuthFilter;
use yii\rest\ActiveController;

/**
 * GenreController maneja las operaciones CRUD para el modelo Genre.
 *
 * Esta clase extiende de yii\rest\ActiveController y proporciona
 * endpoints RESTful para gestionar géneros literarios. Utiliza las
 * acciones predeterminadas de ActiveController sin modificaciones.
 */
class GenreController extends ActiveController
{
    /**
     * @var string La clase del modelo asociado a este controlador.
     */
    public $modelClass = 'app\models\Genre';

    /**
     * Define los comportamientos del controlador.
     *
     * Añade el filtro de autenticación JWT a los comportamientos heredados.
     *
     * @return array Los comportamientos configurados para este controlador
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['jwtAuth'] = [
            'class' => JwtAuthFilter::class,
        ];

        return $behaviors;
    }

    /**
     * Define las acciones del controlador.
     *
     * Este método permite personalizar las acciones disponibles en el controlador.
     * Actualmente, utiliza todas las acciones predeterminadas de ActiveController.
     *
     * @return array Las acciones configuradas para este controlador
     */
    public function actions(): array
    {
        $actions = parent::actions();

        return $actions;
    }
}