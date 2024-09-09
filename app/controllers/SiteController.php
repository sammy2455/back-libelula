<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);

        return $actions;
    }

    public function actionIndex()
    {
        return null;
    }

}
