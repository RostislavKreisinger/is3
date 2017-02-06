<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection(),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'mysql-import-support' => \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection(),
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection(),

        'mysql-master-app' => \Monkey\Connections\MDDatabaseConnections::getMasterAppConnection(),
        'mysql-select-app' => \Monkey\Connections\MDDatabaseConnections::getMasterAppConnection(),
        \Monkey\Connections\MDDatabaseConnections::getMasterAppConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getMasterAppConnection(),

        'mysql-import-pools' => \Monkey\Connections\MDDatabaseConnections::getPoolsConnection(),
        \Monkey\Connections\MDDatabaseConnections::getPoolsConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getPoolsConnection(),

        'mysql-import' => \Monkey\Connections\MDDatabaseConnections::getImportConnection(),
        'mysql-select-import' => \Monkey\Connections\MDDatabaseConnections::getImportConnection(),
        \Monkey\Connections\MDDatabaseConnections::getImportConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getImportConnection(),

        'mysql-import-dw' => \Monkey\Connections\MDDatabaseConnections::getImportDwConnection(),
        \Monkey\Connections\MDDatabaseConnections::getImportDwConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getImportDwConnection(),

        'mysql-import-anal' => \Monkey\Connections\MDDatabaseConnections::getImportAnalConnection(),
        \Monkey\Connections\MDDatabaseConnections::getImportAnalConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getImportAnalConnection(),

        'mysql-app-support' => \Monkey\Connections\MDDatabaseConnections::getAppSupportConnection(),
        \Monkey\Connections\MDDatabaseConnections::getAppSupportConnection()->getName() => \Monkey\Connections\MDDatabaseConnections::getAppSupportConnection(),

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
