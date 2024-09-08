<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Genre extends ActiveRecord
{
    public static function collectionName(): string
    {
        return 'genres';
    }

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
            ['name', 'required'],
            ['name', 'string', 'max' => 100],
            ['description', 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['genre_ids' => '_id']);
    }
}