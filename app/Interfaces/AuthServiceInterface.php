<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function login($data);
    public function register($data);
    public function logout();
}
