<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Database\NutritionPDO;
use App\Classes\Authentication;
use PHPUnit\Framework\TestCase;

class AuthenticationTests extends TestCase
{
    private Authentication $auth;

    private ?string $testUserEmail2 = null;

    private ?int $userId2 = null;

    private static ?string $testUserEmail = null;

    private static ?int $userId = null;

    public function setUp(): void
    {
        if (static::$testUserEmail === null) {
            static::$testUserEmail = uniqid('dummy@mail');
        }

        $this->testUserEmail2 = uniqid('dummy@mail');

        $this->auth = new Authentication(NutritionPDO::getInstance());
    }

    public function testRegisterMethod(): void
    {
        $expectedColumns = ['first_name', 'last_name', 'email', 'gender'];

        $requestData = [
            'first_name'    => 'dummy',
            'last_name'     => 'dummy',
            'email'         => static::$testUserEmail,
            'gender'        => ('man'),
        ];

        $dataToRegisterUser  = [];

        foreach ($expectedColumns as $name) {
            if (isset($requestData[$name])) {
                $dataToRegisterUser[] = $requestData[$name];
            }
        }

        $isRegistered = $this->auth->register($dataToRegisterUser);

        $this->assertTrue($isRegistered);
    }

    public function testUserExistsMethod(): void
    {
        $exists = $this->auth->userExists(static::$testUserEmail);

        $this->assertTrue($exists);

        if ($exists === true) {
            static::$userId = $this->auth->getUserID();
        }
    }

    public function testLoginMethod(): void
    {
        $loginResult = $this->auth->login((int)static::$userId);

        $this->assertTrue($loginResult);
    }

    public function testIsLoggedInMethod(): void
    {
        $isLoggedIn = $this->auth->isLoggedIn((int)static::$userId);

        $this->assertTrue($isLoggedIn);
    }

    public function testLogoutMethod(): void
    {
        $isLoggedOut = $this->auth->logout((int)static::$userId);

        $this->assertTrue($isLoggedOut);
    }

    public function testRegistrationAndLoggin(): void
    {
        // Register a dummy user into the database.
        $registerData = ['testFirstName', 'testLastName', $this->testUserEmail2, 'woman'];

        $isRegistered = $this->auth->register($registerData);

        $this->assertTrue($isRegistered);

        // Check if user exists to be able to get the user ID.
        $userExists = $this->auth->userExists($this->testUserEmail2);

        $this->assertTrue($userExists);

        // Get user ID
        $userID = $this->auth->getUserID();

        $this->assertIsInt($userID);
        
        // Login user
        $isLogged = $this->auth->login($userID);

        $this->assertTrue($isLogged);
    }

    public function testLastInsertedUserID(): void
    {
        // Register a dummy user into the database.
        $registerData = ['testFirstName', 'testLastName', $this->testUserEmail2, 'woman'];

        $registrationSucceeded = $this->auth->register($registerData);

        $this->assertTrue($registrationSucceeded);

        // Get the lastID and check if is int.
        $lastID = $this->auth->getLastID();

        $this->assertIsInt($lastID);
    }
}
