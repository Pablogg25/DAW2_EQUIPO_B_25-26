<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

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

    public function receptor()
    {
        return $this->belongsTo(
            \App\Models\Usuarios::class,
            'receptorId',
            'usuarioId'
        );
    }

    public function remitente()
    {
        return $this->belongsTo(
            \App\Models\Usuarios::class,
            'remitenteId',
            'usuarioId'
        );
    }
}
