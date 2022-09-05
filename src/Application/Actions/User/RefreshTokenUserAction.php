<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

class RefreshTokenUserAction extends UserAction
{

    protected function action(): Response
    {
        $form=$this->getFormData();
        $refresh = $form["refresh-token"];
        if(JWT::decode($refresh,new Key($this->settings->get('jwt-secret'),'HS256'))){
            $old_refresh=JWT::decode($refresh,new Key($this->settings->get('jwt-secret'),'HS256'));
            $token = [
                "iss" => "slim-rest-api",
                "aud" => "slim-rest-api",
                "iat" => time(),
                "exp" => time() + 3600,
                "auth" => ["users"],
                "user_id" => $old_refresh->user_id,
            ];

            $jwt = JWT::encode($token,$this->settings->get('jwt-secret'),'HS256');

            $refresh = [
                "iat" => time(),
                "exp" => time() + 43800,
                "auth" => ["users"],
                "user_id" => $old_refresh->user_id,
            ];

            $refresh = JWT::encode($refresh,$this->settings->get('jwt-secret'),'HS256');
            $result=[
                'success' => true,
                'message' => "Token not expired and verify. Token refreshed.",
                'jwt-token' => $jwt,
                'refresh-token' => $refresh
            ];
            $statusCode=200;
            return $this->respondWithData($result,$statusCode);
        }

    }
}
