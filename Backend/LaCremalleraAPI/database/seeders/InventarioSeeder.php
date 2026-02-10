<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('inventario')->insert([
            ['nombre' => 'Hilo azul', 'descripcion' => 'Carrete de hilo azul fuerte', 'cantidad' => 50, 'stock_minimo' => 10],
            ['nombre' => 'Hilo rojo', 'descripcion' => 'Carrete de hilo rojo', 'cantidad' => 40, 'stock_minimo' => 10],
            ['nombre' => 'Hilo amarillo', 'descripcion' => 'Carrete de hilo amarillo fino', 'cantidad' => 40, 'stock_minimo' => 10],
            ['nombre' => 'Cremallera metálica', 'descripcion' => 'Cremalleras de distintos tamaños', 'cantidad' => 30, 'stock_minimo' => 5],
            ['nombre' => 'Imperdibles', 'descripcion' => 'Caja de imperdibles de aluminio pequeños', 'cantidad' => 5, 'stock_minimo' => 5],
            ['nombre' => 'Botones estándar', 'descripcion' => 'Pack de botones medianos', 'cantidad' => 100, 'stock_minimo' => 20],
            ['nombre' => 'Forro interior', 'descripcion' => 'Material para interior de chaquetas', 'cantidad' => 15, 'stock_minimo' => 5]
        ]);
    }
}
