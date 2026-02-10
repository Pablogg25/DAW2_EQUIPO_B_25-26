<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trabajos')->insert([
            ['prendaId' => 1, 'empleadoId' => 3, 'descripcion' => 'Bajo completo y ajuste lateral', 'fecha_inicio' => '2025-11-20', 'fecha_entrega' => '2025-11-25', 'estado' => 'en_proceso', 'precio' => 12.50],
            ['prendaId' => 2, 'empleadoId' => 4, 'descripcion' => 'Ajuste de costuras delicadas', 'fecha_inicio' => '2025-11-18', 'fecha_entrega' => '2025-11-26', 'estado' => 'pendiente', 'precio' => 18.00],
            ['prendaId' => 3, 'empleadoId' => 5, 'descripcion' => 'Sustitución de cremallera metálica', 'fecha_inicio' => '2025-11-10', 'fecha_entrega' => '2025-11-20', 'estado' => 'listo', 'precio' => 15.00],
            ['prendaId' => 4, 'empleadoId' => 3, 'descripcion' => 'Ajuste de cintura', 'fecha_inicio' => '2025-11-12', 'fecha_entrega' => '2025-11-19', 'estado' => 'entregado', 'precio' => 10.00],
            ['prendaId' => 5, 'empleadoId' => 4, 'descripcion' => 'Arreglo completo de mangas', 'fecha_inicio' => '2025-11-14', 'fecha_entrega' => '2025-11-22', 'estado' => 'en_proceso', 'precio' => 14.00],
            ['prendaId' => 6, 'empleadoId' => 5, 'descripcion' => 'Reparación de botones', 'fecha_inicio' => '2025-11-10', 'fecha_entrega' => '2025-11-12', 'estado' => 'entregado', 'precio' => 6.00],
            ['prendaId' => 7, 'empleadoId' => 3, 'descripcion' => 'Ajuste de cintura completo', 'fecha_inicio' => '2025-11-13', 'fecha_entrega' => '2025-11-18', 'estado' => 'pendiente', 'precio' => 9.00],
            ['prendaId' => 8, 'empleadoId' => 4, 'descripcion' => 'Sustituir forro interior', 'fecha_inicio' => '2025-11-15', 'fecha_entrega' => '2025-11-30', 'estado' => 'pendiente', 'precio' => 25.00]
        ]);
    }
}
