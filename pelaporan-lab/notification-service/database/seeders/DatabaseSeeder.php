<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Utama Sistem Pelaporan Lab
        User::updateOrCreate(
            ['email' => 'admin@labreport.com'],
            [
                'name'     => 'Administrator Lab',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
    }
}
