<?php

class m240909_072416_seed_books_data extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->insert('books', [
            '_id' => '66de32efed27665cd603926f',
            'title' => 'Estrellas del Mañana',
            'ISBN' => '9876543210',
            'author_ids' => ['66de21c6ed27665cd603926e'],
            'genre_ids' => ['66de2f09eff990558ffc4ce9'],
            'brief_description' => 'Una odisea espacial que explora los límites de la humanidad',
            'year_published' => 2022,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:28:43.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:28:43.000Z') * 1000),
        ]);

        $this->insert('books', [
            '_id' => '66de32efed27665cd6039270',
            'title' => 'Sombras en el Espejo',
            'ISBN' => '4567890123',
            'author_ids' => ['66de21c6ed27665cd603926f'],
            'genre_ids' => ['66de2f09eff990558ffc4cea'],
            'brief_description' => 'Un thriller psicológico que desafía la percepción de la realidad',
            'year_published' => 2021,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:29:43.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:29:43.000Z') * 1000),
        ]);

        $this->insert('books', [
            '_id' => '66de32efed27665cd6039271',
            'title' => 'Ecos del Nilo',
            'ISBN' => '7890123456',
            'author_ids' => ['66de21c6ed27665cd6039270'],
            'genre_ids' => ['66de2f09eff990558ffc4cf1'],
            'brief_description' => 'Una novela histórica que entrelaza el pasado y el presente de Egipto',
            'year_published' => 2024,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:30:43.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:30:43.000Z') * 1000),
        ]);

        $this->insert('books', [
            '_id' => '66de32efed27665cd6039272',
            'title' => 'El Bosque Susurrante',
            'ISBN' => '2345678901',
            'author_ids' => ['66de21c6ed27665cd6039271'],
            'genre_ids' => ['66de2f09eff990558ffc4ce8'],
            'brief_description' => 'Una mágica aventura para jóvenes lectores sobre la importancia de la naturaleza',
            'year_published' => 2023,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:31:43.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T23:31:43.000Z') * 1000),
        ]);

        $this->insert('books', [
            '_id' => '66de5c3df28b997d4e0ff2fe',
            'title' => 'Chapther 1: Living my dream',
            'ISBN' => '125789654323',
            'author_ids' => ['66de21c6ed27665cd603926d'],
            'genre_ids' => ['66de2fb4eff990558ffc4ced'],
            'year_published' => 2022,
            'brief_description' => "The fleeting great adventure of Johnny's life",
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-09T02:23:57.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-09T02:23:57.000Z') * 1000),
        ]);

        $this->insert('books', [
            '_id' => '66de9a3ded27665cd6039273',
            'title' => 'Johnny Life',
            'ISBN' => '1257896543',
            'author_ids' => ['66de21c6ed27665cd603926d'],
            'genre_ids' => ['66de2fb4eff990558ffc4ced'],
            'brief_description' => "The fleeting great adventure of Johnny's life",
            'year_published' => 2023,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-09T06:48:29.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-09T06:48:29.000Z') * 1000),
        ]);
    }

    public function down()
    {
        $this->dropCollection('books');
    }
}
