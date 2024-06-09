<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Classes\CaloriesCalculator;
use PHPUnit\Framework\TestCase;

class CaloriesCalculatorTests extends TestCase
{
    public function testTotalCalories(): void
    {
        $kcalCalculator = new CaloriesCalculator();

        $totalCalories = $kcalCalculator->calculateTotalCalories(93, 'moderate');

        $this->assertIsInt($totalCalories);

        // 3060 is the expected value
        $this->assertSame(3060, $totalCalories);
    }

    public function testMacroDistribution(): void
    {
        $kcalCalculator = new CaloriesCalculator();

        $macroDistribution = $kcalCalculator->calculateMacroDistribution(3060, 30, 40, 30);

        $this->assertIsArray($macroDistribution);

        var_dump($macroDistribution);
    }
}
