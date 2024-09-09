<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Clase Genre
 *
 * Esta clase representa el modelo de datos para un género literario en la base de datos MongoDB.
 *
 * @property string $_id ID único del género
 * @property string $name Nombre del género
 * @property string $description Descripción del género
 * @property \MongoDB\BSON\UTCDateTime $created_at Fecha de creación del registro
 * @property \MongoDB\BSON\UTCDateTime $updated_at Fecha de última actualización del registro
 */
class Genre extends ActiveRecord
{
    /**
     * Devuelve el nombre de la colección de MongoDB asociada a este modelo.
     *
     * @return string Nombre de la colección
     */
    public static function collectionName(): string
    {
        return 'genres';
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
            'name',
            'description',
            'created_at',
            'updated_at',
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
            ['name', 'required'],
            ['name', 'string', 'max' => 100],
            ['description', 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Define la relación con los libros del género.
     *
     * @return \yii\mongodb\ActiveQuery Consulta de relación con los libros
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['genre_ids' => '_id']);
    }
}