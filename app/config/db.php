<?php

return [
    'class' => 'yii\mongodb\Connection',
    'dsn' => 'mongodb://mongodb:27017/libelula',
    'options' => [
        'username' => 'root',
        'password' => 'root',
        'authSource' => 'admin'
    ]

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
