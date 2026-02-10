<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Laura Martínez',
                'telefono' => '600111222',
                'email' => 'laura@cremallera.com',
                'direccion' => 'C/ Sol 12',
                'username' => 'laura_adm',
                'password_SHA2' => hash('sha224', 'hash1'),
                'rol' => 'admin'
            ],
            [
                'nombre' => 'Pablo Rivas',
                'telefono' => '600222333',
                'email' => 'pablo@cremallera.com',
                'direccion' => 'C/ Luna 33',
                'username' => 'pablo_adm',
                'password_SHA2' => hash('sha224', 'hash2'),
                'rol' => 'admin'
            ],
            [
                'nombre' => 'Sergio López',
                'telefono' => '600333444',
                'email' => 'sergio@cremallera.com',
                'direccion' => 'C/ Río 21',
                'username' => 'sergio_emp',
                'password_SHA2' => hash('sha224', 'hash3'),
                'rol' => 'empleado'
            ],
            [
                'nombre' => 'Gustavo Bautista',
                'telefono' => '600444555',
                'email' => 'gustavo@cremallera.com',
                'direccion' => 'C/ Águila 2',
                'username' => 'gustavo_emp',
                'password_SHA2' => hash('sha224', 'hash4'),
                'rol' => 'empleado'
            ],
            [
                'nombre' => 'Pablo Núñez',
                'telefono' => '600555666',
                'email' => 'pablo.nunez@cremallera.com',
                'direccion' => 'C/ Olivo 19',
                'username' => 'pablo_emp',
                'password_SHA2' => hash('sha224', 'hash5'),
                'rol' => 'empleado'
            ],
            [
                'nombre' => 'Ana Torres',
                'telefono' => '600666777',
                'email' => 'ana@gmail.com',
                'direccion' => 'Av. Castilla 9',
                'username' => 'ana_cli',
                'password_SHA2' => hash('sha224', 'hash6'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Carlos Pérez',
                'telefono' => '600777888',
                'email' => 'carlos@gmail.com',
                'direccion' => 'C/ Mayor 41',
                'username' => 'carlos_cli',
                'password_SHA2' => hash('sha224', 'hash7'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'María López',
                'telefono' => '600888999',
                'email' => 'maria@gmail.com',
                'direccion' => 'C/ Prado 15',
                'username' => 'maria_cli',
                'password_SHA2' => hash('sha224', 'hash8'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Jorge Díaz',
                'telefono' => '600999111',
                'email' => 'jorge@gmail.com',
                'direccion' => 'C/ Jardines 4',
                'username' => 'jorge_cli',
                'password_SHA2' => hash('sha224', 'hash9'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Elena Ruiz',
                'telefono' => '611222333',
                'email' => 'elena@gmail.com',
                'direccion' => 'C/ Sur 28',
                'username' => 'elena_cli',
                'password_SHA2' => hash('sha224', 'hash10'),
                'rol' => 'cliente'
            ]
        ]);
    }
}
