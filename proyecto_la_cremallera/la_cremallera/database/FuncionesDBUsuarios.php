<?php

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;

require_once __DIR__ . '/ConexionDB.php';

use PDO;
use PDOException;

final class FuncionesDBUsuarios
{

    // --- READ ---
    final public static function getUsuarios()
    {
        //obtener todos los usuarios de la base de datos
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: no se ha podido establecer conexion BBDD");
        }

        $comandoSql = "SELECT * FROM usuarios";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * validatePassword($args)
     * valida el password de un username para comprobar si el password pasado es igual que el de los argumentos de forma segura
     * requiere username y password a comporbar
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function checkPassword($args)
    {
        $q_checkPassword = "SELECT count(*) as found FROM usuarios WHERE username == :nombre AND password_hash == SHA2(:password_h,224)";

        $contrasena = $args['password'] ?? '';
        $username = $args['username'] ?? '';

        if ($contrasena == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB: Se requiere rellenar el campo contraseña");
        }

        if ($username == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB: Se requiere rellenar el campo username");
        }


        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_checkPassword);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_COLUMN);

        if ($result['found'] != 0) {
            return true;
        } else {
            return false;
        }
    }

    // --- CREATE ---
    /** 
     * registrarUsuario($args)
     * Requiere nombre, email, username, y password
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function registrarUsuario($args): bool
    {

        $q_insertUsuario = "INSERT INTO usuarios" .
            "(nombre,telefono,email,direccion,username,password_hash,rol) VALUES" .
            "(:nombre,:telefono,:email,:direccion,:username,SHA2(:password_h,224),'cliente')";

        //obligatorios: nombre, usuario, password, email
        $nombre = $args['nombre'] ?? '';
        $username = $args['username'] ?? '';
        $password = $args['password'] ?? '';
        $email = $args['email'] ?? '';

        //no required
        $telefono = $args['telefono'] ?? '';
        $direccion = $args['direccion'] ?? '';

        if ($nombre == '' || $username == '' || $password == '' || $email == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB: Se requiere nombre, nombre de usuario, contraseña y correo electrónico válidos");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD: no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertUsuario);

        $success = $stmn->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':email' => $email,
            ':direccion' => $direccion,
            ':username' => $username,
            ':password_h' => $password
        ]);

        return $success;
    }

    // --- UPDATE ---

    /**
     * updateDatosUsuario($args)
     * requiere usuarioId objetivo
     * actualiza los datos personales del usuario
     * telefono, email, dirección, nombre usuario y nombre
     * nombre y email son no null
     * Unique: username, email
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updateDatosUsuario($args): bool
    {
        $q_updateUsuario = "UPDATE usuarios SET nombre = :nombre" .
            ",telefono = :telefono" .
            ",email = :email" .
            ",direccion = :direccion" .
            ",username = :username" .
            ",rol = :rol" .
            "WHERE usuarioId = :id";

        //obligatorios: nombre, usuario, password, email
        $nombre = $args['nombre'] ?? '';
        $username = $args['username'] ?? '';
        $email = $args['email'] ?? '';
        $rol = $args['rol'] ?? '';

        $idUsuario = $args['idUsuario'] ?? -1;

        if ($idUsuario < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES DB: no se ha podido identificar id usuario");
        }

        if ($nombre == '' || $username == '' || $email == '' || $rol == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB: no se han rellenado los campos requeridos");
        }

        //no required
        $telefono = $args['telefono'] ?? '';
        $direccion = $args['direccion'] ?? '';

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES DB: no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateUsuario);

        $exito = $stmn->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':email' => $email,
            ':direccion' => $direccion,
            ':username' => $username,
            ':rol' => $rol,
            ':id' => $idUsuario
        ]);

        return $exito;
    }

    /**
     * updatePasswordUsuario($args)
     * permite actualizar el password de un usuario manteniendo la seguridad
     * Solo usable por el propio usuario sobre sí mismo o un admin con privilegios de gestión
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updatePasswordUsuario($args): bool
    {
        $q_updatePassword = "UPDATE usuarios SET password_hash = SHA2(:password_h,224) WHERE usuarioId = :id";

        $contrasena = $args['password'] ?? '';
        $usuarioId = $args['usuarioId'] ?? -1;

        if ($contrasena == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB: campo contraseña vacío");
        }

        if ($usuarioId < 0) {
            throw new FuncionesDBException("ERROR FUNCIONES DB: usuario id no identificado");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES DB: no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updatePassword);

        $exito = $stmn->execute([
            ':password_h' => $contrasena,
            ':id' => $usuarioId
        ]);

        return $exito;
    }

    // ---DELETE---

    /**
     * deleteUsuario($args)
     * elimina un usuario cuyo usuarioId se indique
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteUsuario($args) {
        $q_deleteUsuario="DELETE FROM usuarios WHERE usuarioId = :id";

        $usuarioId=$args['usuarioId']??-1;

        if($usuarioId<0){
            throw new FuncionesDBException("ERROR FUNCIONES DB: usuario id no identificado");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES DB: no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_deleteUsuario);

        $exito = $stmn->execute([
            ':id' => $usuarioId
        ]);

        return $exito;

    }
}
