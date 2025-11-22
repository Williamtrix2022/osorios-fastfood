<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'precio' => 'decimal:2',
    ];

    // Relación: Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación: Un producto puede estar en muchos detalles de pedidos
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }
}