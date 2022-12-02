<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Interfaces\AuthServiceInterface;
use App\Traits\HttpResponses;
use App\Services\AuthService;

class AuthController extends Controller
{
    use HttpResponses;
   // protected AuthService $authService;

    public function __construct(protected AuthServiceInterface $authService)
    {
       // $this->authService = $authService;
    }

    public function login(LoginUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        try {
            $user = $this->authService->login($data);
        } catch (\Exception $e)
        {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'user' => $user,
            'token' => $user->createToken("API Token")->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->only([
            'name',
            'email',
            'password'
        ]);

        try {
            $user = $this->authService->register($data);
        } catch (\Exception $e)
        {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'user' => $user,
            'token' => $user->createToken("API Token")->plainTextToken
        ]);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authService->logout();
        } catch (\Exception $e)
        {
            return $this->error('', $e->getMessage(), 500);
        }

        return $this->success([
            'message' => 'You have been successfully logged out'
        ]);
    }
}
