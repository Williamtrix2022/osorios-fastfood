<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'direccion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    // Métodos auxiliares para roles
    public function isCliente()
    {
        return $this->role === 'cliente';
    }

    public function isEmpleado()
    {
        return $this->role === 'empleado';
    }

    public function isAdministrador()
    {
        return $this->role === 'administrador';
    }
}