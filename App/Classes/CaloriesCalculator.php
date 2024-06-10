<?php

declare(strict_types=1);

namespace App\Classes;

use RuntimeException;

class CaloriesCalculator
{
    private array $activityLevel = ['sedentary' => 14, 'moderate' => 15, 'active' => 16];

    public function calculateTotalCalories(int $weight, string $activity): int
    {
        if (!isset($this->activityLevel[$activity])) {
            throw new RuntimeException('Undefined activity level by which to calculate calories intake.');
        }

        $lbsWeight = intval($weight * 2.2);

        return $lbsWeight * $this->activityLevel[$activity];
    }

    public function calculateMacroDistribution(int $calories, int $protein, int $carbohydrates, int $fats): array
    {
        if (($protein < 1 || $protein > 99) || ($carbohydrates < 1 || $carbohydrates > 99) || ($fats < 1 || $fats > 99)) {
            throw new RuntimeException('Macronutrients percentage can be only two digits.');
        }

        if (($protein + $carbohydrates + $fats) > 100) {
            throw new RuntimeException('Macronutrients percentage exceeds.');
        }

        $kcalFats = ($fats / 100) * $calories;

        $kcalProtein = ($protein / 100) * $calories;

        $kcalCarbohydrates = ($carbohydrates / 100) * $calories;

        $gramsFats = $kcalFats / 9;

        $gramsProtein = $kcalProtein / 4;

        $gramsCarbohydrates = $kcalCarbohydrates / 4;

        return [
            'kcal' => [
                'Fats' => $kcalFats,
                'Protein' => $kcalProtein,
                'Carbohydrates' => $kcalCarbohydrates,
            ],
            'grams' => [
                'Fats' => $gramsFats,
                'Protein' => $gramsProtein,
                'Carbohydrates' => $gramsCarbohydrates
            ]
        ];
    }
}
