<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utilities\DependencyInjector;
use App\Controllers\MacronutrientsController;

class TestClass
{
    public function dummyMethodOne(MacronutrientsController $macronutrientsController): void
    {
        // This method requires one dependency
    }

    public function dummyMethodTwo(): void
    {
        // This method does not require any dependency
    }
}

class DependencyInjectorTests extends TestCase
{
    public function testIfMethodHasDependencies(): void
    {
        $dependencyInjector = new DependencyInjector(TestClass::class, 'dummyMethodOne');

        $dependencies = $dependencyInjector->hasDependencies();

        $this->assertNotEmpty($dependencies);
    }

    public function testIfMethodHasNoDependencies(): void
    {
        $dependencyInjector = new DependencyInjector(TestClass::class, 'dummyMethodTwo');

        $dependencies = $dependencyInjector->hasDependencies();

        $this->assertEmpty($dependencies);
    }

    public function testIfMethodDoesNotExist(): void
    {
        $dependencyInjector = new DependencyInjector(TestClass::class, 'dummyMethodNotExist');

        $dependencies = $dependencyInjector->hasDependencies();

        $this->assertNull($dependencies);
    }
}
