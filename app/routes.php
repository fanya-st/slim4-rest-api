<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\SignUserAction;
use App\Application\Actions\User\LoginUserAction;
use App\Application\Actions\User\RefreshTokenUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->post('', ListUsersAction::class);
        $group->post('/{id}', ViewUserAction::class);
    });

    $app->group('/api', function (Group $group) {
        $group->post('/sign', SignUserAction::class);
        $group->post('/login', LoginUserAction::class);
        $group->post('/refresh', RefreshTokenUserAction::class);
    });
};
