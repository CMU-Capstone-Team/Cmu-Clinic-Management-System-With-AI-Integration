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
        $password = config('clinic.admin.password');

        if (blank($password)) {
            throw new RuntimeException(
                'CLINIC_ADMIN_PASSWORD is missing from .env.'
            );
        }

        User::updateOrCreate(
            [
                'email' => config('clinic.admin.email'),
            ],
            [
                'employee_id' => config(
                    'clinic.admin.employee_id'
                ),
                'name' => config('clinic.admin.name'),
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}