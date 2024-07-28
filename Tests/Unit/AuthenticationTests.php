<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Database\NutritionPDO;
use App\Classes\Authentication;
use PHPUnit\Framework\TestCase;

class AuthenticationTests extends TestCase
{
    public function testRegisterMethod(): void
    {
        $auth = new Authentication($db = NutritionPDO::getInstance());

        $expectedColumns = ['first_name', 'last_name', 'email', 'gender'];

        $requestData = [
            'first_name'    => 'dummy',
            'last_name'     => 'dummy',
            'email'         => uniqid('dummy@mail'),
            'gender'        => ('man'),
        ];

        $dataToRegisterUser  = [];

        foreach ($expectedColumns as $name) {
            if (isset($requestData[$name])) {
                $dataToRegisterUser[] = $requestData[$name];
            }
        }


        $isRegistered = $auth->register($dataToRegisterUser);

        $this->assertTrue($isRegistered);
    }
}