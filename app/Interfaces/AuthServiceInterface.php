<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function login(string $email, string $password);
    public function register(string $name, string $email, string $password);
    public function logout();
}
