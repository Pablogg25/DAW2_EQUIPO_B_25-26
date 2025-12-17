<?php

use la_cremallera\database\ConexionBD;

require_once __DIR__ . '/ConexionDB.php';

final class FuncionesDB{

    final public static function getUsuarios(){
        
        $establecerConexionBD = ConexionBD::getConnection();

        $comandoSql = "SELECT * FROM usuarios";

        $stmt = $establecerConexionBD -> prepare($comandoSql);
        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

    }

}