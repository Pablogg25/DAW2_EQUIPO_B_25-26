<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     required={"nombre", "email", "username", "password", "rol"},
 *     @OA\Property(property="usuarioId", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="telefono", type="string", example="123456789"),
 *     @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="direccion", type="string", example="Calle Falsa 123"),
 *     @OA\Property(property="username", type="string", example="juanperez"),
 *     @OA\Property(property="password", type="string", format="password", example="********"),
 *     @OA\Property(property="rol", type="string", example="admin")
 * )
 */
class Usuarios extends Model
{
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

    // Mutator para encriptar automáticamente la contraseña con bcrypt
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Obtener el rol del usuario
    public function getRolAttribute($value)
    {
        return $value;
    }
}
