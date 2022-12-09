<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function isAuthenticated()
    {
        return $this->userService->isAuthenticated();
    }

    public function getName()
    {
        return $this->userService->getName();
    }

    /**
     * @return string|integer
     */
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
