<?php

declare(strict_types=1);

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

final readonly class AuthService
{
    /**
     * @throws Exception
     */
    public function store(RegisterRequest $request): User
    {
        return DB::transaction(function () use ($request): User {
            $registerData = $request->validated();
            $registerData['password'] = Hash::make($registerData['password']);
            return User::query()->create($registerData);
        });
    }

}
