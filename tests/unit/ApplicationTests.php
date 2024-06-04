<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Classes\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTests extends TestCase
{
    public function testRunMethodRunsTheApplication(): void
    {
        $applicationInstance = new Application();

        $resultReturned = $applicationInstance->run();

        $this->assertNull($resultReturned);
    }
}
