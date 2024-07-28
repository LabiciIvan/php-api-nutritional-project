<?php

use App\Classes\Kernel;
use App\Utilities\Json;
use App\Utilities\Response;
use App\Classes\Application;
use App\Utilities\ErrorLogger;

require __DIR__ . '/vendor/autoload.php';

// Load all routes and $router instance
require __DIR__ . '/App/routes.php';

// Load global $_SERVER and $request instance
require __DIR__ . '/App/request.php';

// Initialise Application by passing Kernel and all necessary dependecies
$app = new Application(new Kernel($request, $router));

try {
    $app->run();
} catch (Exception $e) {
    ErrorLogger::logError($e->getMessage(), __DIR__ . '/errors.txt');
    Response::sendResponse(
        Json::toJson(['data' => 'The system is currently experiencing a malfunction. Please try again later.']),
        500
    );
}
