<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Calendario",
 *     type="object",
 *     required={"eventoId", "titulo", "descripcion", "fecha_inicio", "fecha_fin", "usuarioId", "empleadoId"},
 *     @OA\Property(property="eventoId", type="integer", example=1, description="ID único del evento"),
 *     @OA\Property(property="titulo", type="string", example="Reunión de equipo", description="Título del evento"),
 *     @OA\Property(property="descripcion", type="string", example="Reunión para revisar el progreso del proyecto", description="Descripción del evento"),
 *     @OA\Property(property="fecha_inicio", type="string", format="date-time", example="2023-02-15T09:00:00", description="Fecha y hora de inicio del evento"),
 *     @OA\Property(property="fecha_fin", type="string", format="date-time", example="2023-02-15T11:00:00", description="Fecha y hora de fin del evento"),
 *     @OA\Property(property="usuarioId", type="integer", example=101, description="ID del usuario que crea o asocia el evento"),
 *     @OA\Property(property="empleadoId", type="integer", example=202, description="ID del empleado asociado al evento"),
 *     @OA\Property(property="trabajoId", type="integer", example=303, description="ID del trabajo relacionado con el evento")
 * )
 */
class Calendario extends Model
{
    protected $table = 'calendarios'; // nombre de la tabla en la base de datos
    protected $primaryKey = 'eventoId'; // clave primaria

    public $timestamps = false; // si la tabla no tiene created_at / updated_at

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'usuarioId',
        'empleadoId',
        'trabajoId',
    ];
}
