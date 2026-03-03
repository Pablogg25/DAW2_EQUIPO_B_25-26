<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'usuarioId';
    public $incrementing = true;
    protected $keyType = 'int';
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

    // 🔥 NECESARIO para Sanctum con PK personalizada
    public function getAuthIdentifierName()
    {
        return 'usuarioId';
    }

    public function getAuthIdentifier()
    {
        return $this->usuarioId;
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
