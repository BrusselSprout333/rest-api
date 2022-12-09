<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Auth;

class UserService implements UserServiceInterface
{
    public function getName()
    {
        return Auth::user()->name;
    }

    public function getId()
    {
        return Auth::user()->id;
    }

    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    public function getEmail()
    {
        return Auth::user()->email;
    }

    public function getPhone()
    {
        return Auth::user()->phone;
    }
}

