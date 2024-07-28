<?php

declare(strict_types=1);

namespace App\Classes;

use App\Database\NutritionPDO;

class Authentication
{
    /**
     * Database connection class.
     */
    private    NutritionPDO    $db;

    /**
     * User table columns and specific insert order.
     */
    private    array           $userTableColumns = [
        'first_name',
        'last_name',
        'email',
        'gender'
    ];

    public function __construct(NutritionPDO $db)
    {
        $this->db = $db;
    }

    /**
     * Register user.
     *
     * Registers a new user into the application database.
     */
    public function register(array $data): bool
    {
        $columnsAsStringInsert = implode(', ', $this->userTableColumns);

        $insertQuery = 'INSERT INTO users (' .$columnsAsStringInsert. ') VALUE (?, ?, ?, ?)';

        $isInserted = $this->db->runQuery(
            $insertQuery,
            $data
        );

        return $isInserted === true;
    }
}