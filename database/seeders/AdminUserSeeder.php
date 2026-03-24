<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@lwsvidecode.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@lwsvidecode.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
    }
}
