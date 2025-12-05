<?php

namespace App\Repository\User;

use App\DTO\UserDTO;
use App\Models\User;

interface UserRepositoryInterface
{
    public function store(UserDTO $userDTO): User;
}
