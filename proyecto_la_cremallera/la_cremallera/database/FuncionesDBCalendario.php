<?php

namespace la_cremallera\database;


require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBCalendario
{
    // ---READ---

    /**
     * getEventos()
     * Obtiene todos los datos de eventos del calendario
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getEventos()
    {
        $q_selectEventos = "SELECT * FROM calendario";

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectEventos);
        $stmn->execute();

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    final public static function getEventosByEmpleadoId($args)
    {
        $q_selectEventos = "SELECT * FROM calendario WHERE empleadoId = :id";

        $empleadoId=$args['empleadoId']??-1;
        //controla que el valor del id sea correcto
        if ($empleadoId < 0 || gettype($empleadoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de empleadoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectEventos);
        $stmn->execute([':id'=>$empleadoId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    final public static function getEventosByTrabajo($args){
        
    }

    // ---CREATE---

    // ---UPDATE---

    // ---DELETE---
}
