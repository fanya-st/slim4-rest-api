<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Settings\SettingsInterface;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;
use Selective\Database\Connection;

abstract class UserAction extends Action
{
    protected UserRepository $userRepository;
    protected Connection $connection;
    protected SettingsInterface $settings;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository,Connection $connection,SettingsInterface $settings)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->connection = $connection;
        $this->settings = $settings;
    }
}
