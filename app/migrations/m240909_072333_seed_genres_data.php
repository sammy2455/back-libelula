<?php

class m240909_072333_seed_genres_data extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4ced',
            'name' => 'Fantasy',
            'description' => 'Narrativas que involucran elementos mágicos o sobrenaturales',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:14:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:14:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cee',
            'name' => 'Ciencia Ficción',
            'description' => 'Historias basadas en avances científicos y tecnológicos imaginarios',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:15:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:15:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cef',
            'name' => 'Misterio',
            'description' => 'Relatos que implican la resolución de un crimen o situación enigmática',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:16:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:16:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf0',
            'name' => 'Romance',
            'description' => 'Historias centradas en relaciones amorosas y emocionales',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:17:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:17:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf1',
            'name' => 'Terror',
            'description' => 'Narrativas diseñadas para provocar miedo o aprensión en el lector',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:18:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:18:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf2',
            'name' => 'Aventura',
            'description' => 'Relatos de viajes, descubrimientos y experiencias emocionantes',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:19:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:19:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf3',
            'name' => 'Drama',
            'description' => 'Historias serias que exploran conflictos emocionales y situaciones de la vida',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:20:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:20:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf4',
            'name' => 'Comedia',
            'description' => 'Narrativas con énfasis en el humor y situaciones divertidas',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:21:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:21:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf5',
            'name' => 'Histórico',
            'description' => 'Relatos ambientados en períodos históricos específicos',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:22:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:22:30.000Z') * 1000),
        ]);

        $this->insert('genres', [
            '_id' => '66de2fb4eff990558ffc4cf6',
            'name' => 'Acción',
            'description' => 'Historias llenas de secuencias emocionantes y ritmo rápido',
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:23:30.000Z') * 1000),
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime('2024-09-08T22:23:30.000Z') * 1000),
        ]);
    }

    public function down()
    {
        $this->dropCollection('genres');
    }
}
