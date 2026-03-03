<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prendas extends Model
{
    protected $table = 'prendas';
    protected $primaryKey = 'prendaId';
    public $timestamps = false; // si no tienes created_at / updated_at

    protected $fillable = [
        'usuarioId',
        'tipo',
        'descripcion',
        'color',
        'talla',
    ];

    public function trabajos()
    {
        return $this->hasMany(\App\Models\Trabajos::class, 'prendaId', 'prendaId');
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
