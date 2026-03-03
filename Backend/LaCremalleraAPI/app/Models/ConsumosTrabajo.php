<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumosTrabajo extends Model
{
    protected $table = 'consumos_trabajo';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'trabajoId',
        'itemId',
        'cantidad_usada'
    ];
}