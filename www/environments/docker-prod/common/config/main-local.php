<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('DBDRIVE_LINK') . ';dbname=' . getenv('DBDRIVE_DB'),
            'username' => getenv('DBDRIVE_USER'),
            'password' => getenv('DBDRIVE_PASS'),
            'charset' => 'utf8',
            // Enabling Schema Caching
            // Just empty `/app/runtime/cache/` folder or use `php yii cache/flush-schema` to delete cache
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
