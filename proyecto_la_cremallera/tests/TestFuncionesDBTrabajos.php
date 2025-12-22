<?php

namespace test;

require_once __DIR__ . "/../vendor/autoload.php";

use la_cremallera\database\FuncionesDBTrabajos;
use la_cremallera\database\FuncionesDBUsuarios;
use la_cremallera\err\FuncionesDBException;
use PDOException;
use PHPUnit\Framework\TestCase;

final class TestFuncionesDBTrabajos extends TestCase{
    // tests READ

    public function testGetTrabajos(){
        $q_result=FuncionesDBTrabajos::getTrabajos();

        $this->assertNotEmpty($q_result,"ERROR TEST (FuncionesDBTrabajos): ha surgido un error al intentar obtener los datos de trabajos");

    }

    public function testGetTrabajosByEmpleadoId(){
        $args1=['empleadoId'=>3];
        $args2=['empleadoId'=>5];

        //error tipo erroneo
        $argsE1=['empleadoId'=>"3"];
        // error tipo requerido vacio
        $argsE2=[];

        $q_result1=FuncionesDBTrabajos::getTrabajosByEmpleadoId($args1);
        $this->assertCount(3,$q_result1,"ERROR TEST (FuncionesDBTrabajos): empleado id 3 debería tener 3 trabajos en este momento");
        
        $q_result2=FuncionesDBTrabajos::getTrabajosByEmpleadoId($args2);
        $this->assertCount(2,$q_result2,"ERROR TEST (FuncionesDBTrabajos): empleado id 5 debería tener 2 trabajos en este momento");

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBTrabajos::getTrabajosByEmpleadoId($argsE1);

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBTrabajos::getTrabajosByEmpleadoId($argsE2);

    }

    public function testGetTrabajosByUsuarioId(){
        $args1=['usuarioId'=>8]; //esperado 2 trabajos
        $args2=['usuarioId'=>7]; //esperado 1 trabajo
        $argsE1=['usuarioId'=>"7"]; //error de tipo
        $argsE2=[]; //error de campo requerido vacio

        $q_result1=FuncionesDBTrabajos::getTrabajosByUsuarioId($args1);
        $this->assertCount(2,$q_result1,"ERROR TEST (FuncionesDBTrabajos): empleado id 3 debería tener 3 trabajos en este momento");
        
        $q_result2=FuncionesDBTrabajos::getTrabajosByUsuarioId($args2);
        $this->assertCount(1,$q_result2,"ERROR TEST (FuncionesDBTrabajos): empleado id 5 debería tener 2 trabajos en este momento");

        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBTrabajos::getTrabajosByUsuarioId($argsE1);

        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBTrabajos::getTrabajosByUsuarioId($argsE2);
    }

    public function testGetConsumosTrabajo(){
        //obtener consumos de tabla de consumos por id de trabajo
        $args1=['trabajoId'=>1];
        $argsE1=['trabajoId'=>"1"];
        $argsE2=[];

        $q_result1=FuncionesDBTrabajos::getConsumosTrabajo($args1);
        $this->assertNotEmpty($q_result1,"ERROR TEST (FuncionesDBTrabajos): no se ha podido extraer consumos de trabajo id 1");
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE1=FuncionesDBTrabajos::getConsumosTrabajo($argsE1);
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE2=FuncionesDBTrabajos::getConsumosTrabajo($argsE2);
    }

    // test CREATE

    public function testCreateTrabajo(){
        $args=[
            'prendaId'=>1,
            'fecha_inicio'=>'2025-12-22',
            'fecha_entrega'=>'2026-03-12',
            'descripcion'=>'creado por phpunit',
            'empleadoId'=>1,
            'precio'=>12.5
        ];

        //mala clave foranea
        $argsE1=[
            'prendaId'=>100,
            'fecha_inicio'=>'2025-12-22',
            'fecha_entrega'=>'2026-03-12',
            'descripcion'=>'creado por phpunit',
            'empleadoId'=>1,
            'precio'=>12.5
        ];

        //mal formato de fecha
        $argsE2=[
            'prendaId'=>100,
            'fecha_inicio'=>'2',
            'fecha_entrega'=>'2E4',
            'descripcion'=>'creado por phpunit',
            'empleadoId'=>1,
            'precio'=>12.5
        ];

        //falta valor de empleado Id
        $argsE3=[
            'prendaId'=>1,
            'fecha_inicio'=>'2025-12-22',
            'fecha_entrega'=>'2026-03-12',
            'descripcion'=>'creado por phpunit',
            'precio'=>12.5
        ];

        //mal valor de precio
        $argsE4=[
            'prendaId'=>1,
            'fecha_inicio'=>'2025-12-22',
            'fecha_entrega'=>'2026-03-12',
            'descripcion'=>'creado por phpunit',
            'empleadoId'=>1,
            'precio'=>"12.5"
        ];

        
        $this->expectException(PDOException::class);
        $q_resultE1=FuncionesDBTrabajos::getConsumosTrabajo($argsE1);
        
        $this->expectException(PDOException::class);
        $q_resultE2=FuncionesDBTrabajos::getConsumosTrabajo($argsE2);
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE3=FuncionesDBTrabajos::getConsumosTrabajo($argsE3);
        
        $this->expectException(FuncionesDBException::class);
        $q_resultE4=FuncionesDBTrabajos::getConsumosTrabajo($argsE4);

        $q_result1=FuncionesDBTrabajos::getConsumosTrabajo($args);
        $this->assertTrue($q_result1,"ERROR TEST (FuncionesDBTrabajos): no se ha podido crear trabajo de prueba");
    }

    public function testAsociarConsumo(){
        $args1=['trabajoId'=>9,'itemId'=>1,'cantidad'=>2];
        
        $argsE1=['trabajoId'=>9,'itemId'=>1,'cantidad'=>2];
    }
}