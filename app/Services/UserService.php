<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getName()
    {
        return Auth::user()->name;
    }

    public function getId()
    {
        return Auth::user()->id;
    }
}

