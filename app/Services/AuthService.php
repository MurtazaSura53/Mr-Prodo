<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function signup(array $fields): bool
    {
        $result = User::create($fields);
        return !!$result;
    }
    public function update(User $user, array $fields)
    {
        $user->update($fields);
    }
    public function logout(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
