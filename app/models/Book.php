<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Clase Book
 *
 * Esta clase representa el modelo de datos para un libro en la base de datos MongoDB.
 *
 * @property string $_id ID único del libro
 * @property string $title Título del libro
 * @property array $author_ids IDs de los autores del libro
 * @property array $genre_ids IDs de los géneros del libro
 * @property integer $year_published Año de publicación del libro
 * @property string $brief_description Breve descripción del libro
 * @property string $ISBN Número ISBN del libro
 * @property \MongoDB\BSON\UTCDateTime $created_at Fecha de creación del registro
 * @property \MongoDB\BSON\UTCDateTime $updated_at Fecha de última actualización del registro
 * @property Author[] $authors Autores del libro (cargados dinámicamente)
 * @property Genre[] $genres Géneros del libro (cargados dinámicamente)
 */
class Book extends ActiveRecord
{
    /**
     * Devuelve el nombre de la colección de MongoDB asociada a este modelo.
     *
     * @return string Nombre de la colección
     */
    public static function collectionName(): string
    {
        return 'books';
    }

    /**
     * Define los atributos del modelo.
     *
     * @return array Lista de atributos del modelo
     */
    public function attributes(): array
    {
        return [
            '_id',
            'title',
            'author_ids',
            'genre_ids',
            'year_published',
            'brief_description',
            'ISBN',
            'created_at',
            'updated_at',
            'authors',
            'genres',
        ];
    }

    /**
     * Define los comportamientos del modelo.
     *
     * @return array Lista de comportamientos del modelo
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() { return new \MongoDB\BSON\UTCDateTime(time() * 1000); }
            ],
        ];
    }

    /**
     * Define las reglas de validación para los atributos del modelo.
     *
     * @return array Lista de reglas de validación
     */
    public function rules(): array
    {
        return [
            [['title', 'ISBN', 'author_ids', 'genre_ids'], 'required'],
            ['title', 'string', 'max' => 255],
            ['year_published', 'integer', 'min' => 1000, 'max' => date('Y')],
            ['brief_description', 'string', 'max' => 1000],
            ['ISBN', 'string'],
            ['ISBN', 'unique'],
            ['author_ids', 'each', 'rule' => ['string']],
            ['genre_ids', 'each', 'rule' => ['string']],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Define los campos que se deben serializar al convertir el modelo a array o JSON.
     *
     * @return array Lista de campos a serializar
     */
    public function fields()
    {
        $fields = parent::fields();

        $fields['authors'] = function ($model) {
            return $model->authors;
        };
        $fields['genres'] = function ($model) {
            return $model->genres;
        };

        unset($fields['author_ids'], $fields['genre_ids']);

        return $fields;
    }

    /**
     * Se ejecuta después de que el modelo es cargado desde la base de datos.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->loadAuthors();
        $this->loadGenres();
    }

    /**
     * Carga los autores asociados al libro.
     */
    public function loadAuthors()
    {
        $this->authors = Author::find()->where(['_id' => $this->author_ids])->all();
    }

    /**
     * Carga los géneros asociados al libro.
     */
    public function loadGenres()
    {
        $this->genres = Genre::find()->where(['_id' => $this->genre_ids])->all();
    }

    /**
     * Realiza una búsqueda de libros basada en los parámetros proporcionados.
     *
     * @param array $params Parámetros de búsqueda
     * @return array Lista de libros que coinciden con los criterios de búsqueda
     */
    public function search($params)
    {
        $query = self::find();

        if (!empty($params['genre_id'])) {
            $query->where(['genre_ids' => $params['genre_id']]);
        }

        if (!empty($params['author_id'])) {
            $query->where(['author_ids' => $params['author_id']]);
        }

        return $query->all();
    }
}