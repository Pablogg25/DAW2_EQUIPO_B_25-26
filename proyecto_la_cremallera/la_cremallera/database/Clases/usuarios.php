<?php

namespace la_cremallera\database\Clases;

class usuarios{

    private $usuarioId;
    private $nombre;
    private $telefono;
    private $email;
    private $username;
    private $password_SHA2;
    private $rol;
    private $fecha_registro;


    public function __construct($usuarioId, $nombre, $telefono, $email, $username, $password_SHA2, $rol, $fecha_registro)
    {
        $this->usuarioId = $usuarioId;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->username = $username;
        $this->password_SHA2 = $password_SHA2;
        $this->rol = $rol;
        $this->fecha_registro = $fecha_registro;
    }
        
    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "usuarioId":
            case "usuario":
                $this->usuarioId;
            case "nombre":
                $this->nombre;
            case "telefono":
                $this->telefono;
            case "email":
                $this->email;
            case "username":
                $this->username;
            case "password SHA2":
            case "password":
                $this->password_SHA2;
            case "rol":
                $this->rol;
            case "fecha registro":
            case "fecha":
                $this->fecha_registro;
            default:
                return null;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "usuarioId":
            case "usuario":
                $this->usuarioId = $value;
            case "nombre":
                $this->nombre = $value;;
            case "telefono":
                $this->telefono = $value;;
            case "email":
                $this->email = $value;;
            case "username":
                $this->username = $value;;
            case "password SHA2":
            case "password":
                $this->password_SHA2 = $value;;
            case "rol":
                $this->rol;
            case "fecha registro":
            case "fecha":
                $this->fecha_registro = $value;
            default: 
                return null;
        }
    }
    

}

?>