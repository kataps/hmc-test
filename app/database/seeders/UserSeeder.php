<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Constants\AppConstant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private const CREATE_USER_COUNT = 5;

    public function run(): void
    {
        // Note: Default admin user
        User::updateOrCreate(
            [
                'email' => AppConstant::APP_DEFAULT_USER_EMAIL,
            ],
            [
                'email' => AppConstant::APP_DEFAULT_USER_EMAIL,
                'name' => 'Default User',
                'password' => Hash::make(AppConstant::APP_DEFAULT_USER_PASSWORD)
            ]
        );

        User::factory()
            ->count(self::CREATE_USER_COUNT)
            ->create();
    }
}
