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

        // Empleado
        User::create([
            'name' => 'Carlos Empleado',
            'email' => 'empleado@osorios.com',
            'password' => Hash::make('empleado123'),
            'role' => 'empleado',
            'telefono' => '3009876543',
            'direccion' => 'Avenida 45 #67',
        ]);

        // Cliente de prueba
        User::create([
            'name' => 'Juan Cliente',
            'email' => 'cliente@test.com',
            'password' => Hash::make('cliente123'),
            'role' => 'cliente',
            'telefono' => '3105551234',
            'direccion' => 'Carrera 10 #20-30',
        ]);
    }
}