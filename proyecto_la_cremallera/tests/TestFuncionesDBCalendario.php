<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBCalendario;
use la_cremallera\err\FuncionesDBException;
use PDOException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBCalendario extends TestCase{

    /* ---------- READ ---------- */

    public function testGetEventos(){
        $qResult = FuncionesDBCalendario::getEventos();
        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBCalendario): getEventos() ha surgido un error al intentar obtener los datos de los Eventos!");
    }

    public function testGetEventosByUsuarioId(){
        $argsOk = ['usuarioId' => 1];
        $argsE1 = [];
        $argsE2 = ['usuarioId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::getEventosByUsuarioId($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::getEventosByUsuarioId($argsE2);

        $qResult = FuncionesDBCalendario::getEventosByUsuarioId($argsOk);
        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBCalendario): getEventosByUsuarioId() ha surgido un error al intentar obtener los datos de los Eventos!");
    }

    public function testGetEventosByEmpleadoId(){
        $argsOk = ['empleadoId' => 1];
        $argsE1 = [];
        $argsE2 = ['empleadoId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::getEventosByEmpleadoId($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::getEventosByEmpleadoId($argsE2);

        $qResult = FuncionesDBCalendario::getEventosByEmpleadoId($argsOk);
        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBCalendario): getEventosByEmpleadoId() ha surgido un error al intentar obtener los datos de los Eventos!");
    }

    public function testGetEventosByTrabajo(){
        $argsOk = ['trabajoId' => 1];
        $argsE1 = [];
        $argsE2 = ['trabajoId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::getEventosByTrabajo($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::getEventosByTrabajo($argsE2);

        $qResult = FuncionesDBCalendario::getEventosByTrabajo($argsOk);
        $this->assertNotEmpty($qResult,"ERROR TEST (FuncionesDBCalendario): getEventosByTrabajo() ha surgido un error al intentar obtener los datos de los Eventos!");
    }

    public function testInsertEvento(){
        $argsOk = [
            'titulo' => 'Evento Test',
            'descripcion' => 'Cliente Gustavo',
            'fecha_inicio' => '2025-12-24 01:00',
            'fecha_fin' => '2025-12-25 23:59',
            'usuarioId' => 1,
            'empleadoId' => 1,
            'trabajoId' => 1
        ];

        $argsE1 = [];
        $argsE2 = ['titulo' => 123];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::insertEvento($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::insertEvento($argsE2);

        $qResult = FuncionesDBCalendario::insertEvento($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBCalendario): insertEvento() no inserta correctamente!");
    }

    public function testUpdateEvento(){
        $argsOk = [
            'eventoId' => 1,
            'titulo' => 'Entrega de pantalón',
            'descripcion' => 'Cliente Ana',
            'fecha_inicio' => '2025-11-25 10:00',
            'fecha_fin' => '2025-11-25 10:30',
            'usuarioId' => 6,
            'empleadoId' => 3,
            'trabajoId' => 2 /*cambio de trabajador*/ 
        ];

        $argsE1 = [];
        $argsE2 = ['eventoId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::updateEvento($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::updateEvento($argsE2);

        $qResult = FuncionesDBCalendario::updateEvento($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBCalendario): updateEvento() no actualiza correctamente!");
    }

    public function testDeleteEvento(){
        $argsOk = ['eventoId' => 1];
        $argsE1 = [];
        $argsE2 = ['eventoId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::deleteEvento($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBCalendario::deleteEvento($argsE2);

        $qResult = FuncionesDBCalendario::deleteEvento($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBCalendario): deleteEvento() no borra correctamente!");
    }
}

?>