<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Selective\Database\Connection;


//class InMemoryUserRepository
class InMemoryUserRepository implements UserRepository
{

//    private array $users;
    private Connection $connection;


//    public function __construct(Connection $connection,array $users = null)
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
//        $this->users = $users ?? [
//            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
//            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
//            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
//            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
//            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
//        ];
    }

    public function findAll()
    {
        $query = $this->connection->select()->from('user');

//        $query->columns(['id', 'name']);

        $rows = $query->execute()->fetchAll();


        foreach($rows as $row){
            $result[$row['id']]['id']=$row['id'];
            $result[$row['id']]['name']=$row['name'];
            $result[$row['id']]['login']=$row['login'];
        }
        return $result;
//        return array_values($rows);
    }

    public function findUserOfId(int $id): array
    {
        $query = $this->connection->select()->from('user');

//        $query->columns(['id', 'name']);
        $query->where('id', '=', $id);

        $row = $query->execute()->fetch();
        $user=[
            'id' => $row['id'],
            'name' => $row['name'],
            'login' => $row['login'],
        ];
        if(!$row) {
            throw new UserNotFoundException();
        }

        return $user;

//        if (!isset($this->users[$id])) {
//            throw new UserNotFoundException();
//        }
//
//        return $this->users[$id];
    }
}
