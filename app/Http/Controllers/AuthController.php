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

    public function __construct(protected AuthServiceInterface $authService)
    {
    }

    public function login(LoginUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $email = $request->email;
        $password = $request->password;

        try {
            $user = $this->authService->login($email, $password);
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
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;

        try {
            $user = $this->authService->register($name, $email, $phone, $password);
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
