<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Hamburguesas (categoria_id: 1)
            [
                'categoria_id' => 1,
                'nombre' => 'Hamburguesa Clásica',
                'descripcion' => 'Carne de res, queso, lechuga, tomate y salsa especial',
                'precio' => 28000,
                'imagen' => null,
                'disponible' => true,
            ],
            [
                'categoria_id' => 1,
                'nombre' => 'Hamburguesa Doble',
                'descripcion' => 'Doble carne, doble queso, lechuga y barbacoa',
                'precio' => 38000,
                'imagen' => null,
                'disponible' => true,
            ],
            [
                'categoria_id' => 1,
                'nombre' => 'Hamburguesa Especial',
                'descripcion' => 'Carne premium, queso cheddar, cebolla caramelizada',
                'precio' => 45000,
                'imagen' => null,
                'disponible' => true,
            ],

            // Salchipapas (categoria_id: 2)
            [
                'categoria_id' => 2,
                'nombre' => 'Salchipapa Clásica',
                'descripcion' => 'Papas fritas, salchicha, queso y salsas',
                'precio' => 18000,
                'imagen' => null,
                'disponible' => true,
            ],
            [
                'categoria_id' => 2,
                'nombre' => 'Salchipapa Especial',
                'descripcion' => 'Papas, salchicha, pollo desmenuzado, queso y salsas',
                'precio' => 26000,
                'imagen' => null,
                'disponible' => true,
            ],

            // Picadas (categoria_id: 3)
            [
                'categoria_id' => 3,
                'nombre' => 'Picada Mixta',
                'descripcion' => 'Carne de res, pollo, chorizo, papas y plátano',
                'precio' => 42000,
                'imagen' => null,
                'disponible' => true,
            ],

            // Perros (categoria_id: 4)
            [
                'categoria_id' => 4,
                'nombre' => 'Perro Sencillo',
                'descripcion' => 'Salchicha, pan, papitas y salsas',
                'precio' => 12000,
                'imagen' => null,
                'disponible' => true,
            ],

            // Bebidas (categoria_id: 5)
            [
                'categoria_id' => 5,
                'nombre' => 'Gaseosa',
                'descripcion' => 'Coca Cola, Sprite o Fanta',
                'precio' => 5000,
                'imagen' => null,
                'disponible' => true,
            ],
            [
                'categoria_id' => 5,
                'nombre' => 'Jugo Natural',
                'descripcion' => 'Jugos de frutas naturales',
                'precio' => 8000,
                'imagen' => null,
                'disponible' => true,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
