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
     * Columnas:
     * - facturaId
     * - usuarioId FK
     * - fecha
     * - pagado
     * - total_calculado
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
     * Obtiene los datos de una factura por id
     * 
     * $args:
     * - facturaId (requerido)
     * 
     * Columnas:
     * - facturaId
     * - usuarioId FK
     * - fecha
     * - pagado
     * - total_calculado
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
     * - usuarioId (requerido, FK)
     * 
     * Columnas:
     * - facturaId
     * - usuarioId FK
     * - fecha
     * - pagado
     * - total_calculado
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

    /**
     * getItemsFactura($args)
     * Obtiene los datos de los trabajos asociados a un id de facturaId en factura_trabajos
     * 
     * $args:
     * - facturaId (requerido)
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
    final public static function getItemsFactura($args)
    {
        $q_selectItems = "SELECT * FROM trabajos t RIGHT JOIN factura_trabajo ft on t.trabajoId=ft.trabajoId WHERE ft.facturaId = :id";

        $facturaId = $args['facturaId'] ?? -1;

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de facturaId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_selectItems);
        $stmt->execute([":id" => $facturaId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getFacturasByTrabajoId($args)
     * Obtiene los datos de las facturas asociadas a un trabajo indicado por trabajoId
     * 
     * $args:
     * - trabajoId (requerido,FK)
     * 
     * Columnas:
     * - facturaId
     * - usuarioId FK
     * - fecha
     * - pagado
     * - total_calculado
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    public static function getFacturasByTrabajoId($args)
    {
        $q_selectItems = "SELECT * FROM facturas f RIGHT JOIN factura_trabajo ft on f.facturaId=ft.facturaId WHERE ft.trabajoId = :id";

        $trabajoId = $args['trabajoId'] ?? -1;

        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de trabajoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_selectItems);
        $stmt->execute([":id" => $trabajoId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getTotalFactura($args)
     * calcula la suma total de los items asociados a una factura en base a la suma total de los trabajos asociados.
     * 
     * $args:
     * - facturaId (requerido)
     * 
     * returns: float
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getTotalFactura($args)
    {
        $q_totalFacturaItems = "SELECT sum(t.precio) as total FROM trabajos t RIGHT JOIN factura_trabajo ft ON t.trabajoId=ft.trabajoId WHERE ft.facturaId= :id";

        $facturaId = $args['facturaId'] ?? -1;

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de facturaId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_totalFacturaItems);
        $stmt->execute([":id" => $facturaId]);

        $result = $stmt->fetch(PDO::FETCH_COLUMN);

        return $result['total']??0;
    }

    // ---CREATE---

    /**
     * insertFactura($args)
     * Recibe parametros para insertar una factura, el campo pagada se inicia a false, 
     * el campo "total_calculado" puede calcularse después de forma automática"
     * 
     * $args:
     * - usuarioId (requerido, FK)
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

    /**
     * asociarFacturaTrabajo($args)
     * Asocia en la tabla factura_trabajo una factura y un trabajo como su item
     * 
     * $args:
     * - facturaId (requerido, FK)
     * - trabajoId (requerido, FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function asociarFacturaTrabajo($args)
    {
        $q_insertFacturaTrabajo = "INSERT INTO factura_trabajo (factura_id,trabajo_id) VALUES (:fid,:tid)";

        $facturaId = $args['facturaId'] ?? -1;
        $trabajoId = $args['trabajoId'] ?? -1;

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor facturaId no reconocido ");
        }

        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de trabajoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertFacturaTrabajo);

        $success = $stmn->execute([
            ':fid' => $facturaId,
            ':tid' => $trabajoId
        ]);

        return $success;
    }

    // ---UPDATE---

    /**
     * updateFactura($args)
     * Actualiza los datos de una factura por el id indicado
     * 
     * $args:
     * - facturaId (requerido)
     * - usuarioId (requerido, FK)
     * - fecha (requerido)
     * - pagado, default 0
     * - total_calculado, default null
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateFactura($args)
    {
        $q_updateFactura = "UPDATE facturas SET usuarioId = :usuarioId, fecha= :fecha, pagado= :pagado, total_calculado = :tc WHERE facturaId = :id";

        $facturaId = $args['facturaId'] ?? -1;

        $usuarioId = $args['usuarioId'] ?? -1;
        $fecha = $args['fecha'] ?? '';
        $pagado = $args['pagado'] ?? 0;
        $totalCalculado = $args['total_calculado'] ?? 'null';

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de facturaId no reconocido");
        }

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de usuarioId no reconocido");
        }

        if ($fecha == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): el campo fecha es requerido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateFactura);

        $success = $stmn->execute([
            ':id' => $facturaId,
            ':usuarioId' => $usuarioId,
            ':fecha' => $fecha,
            ':pagado' => $pagado,
            ':total_calculado' => $totalCalculado
        ]);

        return $success;
    }

    // ---DELETE---

    /**
     * deleteFactura($args)
     * elimina una factura con el id pasado
     * 
     * $args:
     * - facturaId (requerido)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteFactura($args)
    {
        $q_deleteFactura = "DELETE FROM facturas WHERE facturaId = :id";

        $facturaId = $args['facturaId'] ?? -1;

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de facturaId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_deleteFactura);

        $success = $stmn->execute([
            ':id' => $facturaId,
        ]);

        return $success;
    }

    /**
     * desasociarFacturaTrabajo($args)
     * Elimina la asociación en la tabla factura_trabajo de un item a una factura pasando sus pares de ids
     * 
     * $args:
     * - facturaId (requerido,FK)
     * - trabajoId (requerido,FK)
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function desasociarFacturaTrabajo($args)
    {
        $q_deleteFacturaTrabajo = "DELETE FROM factura_trabajo WHERE facturaId = :fid AND trabajoId = :tid";

        $facturaId = $args['facturaId'] ?? -1;
        $trabajoId = $args['trabajoId'] ?? -1;

        if ($facturaId < 0 || gettype($facturaId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor facturaId no reconocido ");
        }

        if ($trabajoId < 0 || gettype($trabajoId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): valor de trabajoId no reconocido");
        }

        $conexion = ConexionBD::getConnection();
        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (FACTURAS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_deleteFacturaTrabajo);

        $success = $stmn->execute([
            ':fid' => $facturaId,
            ':tid' => $trabajoId
        ]);

        return $success;
    }
}
