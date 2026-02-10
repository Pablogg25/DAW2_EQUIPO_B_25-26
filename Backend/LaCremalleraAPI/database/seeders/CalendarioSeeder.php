<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalendarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('calendario')->insert([
            ['titulo' => 'Entrega de pantalón', 'descripcion' => 'Cliente Ana', 'fecha_inicio' => '2025-11-25 10:00', 'fecha_fin' => '2025-11-25 10:30', 'usuarioId' => 6, 'empleadoId' => 3, 'trabajoId' => 1],
            ['titulo' => 'Revisión vestido', 'descripcion' => 'Cliente Ana', 'fecha_inicio' => '2025-11-26 09:00', 'fecha_fin' => '2025-11-26 09:30', 'usuarioId' => 6, 'empleadoId' => 4, 'trabajoId' => 2],
            ['titulo' => 'Entrega chaqueta', 'descripcion' => 'Cliente Carlos', 'fecha_inicio' => '2025-11-20 11:00', 'fecha_fin' => '2025-11-20 11:30', 'usuarioId' => 7, 'empleadoId' => 5, 'trabajoId' => 3]
        ]);
    }
}
