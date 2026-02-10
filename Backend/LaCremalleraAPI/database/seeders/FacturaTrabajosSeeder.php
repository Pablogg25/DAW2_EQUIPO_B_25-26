<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturaTrabajosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('factura_trabajos')->insert([
            ['facturaId' => 1, 'trabajoId' => 1],
            ['facturaId' => 2, 'trabajoId' => 4],
            ['facturaId' => 3, 'trabajoId' => 2],
            ['facturaId' => 4, 'trabajoId' => 8]
        ]);
    }
}
