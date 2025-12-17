<?php

namespace la_cremallera\database;

use PDO;

final class FuncionesDB{

    final public static function getUsuarios(PDO $conexion){
        //obtener todos los usuarios de la base de datos

        if(isset($conexion)){
            echo "<p>ERROR CONEXION BD: conexión no establecida</p>";
            return;
        }
        $q_selectUsuarios="SELECT nombre,email FROM usuarios";

        $resultadoQuery=$conexion->query($q_selectUsuarios);

        return $resultadoQuery;
    }

}