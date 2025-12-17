<?php

use la_cremallera\database\ConexionBD;

require_once __DIR__ . '/ConexionDB.php';

use PDO;

final class FuncionesDB{

    final public static function getUsuarios(){
        //obtener todos los usuarios de la base de datos
        $conexion = ConexionBD::getConnection();

        if(!isset($conexion)){
            echo "<p>ERROR CONEXION BD: conexión no establecida</p>";
            return;
        }

        $comandoSql = "SELECT * FROM usuarios";

        $stmt = $conexion -> prepare($comandoSql);
        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

    }

}