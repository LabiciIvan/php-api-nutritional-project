<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Database\NutritionPDO;
use App\Classes\Authentication;
use PHPUnit\Framework\TestCase;

class AuthenticationTests extends TestCase
{
    private Authentication $auth;

    private static ?string $testUserEmail = null;

    private static ?int $userId = null;

    public function setUp(): void
    {
        if (static::$testUserEmail === null) {
            static::$testUserEmail = uniqid('dummy@mail');
        }

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
}
