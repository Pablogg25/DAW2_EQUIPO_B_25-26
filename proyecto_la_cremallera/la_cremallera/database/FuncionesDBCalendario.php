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

    /**
     * getEventosByUsuarioId($args)
     * Obtiene todos los datos de eventos del calendario que pertenezcan al usuario cuyo id se indique
     * 
     * $args:
     * - usuarioId (requerido, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getEventosByUsuarioId($args)
    {
        $q_selectEventos = "SELECT * FROM calendario WHERE usuarioId = :id";

        $usuarioId = $args['usuarioId'] ?? -1;
        //controla que el valor del id sea correcto
        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de usuarioId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectEventos);
        $stmn->execute([':id' => $usuarioId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getEventosByEmpleadoId($args)
     * Obtiene todos los datos de eventos del calendario en los que el empleado indicado esté asociado
     * 
     * $args:
     * - empleadoId (requerido, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getEventosByEmpleadoId($args)
    {
        $q_selectEventos = "SELECT * FROM calendario WHERE empleadoId = :id";

        $empleadoId = $args['empleadoId'] ?? -1;
        //controla que el valor del id sea correcto
        if ($empleadoId < 0 || gettype($empleadoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de empleadoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectEventos);
        $stmn->execute([':id' => $empleadoId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getEventosByTrabajo($args)
     * Obtiene todos los datos de eventos del calendario en los que el trabajo asociado se indique
     * 
     * $args:
     * - trabajoId (requerido, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getEventosByTrabajo($args)
    {
        $q_selectEventos = "SELECT * FROM calendario WHERE trabajoId = :id";

        $trabajoId = $args['trabajoId'] ?? -1;
        //controla que el valor del id sea correcto
        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de trabajoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectEventos);
        $stmn->execute([':id' => $trabajoId]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---CREATE---

    /**
     * insertEvento($args)
     * Recibe parametros para insertar un evento en el calendario
     * 
     * $args:
     * - titulo (requerido)
     * - descripcion
     * - fecha_inicio (requerido)
     * - fecha_fin (requerido)
     * - usuarioId (requerido, FK)
     * - empleadoId (opcional, FK)
     * - trabajoId (opcional, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function insertEvento($args)
    {
        $q_insertEvento = "INSERT INTO calendario (titulo, descripcion, fecha_inicio, fecha_fin, usuarioId, empleadoId, trabajoId) VALUES " .
            "(:titulo, :descripcion, :fecha_inicio, :fecha_fin, :usuarioId, :empleadoId, :trabajoId)";

        $titulo = $args['titulo'] ?? ''; //req
        $descripcion = $args['descripcion'] ?? '';
        $fecha_inicio = $args['fecha_inicio'] ?? ''; //req
        $fecha_fin = $args['fecha_fin'] ?? ''; //req
        $usuarioId = $args['usuarioId'] ?? -1; //req
        $empleadoId = $args['empleadoId'] ?? 'null';
        $trabajoId = $args['trabajoId'] ?? 'null';

        if ($titulo == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): el campo titulo es requerido");
        }

        if ($fecha_inicio == '' || $fecha_fin == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): los campos fecha_inicio y fecha_fin son requeridos");
        }

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de usuarioId no reconocido");
        }

        if ($empleadoId != 'null') {
            if ($empleadoId < 0 || gettype($empleadoId) != 'integer') {
                throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de empleadoId no reconocido");
            }
        }

        if ($trabajoId != 'null') {
            if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
                throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de trabajoId no reconocido");
            }
        }


        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        //(:titulo, :descripcion, :fecha_inicio, :fecha_fin, :usuarioId, :empleadoId, :trabajoId)
        $stmn = $conexion->prepare($q_insertEvento);
        $exito = $stmn->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha_incio' => $fecha_inicio,
            ':fecha_fin' => $fecha_inicio,
            ':usuarioId' => $usuarioId,
            ':empleadoId' => $empleadoId,
            ':trabajoId' => $trabajoId
        ]);

        return $exito;
    }

    // ---UPDATE---

    /**
     * insertEvento($args)
     * Recibe parametros para actualizar un evento cuyo id se indique
     * 
     * $args:
     * - eventoId (requerido)
     * - titulo (requerido)
     * - descripcion
     * - fecha_inicio (requerido)
     * - fecha_fin (requerido)
     * - usuarioId (requerido, FK)
     * - empleadoId (opcional, FK)
     * - trabajoId (opcional, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateEvento($args)
    {
        $q_updateEvento = "UPDATE calendario SET " .
            "titulo = :titulo, descripcion = :descripcion, fecha_inicio= :fecha_inicio, fecha_fin = :fecha_fin," .
            " usuarioId = :usuarioId, empleadoId = :empleadoId, trabajoId = :trabajoId WHERE eventoId = :id";

        $eventoId = $args['eventoId'] ?? -1;

        if ($eventoId < 0 || gettype($eventoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de eventoId no reconocido");
        }

        $titulo = $args['titulo'] ?? ''; //req
        $descripcion = $args['descripcion'] ?? '';
        $fecha_inicio = $args['fecha_inicio'] ?? ''; //req
        $fecha_fin = $args['fecha_fin'] ?? ''; //req
        $usuarioId = $args['usuarioId'] ?? -1; //req
        $empleadoId = $args['empleadoId'] ?? 'null';
        $trabajoId = $args['trabajoId'] ?? 'null';

        if ($titulo == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): el campo titulo es requerido");
        }

        if ($fecha_inicio == '' || $fecha_fin == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): los campos fecha_inicio y fecha_fin son requeridos");
        }

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de usuarioId no reconocido");
        }

        if ($empleadoId != 'null') {
            if ($empleadoId < 0 || gettype($empleadoId) != 'integer') {
                throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de empleadoId no reconocido");
            }
        }

        if ($trabajoId != 'null') {
            if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
                throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de trabajoId no reconocido");
            }
        }


        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        //(:titulo, :descripcion, :fecha_inicio, :fecha_fin, :usuarioId, :empleadoId, :trabajoId)
        $stmn = $conexion->prepare($q_updateEvento);
        $exito = $stmn->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha_incio' => $fecha_inicio,
            ':fecha_fin' => $fecha_inicio,
            ':usuarioId' => $usuarioId,
            ':empleadoId' => $empleadoId,
            ':trabajoId' => $trabajoId,
            ':id' => $eventoId
        ]);

        return $exito;
    }

    // ---DELETE---

    /**
     * deleteEvento($args)
     * Elimina un evento en la tabla calendario cuyo id se indique
     * 
     * $args:
     * - eventoId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteEvento($args){
        $q_deleteEvento="DELETE FROM calendario WHERE eventoId = :id";

        $eventoId = $args['eventoId'] ?? -1;

        if ($eventoId < 0 || gettype($eventoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): valor de eventoId no reconocido");
        }

        
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (CALENDARIO): no se ha podido establecer conexion BBDD");
        }

        //(:titulo, :descripcion, :fecha_inicio, :fecha_fin, :usuarioId, :empleadoId, :trabajoId)
        $stmn = $conexion->prepare($q_deleteEvento);
        $exito = $stmn->execute([
            ':id' => $eventoId
        ]);

        return $exito;
    }
}
