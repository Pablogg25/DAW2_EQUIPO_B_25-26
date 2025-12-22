<?php
namespace la_cremallera\database;

use PDO;
use PDOException;
use Dotenv\Dotenv;

//crear clase singleton con protección

final class ConexionDB
{
    //variable privada singleton
    private static ?PDO $connection = null;
    private static string $envPath=__DIR__ . '/../../';

    private function __construct()
    {
        // Nadie podrá extender de la clase ni sobreescribir el metodo
    }

    private function __clone()
    {
        //nadie podrá acceder a ello
    }

    //método estaático que no puede ser modificado
    final public static function getConnection()
    {
        try {
            if (!self::$connection) {

                //cargar fichero de entorno,el fichero debe estar en la raiz, lejos de los archivos publicos
                // la ruta apunta a la carpeta del fichero de entorno
                $dotenv = Dotenv::createImmutable(ConexionDB::$envPath);
                $dotenv->load();

                // Crear instancia
                $opciones=array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8");

                //obtener variables de entrono e inyectar
                self::$connection=new PDO(
                    dsn:$_ENV['DB_DSN'],
                    username:$_ENV['DB_USERNAME'],
                    password:$_ENV['DB_PASSWORD'],
                    options:$opciones
                );

                //establecer modos de error

                self::$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            //códigos de error
            echo match ($e->getCode()) {
                1049 => 'Base de datos no encontrada',
                1045 => 'Acceso denegado',
                2002 => 'Conexión rechazada',
                default => 'Error desconocido'
            };
            //mensaje de error
            echo "Error de conexión a base de datos: " . $e->getMessage();
        }
        // devolver conexion al final
        return self::$connection;
    }
}