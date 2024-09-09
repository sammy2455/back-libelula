<?php

namespace app\controllers;

use app\components\JwtAuthFilter;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * BookController maneja las operaciones CRUD para el modelo Book.
 *
 * Esta clase extiende de yii\rest\ActiveController y proporciona
 * endpoints RESTful para gestionar libros, incluyendo listado,
 * visualización, creación, actualización y eliminación.
 */
class BookController extends ActiveController
{
    public $modelClass = 'app\models\Book';

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
     * Sobrescribe las acciones predeterminadas, personaliza el proveedor de datos
     * para la acción de índice y el método de búsqueda para la acción de vista.
     *
     * @return array Las acciones configuradas para este controlador
     */
    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['view']['findModel'] = [$this, 'findModel'];

        return $actions;
    }

    /**
     * Prepara el proveedor de datos para la acción de índice.
     *
     * Permite filtrar libros por género, autor y año de publicación si se proporcionan en la consulta.
     *
     * @return ActiveDataProvider El proveedor de datos configurado
     * @throws ServerErrorHttpException si ocurre un error al preparar los datos
     */
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

    /**
     * Busca un modelo Book basado en su ID.
     *
     * @param string $id El ID del libro a buscar
     * @return Book El modelo Book encontrado
     * @throws NotFoundHttpException si el libro no existe
     * @throws ServerErrorHttpException si ocurre un error al buscar el libro
     */
    public function findModel(string $id): Book
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

    /**
     * Crea un nuevo libro.
     *
     * @param Request $request La solicitud HTTP
     * @return Book El libro creado
     * @throws BadRequestHttpException si los datos proporcionados no son válidos
     * @throws ServerErrorHttpException si ocurre un error al crear el libro
     */
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

    /**
     * Actualiza un libro existente.
     *
     * @param Request $request La solicitud HTTP
     * @param string $id El ID del libro a actualizar
     * @return Book El libro actualizado
     * @throws NotFoundHttpException si el libro no existe
     * @throws BadRequestHttpException si los datos proporcionados no son válidos
     * @throws ServerErrorHttpException si ocurre un error al actualizar el libro
     */
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

    /**
     * Elimina un libro existente.
     *
     * @param Response $response La respuesta HTTP
     * @param string $id El ID del libro a eliminar
     * @throws NotFoundHttpException si el libro no existe
     * @throws ServerErrorHttpException si ocurre un error al eliminar el libro
     */
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