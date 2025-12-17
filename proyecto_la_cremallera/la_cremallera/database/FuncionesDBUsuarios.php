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
     * validatePassword($args)
     * valida el password de un usuario id para comprobar si el id pasado es igual que el de los argumentos de forma segura
     */
    final public static function validatePassword($args){

    }

    // --- CREATE ---
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
                ':password' => $password
            ]);

            return $success;
        } catch (PDOException $e) {
            echo "<p class='error'> error al insertar: " . $e->getMessage() . " </p>";
            return false;
        }
    }

    // --- UPDATE ---

    /**
     * updateDatosUsuario($args)
     * requiere usuarioId objetivo
     * actualiza los datos personales del usuario
     * telefono, email, dirección, nombre usuario y nombre
     * nombre y email son no null
     * email es unique
     * nombre usuario es unique
     */
    final public static function updateDatosUsuario($args):bool{
        $q_updateUsuario="UPDATE usuarios SET nombre = :nombre".
        ",telefono = :telefono".
        ",email = :email".
        ",direccion = :direccion".
        ",username = :username".
        ",rol = :rol".
        "WHERE usuarioId = :id";

        //obligatorios: nombre, usuario, password, email
        $nombre = $args['nombre'] ?? '';
        $username = $args['username'] ?? '';
        $email = $args['email'] ?? '';
        $rol=$args['rol']??'';

        $idUsuario=$args['idUsuario']??-1;

        if($idUsuario<0){
            echo "<p class='error'>ERROR FUNCIONES DB: no se ha podido identificar id usuario</p>";
            return false;
        }

        if($nombre==''||$username==''||$email==''||$rol==''){
            echo "<p class='error'> error de validación: no se han rellenado los campos requeridos </p>";
            return false;
        }

        //no required
        $telefono = $args['telefono'] ?? '';
        $direccion = $args['direccion'] ?? '';
        
        try{
            $conexion=ConexionBD::getConnection();

            if(!isset($conexion)){
                echo "<p class='error'> error al actualizar usuario: no se ha establecido conexión </p>";
                return false;
            }

            $stmn=$conexion->prepare($q_updateUsuario);

            $exito=$stmn->execute([
                ':nombre' => $nombre,
                ':telefono' => $telefono,
                ':email' => $email,
                ':direccion' => $direccion,
                ':username' => $username,
                ':rol' => $rol,
                ':id'=>$idUsuario
            ]);

            return true;
        }catch(PDOException $e){
            echo "<p class='error'> error al actualizar: " . $e->getMessage() . " </p>";
            return false;
        }
    }

    /**
     * updatePasswordUsuario($args)
     * permite actualizar el password de un usuario manteniendo la seguridad
     */
    final public static function updatePasswordUsuario($args){

    }
}
