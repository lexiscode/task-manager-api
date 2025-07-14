<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Seed yourself as admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Password123'),
                'role' => RoleEnum::ADMIN->value,
            ]
        );

        // Optional: create one member for testing
        User::updateOrCreate(
            ['email' => 'member@member.com'],
            [
                'name' => 'Member User',
                'password' => Hash::make('Password123'),
                'role' => RoleEnum::MEMBER->value,
            ]
        );
    }
}
