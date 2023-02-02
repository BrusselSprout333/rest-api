<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\UserServiceInterface;

class UserController extends Controller
{

    public function __construct(private UserServiceInterface $userService)
    {
    }

    public function isAuthenticated()
    {
        return $this->userService->isAuthenticated();
    }

    public function getName()
    {
        return $this->userService->getName();
    }

    public function getId()
    {
        return $this->userService->getId();
    }
    public function getEmail()
    {
        return $this->userService->getEmail();
    }

    public function getPhone() 
    {
        return $this->userService->getPhone();
    }
}
