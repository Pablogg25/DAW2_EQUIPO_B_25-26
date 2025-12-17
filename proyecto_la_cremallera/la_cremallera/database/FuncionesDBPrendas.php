<?php

namespace la_cremallera\database;

require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBPrendas
{
    // ---READ---
    final public static function getPrendas()
    {
        //obtener todas las prendas de la base de datos
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: no se ha podido establecer conexion BBDD");
        }

        $comandoSql = "SELECT * FROM prendas";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    final public static function getPrendasByUsuarioId($args)
    {
        //obtener todas las prendas de un usuario

        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: no se ha podido establecer conexion BBDD");
        }

        $comandoSql = "SELECT * FROM prendas WHERE usuarioId = :id";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute([
            ":id" => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ---CREATE---

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

        if ($usuarioId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_insertPrenda);
        $stmt->execute([
            ":id" => $usuarioId,
            ":tipo" => $tipo,
            ":desc" => $descripcion,
            ":color" => $color,
            ":talla" => $talla
        ]);
    }


    // ---UPDATE---


    // ---DELETE---
}
