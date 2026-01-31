<?php
// Database configuration for ProjektUBT
// Update these values to match your phpMyAdmin/MySQL setup.
return [
    'driver' => 'mysql',
    'mysql' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'banka_db',
        'user' => 'root',
        'pass' => ''
    ],
    // if you prefer sqlite, change driver to 'sqlite' and adjust path
    'sqlite_path' => __DIR__ . '/data/database.sqlite'
];
