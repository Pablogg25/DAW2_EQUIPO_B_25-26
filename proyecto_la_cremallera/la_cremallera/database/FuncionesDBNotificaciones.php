<?php

namespace la_cremallera\database;


require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBNotificaciones
{

    // ---READ---

    /**
     * getNotificaciones()
     * Obtiene todos los datos de la tabla notificaciones
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getNotificaciones()
    {
        $q_selectNotificaciones = "SELECT * FROM notificaciones";

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectNotificaciones);
        $stmn->execute();

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getNotificacionesByReceptor($args)
     * Obtiene losdatos de las notificaciones recibidas por el usuario indicado por id
     * 
     * $args:
     * - receptorId (requerido usuarioId, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getNotificacionesByReceptor($args)
    {
        $q_selectNotificaciones = "SELECT * FROM notificaciones WHERE receptorId = :id";

        $receptorId = $args['receptorId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($receptorId < 0 || gettype($receptorId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de receptorId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectNotificaciones);
        $stmn->execute([
            ":id" => $receptorId
        ]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getNotificacionesByRemitente($args)
     * Obtiene losdatos de las notificaciones neviadaspor un usuario indicado por el id
     * 
     * $args:
     * - remitenteId (requerido usuarioId, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getNotificacionesByRemitente($args)
    {
        $q_selectNotificaciones = "SELECT * FROM notificaciones WHERE remitenteId = :id";

        $remitenteId = $args['remitenteId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($remitenteId < 0 || gettype($remitenteId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de remitenteId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectNotificaciones);
        $stmn->execute([
            ":id" => $remitenteId
        ]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getNotificacionesByTrabajoId($args)
     * Obtiene los datos de las notificaciones enviadas en relación aun trabajo
     * 
     * $args:
     * - trabajoId (requerido, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getNotificacionesByTrabajoId($args)
    {
        $q_selectNotificaciones = "SELECT * FROM notificaciones WHERE trabajoId = :id";

        $trabajoId = $args['trabajoId'] ?? -1;

        //controla que el valor del id sea correcto
        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de trabajoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_selectNotificaciones);
        $stmn->execute([
            ":id" => $trabajoId
        ]);

        return $stmn->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---CREATE---

    /**
     * insertNotificacion($args)
     * inserta los datos de una notificación nueva en la tabla notificaciones
     * 
     * $args:
     * - receptorId (requerido, FK)
     * - remitenteId (requerido, FK)
     * - trabajoId (FK, default null)
     * - tipo (enum: 'recordatorio_entrega', 'trabajo_listo', 'factura_generada', 'notificacion'; default 'notificacion')
     * - asunto
     * - mensaje (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function insertNotificacion($args)
    {
        $q_insertNotificacion = "INSERT INTO notificaciones (receptorId,remitenteId, trabajoId, tipo, asunto, mensaje) VALUES" .
            "(:receptor, :remitente, :trabajoId, :tipo, :asunto, :mensaje)";

        $receptorId = $args['receptorId'] ?? -1;
        $remitenteId = $args['remitenteId'] ?? -1;
        $trabajoId = $args['trabajoId'] ?? 'null';
        //'recordatorio_entrega','trabajo_listo','factura_generada','notificacion'
        $tipo = $args['tipo'] ?? 'notificacion';
        $mensaje = $args['mensaje'] ?? '';
        $asunto = $args['asunto'] ?? '';

        //controla que el valor del id sea correcto
        if ($receptorId < 0 || gettype($receptorId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de receptorId no reconocido");
        }

        //controla que el valor del id sea correcto
        if ($remitenteId < 0 || gettype($remitenteId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de remitenteId no reconocido");
        }

        //controla que el valor del id sea correcto
        if ($trabajoId != 'null') {
            //valor opcional
            if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
                throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de remitenteId no reconocido");
            }
        }

        //campo enumerado
        if ($tipo != 'notificacion' && $tipo != 'recordatorio_entrega' && $tipo != 'trabajo_listo' && $tipo != 'factura_generada') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor incorrecto en campo enumerado tipo");
        }

        //requerido
        if ($mensaje == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): el campo mensaje es requerido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertNotificacion);

        $success = $stmn->execute([
            ':receptor' => $receptorId,
            ':remitente' => $remitenteId,
            ':trabajoId' => $trabajoId,
            ':tipo' => $tipo,
            ':asunto' => $asunto,
            ':mensaje' => $mensaje
        ]);

        return $success;
    }


    // ---UPDATE---

    /**
     * updateMensaje($args)
     * Actualiza los datos de una notificación cuyo id se indique
     * 
     * $args:
     * - notificacionId (requerido)
     * - receptorId (requerido, FK)
     * - remitenteId (requerido, FK)
     * - trabajoId (FK, default null)
     * - tipo (enum: 'recordatorio_entrega', 'trabajo_listo', 'factura_generada', 'notificacion'; default 'notificacion')
     * - asunto
     * - mensaje (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateMensaje($args){
        $q_updateNotificacion = "UPDATE notificaciones SET receptorId = :receptor, remitenteId = :remitente,".
        " trabajoId = :trabajoId,".
        " tipo = :tipo,".
        " asunto = :asunto,".
        " mensaje = :mensaje) WHERE notificacionId = :id";

        $notificacionId=$args['notificacionId']??-1;

        if ($notificacionId < 0 || gettype($notificacionId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de notificacionId no reconocido");
        }
        
        $receptorId = $args['receptorId'] ?? -1;
        $remitenteId = $args['remitenteId'] ?? -1;
        $trabajoId = $args['trabajoId'] ?? 'null';
        //'recordatorio_entrega','trabajo_listo','factura_generada','notificacion'
        $tipo = $args['tipo'] ?? 'notificacion';
        $mensaje = $args['mensaje'] ?? '';
        $asunto = $args['asunto'] ?? '';

        //controla que el valor del id sea correcto
        if ($receptorId < 0 || gettype($receptorId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de receptorId no reconocido");
        }

        //controla que el valor del id sea correcto
        if ($remitenteId < 0 || gettype($remitenteId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de remitenteId no reconocido");
        }

        //controla que el valor del id sea correcto
        if ($trabajoId != 'null') {
            //valor opcional
            if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
                throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de remitenteId no reconocido");
            }
        }

        //campo enumerado
        if ($tipo != 'notificacion' && $tipo != 'recordatorio_entrega' && $tipo != 'trabajo_listo' && $tipo != 'factura_generada') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor incorrecto en campo enumerado tipo");
        }

        //requerido
        if ($mensaje == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): el campo mensaje es requerido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateNotificacion);

        $success = $stmn->execute([
            ':receptor' => $receptorId,
            ':remitente' => $remitenteId,
            ':trabajoId' => $trabajoId,
            ':tipo' => $tipo,
            ':asunto' => $asunto,
            ':mensaje' => $mensaje,
            ':id'=>$notificacionId
        ]);

        return $success;
    }


    // ---DELETE---

    /**
     * deleteNotificacion($args)
     * Elimina una notificación en la base de datos pasando su id
     * 
     * $args:
     * - notificacionId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteNotificacion($args){
        $q_deleteNotificacion="DELETE FROM notificaciones WHERE notificacionId = :id";

        $notificacionId=$args['notificacionId']??-1;

        if ($notificacionId < 0 || gettype($notificacionId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (NOTIFICACIONES): valor de notificacionId no reconocido");
        }

        
        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_deleteNotificacion);

        $success = $stmn->execute([
            ':id' => $notificacionId,
        ]);

        return $success;
    }

}
