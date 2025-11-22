<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'estado',
        'notas',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relación: Un pedido pertenece a un usuario (cliente)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un pedido tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    // Relación: Un pedido tiene un pago
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    // Método auxiliar para obtener productos del pedido
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_pedidos')
            ->withPivot('cantidad', 'precio_unitario', 'subtotal')
            ->withTimestamps();
    }
}