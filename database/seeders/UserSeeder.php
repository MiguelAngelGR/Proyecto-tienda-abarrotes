<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@ejemplo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Despachador',
            'email' => 'despachador@ejemplo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'dispatcher'
        ]);
    }
}