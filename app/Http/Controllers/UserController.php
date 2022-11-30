<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
//        try {
//            //return Auth::user()->getAuthIdentifier();
//           // $check = Auth::check();
//
//            //    return ! is_null($this->user());
//
//            return $this->success([
//                'result' => Auth::check(),
//            ]);
//
//            //return Auth::attempt($request->only(['email', 'password'])) ? true : false;
//        } catch (\Exception $e)
//        {
//            return $this->error('', $e->getMessage(), 500);
//        }
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
