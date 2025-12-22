<?php

namespace la_cremallera\database;


require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionDB;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBTrabajos
{

    // ---READ---
    /**
     * getTrabajos()
     * Obtiene todos los datos de la tabla trabajos
     * 
     * Columnas:
     * - trabajoId
     * - prendaId (FK)
     * - empleadoId (FK)
     * - descripcion 
     * - fecha_inicio
     * - fecha_entrega
     * - estado
     * - precio
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getTrabajos()
    {
        $q_selectTrabajos = "SELECT * FROM trabajos";

        $conexion = ConexionDB::getConnection();

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
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - empleadoId (requerido)
     * 
     * Columnas:
     * - trabajoId
     * - prendaId (FK)
     * - empleadoId (FK)
     * - descripcion 
     * - fecha_inicio
     * - fecha_entrega
     * - estado
     * - precio
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

        //controla que el valor del id sea correcto
        if ($empleadoId < 0 || gettype($empleadoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de empleadoId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

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
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - usuarioId (requerido)
     * 
     * Columnas:
     * - trabajoId
     * - prendaId (FK)
     * - empleadoId (FK)
     * - descripcion 
     * - fecha_inicio
     * - fecha_entrega
     * - estado
     * - precio
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getTrabajosByUsuarioId($args)
    {
        $q_selectTrabajosEmpleado = "SELECT * FROM trabajos t LEFT JOIN prendas p ON t.prendaId = p.prendaId WHERE p.usuarioId= :id";

        //requerido usuarioId
        $usuarioId = $args['usuarioId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de usuarioId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectTrabajosEmpleado);
        $stmn->execute([":id" => $usuarioId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    // getConsumos

    /**
     * getConsumosTrabajo($args)
     * Recibe un id de trabajoy muestra sus consumos asociados
     * 
     * $args:
     * - trabajoId (required, FK)
     * 
     * Columnas:
     * - trabajoId (FK)
     * - itemId (FK)
     * - cantidad_usada
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getConsumosTrabajo($args){
        $q_selectConsumos="SELECT * FROM consumos_trabajo WHERE trabajoId = :id";
        
        //requerido usuarioId
        $trabajoId = $args['trabajoId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de trabajoId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectConsumos);
        $stmn->execute([":id" => $trabajoId]);

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
     * args:
     * - prendaId (requerido)
     * - empleadoId
     * - descripcion
     * - fecha_inicio (requerido)
     * - fecha_entrega (requerido)
     * - estado (enum: 'pendiente','en_proceso','listo','entregado')
     * - precio
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

        //controla que el valor del id sea correcto
        if ($prendaId < 0 || gettype($prendaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de prendaId no reconocido");
        }

        if ($fecha_i == '' || $fecha_e == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): fecha de inicio y fecha de entrega son campos requeridos");
        }

        if ($estado != 'pendiente' && $estado != 'en_proceso' && $estado != 'listo' && $estado != 'entregado') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor incorrecto en campo enumerado estado");
        }

        if ($empleadoId != '') {
            //controla que el valor del id sea correcto
            if (gettype($empleadoId) != 'integer' && $empleadoId < 0) {
                throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): empleadoId no reconocido");
            }
        }

        //controla que el valor del precio sea correcto
        if ($precio<0||(gettype($precio) != 'double' && gettype($precio) != 'integer')) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de precio no válido: se espera numero decimal positivo");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertTrabajo);
        $exito = $stmn->execute([
            ":prenda" => $prendaId,
            ":empleado" => $empleadoId,
            ":descripcion" => $descripcion,
            ":fecha_i" => $fecha_i,
            ":fecha_e" => $fecha_e,
            ":estado" => $estado,
            ":precio" => $precio
        ]);

        return $exito;
    }

    /**
     * asociarConsumo($args)
     * Crea una entrada en la tabla de consumos_trabajo con la cantidad de material consumida
     * 
     * $args:
     * - trabajoId (requerido, FK)
     * - itemId (requerido, FK)
     * - cantidad (default 0)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function asociarConsumo($args){
        $q_insertConsumo="INSERT INTO consumos_trabajo (trabajo_id,itemId,cantidad_usada) VALUES ".
        "(:trabajo,:item,:cantidad)";

        $trabajoid=$args['trabajoId']??-1;
        $itemId=$args['itemId']??-1;
        $cantidad=$args['cantidad']??0;

        if ($trabajoid < 0 || gettype($trabajoid) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de trabajoid no reconocido");
        }

        if ($itemId < 0 || gettype($itemId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de itemId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertConsumo);
        $exito = $stmn->execute([
            ":trabajo" => $trabajoid,
            ":item" => $itemId,
            ":cantidad" => $cantidad
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
     * args:
     * - trabajoId (requerido)
     * - prendaId (requerido)
     * - empleadoId
     * - descripcion
     * - fecha_inicio (requerido)
     * - fecha_entrega (requerido)
     * - estado (enum: 'pendiente','en_proceso','listo','entregado')
     * - precio
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateTrabajo($args)
    {
        $q_updateTrabajo = "UPDATE trabajos SET " .
            "prendaId = :prenda, empleadoId = :empleado, descripcion = :descr, " .
            "fecha_inicio = :fecha_i, fecha_entrega = :fecha_e, estado = :estado, precio = :precio " .
            "WHERE trabajoId = :id";


        //estado es enum 'pendiente','en_proceso','listo','entregado'
        //las fechas son no null
        //prendaId es no null

        $trabajoId = $args['trabajoId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de trabajoId no reconocido");
        }

        $prendaId = $args['prendaId'] ?? -1;
        $fecha_i = $args['fecha_inicio'] ?? '';
        $fecha_e = $args['fecha_entrega'] ?? '';

        $empleadoId = $args['empleadoId'] ?? '';
        $descripcion = $args['descripcion'] ?? '';
        $estado = $args['estado'] ?? 'pendiente';
        $precio = $args['precio'] ?? 0;

        //controla que el valor del id sea correcto
        if ($prendaId < 0 || gettype($prendaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de prendaId no reconocido");
        }

        if ($fecha_i == '' | $fecha_e == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): fecha de inicio y fecha de entrega son campos requeridos");
        }

        if ($estado != 'pendiente' && $estado != 'en_proceso' && $estado != 'listo' && $estado != 'entregado') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor incorrecto en campo enumerado estado");
        }

        if ($empleadoId != '') {
            //controla que el valor del id sea correcto
            if (gettype($empleadoId) != 'integer' && $empleadoId < 0) {
                throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): empleadoId no reconocido");
            }
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateTrabajo);
        $exito = $stmn->execute([
            ":prenda" => $prendaId,
            ":empleado" => $empleadoId,
            ":descripcion" => $descripcion,
            ":fecha_i" => $fecha_i,
            ":fecha_e" => $fecha_e,
            ":estado" => $estado,
            ":precio" => $precio,
            ":id" => $trabajoId
        ]);

        return $exito;
    }

    /**
     * updateConsumo($args)
     * actualiza la cantidad consumida en la tabla de consumos_trabajo con el par de ids aportados
     * 
     * $args:
     * - trabajoId (requerido, FK)
     * - itemId (requerido, FK)
     * - cantidad (default 0)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateConsumo($args){
        $q_updateConsumo="UPDATE consumos_trabajo SET cantidad = :cantidad WHERE trabajoId = :trabajo AND itemId = :item";

        $trabajoid=$args['trabajoId']??-1;
        $itemId=$args['itemId']??-1;
        $cantidad=$args['cantidad']??0;

        if ($trabajoid < 0 || gettype($trabajoid) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de trabajoid no reconocido");
        }

        if ($itemId < 0 || gettype($itemId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de itemId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateConsumo);
        $exito = $stmn->execute([
            ":trabajo" => $trabajoid,
            ":item" => $itemId,
            ":cantidad" => $cantidad
        ]);

        return $exito;
    }

    // ---DELETE---

    /**
     * deleteTrabajo($args)
     * Elimina un trabajo por trabajoId
     * 
     * $args:
     * - trabajoId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteTrabajo($args)
    {
        $q_deleteTrabajo = "DELETE FROM trabajos WHERE trabajoId = :id";

        $trabajoId = $args['trabajoId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): trabajoId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_deleteTrabajo);
        $exito = $stmn->execute([":id" => $trabajoId]);

        return $exito;
    }

    /**
     * deleteConsumo($args)
     * Elimina la asociación del consumo de un objeto por el trabajo y su cantidad empleada
     * 
     * $args:
     * - trabajoId (requerido, FK)
     * - itemId (requerido, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteConsumo($args){
        $q_updateConsumo="DELETE FROM consumos_trabajo WHERE trabajoId = :trabajo AND itemId = :item";

        $trabajoid=$args['trabajoId']??-1;
        $itemId=$args['itemId']??-1;

        if ($trabajoid < 0 || gettype($trabajoid) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de trabajoid no reconocido");
        }

        if ($itemId < 0 || gettype($itemId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): valor de itemId no reconocido");
        }

        $conexion = ConexionDB::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (TRABAJOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateConsumo);
        $exito = $stmn->execute([
            ":trabajo" => $trabajoid,
            ":item" => $itemId
        ]);

        return $exito;
    }
}
