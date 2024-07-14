<?php

declare(strict_types=1);

namespace App\Controllers;

use Exception;
use App\Utilities\Json;
use App\Classes\Request;
use App\Utilities\Response;
use App\Utilities\ErrorLogger;
use App\Classes\CaloriesCalculator;

class MacronutrientsController
{
    public function calculateCalories(Request $request): void
    {
        $requestData = $request->getRequestData();

        $requestData = Json::fromJson($requestData);

        if ($requestData === null) {
            Response::sendResponse(Json::toJson(['data' => 'Request is missing required data']), 400);
        }

        $requiredKeys = array_flip(['weight', 'activityLevel']);

        $missingKeys = array_diff_key($requiredKeys, $requestData);

        $extraKeys = array_diff_key($requestData, $requiredKeys);

        if ($missingKeys) {
            Response::sendResponse(Json::toJson(['data' => 'Missing keys in request: ' . implode(',', array_keys($missingKeys))]), 403);
        }

        if ($extraKeys) {
            Response::sendResponse(Json::toJson(['data' => 'Extra keys in request: ' . implode(',', array_keys($extraKeys))]), 403);
        }

        $kclCalculator = new CaloriesCalculator();

        $totalCalories = $kclCalculator->calculateTotalCalories($requestData['weight'], $requestData['activityLevel']);

        Response::sendResponse(Json::toJson(['data' => ['kcal' => $totalCalories]]), 200);
    }

    public function calculateMacronutrients(Request $request): void
    {
        $requestData = $request->getRequestData();

        $requestData = Json::fromJson($requestData);

        if ($requestData === null) {
            Response::sendResponse(Json::toJson(['data' => 'Request is missing required data']), 400);
        }

        $requiredKeys = array_flip(['calories', 'protein', 'carbohydrates', 'fats']);

        $missingKeys = array_diff_key($requiredKeys, $requestData);

        $extraKeys = array_diff_key($requestData, $requiredKeys);

        if ($missingKeys) {
            Response::sendResponse(Json::toJson(['data' => 'Missing keys in request: ' . implode(',', array_keys($missingKeys))]), 403);
        }

        if ($extraKeys) {
            Response::sendResponse(Json::toJson(['data' => 'Extra keys in request: ' . implode(',', array_keys($extraKeys))]), 403);
        }

        $kclCalculator = new CaloriesCalculator();

        try {
            $macroDistribution = $kclCalculator->calculateMacroDistribution(
                $requestData['calories'],
                $requestData['protein'],
                $requestData['carbohydrates'],
                $requestData['fats'],
            );
        } catch (Exception $e) {
            ErrorLogger::logError($e->getMessage(), __DIR__ . '/errors.txt');
            Response::sendResponse(Json::toJson(['data' => $e->getMessage()]), 400);
        }

        Response::sendResponse(Json::toJson(['data' => $macroDistribution]), 200);
    }
}
