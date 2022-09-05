<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{

    public function findAll();

    public function findUserOfId(int $id):array;
}
