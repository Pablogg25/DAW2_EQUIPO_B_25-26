<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'password' => Hash::make('lauadm'),
                'rol' => 'admin'
            ],
            [
                'nombre' => 'Pablo Rivas',
                'telefono' => '600222333',
                'email' => 'pablo@cremallera.com',
                'direccion' => 'C/ Luna 33',
                'username' => 'pablo_adm',
                'password' => Hash::make('pabadm'),
                'rol' => 'admin'
            ],
            [
                'nombre' => 'Sergio López',
                'telefono' => '600333444',
                'email' => 'sergio@cremallera.com',
                'direccion' => 'C/ Río 21',
                'username' => 'sergio_emp',
                'password' => Hash::make('seremp'),
                'rol' => 'empleado'
            ],
            [
                'nombre' => 'Gustavo Bautista',
                'telefono' => '600444555',
                'email' => 'gustavo@cremallera.com',
                'direccion' => 'C/ Águila 2',
                'username' => 'gustavo_emp',
                'password' => Hash::make('gusemp'),
                'rol' => 'empleado'
            ],
            [
                'nombre' => 'Pablo Núñez',
                'telefono' => '600555666',
                'email' => 'pablo.nunez@cremallera.com',
                'direccion' => 'C/ Olivo 19',
                'username' => 'pablo_emp',
                'password' => Hash::make('pabemp'),
                'rol' => 'empleado'
            ],
            [
                'nombre' => 'Ana Torres',
                'telefono' => '600666777',
                'email' => 'ana@gmail.com',
                'direccion' => 'Av. Castilla 9',
                'username' => 'ana_cli',
                'password' => Hash::make('anacli'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Carlos Pérez',
                'telefono' => '600777888',
                'email' => 'carlos@gmail.com',
                'direccion' => 'C/ Mayor 41',
                'username' => 'carlos_cli',
                'password' => Hash::make('carcli'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'María López',
                'telefono' => '600888999',
                'email' => 'maria@gmail.com',
                'direccion' => 'C/ Prado 15',
                'username' => 'maria_cli',
                'password' => Hash::make('marcli'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Jorge Díaz',
                'telefono' => '600999111',
                'email' => 'jorge@gmail.com',
                'direccion' => 'C/ Jardines 4',
                'username' => 'jorge_cli',
                'password' => Hash::make('jorcli'),
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Elena Ruiz',
                'telefono' => '611222333',
                'email' => 'elena@gmail.com',
                'direccion' => 'C/ Sur 28',
                'username' => 'elena_cli',
                'password' => Hash::make('elecli'),
                'rol' => 'cliente'
            ]
        ]);
    }
}
