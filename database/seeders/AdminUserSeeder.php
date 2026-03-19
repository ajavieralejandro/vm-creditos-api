<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@vmcreditos.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ],
        );
    }
}
