<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

class SignUserAction extends UserAction
{

    protected function action(): Response
    {
        $pdo= $this->connection;
        $user=$this->getFormData();
        $name = $user["name"];
        $login = $user["login"];
        $password = $user["password"];
        if($pdo->insert()->into('user')->set(['login' => $login, 'password' => $password, 'name' => $name])->execute()){
            $registred_user=$pdo->select()
                ->from('user')
                ->where('name','=',$name)
                ->where('login','=',$login)
                ->where('password','=',$password)
                ->limit(1)->execute()->fetch();
            $token = [
                "iss" => "slim-rest-api",
                "aud" => "slim-rest-api",
                "iat" => time(),
                "exp" => time() + 3600,
                "data" => [
                    "user_id" => $registred_user['id']
                ]
            ];

            $jwt = JWT::encode($token,$this->settings->get('jwt-secret'),'HS256');

            $refresh = [
                "iat" => time(),
                "exp" => time() + 43800,
                "data" => [
                    "user_id" => $registred_user['id']
                ]
            ];

            $refresh = JWT::encode($refresh,$this->settings->get('jwt-secret'),'HS256');

            $result=[
                'user_id' => $registred_user['id'],
                'success' => true,
                'message' => "Register Successfull",
//                'jwt-token-decoded' => JWT::decode($jwt,new Key($this->settings->get('jwt-secret'),'HS256')),
                'jwt-token' => $jwt,
                'refresh-token' => $refresh
            ];
            $statusCode=200;
        }
        else{
            $result=[
                'success' => false,
                'message' => "Register denied",
            ];
            $statusCode=500;
        }
        return $this->respondWithData($result,$statusCode);
    }
}
