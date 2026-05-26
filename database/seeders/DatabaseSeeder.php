<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAccounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ],
        );

        UserAccounts::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@gmail.com')],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'username' => env('ADMIN_USERNAME', 'admin@gmail.com'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Ggwp123_')),
                'role' => 'admin',
                'is_active' => true,
                'must_change_password' => false,
            ],
        );
    }
}
