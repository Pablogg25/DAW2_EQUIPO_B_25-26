<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumosTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('consumos_trabajo')->insert([
            ['trabajoId' => 1, 'itemId' => 1, 'cantidad_usada' => 2],
            ['trabajoId' => 3, 'itemId' => 3, 'cantidad_usada' => 1],
            ['trabajoId' => 5, 'itemId' => 1, 'cantidad_usada' => 1],
            ['trabajoId' => 6, 'itemId' => 4, 'cantidad_usada' => 2],
            ['trabajoId' => 8, 'itemId' => 5, 'cantidad_usada' => 1]
        ]);
    }
}
