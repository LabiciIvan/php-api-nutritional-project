<?php 

declare(strict_types=1);

namespace App\Middlewares;

use App\Utilities\Json;
use App\Classes\Request;
use App\Database\NutritionPDO;
use App\Utilities\Response;

class AuthMiddleware
{
    public function isUserLogged(Request $request): void
    {
        $bearerToken = $request->getBearerToken();

        if (!$bearerToken) {
            Response::sendResponse(Json::toJson(['fail' => 'Bearer Token is missing']), 403);
        }

        $db = NutritionPDO::getInstance();

        $queryString = <<<EOS
            SELECT user_id AS userID FROM login WHERE token = '$bearerToken';
        EOS;

        $result = $db->quickFetch($queryString);

        if (!$result) {
            Response::sendResponse(Json::toJson(['fail' => 'User is not logged in']), 404);
        }

        var_dump($result);

        Response::sendResponse(Json::toJson(['data' => 'Bearer Token received'. $bearerToken]), 200);
    }
}