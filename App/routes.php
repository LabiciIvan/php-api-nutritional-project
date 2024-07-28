<?php

declare(strict_types=1);

namespace App;

use App\Classes\Router;
use App\Utilities\Response;
use App\Controllers\MacronutrientsController;

$router = new Router();

$router->get('/', [Response::class, 'missingMethod']);

$router->post('/calculate/calories/', [MacronutrientsController::class, 'calculateCalories']);

$router->post('/calculate/macronutrients/', [MacronutrientsController::class, 'calculateMacronutrients']);