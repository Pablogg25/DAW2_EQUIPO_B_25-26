<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Inventario",
 *     type="object",
 *     required={"itemId", "nombre", "descripcion", "cantidad", "stock_minimo"},
 *     @OA\Property(property="itemId", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Camiseta Roja"),
 *     @OA\Property(property="descripcion", type="string", example="Camiseta de algodón tamaño M"),
 *     @OA\Property(property="cantidad", type="integer", example=50),
 *     @OA\Property(property="stock_minimo", type="integer", example=5)
 * )
 */

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'itemId';
    public $timestamps = false;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'stock_minimo',
    ];
}
