<?php

namespace la_Cremallera\database\Clases;

class Usuario
{
    //Variables de la Clase Usuario
    private $usuarioId;
    private $nombre;
    private $telefono;
    private $email;
    private $direccion;
    private $username;
    private $passwordSha2;
    private $rol;
    private $fechaRegistro;

    //Constructor para inicializa la clase Usuario
    public function __construct($nombre = null, $telefono = null, $email = null, $direccion = null, $username = null, $passwordSha2 = null, $rol = null, $fechaRegistro = null, $usuarioId = null)
    {
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->direccion = $direccion;
        $this->username = $username;
        $this->passwordSha2 = $passwordSha2;
        $this->rol = $rol;
        $this->fechaRegistro = $fechaRegistro;
        if (!$usuarioId == null) {
            $this->usuarioId = $usuarioId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'usuarioid':
            case 'usuario':
            case 'id':
                return $this->usuarioId;
            case 'nombre':
            case 'nom':
                return $this->nombre;
            case 'telefono':
            case 'tel':
            case 'celular':
            case 'cel':
                return $this->telefono;
            case 'email':
            case 'correo':
                return $this->email;
            case 'direccion':
            case 'dirección':
            case 'dir':
                return $this->direccion;
            case 'username':
            case 'user':
                return $this->username;
            case 'password':
            case 'passwordsha2':
            case 'contraseña':
            case 'contra':
                return $this->passwordSha2;
            case 'rol':
                return $this->rol;
            case 'fecha registro':
            case 'fecha':
            case 'registro':
            case 'fec':
            case 'reg':
                return $this->fechaRegistro;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'nombre':
            case 'nom':
                $this->nombre = $nuevaVariable;
                return true;
            case 'telefono':
            case 'tel':
            case 'celular':
            case 'cel':
                $this->telefono = $nuevaVariable;
                return true;
            case 'email':
            case 'correo':
                $this->email = $nuevaVariable;
                return true;
            case 'direccion':
            case 'dir':
                $this->direccion = $nuevaVariable;
                return true;
            case 'username':
            case 'usuario':
                $this->username = $nuevaVariable;
                return true;
            case 'password':
            case 'passwordsha2':
            case 'contraseña':
                $this->passwordSha2 = $nuevaVariable;
                return true;
            case 'rol':
                $this->rol = $nuevaVariable;
                return true;
            case 'fecha registro':
            case 'fecha':
            case 'registro':
            case 'fec':
            case 'reg':
                $this->fechaRegistro = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }
}
