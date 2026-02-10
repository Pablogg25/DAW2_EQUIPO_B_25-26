<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Prenda",
 *     type="object",
 *     required={"prendaId", "nombre", "categoria"},
 *     @OA\Property(property="prendaId", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Camiseta"),
 *     @OA\Property(property="categoria", type="string", example="Ropa deportiva"),
 *     @OA\Property(property="usuarioId", type="integer", example=1),
 *     @OA\Property(property="tipo", type="string", example="Deportiva"),
 *     @OA\Property(property="descripcion", type="string", example="Camiseta para actividades deportivas"),
 *     @OA\Property(property="color", type="string", example="Rojo"),
 *     @OA\Property(property="talla", type="string", example="M")
 * )
 */
class Prendas extends Model
{
    protected $table = 'prendas';
    protected $primaryKey = 'prendaId';
    public $timestamps = false; // si no tienes created_at / updated_at

    protected $fillable = [
        'usuarioId',
        'tipo',
        'descripcion',
        'color',
        'talla',
    ];

    /**
     * Relación con trabajos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trabajos()
    {
        return $this->hasMany(\App\Models\Trabajos::class, 'prendaId', 'prendaId');
    }
}
