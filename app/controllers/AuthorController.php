<?php

namespace app\controllers;

use app\components\JwtAuthFilter;
use app\models\Author;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class AuthorController extends ActiveController
{
    public $modelClass = 'app\models\Author';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['jwtAuth'] = [
            'class' => JwtAuthFilter::class,
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider(): ActiveDataProvider
    {
        try {
            $query = Author::find();

            if ($nationality = Yii::$app->request->get('nationality')) {
                $query->andWhere(['nationality' => $nationality]);
            }

            return new ActiveDataProvider([
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al preparar los datos: ' . $e->getMessage());
        }
    }

    public function findModel($id): Author
    {
        try {
            $model = Author::findOne($id);
            if ($model === null) {
                throw new NotFoundHttpException('El autor solicitado no existe.');
            }

            return $model;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al buscar el autor: ' . $e->getMessage());
        }
    }

    public function actionCreate(Request $request): Author
    {
        try {
            $model = new Author();
            $dataRequest = $request->getBodyParams();
            $model->load($dataRequest, '');

            if (!$model->validate() || !$model->save()) {
                throw new BadRequestHttpException(json_encode($model->errors));
            }

            Yii::$app->response->setStatusCode(201);
            return $model;
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al crear el autor: ' . $e->getMessage());
        }
    }

    public function actionUpdate(Request $request, string $id): Author
    {
        try {
            $model = $this->findModel($id);

            $dataRequest = $request->getBodyParams();
            $model->load($dataRequest, '');

            if (!$model->validate() || !$model->save()) {
                throw new BadRequestHttpException(json_encode($model->errors));
            }

            return $model;
        } catch (NotFoundHttpException|BadRequestHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al actualizar el autor: ' . $e->getMessage());
        }
    }

    public function actionDelete(Response $response, string $id)
    {
        try {
            $model = $this->findModel($id);

            if (!$model->delete()) {
                throw new ServerErrorHttpException('No se pudo eliminar el autor.');
            }

            $response->setStatusCode(204);
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al eliminar el autor: ' . $e->getMessage());
        }
    }
}