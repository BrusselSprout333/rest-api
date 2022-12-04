<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class AuthService implements AuthServiceInterface
{
    public function login(string $email, string $password)
    {
        if(!Auth::attempt(['email' => $email, 'password' => $password])) {
            throw new InvalidArgumentException('User not found');
        }

        return User::where('email', $email)->first();
    }

    public function register(string $name, string $email, string $password)
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }
}
