<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsumosTrabajo;
use App\Models\Facturas;
use App\Models\Usuarios;

class Trabajos extends Model
{
    protected $table = 'trabajos';

    protected $primaryKey = 'trabajoId';

    public $timestamps = false;

    protected $fillable = [
        'prendaId',
        'empleadoId',
        'descripcion',
        'fecha_inicio',
        'fecha_entrega',
        'estado',
        'precio',
    ];

    public function consumos()
    {
        return $this->hasMany(
            ConsumosTrabajo::class,
            'trabajoId',
            'trabajoId'
        );
    }

    public function facturas()
    {
        return $this->belongsToMany(
            Facturas::class,
            'factura_trabajos',
            'trabajoId',
            'facturaId'
        );
    }

    public function prenda()
    {
        return $this->belongsTo(
            \App\Models\Prendas::class,
            'prendaId',
            'prendaId'
        );
    }

    public function empleado()
    {
        return $this->belongsTo(
            Usuarios::class,
            'empleadoId',
            'usuarioId'
        );
    }
}
