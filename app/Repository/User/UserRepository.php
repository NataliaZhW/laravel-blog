<?php

namespace App\Repository\User;

use App\DTO\UserDTO;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function store(UserDTO $userDTO): User
    {
        $newUser = new User();
        $newUser->name = $userDTO->getName();
        $newUser->email = $userDTO->getEmail();
        $newUser->password = $userDTO->getPassword();
        $newUser->save();

        return $newUser;
    }
}