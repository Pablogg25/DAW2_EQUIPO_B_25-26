<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBUsuarios;
use la_cremallera\err\FuncionesDBException;
use PDOException;
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

        $argsE=[];

        $qResult=FuncionesDBUsuarios::getUsuarioByName($args1);

        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBUsuarios): testGetUsuarioByName() devuelve vacío. Compruebe que existen datos de usuario");
        $this->assertCount(1,$qResult,"ERROR TEST (FuncionesDBUsuarios): testGetUsuarioByName() devuelve count = 0. Asegurese que existe al menos un usuario admin");
        $this->assertArrayNotHasKey('password_SHA2',$qResult,"ERROR TEST (FuncionesDBUsuarios): testGetUsuarioByName() devuelve filas con contraseña privada. No se deben devolver los datos de contraseña");
    
        
        //testeo de escepcionespor campos requeridos
        $this->expectException(FuncionesDBException::class);
        $q_resultE=FuncionesDBUsuarios::getUsuarioByName($argsE);
    }

    //todo: añadir un usuario administrador genérico para mejor testeo
    public function testGetUsuarioById(){
        $args1=[
            'usuarioId'=>1
        ];

        $argsE=[];

        $argsE2=[
            'usuarioId'=>'1'
        ];

        $qResult=FuncionesDBUsuarios::getUsuarioById($args1);

        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBUsuarios): getUsuarioById() devuelve vacío. Compruebe que existen datos de usuario");
        $this->assertCount(1,$qResult,"ERROR TEST (FuncionesDBUsuarios): getUsuarioById() devuelve count = 0. Asegurese que existe al menos un usuario admin");
        $this->assertArrayNotHasKey('password_SHA2',$qResult,"ERROR TEST (FuncionesDBUsuarios): getUsuarioById() devuelve filas con contraseña privada. No se deben devolver los datos de contraseña");

        
        //testeo de escepcionespor campos requeridos
        $this->expectException(FuncionesDBException::class);
        $q_resultE=FuncionesDBUsuarios::getUsuarioById($argsE);

        //comprobación de tipo erroneo
        $this->expectException(FuncionesDBException::class);
        $q_resultE=FuncionesDBUsuarios::getUsuarioById($argsE2);
    }

    // test validate
    public function testCheckPassword(){
        $args1=[
            'username'=>'laura_adm',
            'password'=>'hash1'
        ];
        $args2=[
            'username'=>'laura_adm',
            'password'=>'hash_1'
        ];

        $argsE=[
            'username'=>'laura_adm'
        ];

        $argsE2=[
            'password'=>'hash_1'
        ];

        $q_result1=FuncionesDBUsuarios::checkPassword($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBUsuarios): checkPassword da falso en comprobación correcta");

        $q_result2=FuncionesDBUsuarios::checkPassword($args2);
        $this->assertFalse($q_result2,"ERROR TEST (FuncionesDBUsuarios): checkPassword da true en comprobación erronea");

        //testeo de escepcionespor campos requeridos
        $this->expectException(FuncionesDBException::class);
        $q_resultE=FuncionesDBUsuarios::checkPassword($argsE);

        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBUsuarios::checkPassword($argsE2);
    }

    //tests CREATE

    //test insert parausuario phpunit

    public function testRegistrarUsuario(){
        //falta un argumento
        $args2=[
            'nombre'=>'phpunit',
            'username'=>'phpunit',
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

    // test update
    public function testUpdateUsuario(){

        //update correcto
        $args1=[
            'usuarioId'=>11,
            'nombre'=>'phpunit',
            'username'=>'notphpunit',
            'email'=>'phpunit@noreply.com'
        ];

        //error de clave única username
        $argsE1=[
            'usuarioId'=>11,
            'nombre'=>'phpunit',
            'username'=>'laura_adm',
            'email'=>'phpunit@noreply.com'
        ];

        //error por falta de campo requerido
        $argsE2=[
            'usuarioId'=>11,
            'username'=>'notphpunit',
            'email'=>'phpunit@noreply.com'
        ];

        //error por id no reconocido
        $argsE3=[
            'usuarioId'=>'none',
            'nombre'=>'phpunit',
            'username'=>'notphpunit',
            'email'=>'phpunit@noreply.com'
        ];

        //error por valore numerado incorrecto
        $argsE4=[
            'usuarioId'=>11,
            'nombre'=>'phpunit',
            'username'=>'notphpunit',
            'rol'=>'hacker',
            'email'=>'phpunit@noreply.com'
        ];

        $q_update1=FuncionesDBUsuarios::updateDatosUsuario($args1);
        $this->assertTrue($q_update1, "ERROR TEST (FuncionesDBUsuarios): ha surgido un error al intentar actualizar un usuario de forma correcta");

        
        $this->expectException(PDOException::class);
        $q_updateE1=FuncionesDBUsuarios::updateDatosUsuario($argsE1);
        //$this->assertFalse($q_updateE1,"ERROR TEST (FuncionesDBUsuarios): una actualización con violación de clave única en username se ha realizado");

        $this->expectException(FuncionesDBException::class);
        $q_updateE2=FuncionesDBUsuarios::updateDatosUsuario($argsE2);

        
        $this->expectException(FuncionesDBException::class);
        $q_updateE2=FuncionesDBUsuarios::updateDatosUsuario($argsE3);
    }

    public function testUpdatePasswordUsuario(){
        //update correcto
        $args1=[
            'usuarioId'=>11,
            'password'=>'notphpunit'
        ];

        //excpecion falta argumento
        $argsE1=[
            'usuarioId'=>11,
        ];

        //excepcion tipo erroneo
        $argsE2=[
            'usuarioId'=>"none",
            'password'=>'notphpunit'
        ];

        $q_result1=FuncionesDBUsuarios::updatePasswordUsuario($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBUsuarios): ha surgido un error al intentar actualizar el password de un usuario de forma correcta");
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBUsuarios::updatePasswordUsuario($argsE1);

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBUsuarios::updatePasswordUsuario($argsE2);

    }

    // test DELETE
    public function testDeleteUsuario(){
        //testear que se deletea el usuario anterior
        $args1=[
            'usuarioId'=>11
        ];

        //argumentos vacios
        $argsE1=[];

        //tipo erroneo
        $argsE2=['usuarioId'=>'none'];
        
        //excepciones de condiciones
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBUsuarios::deleteUsuario($argsE1);
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBUsuarios::deleteUsuario($argsE2);

        $q_result1=FuncionesDBUsuarios::deleteUsuario($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBUsuarios): No se ha podido borrar correctamente el usuario de prueba en BBDD");
    }
}