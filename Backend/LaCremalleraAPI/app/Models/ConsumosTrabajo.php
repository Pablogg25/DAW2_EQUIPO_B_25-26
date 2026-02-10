<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="ConsumosTrabajo",
 *     type="object",
 *     required={"trabajoId", "itemId", "cantidad_usada"},
 *     @OA\Property(property="trabajoId", type="integer", example=1, description="ID del trabajo asociado al consumo"),
 *     @OA\Property(property="itemId", type="integer", example=1, description="ID del item utilizado en el trabajo"),
 *     @OA\Property(property="cantidad_usada", type="integer", example=5, description="Cantidad de item utilizado en el trabajo")
 * )
 */
class ConsumosTrabajo extends Model
{
    protected $table = 'consumos_trabajo';
    public $timestamps = false;

    protected $fillable = [
        'trabajoId',
        'itemId',
        'cantidad_usada',
    ];
}
