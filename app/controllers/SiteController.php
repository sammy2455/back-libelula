<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * SiteController es el controlador principal para las páginas del sitio.
 *
 * Esta clase extiende de yii\web\Controller y se utiliza típicamente
 * para manejar las páginas generales del sitio web, como la página de inicio.
 */
class SiteController extends Controller
{
    /**
     * Define los comportamientos del controlador.
     *
     * Actualmente, utiliza los comportamientos predeterminados de Controller
     * sin modificaciones adicionales.
     *
     * @return array Los comportamientos configurados para este controlador
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Define las acciones del controlador.
     *
     * Este método elimina la acción 'index' predeterminada,
     * permitiendo que sea manejada por el método actionIndex() personalizado.
     *
     * @return array Las acciones configuradas para este controlador
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);

        return $actions;
    }

    /**
     * Acción para la página de inicio.
     *
     * Este método maneja la solicitud para la página de inicio del sitio.
     * Actualmente, no realiza ninguna operación y devuelve null.
     *
     * @return null
     */
    public function actionIndex()
    {
        return null;
    }
}