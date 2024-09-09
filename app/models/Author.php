<?php

namespace app\models;

use MongoDB\BSON\UTCDateTime;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;

/**
 * Clase Author
 *
 * Esta clase representa el modelo de datos para un autor en la base de datos MongoDB.
 *
 * @property string $_id ID único del autor
 * @property string $full_name Nombre completo del autor
 * @property string $birthday Fecha de nacimiento del autor
 * @property string $nationality Nacionalidad del autor
 * @property string $short_biography Breve biografía del autor
 * @property UTCDateTime $created_at Fecha de creación del registro
 * @property UTCDateTime $updated_at Fecha de última actualización del registro
 */
class Author extends ActiveRecord
{
    /**
     * Devuelve el nombre de la colección de MongoDB asociada a este modelo.
     *
     * @return string Nombre de la colección
     */
    public static function collectionName(): string
    {
        return 'authors';
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
            'full_name',
            'birthday',
            'nationality',
            'short_biography',
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
                'value' => function() { return new UTCDateTime(time() * 1000); }
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
            [['full_name', 'birthday', 'nationality'], 'required'],
            ['full_name', 'string', 'max' => 100],
            ['full_name', 'unique'],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
            ['nationality', 'string'],
            ['short_biography', 'string', 'max' => 1000],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Define la relación con los libros del autor.
     *
     * @return \yii\mongodb\ActiveQuery Consulta de relación con los libros
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['author_ids' => '_id']);
    }
}