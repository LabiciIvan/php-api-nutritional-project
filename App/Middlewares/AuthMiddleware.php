<?php 

declare(strict_types=1);

namespace App\Middlewares;

use App\Utilities\Json;
use App\Classes\Request;
use App\Utilities\Response;
use App\Database\NutritionPDO;
use App\Utilities\ErrorLogger;

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
            SELECT user_id AS userID, expire_at AS expire
            FROM login
            WHERE token = '$bearerToken' AND expire_at > CURDATE();
        EOS;

        $result = $db->quickFetch($queryString);

        if (!$result) {
            Response::sendResponse(Json::toJson(['fail' => 'Authenticate to access this resource']), 404);
        }

        $loginTrack = "User with ID = {$result[0]['userID']}, TOKEN = $bearerToken and EXPIRE = {$result[0]['expire']}, logged in the application.";

        // Keep track in logs of users which logged in
        ErrorLogger::logError($loginTrack, __DIR__ . '/../../errors.txt');
    }
}
