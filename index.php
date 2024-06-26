<?php

use App\Classes\Router;
use App\Classes\Request;
use App\Classes\Application;

use App\Utilities\Json;
use App\Utilities\Response;
use App\Utilities\ErrorLogger;
use App\Controllers\MacronutrientsController;

require __DIR__ . '/vendor/autoload.php';

$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$router = new Router();

$router->get('/', [Response::class, 'missingMethod']);

$router->post('/calculate/calories/', [MacronutrientsController::class, 'calculateCalories']);

$router->post('/calculate/macronutrients/', [MacronutrientsController::class, 'calculateMacronutrients']);

$app = new Application($request, $router);

try {
    $app->run();
} catch (Exception $e) {
    ErrorLogger::logError($e->getMessage(), __DIR__ . '/errors.txt');
    Response::sendResponse(Json::toJson(['data' => 'The system is currently experiencing a malfunction. Please try again later.']), 500);
}
