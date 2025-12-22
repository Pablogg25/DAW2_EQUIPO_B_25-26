<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBPrendas;
use la_cremallera\err\FuncionesDBException;
use PDOException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBPrendas extends TestCase{
    public function testGetPrendas(){
        $q_getPrendas=FuncionesDBPrendas::getPrendas();

        $this->assertNotEmpty($q_getPrendas,"ERROR TEST (FuncionesDBPrendas): ha surgido un error al intentar obtener los datos de prendas");

    }

    public function testGetPrendasByUsuarioId(){
        $args1=[
            "usuarioId"=>6
        ];
        $argsE1=[];
        $argsE2=["usuarioId"=>1];
        $argsE3=["usuarioId"=>"6"];

        $q_result1=FuncionesDBPrendas::getPrendasByUsuarioId($args1);
        $this->assertCount(2,$q_result1,"ERROR TEST (FuncionesDBPrendas): error al obtener numero de prendas, usuario id 6 debería tener 2 prendas en este momento");

        $q_resultE2=FuncionesDBPrendas::getPrendasByUsuarioId($argsE2);
        $this->assertCount(0,$q_resultE2,"ERROR TEST (FuncionesDBPrendas): error al obtener numero de prendas, usuario id 1 debería tener 0 prendas en este momento");

        //error de campo obligatorio
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBPrendas::getPrendasByUsuarioId($argsE1);

        //error de tipo de dato
        $this->expectException(FuncionesDBException::class);
        $q_resultE3=FuncionesDBPrendas::getPrendasByUsuarioId($argsE3);

    }

    // test CREATE
    public function testCrearPrenda(){
        $args1=[
            "usuarioId"=>6,
            "tipo"=>"phpunit",
            "descripcion"=>"creado por phpunit",
            "color"=>'ninguno'
        ];

        $argsE1=[
            "usuarioId"=>"6",
            "tipo"=>"phpunit",
            "descripcion"=>"creado por phpunit",
            "color"=>'ninguno'
        ];

        $argsE2=[
            "usuarioId"=>6,
            "descripcion"=>"creado por phpunit",
            "color"=>'ninguno'
        ];

        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBPrendas::crearPrenda($argsE1);

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBPrendas::crearPrenda($argsE2);

        $q_result1=FuncionesDBPrendas::crearPrenda($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBPrendas): error al crear prenda nueva");

    }

    //test UPDATE
    public function testUpdatePrenda(){
        $args1=[
            "prendaId"=>9,
            "usuarioId"=>6,
            "tipo"=>"phpNOTunit",
            "descripcion"=>"creado por phpunit",
            "color"=>'php'
        ];

        $q_result=FuncionesDBPrendas::updatePrenda($args1);
        $this->assertTrue($q_result,"ERROR TEST (FuncionesDBPrendas): error al actualizar prenda");

        //error de campo requerido vacio
        $argsE1=[
            "usuarioId"=>6,
            "tipo"=>"phpNOTunit",
            "descripcion"=>"creado por phpunit",
            "color"=>'php'
        ];

        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBPrendas::updatePrenda($argsE1);

        //error de campo requerido tipo erroneo
        $argsE2=[
            "prendaId"=>9,
            "usuarioId"=>"6",
            "tipo"=>"phpNOTunit",
            "descripcion"=>"creado por phpunit",
            "color"=>'php'
        ];

        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBPrendas::updatePrenda($argsE2);

        //error de campo requerido vacio
        $argsE3=[
            "prendaId"=>9,
            "usuarioId"=>6,
            "descripcion"=>"creado por phpunit",
            "color"=>'php'
        ];

        $this->expectException(FuncionesDBException::class);
        $q_resultE3=FuncionesDBPrendas::updatePrenda($argsE3);

        //error de foreign key inexistente
        $argsE4=[
            "prendaId"=>9,
            "usuarioId"=>100,
            "tipo"=>"phpNOTunit",
            "descripcion"=>"creado por phpunit",
            "color"=>'php'
        ];

        
        $this->expectException(PDOException::class);
        $q_resultE3=FuncionesDBPrendas::updatePrenda($argsE4);
    }

    // test DELETE
    public function testDeletePrenda(){
        $args=["prendaId"=>9];
        //error tipo incorrecto
        $argsE1=["prendaId"=>"9"];
        //error tipo falta requerido
        $argsE2=[];

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBPrendas::deletePrenda($argsE1);

        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBPrendas::deletePrenda($argsE2);

        $q_result1=FuncionesDBPrendas::deletePrenda($args);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBPrendas): error al eliminar prenda de prueba");

    }
}