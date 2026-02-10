<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     required={"nombre", "telefono", "email", "direccion", "username", "password_SHA2", "rol"},
 *     @OA\Property(property="usuarioId", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="telefono", type="string", example="123456789"),
 *     @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="direccion", type="string", example="Calle Falsa 123"),
 *     @OA\Property(property="username", type="string", example="juanperez"),
 *     @OA\Property(property="password_SHA2", type="string", format="password", example="f0e1a2b3c4d5e6f7g8h9i0j1k2l3m4n5"),
 *     @OA\Property(property="rol", type="string", example="admin")
 * )p
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
        'password_SHA2',
        'rol'
    ];

    protected $hidden = [
        'password_SHA2'
    ];

    // Mutator para guardar password como SHA224
    public function setPasswordSHA2Attribute($value)
    {
        $this->attributes['password_SHA2'] = hash('sha224', $value);
    }

    /**
     * Obtener el rol del usuario (para mejorar la documentación y facilitar la búsqueda).
     */
    public function getRolAttribute($value)
    {
        return $value;
    }

}
