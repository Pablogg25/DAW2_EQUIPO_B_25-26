<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBInventario;
use la_cremallera\err\FuncionesDBException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBInventario extends TestCase{

    public function testGetInventario(){
        $q_result=FuncionesDBInventario::getInventario();

        $this->assertNotEmpty($q_result,"ERROR TEST (FuncionesDBInventario): ha surgido un error al intentar obtener los datos del inventario");
    }

    public function testGetItem(){
        $args1=["itemId"=>1];
        $argsE1=[];
        $argsE2=["itemId"=>"n"];

        $q_result1=FuncionesDBInventario::getItem($args1);
        $this->assertNotEmpty($q_result1,"ERROR TEST (FuncionesDBInventario): ha surgido un error al intentar obtener los datos de item");

        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBInventario::getItem($argsE1);
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBInventario::getItem($argsE2);
    }

    public function testGetItemsBajoStock(){
        $q_result=FuncionesDBInventario::getItemsBajoStock();

        $this->assertNotEmpty($q_result,"ERROR TEST (FuncionesDBInventario): ha surgido un error al intentar obtener datos de items bajos en stock");
    
    }

    public function testInsertItem(){
        $args1=[
            "nombre"=>'phpunit',
            'descripcion'=>'creado por phpunit',
            'cantidad'=>1,
            'stock_minimo'>=5
        ];

        $argsE1=[
            'descripcion'=>'creado por phpunit',
            'cantidad'=>1,
            'stock_minimo'>=5
        ];

        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBInventario::insertItem($argsE1);

        $q_result1=FuncionesDBInventario::insertItem($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBInventario): ha surgido un error al intentar insertar un item");

    }

    public function testUpdateItem(){
        $args1=[
            "itemId"=>8,
            "nombre"=>"notphpunit",
            "descripcion"=>"creado por notphpunit",
            "cantidad"=>5,
            "stock_minimo"=>10
        ];

        //error de tipo de campo erroneo
        $argsE1=[
            "itemId"=>"8",
            "nombre"=>"notphpunit",
            "descripcion"=>"creado por notphpunit",
            "cantidad"=>5,
            "stock_minimo"=>10
        ];

        //error de campo faltante
        $argsE2=[
            "itemId"=>8,
            "descripcion"=>"creado por notphpunit",
            "cantidad"=>5,
            "stock_minimo"=>10
        ];

        //error de formato de numero
        $argsE3=[
            "itemId"=>8,
            "nombre"=>"notphpunit",
            "descripcion"=>"creado por notphpunit",
            "cantidad"=>5,
            "stock_minimo"=>-10
        ];

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBInventario::updateItem($argsE1);

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBInventario::updateItem($argsE2);
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE3=FuncionesDBInventario::updateItem($argsE3);

        $q_result1=FuncionesDBInventario::updateItem($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBInventario): ha surgido un error al intentar actualizar un item");
    }

    // test DELETE
    public function testDeleteItem(){
        $args1=["itemId"=>8];
        $argsE1=["itemId"=>"8"];
        $argsE2=[];

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBInventario::deleteItem($argsE1);

        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBInventario::deleteItem($argsE2);

        $q_result1=FuncionesDBInventario::deleteItem($args1);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBInventario): no se ha podido eliminar el item de prueba");

    }
}