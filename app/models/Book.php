<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Book extends ActiveRecord
{
    public static function collectionName(): string
    {
        return 'books';
    }

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

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAuthors();
        $this->loadGenres();
    }

    public function loadAuthors()
    {
        $this->authors = Author::find()->where(['_id' => $this->author_ids])->all();
    }

    public function loadGenres()
    {
        $this->genres = Genre::find()->where(['_id' => $this->genre_ids])->all();
    }

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