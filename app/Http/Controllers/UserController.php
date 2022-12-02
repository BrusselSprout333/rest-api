<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\HttpResponses;

class UserController extends Controller
{
    use HttpResponses;

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
}
