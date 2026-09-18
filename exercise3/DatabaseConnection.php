<?php

class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

//Demonstration script
$db1 = DatabaseConnection::getInstance();
$db2 = DatabaseConnection::getInstance();

if ($db1 === $db2) {
    echo "Singleton works: Both variables hold the same instance.\n";
}
