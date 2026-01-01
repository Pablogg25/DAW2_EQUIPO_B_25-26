<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBFacturas;
use la_cremallera\err\FuncionesDBException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBFacturas extends TestCase
{

    public function testGetFacturas()
    {
        $qResult = FuncionesDBFacturas::getFacturas();
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBFacturas): getFacturas() ha surgido un error al intentar obtener los datos de las Facturas!");
    }

    public function testGetFacturaById()
    {
        $argsOk = ['facturaId' => 1];
        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getFacturaById($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getFacturaById($argsE2);

        $qResult = FuncionesDBFacturas::getFacturaById($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBFacturas): getFacturaById() ha surgido un error al intentar obtener los datos de las Facturas!");
    }

    public function testGetFacturaByUsuarioId()
    {
        $argsOk = ['usuarioId' => 1];
        $argsE1 = [];
        $argsE2 = ['usuarioId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getFacturaByUsurioId($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getFacturaByUsurioId($argsE2);

        $qResult = FuncionesDBFacturas::getFacturaByUsurioId($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBFacturas): getFacturaByUsurioId() ha surgido un error al intentar obtener los datos de las Facturas!");
    }

    public function testGetItemsFactura()
    {
        $argsOk = ['facturaId' => 1];
        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getItemsFactura($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getItemsFactura($argsE2);

        $qResult = FuncionesDBFacturas::getItemsFactura($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBFacturas): getItemsFactura() ha surgido un error al intentar obtener los datos de las Facturas!");
    }

    public function testGetFacturasByTrabajoId()
    {
        $argsOk = ['trabajoId' => 1];
        $argsE1 = [];
        $argsE2 = ['trabajoId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getFacturasByTrabajoId($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getFacturasByTrabajoId($argsE2);

        $qResult = FuncionesDBFacturas::getFacturasByTrabajoId($argsOk);
        $this->assertIsArray($qResult,"ERROR TEST (FuncionesDBFacturas): getFacturasByTrabajoId() ha surgido un error al intentar obtener los datos de las Facturas!");
    }

    public function testGetTotalFactura()
    {
        $argsOk = ['facturaId' => 1];
        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getTotalFactura($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::getTotalFactura($argsE2);

        $qResult = FuncionesDBFacturas::getTotalFactura($argsOk);
        $this->assertIsNumeric($qResult,"ERROR TEST (FuncionesDBFacturas): getTotalFactura() no devuelve un valor numérico!");
    }

    public function testInsertFactura()
    {
        $argsOk = [
            'usuarioId' => 1,
            'fecha' => '2025-01-01'
        ];

        $argsE1 = [];
        $argsE2 = ['usuarioId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::insertFactura($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::insertFactura($argsE2);

        $qResult = FuncionesDBFacturas::insertFactura($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBFacturas): insertFactura() no inserta correctamente!");
    }

    public function testAsociarFacturaTrabajo()
    {
        $argsOk = [
            'facturaId' => 1,
            'trabajoId' => 1
        ];

        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::asociarFacturaTrabajo($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::asociarFacturaTrabajo($argsE2);

        $qResult = FuncionesDBFacturas::asociarFacturaTrabajo($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBFacturas): asociarFacturaTrabajo() no funciona!");
    }

    public function testUpdateFactura()
    {
        $argsOk = [
            'facturaId' => 1,
            'usuarioId' => 1,
            'fecha' => '2025-12-24',
            'pagado' => 1,
            'total_calculado' => 100
        ];

        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::updateFactura($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::updateFactura($argsE2);

        $qResult = FuncionesDBFacturas::updateFactura($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBFacturas): updateFactura() no actualiza correctamente!");
    }

    public function testDeleteFactura()
    {
        $argsOk = ['facturaId' => 1];
        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::deleteFactura($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::deleteFactura($argsE2);

        $qResult = FuncionesDBFacturas::deleteFactura($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBFacturas): deleteFactura() no borra correctamente!");
    }

    public function testDesasociarFacturaTrabajo()
    {
        $argsOk = [
            'facturaId' => 1,
            'trabajoId' => 1
        ];

        $argsE1 = [];
        $argsE2 = ['facturaId' => '1'];

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::desasociarFacturaTrabajo($argsE1);

        $this->expectException(FuncionesDBException::class);
        FuncionesDBFacturas::desasociarFacturaTrabajo($argsE2);

        $qResult = FuncionesDBFacturas::desasociarFacturaTrabajo($argsOk);
        $this->assertTrue($qResult,"ERROR TEST (FuncionesDBFacturas): desasociarFacturaTrabajo() no funciona!");
    }
}

?>