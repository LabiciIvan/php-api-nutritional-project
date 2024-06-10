<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Classes\CaloriesCalculator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CaloriesCalculatorTests extends TestCase
{
    private CaloriesCalculator $kcalCalculator;

    public function setUp(): void
    {
        $this->kcalCalculator = new CaloriesCalculator();
    }
    
    public function testTotalCalories(): void
    {
        $totalCalories = $this->kcalCalculator->calculateTotalCalories(93, 'moderate');

        $this->assertIsInt($totalCalories);

        // 3060 is the expected value
        $this->assertSame(3060, $totalCalories);
    }

    public function testMacroDistribution(): void
    {
        $macroDistribution = $this->kcalCalculator->calculateMacroDistribution(3060, 30, 40, 30);

        $this->assertIsArray($macroDistribution);

        $this->assertArrayHasKey('kcal', $macroDistribution);

        $this->assertArrayHasKey('grams', $macroDistribution);
    }

    public function testRuntimeExceptionForWrongActivityLevel(): void
    {
        $this->expectException(RuntimeException::class);

        $this->expectExceptionMessage('Undefined activity level by which to calculate calories intake.');

        $this->kcalCalculator->calculateTotalCalories(90, 'lazy');
    }

    public function testRuntimeExceptionWithMoreThenTwoDigitsMacronutrientsPercentage(): void
    {
        $this->expectExceptionMessage('Macronutrients percentage can be only two digits.');

        $this->kcalCalculator->calculateMacroDistribution(2000, 101, 30, 300);
    }

    public function testRuntimeExceptionWhenMacronutrientAddedExceedOneHundredPercentage(): void
    {
        $this->expectExceptionMessage('Macronutrients percentage exceeds.');

        $this->kcalCalculator->calculateMacroDistribution(2000, 90, 90, 90);
    }
}
