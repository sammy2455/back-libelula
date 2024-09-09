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

/**
 * AuthorController maneja las operaciones CRUD para el modelo Author.
 *
 * Esta clase extiende de yii\rest\ActiveController y proporciona
 * endpoints RESTful para gestionar autores, incluyendo listado,
 * creación, actualización y eliminación.
 */
class AuthorController extends ActiveController
{
    public $modelClass = 'app\models\Author';

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
     * Sobrescribe las acciones predeterminadas y personaliza el proveedor de datos
     * para la acción de índice.
     *
     * @return array Las acciones configuradas para este controlador
     */
    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * Prepara el proveedor de datos para la acción de índice.
     *
     * Permite filtrar autores por nacionalidad si se proporciona en la consulta.
     *
     * @return ActiveDataProvider El proveedor de datos configurado
     * @throws ServerErrorHttpException si ocurre un error al preparar los datos
     */
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

    /**
     * Busca un modelo Author basado en su ID.
     *
     * @param string $id El ID del autor a buscar
     * @return Author El modelo Author encontrado
     * @throws NotFoundHttpException si el autor no existe
     * @throws ServerErrorHttpException si ocurre un error al buscar el autor
     */
    public function actionView(string $id): Author
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

    /**
     * Crea un nuevo autor.
     *
     * @param Request $request La solicitud HTTP
     * @return Author El autor creado
     * @throws BadRequestHttpException si los datos proporcionados no son válidos
     * @throws ServerErrorHttpException si ocurre un error al crear el autor
     */
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

    /**
     * Actualiza un autor existente.
     *
     * @param Request $request La solicitud HTTP
     * @param string $id El ID del autor a actualizar
     * @return Author El autor actualizado
     * @throws NotFoundHttpException si el autor no existe
     * @throws BadRequestHttpException si los datos proporcionados no son válidos
     * @throws ServerErrorHttpException si ocurre un error al actualizar el autor
     */
    public function actionUpdate(Request $request, string $id): Author
    {
        try {
            $model = Author::findOne($id);

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

    /**
     * Elimina un autor existente.
     *
     * @param Response $response La respuesta HTTP
     * @param string $id El ID del autor a eliminar
     * @throws NotFoundHttpException si el autor no existe
     * @throws ServerErrorHttpException si ocurre un error al eliminar el autor
     */
    public function actionDelete(Response $response, string $id)
    {
        try {
            $model = Author::findOne($id);

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