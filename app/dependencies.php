<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Selective\Database\Connection;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function () {
            $driver = 'mysql';
            $host = 'localhost';
            $dbname = 'slim_db';
            $username = 'root';
            $password = '';
            $charset = 'utf8mb4';
            $dsn = "$driver:host=$host;dbname=$dbname;charset=$charset";

            return new PDO($dsn, $username, $password);
        },
        Connection::class => function (ContainerInterface $c) {
            return new Connection($c->get(PDO::class));
        },
    ]);
};
