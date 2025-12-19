<?php

namespace la_cremallera\database;

require_once __DIR__ . '/ConexionDB.php';

use la_cremallera\database\ConexionBD;
use la_cremallera\err\FuncionesDBException;
use PDO;

final class FuncionesDBUsuarios
{

    // --- READ ---
    /**
     * getUsuarios()
     * Obtiene los datos de todos los usuarios (excepto la contraseña)
     * 
     * Columnas:
     * - usuarioId
     * - nombre
     * - telefono
     * - email
     * - direccion
     * - username
     * - rol
     * - fecha_registro
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getUsuarios()
    {
        //obtener todos los usuarios de la base de datos
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $comandoSql = "SELECT usuarioId,nombre,telefono,email,direccion,username,rol,fecha_regitro FROM usuarios";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getUsuarioByName($args)
     * Obtiene los datos de un username, excepto contraseña
     * 
     * $args:
     * - username (requerido)
     * 
     * 
     * Columnas:
     * - usuarioId
     * - nombre
     * - telefono
     * - email
     * - direccion
     * - username
     * - rol
     * - fecha_registro
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getUsuarioByName($args)
    {
        //obtener todos los usuarios de la base de datos

        $nombreUsuario = $args['username'] ?? '';
        if ($nombreUsuario == '') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): se requiere rellenar el campo username");
        }


        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $comandoSql = "SELECT usuarioId,nombre,telefono,email,direccion,username,rol,fecha_regitro FROM usuarios WHERE username= :username";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute([
            ":username" => $nombreUsuario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getUsuarioById($args)
     * Obtiene los datos de un username, excepto contraseña
     * 
     * $args:
     * - username (requerido)
     * 
     * Columnas:
     * - usuarioId
     * - nombre
     * - telefono
     * - email
     * - direccion
     * - username
     * - rol
     * - fecha_registro
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function getUsuarioById($args)
    {
        //obtener todos los usuarios de la base de datos

        $usuarioId = $args['usuarioId'] ?? -1;
        if ($usuarioId == '' || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): se requiere rellenar el campo username");
        }


        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $comandoSql = "SELECT nombre,telefono,email,direccion,username,rol,fecha_regitro FROM usuarios WHERE usuarioId= :id";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute([
            ":id" => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * validatePassword($args)
     * valida el password de un username para comprobar si el password pasado es igual que el de los argumentos de forma segura
     * requiere username y password a comprobar. Devuelve true o false
     * 
     * Gestionar excepciones en negocio del endpoint.
     * 
     * $args:
     * - username (requerido)
     * - password (requerido)
     * 
     * returns: bool
     * 
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function checkPassword($args): bool
    {
        $q_checkPassword = "SELECT count(*) as found FROM usuarios WHERE username == :username AND password_SHA2 == SHA2(:password_s,224)";

        $contrasena = $args['password'] ?? '';
        $username = $args['username'] ?? '';


        if ($username == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): Se requiere rellenar el campo username");
        }

        if ($contrasena == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): Se requiere rellenar el campo contraseña");
        }


        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $stmt = $conexion->prepare($q_checkPassword);
        $stmt->execute([
            ":username" => $username,
            ":password_s" => $contrasena
        ]);

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
     * Recibe datos para crear un nuevo usuario. La contraseña se codifica en SHA2 de longitud 224. Recibe por defecto rol 'cliente'
     * 
     * $args:
     * - nombre (requerido)
     * - username (requerido, unique)
     * - password (requerido)
     * - email (requerido, unique)
     * - telefono
     * - direccion
     * - rol (enum:'cliente','empleado','admin'). Default: 'cliente'
     * 
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function registrarUsuario($args): bool
    {

        $q_insertUsuario = "INSERT INTO usuarios" .
            "(nombre,telefono,email,direccion,username,password_SHA2,rol) VALUES" .
            "(:nombre,:telefono,:email,:direccion,:username,SHA2(:password_s,224),:rol)";

        //required
        $nombre = $args['nombre'] ?? '';
        $username = $args['username'] ?? '';
        $password = $args['password'] ?? '';
        $email = $args['email'] ?? '';

        //no required
        $telefono = $args['telefono'] ?? '';
        $direccion = $args['direccion'] ?? '';
        $rol=$args['rol']??'cliente';

        if ($nombre == '' || $username == '' || $password == '' || $email == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): Se requiere nombre, nombre de usuario, contraseña y correo electrónico válidos");
        }

        if($rol!='cliente'&&$rol!='empleado'&&$rol!='admin'){
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): valor incorrecto en campo enumerado rol");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES BD (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_insertUsuario);

        $success = $stmn->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':email' => $email,
            ':direccion' => $direccion,
            ':username' => $username,
            ':password_s' => $password,
            ':rol'=>$rol
        ]);

        return $success;
    }

    // --- UPDATE ---

    /**
     * updateDatosUsuario($args)
     * Actualiza los datos personales de un usuario con id indicado.
     * telefono, email, dirección, username y nombre.
     * 
     * $args:
     * - usuarioId (requerido)
     * - nombre (requerido)
     * - username (requerido, unique)
     * - email (requerido, unique)
     * - telefono
     * - direccion
     * - rol (enum:'cliente','empleado','admin'). Default: 'cliente'
     * 
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
        $rol = $args['rol'] ?? 'cliente';

        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): valor de usuarioId no reconocido");
        }

        if ($nombre == '' || $username == '' || $email == '' || $rol == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): no se han rellenado los campos requeridos");
        }

        if($rol!='cliente'&&$rol!='empleado'&&$rol!='admin'){
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): valor incorrecto en campo enumerado rol");
        }

        //no required
        $telefono = $args['telefono'] ?? '';
        $direccion = $args['direccion'] ?? '';

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updateUsuario);

        $exito = $stmn->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':email' => $email,
            ':direccion' => $direccion,
            ':username' => $username,
            ':rol' => $rol,
            ':id' => $usuarioId
        ]);

        return $exito;
    }

    /**
     * updatePasswordUsuario($args)
     * Permite actualizar el password de un usuario manteniendo la seguridad. La contraseña se codifica en SHA2 de longitud 224.
     * Solo debe ser usable por el propio usuario sobre sí mismo o un admin con privilegios de gestión.
     * 
     * $args:
     * - usuarioId (requerido)
     * - password (requerido)
     * 
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function updatePasswordUsuario($args): bool
    {
        $q_updatePassword = "UPDATE usuarios SET password_SHA2 = SHA2(:password_s,224) WHERE usuarioId = :id";

        $usuarioId = $args['usuarioId'] ?? -1;
        $contrasena = $args['password'] ?? '';

        if ($contrasena == '') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): campo contraseña vacío");
        }

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): valor de usuario id no identificado");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_updatePassword);

        $exito = $stmn->execute([
            ':password_s' => $contrasena,
            ':id' => $usuarioId
        ]);

        return $exito;
    }

    // ---DELETE---

    /**
     * deleteUsuario($args)
     * elimina un usuario cuyo usuarioId se indique.
     * 
     * $args:
     * - usuarioId (requerido)
     * 
     * Gestionar excepciones en negocio del endpoint.
     * Excepciones:
     * - FuncionesDBException
     * - PDOException
     */
    final public static function deleteUsuario($args)
    {
        $q_deleteUsuario = "DELETE FROM usuarios WHERE usuarioId = :id";

        $usuarioId = $args['usuarioId'] ?? -1;

        if ($usuarioId < 0 || gettype($usuarioId) != 'integer') {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): usuario id no identificado");
        }

        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            throw new FuncionesDBException("ERROR FUNCIONES DB (USUARIOS): no se ha podido establecer conexion BBDD");
        }

        $stmn = $conexion->prepare($q_deleteUsuario);

        $exito = $stmn->execute([
            ':id' => $usuarioId
        ]);

        return $exito;
    }
}
