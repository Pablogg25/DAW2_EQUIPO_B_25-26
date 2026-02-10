<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Factura",
 *     type="object",
 *     required={"facturaId", "usuarioId", "empleadoId", "fecha"},
 *     @OA\Property(property="facturaId", type="integer", example=1, description="Identificador único de la factura"),
 *     @OA\Property(property="usuarioId", type="integer", example=101, description="Identificador del usuario asociado a la factura"),
 *     @OA\Property(property="empleadoId", type="integer", example=202, description="Identificador del empleado que genera la factura"),
 *     @OA\Property(property="fecha", type="string", format="date", example="2023-02-15", description="Fecha de emisión de la factura"),
 *     @OA\Property(property="pagado", type="boolean", example=false, description="Indica si la factura ha sido pagada"),
 *     @OA\Property(property="total_calculado", type="float", example=150.75, description="El total calculado de la factura")
 * )
 */
class Facturas extends Model
{
    protected $table = 'facturas';
    protected $primaryKey = 'facturaId';
    public $timestamps = false;

    protected $fillable = [
        'usuarioId',
        'fecha',
        'pagado',
        'total_calculado',
    ];

    // Relación muchos a muchos con trabajos
    public function trabajos()
    {
        return $this->belongsToMany(
            Trabajos::class,// Modelo relacionado
            'factura_trabajos',   // Tabla pivote
            'trabajoId',         // FK en la tabla pivote
            'facturaId'          // FK en la tabla pivote
        );
    }
    
    

}
