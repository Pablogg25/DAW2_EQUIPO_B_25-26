<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificacionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notificaciones')->insert([
            [
                'receptorId' => 6,
                'remitenteId' => 1,
                'trabajoId' => 1,
                'tipo' => 'recordatorio_entrega',
                'asunto' => 'recogida prenda',
                'mensaje' => 'Su prenda estará lista para recoger el día 25'
            ],
            [
                'receptorId' => 6,
                'remitenteId' => 1,
                'trabajoId' => 2,
                'tipo' => 'trabajo_listo',
                'asunto' => 'trabajo acabado',
                'mensaje' => 'Su vestido ya está disponible'
            ],
            [
                'receptorId' => 7,
                'remitenteId' => 1,
                'trabajoId' => 3,
                'tipo' => 'factura_generada',
                'asunto' => 'factura trabajo',
                'mensaje' => 'Se ha emitido su factura'
            ]
        ]);
    }
}
