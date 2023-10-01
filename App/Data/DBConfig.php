<?php
//App/Data/DBConfig.php

declare(strict_types=1);

namespace App\Data;

class DBConfig {
    public static string $DB_CONNSTRING = "mysql:host=localhost;dbname=broodjes;charset=utf8";
    public static string $DB_USERNAME = "root";
    public static string $DB_PASSWORD = "";
}