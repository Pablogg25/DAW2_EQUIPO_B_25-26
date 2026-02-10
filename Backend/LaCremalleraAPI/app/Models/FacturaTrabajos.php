<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="FacturaTrabajo",
 *     type="object",
 *     required={"facturaId", "trabajoId"},
 *     @OA\Property(property="facturaId", type="integer", example=1, description="Identificador único de la factura"),
 *     @OA\Property(property="trabajoId", type="integer", example=1, description="Identificador único del trabajo")
 * )
 */
class FacturaTrabajos extends Model
{
    protected $table = 'factura_trabajos';
    protected $primaryKey = 'id'; // o null si no tienes PK
    public $timestamps = false;

    // Columnas que se pueden llenar masivamente
    protected $fillable = [
        'facturaId',
        'trabajoId',
    ];
}

