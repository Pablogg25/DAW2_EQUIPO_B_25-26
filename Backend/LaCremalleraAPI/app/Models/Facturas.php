<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trabajos;

class Facturas extends Model
{
    protected $table = 'facturas';

    protected $primaryKey = 'facturaId';

    public $timestamps = false;

    protected $fillable = [
        'usuarioId',
        'fecha',
        'pagado',
        'total_calculado'
    ];

    public function trabajos()
    {
        return $this->belongsToMany(
            Trabajos::class,
            'factura_trabajos',
            'facturaId',
            'trabajoId'
        );
    }
    
    public function usuario()
    {
        return $this->belongsTo(
            \App\Models\Usuarios::class,
            'usuarioId',
            'usuarioId'
        );
    }
}