<?php

//inicar conexión bbdd

use la_cremallera\database\ConexionDB;
use la_cremallera\database\FuncionesDBUsuarios;

require_once __DIR__ . "/../vendor/autoload.php";

//prueba de conexión
$conexionDB= ConexionDB::getConnection();

//intento de obtener datos de usuarios
$listaUsuarios=FuncionesDBUsuarios::getUsuarios();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Testeo de conexión SQL y Funciones DB</h1>
    <p>Prueba de conexion <?= ($conexionDB)? 'Conexion establecida':'Error de conexion' ?></p>

    <p>Como usar phpunit: <a href="https://www.freecodecamp.org/news/test-php-code-with-phpunit/">pruebas phpunit</a></p>
    <p>Prueba de obtener usuarios</p>
    <?php if($listaUsuarios): ?>
        <p>Nombres de usuario: </p>
        <ul>
            <?php foreach($listaUsuarios as $u): ?>
                <li><?= $u['nombre'] ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if(!$listaUsuarios): ?>
        <p>No se han podido obtener datos</p>
    <?php endif; ?>
</body>
</html>