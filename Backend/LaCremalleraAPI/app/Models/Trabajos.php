<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Trabajo",
 *     type="object",
 *     required={"trabajoId", "nombre", "descripcion", "fecha_inicio", "fecha_entrega", "estado", "precio"},
 *     @OA\Property(property="trabajoId", type="integer", example=1),
 *     @OA\Property(property="prendaId", type="integer", example=10),
 *     @OA\Property(property="empleadoId", type="integer", example=3),
 *     @OA\Property(property="nombre", type="string", example="Reparación de máquina"),
 *     @OA\Property(property="descripcion", type="string", example="Trabajo de reparación del equipo X"),
 *     @OA\Property(property="fecha_inicio", type="string", format="date", example="2026-02-10"),
 *     @OA\Property(property="fecha_entrega", type="string", format="date", example="2026-02-15"),
 *     @OA\Property(property="estado", type="string", example="en_proceso"),
 *     @OA\Property(property="precio", type="float", example=150.75)
 * )
 */
class Trabajos extends Model
{
    protected $table = 'trabajos';
    protected $primaryKey = 'trabajoId';
    public $timestamps = false; // si no tienes created_at / updated_at

    protected $fillable = [
        'prendaId',
        'empleadoId',
        'descripcion',
        'fecha_inicio',
        'fecha_entrega',
        'estado',
        'precio',
    ];

    // Relación con consumos
    /**
     * Relación uno a muchos con ConsumosTrabajo
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consumos()
    {
        return $this->hasMany(ConsumosTrabajo::class, 'trabajoId', 'trabajoId');
    }

    // Relación muchos a muchos con Facturas
    /**
     * Relación muchos a muchos con Facturas
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facturas()
    {
        return $this->belongsToMany(
            Facturas::class,      // Modelo relacionado
            'factura_trabajos',   // Tabla pivote
            'trabajoId',         // FK en la tabla pivote
            'facturaId'          // FK en la tabla pivote
        );
    }

    public function getEstadoAttribute($value)
    {
        return $value;
    }

}
