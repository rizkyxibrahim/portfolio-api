<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('admin.name');
        $username = config('admin.username');
        $email = config('admin.email');
        $phone = config('admin.phone');
        $password = config('admin.password');

        if (blank($username) || blank($email) || blank($password)) {
            throw new RuntimeException(
                'ADMIN_USERNAME, ADMIN_EMAIL, and ADMIN_PASSWORD must be configured.'
            );
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'username' => $username,
                'phone' => $phone ?: null,
                'password' => Hash::make($password),
                'role' => 'admin',
                'locale' => 'en',
                'email_verified_at' => now(),
            ]
        );

        if ($user->role !== 'admin') {
            throw new RuntimeException(
                'The configured admin email already belongs to a non-admin account.'
            );
        }
    }
}
