<?php

use la_cremallera\database\ConexionBD;

require_once __DIR__ . '/ConexionDB.php';

use PDO;
use PDOException;

final class FuncionesDBUsuarios
{

    final public static function getUsuarios()
    {
        //obtener todos los usuarios de la base de datos
        $conexion = ConexionBD::getConnection();

        if (!isset($conexion)) {
            echo "<p class='error'>ERROR CONEXION BD: conexión no establecida</p>";
            return;
        }

        $comandoSql = "SELECT * FROM usuarios";

        $stmt = $conexion->prepare($comandoSql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 
     * registrarUsuario($args)
     * Requiere nombre, email, username, y password
     * Negocio debe gestionar el estado de transacción y rollback en caso de errores
     */
    final public static function registrarUsuario($args):bool
    {

        $q_insertUsuario = "INSERT INTO usuarios" .
            "(nombre,telefono,email,direccion,username,password_hash,rol) VALUES" .
            "(:nombre,:telefono,:email,:direccion,:username,SHA2(':password',224),'cliente')";

        //obligatorios: nombre, usuario, password, email
        $nombre = $args['nombre'] ?? '';
        $username = $args['username'] ?? '';
        $password = $args['password'] ?? '';
        $email = $args['email'] ?? '';

        //no required
        $telefono = $args['telefono'] ?? '';
        $direccion = $args['direccion'] ?? '';

        if ($nombre == '' || $username == '' || $password == '' || $email == '') {
            echo "<p class='error'>ERROR FUNCIONES DB: Se requiere nombre, nombre de usuario, contraseña y correo electrónico válidos</p>";
            return false;
        }

        try {
            $conexion = ConexionBD::getConnection();

            if (!isset($conexion)) {
                echo "<p class='error'>ERROR CONEXION BD: no se ha podido establecer conexion</p>";
                return false;
            }

            $stmn = $conexion->prepare($q_insertUsuario);

            $success = $stmn->execute([
                ':nombre' => $nombre,
                ':telefono' => $telefono,
                ':email' => $email,
                ':direccion' => $direccion,
                ':username' => $username,
                ':passord' => $password
            ]);

            return $success;
        } catch (PDOException $e) {
            echo "<p class='error'> error al inertar: " . $e->getMessage() . " </p>";
            return false;
        }
    }
}
