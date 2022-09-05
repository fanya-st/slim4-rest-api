<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginUserAction extends UserAction
{

    protected function action(): Response
    {
        $user=$this->getFormData();
        $login = $user["login"];
        $password = $user["password"];

        $pdo= $this->connection;
            $user=$pdo->select()
                ->from('user')
                ->where('login','=',$login)
                ->where('password','=',$password)
                ->limit(1)->execute()->fetch();
        if(!empty($user)){
            $token = [
                "iss" => "slim-rest-api",
                "aud" => "slim-rest-api",
                "iat" => time(),
                "exp" => time() + 3600,
                "user_id" => $user['id'],
                "auth" => ["users"]
            ];

            $jwt = JWT::encode($token,$this->settings->get('jwt-secret'),'HS256');

            $refresh = [
                "iat" => time(),
                "exp" => time() + 43800,
                "user_id" => $user['id']
            ];

            $refresh = JWT::encode($refresh,$this->settings->get('jwt-secret'),'HS256');

            $result=[
                'user_id' => $user['id'],
                'message' => "Login Successfull",
                'jwt-token' => $jwt,
                'jwt-token-decoded' => JWT::decode($jwt,new Key($this->settings->get('jwt-secret'),'HS256')),
                'refresh-token' => $refresh
            ];
            $statusCode=200;
        }
        else{
            $result=[
                'message' => "Login denied",
            ];
            $statusCode=500;
        }
        return $this->respondWithData($result,$statusCode);
    }
}
