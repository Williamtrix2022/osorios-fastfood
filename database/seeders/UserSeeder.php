<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Admin Osorios',
            'email' => 'admin@osorios.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'telefono' => '3001234567',
            'direccion' => 'Calle Principal #123',
        ]);
    }
}
