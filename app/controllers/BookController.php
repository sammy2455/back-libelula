<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class BookController extends ActiveController
{
    public $modelClass = 'app\models\Book';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['view']['findModel'] = [$this, 'findModel'];

        return $actions;
    }

    public function prepareDataProvider(): ActiveDataProvider
    {
        try {
            $query = Book::find();

            if ($genre = Yii::$app->request->get('genre')) {
                $query->andWhere(['genre_ids' => $genre]);
            }
            if ($author = Yii::$app->request->get('author')) {
                $query->andWhere(['author_ids' => $author]);
            }
            if ($year = Yii::$app->request->get('year')) {
                $query->andWhere(['year_published' => intval($year)]);
            }

            return new ActiveDataProvider([
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al preparar los datos: ' . $e->getMessage());
        }
    }

    public function findModel($id): Book
    {
        try {
            $model = Book::findOne($id);
            if ($model === null) {
                throw new NotFoundHttpException('El libro solicitado no existe.');
            }

            return $model;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al buscar el libro: ' . $e->getMessage());
        }
    }

    public function actionCreate(Request $request): Book
    {
        try {
            $model = new Book();
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
            throw new ServerErrorHttpException('Error al crear el libro: ' . $e->getMessage());
        }
    }

    public function actionUpdate(Request $request, string $id): Book
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
            throw new ServerErrorHttpException('Error al actualizar el libro: ' . $e->getMessage());
        }
    }

    public function actionDelete(Response $response, string $id)
    {
        try {
            $model = $this->findModel($id);

            if (!$model->delete()) {
                throw new ServerErrorHttpException('No se pudo eliminar el libro.');
            }

            $response->setStatusCode(204);
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Error al eliminar el libro: ' . $e->getMessage());
        }
    }

}