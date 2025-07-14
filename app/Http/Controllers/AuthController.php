<?php

namespace App\Http\Controllers;

use Exception;
use App\Actions\AuthService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

final class AuthController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * @param \App\Http\Requests\LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('', 'Invalid credentials', 401);
        }

        $admin = Auth::user();
        $token = $admin->createToken($admin->name . ' API Token')->plainTextToken;

        return $this->success(['admin' => $admin, 'token' => $token], 'Login successful', 200);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $admin = $this->authService->store($request);
            return $this->success($admin, 'Registration successful', 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Registration failed', 500);
        }
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->currentAccessToken()) {
            return $this->error('', 'Not authenticated or no active token', 401);
        }
        $user->currentAccessToken()->delete();
        return $this->success('', 'You have been logged out!', 200);
    }
}
