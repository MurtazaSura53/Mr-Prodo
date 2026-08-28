<?php

namespace App\Services;

use App\Models\User;


class AuthService
{
    public function signup(array $fields): bool
    {
        $result = User::create($fields);
        return !!$result;
    }
}
