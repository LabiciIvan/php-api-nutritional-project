<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use RuntimeException;

class NutritionPDO extends PDO
{
    private static $db = null;

    private function __construct($file = 'settings.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) {
            throw new RuntimeException('Unable to open ' . $file);
        }

        $dsn = $settings['database']['driver'] . ':dbname=' . $settings['database']['schema'] . ';host:' . $settings['database']['host'];

        parent::__construct($dsn, $settings['database']['username'], $settings['database']['password']);
    }

    public static function getInstance(): PDO
    {
        if (!NutritionPDO::$db) {
            NutritionPDO::$db = new NutritionPDO();
        }

        return NutritionPDO::$db;
    }
}
