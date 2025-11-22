<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Hamburguesas',
                'descripcion' => 'Deliciosas hamburguesas artesanales',
            ],
            [
                'nombre' => 'Salchipapas',
                'descripcion' => 'Salchipapas con variedad de salsas',
            ],
            [
                'nombre' => 'Picadas',
                'descripcion' => 'Picadas para compartir',
            ],
            [
                'nombre' => 'Perros Calientes',
                'descripcion' => 'Hot dogs al estilo colombiano',
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Refrescos y jugos naturales',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}