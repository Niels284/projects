<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

use Medoo\Medoo;

final class DatabaseUpdated
{
    private static $instance;

    public static function getInstance(): Medoo
    {
        if (self::$instance === null) {
            self::$instance = new Medoo([
                'database_type' => 'mysql',
                'database_name' => 'prison_system',
                'server' => 'localhost',
                'username' => 'root',
                'password' => 'root'
            ]);
        }
        return self::$instance;
    }
}
