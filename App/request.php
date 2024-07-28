<?php

declare(strict_types=1);

namespace App;

use App\Classes\Request;

$requestMethod          = $_SERVER['REQUEST_METHOD'];

$requestURI             = $_SERVER['REQUEST_URI'];

$authorisationHeader    = (isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null);

$request = new Request($requestMethod, $requestURI, $authorisationHeader);
