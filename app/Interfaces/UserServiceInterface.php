<?php

namespace App\Interfaces;

interface UserServiceInterface
{
    public function getName();
    public function getId();
    public function isAuthenticated();
    public function getPhone();
}
