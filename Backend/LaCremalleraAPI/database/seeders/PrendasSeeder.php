<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrendasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('prendas')->insert([
            ['usuarioId' => 6, 'tipo' => 'Pantalón', 'descripcion' => 'Bajo y ajuste de pierna', 'color' => 'Azul', 'talla' => 'M'],
            ['usuarioId' => 6, 'tipo' => 'Vestido', 'descripcion' => 'Ajuste de cintura y hombros', 'color' => 'Rojo', 'talla' => 'L'],
            ['usuarioId' => 7, 'tipo' => 'Chaqueta', 'descripcion' => 'Cambio de cremallera', 'color' => 'Negro', 'talla' => 'XL'],
            ['usuarioId' => 8, 'tipo' => 'Falda', 'descripcion' => 'Ajuste de cintura', 'color' => 'Verde', 'talla' => 'S'],
            ['usuarioId' => 8, 'tipo' => 'Abrigo', 'descripcion' => 'Arreglo en mangas', 'color' => 'Beige', 'talla' => 'M'],
            ['usuarioId' => 9, 'tipo' => 'Camisa', 'descripcion' => 'Arreglo en botones', 'color' => 'Blanco', 'talla' => 'M'],
            ['usuarioId' => 10, 'tipo' => 'Pantalón', 'descripcion' => 'Ajuste de cintura', 'color' => 'Gris', 'talla' => 'S'],
            ['usuarioId' => 10, 'tipo' => 'Chaqueta', 'descripcion' => 'Sustituir forro interior', 'color' => 'Azul', 'talla' => 'L']
        ]);
    }
}
