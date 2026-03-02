<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'usuarioId';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'username',
        'password',
        'rol'
    ];

    protected $hidden = [
        'password'
    ];

    // Mutator password
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Roles
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    public function isEmpleado()
    {
        return $this->rol === 'empleado';
    }

    public function isCliente()
    {
        return $this->rol === 'cliente';
    }
}
