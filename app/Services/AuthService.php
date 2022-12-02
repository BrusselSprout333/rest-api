<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AuthService implements AuthServiceInterface
{
    public function login($data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails())
            throw new InvalidArgumentException($validator->errors()->first());

        if(!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw new InvalidArgumentException('User not found');
        }

        return User::where('email', $data['email'])->first();

    }

    public function register($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required'
        ]);

        if($validator->fails())
            throw new InvalidArgumentException($validator->errors()->first());

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }
}
