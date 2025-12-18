<?php

namespace la_cremallera\database;

require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBPrendas
{
    // ---READ---
    /**
     * getPrendas()
     * Obtiene todos los datos de la tabla prendas
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getPrendas()
    {
        //obtener todas las prendas de la base de datos
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): no se ha podido establecer conexion BBDD");
        }

        $selectPrendas = "SELECT * FROM prendas";

        $stmt = $conexion->prepare($selectPrendas);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getPrendasByUsuarioId($args)
     * recibe usuarioId y devuelve los datos de prendas con ese usuarioId
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - usuarioId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getPrendasByUsuarioId($args)
    {
        //obtener todas las prendas de un usuario

        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): no se ha podido establecer conexion BBDD");
        }

        $selectprendaByUser = "SELECT * FROM prendas WHERE usuarioId = :id";

        $stmt = $conexion->prepare($selectprendaByUser);
        $stmt->execute([
            ":id" => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ---CREATE---
    /**
     *  crearPrenda($args)
     * recibe usuarioId y datos paracrearuna prenda.
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - usuarioId (requerido)
     * - tipo
     * - descripcion
     * - color
     * - talla
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function crearPrenda($args)
    {
        //requiere usuarioId

        $q_insertPrenda = "INSERT INTO prendas (usuarioId,tipo,descripcion,color,talla) VALUES " .
            "(:id,:tipo,:desc,:color,:talla)";
        $usuarioId = $args['usuarioId'] ?? -1;
        $tipo = $args['tipo'] ?? '';
        $descripcion = $args['descripcion'] ?? '';
        $color = $args['color'] ?? '';
        $talla = $args['talla'] ?? '';

        //usuarioId requerido
        if ($usuarioId < 0  || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_insertPrenda);
        $exito = $stmt->execute([
            ":id" => $usuarioId,
            ":tipo" => $tipo,
            ":desc" => $descripcion,
            ":color" => $color,
            ":talla" => $talla
        ]);

        return $exito;
    }


    // ---UPDATE---

    /**
     *  updatePrenda($args)
     * recibe prendaId y datos para actualizar una prenda
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - prendaId (requerido)
     * - usuarioId (requerido)
     * - tipo
     * - descripcion
     * - color
     * - talla
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updatePrenda($args)
    {
        $q_updatePrenda = "UPDATE prendas SET " .
            "usuarioId = :usuarioId, tipo = :tipo, descripcion = :desc, color = :color, talla = :talla " .
            "WHERE prendaId = :id";

        $prendaId = $args['prendaId'] ?? -1;
        $usuarioId = $args['usuarioId'] ?? -1;
        $tipo = $args['tipo'] ?? '';
        $descripcion = $args['descripcion'] ?? '';
        $color = $args['color'] ?? '';
        $talla = $args['talla'] ?? '';

        //usuarioId requerido
        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): valor de usuarioId no reconocido");
        }

        if ($prendaId < 0 || gettype($prendaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): valor de prendaId no reconocido");
        }

        //prendaIdrequerido

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_updatePrenda);
        $exito = $stmt->execute([
            ":usuarioId" => $usuarioId,
            ":tipo" => $tipo,
            ":desc" => $descripcion,
            ":color" => $color,
            ":talla" => $talla
        ]);

        return $exito;
    }

    // ---DELETE---

    /**
     * deletePrenda($args)
     * recibe prendaId para eliminar los datos de la prenda
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - prendaId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deletePrenda($args)
    {
        $q_deletePrenda = "DELETE FROM prendas WHERE prendaId = :id";

        $prendaId = $args['prendaId'] ?? -1;

        if ($prendaId < 0 || gettype($prendaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): valor de prendaId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (PRENDAS): no se ha podido establecer conexion BBDD");
        }


        $stmn = $conexion->prepare($q_deletePrenda);

        $exito = $stmn->execute([":id" => $prendaId]);

        return $exito;
    }
}
