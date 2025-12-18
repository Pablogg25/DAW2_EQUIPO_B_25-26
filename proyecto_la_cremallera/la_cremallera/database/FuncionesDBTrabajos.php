<?php

namespace la_cremallera\database;


require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBTrabajos
{

    // ---READ---
    final public static function getTrabajos()
    {
        $q_selectTrabajos = "SELECT * FROM trabajos";

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectTrabajos);
        $stmn->execute();

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getTrabajosByEmpleadoId($args)
     * recibe un empleadoId y devuelve los datos filas con ese empleadoId
     * 
     * Requiere empleadoId
     * Gestionar excepciones en negocio del endpoint.
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getTrabajosByEmpleadoId($args)
    {
        $q_selectTrabajosEmpleado = "SELECT * FROM trabajos WHERE empleadoId= :id";

        //requerido empleadoId
        $empleadoId = $args['empleadoId'] ?? -1;

        if ($empleadoId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): empleadoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectTrabajosEmpleado);
        $stmn->execute([":id" => $empleadoId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getTrabajosByUsuarioId($args)
     * recibe un usuarioId y devuelve los datos de trabajos cuyas prendas en tabla prendas tengan ese usuarioId
     * 
     * requiere usuarioId
     * Gestionar excepciones en negocio del endpoint.
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getTrabajosByUsuarioId($args)
    {
        $q_selectTrabajosEmpleado = "SELECT * FROM trabajos t LEFT JOIN prendas p ON t.prendaId = p.prendaId WHERE p.usuaruioId= :id";

        //requerido usuarioId
        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectTrabajosEmpleado);
        $stmn->execute([":id" => $usuarioId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- CREATE---
    /**
     * createTrabajo($args)
     * recibe los datos de trabajo para insertar en la base de datos.
     * 
     * Requiere prendaId, fecha_inicio, fecha_entrega.
     * Gestionar excepciones en negocio del endpoint.
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function createTrabajo($args)
    {
        $q_insertTrabajo = "INSERT INTO trabajos (prendaId,empleadoId,descripcion,fecha_inicio,fecha_entrega,estado,precio) VALUES " .
            "(:prenda,:empleado,:descripcion,:fecha_i,:fecha_e,:estado,:precio)";

        //estado es enum 'pendiente','en_proceso','listo','entregado'
        //las fechas son no null
        //prendaId es no null

        $prendaId = $args['prendaId'] ?? -1;
        $fecha_i = $args['fecha_inicio'] ?? '';
        $fecha_e = $args['fecha_entrega'] ?? '';

        $empleadoId = $args['empleadoId'] ?? '';
        $descripcion = $args['descripcion'] ?? '';
        $estado = $args['estado'] ?? 'pendiente';
        $precio = $args['precio'] ?? 0;

        if ($prendaId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): prendaId no reconocido");
        }

        if ($fecha_i == '' | $fecha_e == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): fecha de inicio y fecha de entrega son campos requeridos");
        }

        if ($estado != 'pendiente' && $estado != 'en_proceso' && $estado != 'listo' && $estado != 'entregado') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor incorrecto en campo enumerado estado");
        }

        if($empleadoId!='' && $empleadoId<0){
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): empleadoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertTrabajo);
        $exito=$stmn->execute([
            ":prenda"=>$prendaId,
            ":empleado"=>$empleadoId,
            ":descripcion"=>$descripcion,
            ":fecha_i"=>$fecha_i,
            ":fecha_e"=>$fecha_e,
            ":estado"=>$estado,
            ":precio"=>$precio
        ]);

        return $exito;
    }

    // ---UPDATE---

    /**
     * updateTrabajo($args)
     * Actualiza los datos de un trabajo por trabajoId.
     * 
     * Requiere trabajoId, prendaId, fecha_inicio, fecha_entrega.
     * Gestionar excepciones en negocio del endpoint.
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateTrabajo($args){
        $q_updateTrabajo="UPDATE trabajos SET ".
        "prendaId = :prenda, empleadoId = :empleado, descripcion = :descr, ".
        "fecha_inicio = :fecha_i, fecha_entrega = :fecha_e, estado = :estado, precio = :precio ".
        "WHERE trabajoId = :id";

        
        //estado es enum 'pendiente','en_proceso','listo','entregado'
        //las fechas son no null
        //prendaId es no null

        $trabajoId=$args['trabajoId']??-1;

        if($trabajoId<0){
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): trabajoId es un campo requerido");
        }

        $prendaId = $args['prendaId'] ?? -1;
        $fecha_i = $args['fecha_inicio'] ?? '';
        $fecha_e = $args['fecha_entrega'] ?? '';

        $empleadoId = $args['empleadoId'] ?? '';
        $descripcion = $args['descripcion'] ?? '';
        $estado = $args['estado'] ?? 'pendiente';
        $precio = $args['precio'] ?? 0;

        if ($prendaId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): prendaId no reconocido");
        }

        if ($fecha_i == '' | $fecha_e == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): fecha de inicio y fecha de entrega son campos requeridos");
        }

        if ($estado != 'pendiente' && $estado != 'en_proceso' && $estado != 'listo' && $estado != 'entregado') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor incorrecto en campo enumerado estado");
        }

        if($empleadoId!='' && $empleadoId<0){
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): empleadoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateTrabajo);
        $exito=$stmn->execute([
            ":prenda"=>$prendaId,
            ":empleado"=>$empleadoId,
            ":descripcion"=>$descripcion,
            ":fecha_i"=>$fecha_i,
            ":fecha_e"=>$fecha_e,
            ":estado"=>$estado,
            ":precio"=>$precio,
            ":id"=>$trabajoId
        ]);

        return $exito;
    }

    // ---DELETE---
    final public static function deleteTrabajo($args){
        $q_deleteTrabajo="DELETE FROM trabajos WHERE trabajoId = :id";

        $trabajoId=$args['trabajoId']??-1;

        if($trabajoId<0){
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): trabajoId no reconocido");
        }

        
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn=$conexion->prepare($q_deleteTrabajo);
        $exito=$stmn->execute([":id"=>$trabajoId]);

        return $exito;
    }
}
