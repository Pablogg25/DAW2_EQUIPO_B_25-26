<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Notificacion",
 *     type="object",
 *     required={"notificacionId", "receptorId", "remitenteId", "trabajoId", "tipo", "asunto", "mensaje", "fecha_envio"},
 *     @OA\Property(property="notificacionId", type="integer", example=1),
 *     @OA\Property(property="receptorId", type="integer", example=2),
 *     @OA\Property(property="remitenteId", type="integer", example=3),
 *     @OA\Property(property="trabajoId", type="integer", example=10),
 *     @OA\Property(property="tipo", type="string", enum={"notificacion", "recordatorio_entrega", "trabajo_listo", "factura_generada"}, example="trabajo_listo"),
 *     @OA\Property(property="asunto", type="string", example="Reparación completada"),
 *     @OA\Property(property="mensaje", type="string", example="El trabajo ha sido completado y está listo para ser entregado."),
 *     @OA\Property(property="fecha_envio", type="string", format="date-time", example="2026-02-01T12:30:00Z")
 * )
 */
class Notificaciones extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'notificacionId';
    public $timestamps = false;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'receptorId',
        'remitenteId',
        'trabajoId',
        'tipo',
        'asunto',
        'mensaje',
        'fecha_envio',
    ];

    // Tipo de dato de fecha_envio si quieres mutator
    protected $dates = [
        'fecha_envio',
    ];

    /**
     * Reglas para tipo (enum)
     *
     * @return array
     */
    public static function tiposValidos()
    {
        return ['notificacion', 'recordatorio_entrega', 'trabajo_listo', 'factura_generada'];
    }
}
