<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBUsuarios;
use la_cremallera\err\FuncionesDBException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBUsuarios extends TestCase{

    // tests READ
    public function testGetUsuarios(){
        $qResult=FuncionesDBUsuarios::getUsuarios();

        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBUsuarios): GetUsuarios() devuelve vacío. Compruebe que existen datos de usuario");

        $primerResultado=$qResult[0];

        $this->assertArrayNotHasKey('password_SHA2',$primerResultado,"ERROR TEST (FuncionesDBUsuarios): GetUsuarios() devuelve filas con contraseña privada. No se deben devolver los datos de contraseña");

    }

    //todo: añadir un usuario administrador genérico para mejor testeo
    public function testGetUsuarioByName(){
        $args1=[
            'username'=>'laura_adm'
        ];

        $qResult=FuncionesDBUsuarios::getUsuarioByName($args1);

        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBUsuarios): testGetUsuarioByName() devuelve vacío. Compruebe que existen datos de usuario");
        $this->assertCount(1,$qResult,"ERROR TEST (FuncionesDBUsuarios): testGetUsuarioByName() devuelve count = 0. Asegurese que existe al menos un usuario admin");
        $this->assertArrayNotHasKey('password_SHA2',$qResult,"ERROR TEST (FuncionesDBUsuarios): testGetUsuarioByName() devuelve filas con contraseña privada. No se deben devolver los datos de contraseña");

    }

    //tests WRITE

    //test insert parausuario phpunit

    public function testRegistrarUsuario(){
        //falta un argumento
        $args2=[
            'nombre'=>'phpunit',
            'username'=>'phpunit',
            'password'=>'phpunit',
            'email'=>'phpunit@noreply.com'
        ];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBUsuarios::registrarUsuario($args2);

        //enumerado de valor erroneo
        $args3=[
            'username'=>'phpunit',
            'password'=>'phpunit',
            'email'=>'phpunit@noreply.com',
            'rol'=>'hacker'
        ];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBUsuarios::registrarUsuario($args3);

        //insert correcto
        $args1=[
            'nombre'=>'phpunit',
            'username'=>'phpunit',
            'password'=>'phpunit',
            'email'=>'phpunit@noreply.com'
        ];

        $exito=FuncionesDBUsuarios::registrarUsuario($args1);
        $this->assertTrue($exito,"ERROR TEST (FuncionesDBUsuarios): ha surgido un error al intentar insertar un usuario válido");
    }
}