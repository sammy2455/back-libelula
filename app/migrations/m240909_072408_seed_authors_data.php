<?php

class m240909_072408_seed_authors_data extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->insert('authors', [
            '_id' => '66de21c6ed27665cd603926d',
            'full_name' => 'Dayanna Alcivar',
            'birthday' => '2002-09-30',
            'nationality' => 'Ecuatoriana',
            'short_biography' => 'Escritora biografica y fantasia',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:14:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:14:30.000Z') * 1000),
        ]);

        $this->insert('authors', [
            '_id' => '66de21c6ed27665cd603926e',
            'full_name' => 'Carlos Mendoza',
            'birthday' => '1985-03-15',
            'nationality' => 'Mexicano',
            'short_biography' => 'Autor de novelas de ciencia ficción y ensayos científicos',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:15:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:15:30.000Z') * 1000),
        ]);

        $this->insert('authors', [
            '_id' => '66de21c6ed27665cd603926f',
            'full_name' => 'Isabel Fuentes',
            'birthday' => '1978-11-22',
            'nationality' => 'Española',
            'short_biography' => 'Reconocida por sus thrillers psicológicos y novelas de misterio',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:16:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:16:30.000Z') * 1000),
        ]);

        $this->insert('authors', [
            '_id' => '66de21c6ed27665cd6039270',
            'full_name' => 'Ahmed Al-Rashid',
            'birthday' => '1990-07-08',
            'nationality' => 'Egipcio',
            'short_biography' => 'Poeta y novelista, explora temas de identidad cultural en el mundo moderno',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:17:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:17:30.000Z') * 1000),
        ]);

        $this->insert('authors', [
            '_id' => '66de21c6ed27665cd6039271',
            'full_name' => 'Sofia Larsson',
            'birthday' => '1982-05-19',
            'nationality' => 'Sueca',
            'short_biography' => 'Escritora de literatura infantil y juvenil con enfoque en temas ambientales',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:18:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:18:30.000Z') * 1000),
        ]);
    }

    public function down()
    {
        $this->dropCollection('authors');
    }
}
