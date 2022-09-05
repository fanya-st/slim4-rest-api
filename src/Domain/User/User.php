<?php
declare(strict_types=1);

namespace App\Domain\User;


//class User implements JsonSerializable
class User
{


    private ?int $id;

    private string $login;

    private string $name;

    public function __construct(?int $id, string $name, string $login)
    {
        $this->id = $id;
        $this->name = strtolower($name);
        $this->login = ucfirst($login);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogin(): string
    {
        return $this->login;
    }


//    #[\ReturnTypeWillChange]
//    public function jsonSerialize(): array
//    {
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'login' => $this->login,
//        ];
//    }
}
