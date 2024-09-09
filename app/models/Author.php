<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;

class Author extends ActiveRecord
{
    public static function collectionName(): string
    {
        return 'authors';
    }

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
            [['full_name', 'birthday', 'nationality'], 'required'],
            ['full_name', 'string', 'max' => 100],
            ['full_name', 'unique'],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
            ['nationality', 'string'],
            ['short_biography', 'string', 'max' => 1000],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['author_ids' => '_id']);
    }
}