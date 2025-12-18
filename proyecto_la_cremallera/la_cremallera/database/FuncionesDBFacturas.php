<?php

namespace la_cremallera\database;


require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBFacturas
{

    // ---READ---
    /**
     * getFacturas()
     * devuelve todos los datos de la tabla facturas
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getFacturas()
    {
        $q_selectFacturas = "SELECT * FROM facturas";

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_selectFacturas);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getFacturaById($args)
     * Obtiene los datosde unafactura por id
     * 
     * $args:
     * - facturaId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getFacturaById($args)
    {
        $q_selectFacturas = "SELECT * FROM facturas WHERE facturaId = :id";

        $facturaId = $args['facturaId'] ?? -1;

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de facturaId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_selectFacturas);
        $stmt->execute([":id" => $facturaId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getFacturaByUsurioId($args)
     * Obtiene los datos de las facturas pora un usuarioId
     * 
     * $args:
     * - usuarioId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getFacturaByUsurioId($args)
    {
        $q_selectFacturas = "SELECT * FROM facturas WHERE usuarioId = :id";

        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_selectFacturas);
        $stmt->execute([":id" => $usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---CREATE---

    /**
     * insertFactura($args)
     * Recibe parametros para insertar una factura, elcampo pagada se inicia a false, 
     * el campo "total_calculado" puede calcularse después de forma automática"
     * 
     * $args:
     * - usuarioId (requerido)
     * - fecha (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function insertFactura($args)
    {
        $q_insertFactura = "INSERT INTO facturas (usuarioId,fecha) VALUES (:usuarioId,:fecha)";

        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de usuarioId no reconocido");
        }

        $fecha = $args['fecha'] ?? '';

        if ($fecha == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): el campo fecha es requerido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertFactura);

        $success = $stmn->execute([
            ':usuarioId' => $usuarioId,
            ':fecha' => $fecha
        ]);

        return $success;
    }
}
