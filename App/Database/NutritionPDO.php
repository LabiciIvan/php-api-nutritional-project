<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;
use App\Utilities\ErrorLogger;

class NutritionPDO extends PDO
{
    private static ?NutritionPDO $db = null;

    private function __construct($file = 'settings.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) {
            throw new RuntimeException('Unable to open ' . $file);
        }

        $dsn = $settings['database']['driver'] . ':dbname=' . $settings['database']['schema'] . ';host:' . $settings['database']['host'];

        parent::__construct($dsn, $settings['database']['username'], $settings['database']['password']);
    }

    public static function getInstance(): NutritionPDO
    {
        if (!NutritionPDO::$db) {
            NutritionPDO::$db = new NutritionPDO();
        }

        return NutritionPDO::$db;
    }

    /**
     * Execute SELECT queries.
     *
     * Created to ease the usage of the SELECT queries.
     *
     * Can result in unusual behaviour when used to INSERT,
     * UPDATE or DELETE queries.
     *
     * @throws    PDOException  Exception fetching any data
     *
     * @return    null|array    null if no data fetched or array with fetched data
     */
    public function quickFetch(string $query): ?array
    {
        try {
            $fetchedPDO = self::$db->query($query);
        } catch (PDOException $e) {
            ErrorLogger::logError($e->getMessage(), __DIR__ .  '/../../errors.txt');
            return null;
        }

        if (!isset($fetchedPDO) || $fetchedPDO === false) {
            ErrorLogger::logError('Issue fetching data from the database in: ' .__METHOD__. ' on line: '. __LINE__, __DIR__ .  '/../../errors.txt');
            return null;
        }

        $fetchedData = [];

        // Stack into an array data fetched
        foreach ($fetchedPDO as $row) {
            $fetchedData[] = $row;
        }

        return (!empty($fetchedData) ? $fetchedData : null);
    }

}
