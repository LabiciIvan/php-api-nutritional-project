<?php

declare(strict_types=1);

namespace App\Classes;

use App\Database\NutritionPDO;
use DateTime;

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

    private   ?int             $userID = null;

    public function __construct(NutritionPDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get user ID.
     *
     * Return the ID of the user which was
     * previously stored by userExists() method.
     */
    public function getUserID(): ?int
    {
        return $this->userID;
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

    /**
     * Check if user exists.
     *
     * Checks if user exists by fetching the id from
     * database where users unique email column matches
     * a certain email address.
     */
    public function userExists(string $userEmail): bool
    {
        $queryString = <<<EOD
            SELECT id
            FROM users
            WHERE email = '$userEmail';
        EOD;

        $results = $this->db->quickFetch($queryString);

        if ($results === null || !isset($results[0]['id'])) {
            $this->userID = null;
            return false;
        }

        // Store userId for future use.
        $this->userID = (int)$results[0]['id'];

        return true;
    }

    /**
     * Is logged in.
     * 
     * Check if user is logged in the database by fetching
     * the id column of the records from login table where
     * the deleted column is 'N'.
     * 
     * @return  
     */
    public function isLoggedIn(int $userId) {
        $queryString = <<<EOD
            SELECT id FROM login
            WHERE deleted = 'N' AND user_id = $userId;
        EOD;

        $results = $this->db->quickFetch($queryString);

        if ($results === null) {
            return false;
        }

        return true;
    }

    /**
     * Login user.
     *
     * Log in a user into the database by generating a login token
     * add prefixing it with the userID and two underscores.
     */
    public function login(int $userID): bool
    {
        $insertQuery = <<<EOS
            INSERT INTO login (token, expire_at, user_id) 
            VALUES (?, ?, ?);
        EOS;

        $dateTime = new DateTime();

        $dateTime->modify('+1 day');

        $expire_at = $dateTime->format('Y-m-d H:i:s');

        $dataInsertQuery = [uniqid($userID . '__'), $expire_at, $userID];

        $isLoggedIn = $this->db->runQuery($insertQuery, $dataInsertQuery);

        return $isLoggedIn === true;
    }

    public function logout(int $userID): bool
    {
        $insertQuery = <<<EOS
            UPDATE login SET deleted = 'Y'
            WHERE deleted = 'N' AND user_id = ?;
        EOS;

        $isLoggedOut = $this->db->runQuery($insertQuery, [$userID]);

        if ($isLoggedOut === null) {
            return false;
        }

        return true;
    }
}
