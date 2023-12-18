<?php

use Medoo\Medoo;

final class DatabaseUpdated // connectie maken met database
{
    private static $instance;

    public static function getInstance(): Medoo
    {
        if (self::$instance === null) {
            self::$instance = new Medoo([
                'database_type' => 'mysql',
                'database_name' => 'user_identity',
                'server' => 'localhost',
                'username' => 'root',
                'password' => 'root'
            ]);
        }
        return self::$instance;
    }
}
