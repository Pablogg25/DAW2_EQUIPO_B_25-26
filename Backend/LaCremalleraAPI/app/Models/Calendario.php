<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    protected $table = 'calendario'; // nombre de la tabla en la base de datos
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
