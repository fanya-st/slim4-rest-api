<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
//        $data = $this->request->getParsedBody()["token"];
        $token=JWT::decode($this->request->getParsedBody()["token"],new Key($this->settings->get('jwt-secret'),'HS256'));
        if (in_array("users", $token->auth)) {
            $users = $this->userRepository->findAll();

            $this->logger->info("Users list was viewed.");

            return $this->respondWithData($users);
        } else {
            return $this->respondWithData(null,401);
        }

    }
}
