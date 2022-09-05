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
//        $pdo= $this->connection;
//        $user=$pdo->select()
//            ->from('user')
//            ->where('login','=',$login)
//            ->where('password','=',$password)
//            ->limit(1)->execute()->fetch();
//        if(JWT::decode($jwt,new Key($this->settings->get('jwt-secret'),'HS256')) && JWT::decode($refresh,new Key($this->settings->get('jwt-secret'),'HS256'))){
//            $jwt=JWT::decode($jwt,new Key($this->settings->get('jwt-secret'),'HS256'));
//            $refresh=JWT::decode($refresh,new Key($this->settings->get('jwt-secret'),'HS256'));
//            $token = [
//                "iss" => "slim-rest-api",
//                "aud" => "slim-rest-api",
//                "iat" => time(),
//                "exp" => time() + 60,
//                "data" => [
//                    "user_id" => $user['id']
//                ]
//            ];
//
//            $jwt = JWT::encode($token,$this->settings->get('jwt-secret'),'HS256');
//
//            $refresh = [
//                "iat" => time(),
//                "exp" => time() + 43800,
//                "data" => [
//                    "user_id" => $user['id']
//                ]
//            ];
//
//            $refresh = JWT::encode($refresh,$this->settings->get('jwt-secret'),'HS256');

//            $result=[
//                'user_id' => $jwt->data->user_id,
//                'success' => true,
//                'message' => "Refresh Successfull",
//                'jwt-token-decoded' => $jwt,
//                'refresh-token-decoded' => $refresh
//            ];
//            $statusCode=200;
//        }
//        else{
//            $result=[
//                'success' => false,
//                'message' => "Refresh denied",
//            ];
//            $statusCode=500;
//        }
        if(JWT::decode($refresh,new Key($this->settings->get('jwt-secret'),'HS256'))){
            $old_refresh=JWT::decode($refresh,new Key($this->settings->get('jwt-secret'),'HS256'));
            $token = [
                "iss" => "slim-rest-api",
                "aud" => "slim-rest-api",
                "iat" => time(),
                "exp" => time() + 3600,
                "data" => [
                    "user_id" => $old_refresh->data->user_id
                ]
            ];

            $jwt = JWT::encode($token,$this->settings->get('jwt-secret'),'HS256');

            $refresh = [
                "iat" => time(),
                "exp" => time() + 43800,
                "data" => [
                    "user_id" => $old_refresh->data->user_id
                ]
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
