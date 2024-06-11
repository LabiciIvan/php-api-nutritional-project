<?php

use App\Classes\Router;
use App\Classes\Request;
use App\Classes\Application;

use App\Utilities\Json;
use App\Utilities\Response;
use App\Classes\CaloriesCalculator;

require __DIR__ . '/vendor/autoload.php';

$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$router = new Router();

$router->post('/calculate/calories/', function () use($request) {
    $requestData = $request->getRequestData();

    if ($requestData === null) {
        Response::sendResponse(Json::toJson(['data' => 'Request is missing required data']), 400);
    }

    $requestData = Json::fromJson($requestData);

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
});

$router->post('/calculate/macronutrients/', function () use($request) {

    $requestData = $request->getRequestData();

    if ($requestData === null) {
        Response::sendResponse(Json::toJson(['data' => 'Request is missing required data']), 400);
    }

    $requestData = Json::fromJson($requestData);

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
        Response::sendResponse(Json::toJson(['data' => $e->getMessage()]), 400);
    }

    Response::sendResponse(Json::toJson(['data' => $macroDistribution]), 200);
});

$app = new Application($request, $router);

try {
    $app->run();
} catch (Exception $e) {
    exit;
}
