<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('facturas')->insert([
            ['usuarioId' => 6, 'fecha' => '2025-11-25', 'pagado' => true],
            ['usuarioId' => 8, 'fecha' => '2025-11-19', 'pagado' => true],
            ['usuarioId' => 6, 'fecha' => '2025-11-26', 'pagado' => false],
            ['usuarioId' => 10, 'fecha' => '2025-11-30', 'pagado' => false]
        ]);
    }
}
