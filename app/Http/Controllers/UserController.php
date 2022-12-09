<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\UserServiceInterface;
use App\Services\UserService;

class UserController extends Controller
{

    //protected UserServiceInterface $userService;

    public function __construct(private UserServiceInterface $userService)
    {
       // $this->userService = $userService;
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
