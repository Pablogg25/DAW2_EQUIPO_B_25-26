<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBUsuarios;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBUsuarios extends TestCase{

    public function testGetUsuarios(){
        $qResult=FuncionesDBUsuarios::getUsuarios();

        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBUsuarios): GetUsuarios() devuelve vacío");

        $primerResultado=$qResult[0];

        $this->assertArrayNotHasKey('password_SHA2',$primerResultado,"ERROR TEST (FuncionesDBUsuarios): GetUsuarios() devuelve filas con contraseña privada");

    }
}