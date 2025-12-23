<?php

namespace test;

require_once __DIR__ ."/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBCalendario;
use la_cremallera\err\FuncionesDBException;
use PDOException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBCalendario extends TestCase{

    //test READ
    public function testGetCalendario(){

        $qResult = FuncionesDBCalendario::getEventos();

        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBCalendario): GetEventos() devuelve vacío. Compruebe que existen datos de eventos" );

    }

    public function testGetEventosByUsuarioId(){
        
        //valor String
        $args1 = [
            'usuarioId' => '6'
        ];

        //valor numerico
        $args2 = [
            'usuarioId' => 6
        ];

        //vacio
        $argsE = [];

        $qResult = FuncionesDBCalendario::getEventosByUsuarioId($args2);

        $this->assertNotEmpty($qResult, "ERROR TEST (FuncionesDBCalendario) : testGetEventoByUsuarioId() no se ha podido obtener valores");

        //testeo de expciociones por tipo erroneo
        $this->expectException(FuncionesDBException::class);
        $qResult1 = FuncionesDBCalendario::getEventosByUsuarioId($args2);

        //testeo de campo requeridos
        $this->expectException(FuncionesDBException::class);
        $qResultE = FuncionesDBCalendario::getEventosByUsuarioId($argsE);

        //comprobacion de tipo erroneo
        $this->expectException(FuncionesDBException::class);
        $qResult2 = FuncionesDBCalendario::getEventosByUsuarioId($args1);

    }

    public function testGet(){
        
    }
}

?>