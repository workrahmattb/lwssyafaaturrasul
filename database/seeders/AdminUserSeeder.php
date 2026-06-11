<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lwsvidecode.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@lwsvidecode.com',
                'password' => 'admin123',
                'is_admin' => true,
            ]
        );
    }
}
