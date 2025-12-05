<?php

namespace App\DTO;

use Illuminate\Support\Facades\Hash;

class UserDTO
{
    public function __construct(
        private string $name,
        private string $email,
        private string $password,
    ) {}


    public function getArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return Hash::make($this->password);
    }
}
