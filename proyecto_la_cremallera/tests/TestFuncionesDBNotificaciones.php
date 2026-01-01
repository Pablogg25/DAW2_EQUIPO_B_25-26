<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBNotificaciones;
use la_cremallera\err\FuncionesDBException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBNotificaciones extends TestCase
{

    public function testGetNotificaciones()
    {
        $qResult = FuncionesDBNotificaciones::getNotificaciones();
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBNotificaciones): getNotificaciones() ha surgido un error al intentar obtener los datos de las Notificaciones!");
    }

    public function testGetNotificacionesByReceptor()
    {
        $argsOk = ['receptorId' => 1];
        $argsE1 = [];
        $argsE2 = ['receptorId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::getNotificacionesByReceptor($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::getNotificacionesByReceptor($argsE2);

        $qResult = FuncionesDBNotificaciones::getNotificacionesByReceptor($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBNotificaciones): getNotificacionesByReceptor() ha surgido un error al intentar obtener los datos de las Notificaciones!");
    }

    public function testGetNotificacionesByRemitente()
    {
        $argsOk = ['remitenteId' => 1];
        $argsE1 = [];
        $argsE2 = ['remitenteId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::getNotificacionesByRemitente($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::getNotificacionesByRemitente($argsE2);

        $qResult = FuncionesDBNotificaciones::getNotificacionesByRemitente($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBNotificaciones): getNotificacionesByRemitente() nha surgido un error al intentar obtener los datos de las Notificaciones!");
    }

    public function testGetNotificacionesByTrabajoId()
    {
        $argsOk = ['trabajoId' => 1];
        $argsE1 = [];
        $argsE2 = ['trabajoId' => 'none'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::getNotificacionesByTrabajoId($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::getNotificacionesByTrabajoId($argsE2);

        $qResult = FuncionesDBNotificaciones::getNotificacionesByTrabajoId($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBNotificaciones): getNotificacionesByTrabajoId() ha surgido un error al intentar obtener los datos de las Notificaciones!");
    }

    public function testInsertNotificacion()
    {
        $argsOk = [
            'receptorId' => 1,
            'remitenteId' => 1,
            'trabajoId' => 1,
            'tipo' => 'recordatorio_entrega',
            'asunto' => 'es un recordatorio',
            'mensaje' => 'que funciones el test'
        ];

        $argsE1 = [];
        $argsE2 = ['receptorId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::insertNotificacion($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::insertNotificacion($argsE2);

        $qResult = FuncionesDBNotificaciones::insertNotificacion($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBNotificaciones): insertNotificacion() no inserta correctamente!");
    }

    public function testUpdateMensaje()
    {
        $argsOk = [
            'notificacionId' => 1,
            'receptorId' => 1,
            'remitenteId' => 1,
            'trabajoId' => 1,
            'tipo' => 'recordatorio_entrega',
            'asunto' => 'msunto modificado',
            'mensaje' => 'mensaje modificado'
        ];

        $argsE1 = [];
        $argsE2 = ['notificacionId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::updateMensaje($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::updateMensaje($argsE2);

        $qResult = FuncionesDBNotificaciones::updateMensaje($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBNotificaciones): updateMensaje() no actualiza correctamente!");
    }

    public function testDeleteNotificacion()
    {
        $argsOk = ['notificacionId' => 1];
        $argsE1 = [];
        $argsE2 = ['notificacionId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::deleteNotificacion($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBNotificaciones::deleteNotificacion($argsE2);

        $qResult = FuncionesDBNotificaciones::deleteNotificacion($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBNotificaciones): deleteNotificacion() no borra correctamente!");
    }
}

?>