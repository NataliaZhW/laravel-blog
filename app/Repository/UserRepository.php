<?php

namespace App\Repository;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function store(
        string $username,
        string $email,
        string $password
    ): User
    {
        $user = User::query()->create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 'user',
            'role_id' => Role::query()
                ->whereRaw('LOWER(name) = ?', [strtolower('uSeR')])
                ->first()?->id
        ]);

        Auth::login($user);
        return $user;
    }

    public function update($request)
    {

    }

    public function delete($request)
    {

    }


}
