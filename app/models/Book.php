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

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['_id' => 'author_ids']);
    }

    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['_id' => 'genre_ids']);
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