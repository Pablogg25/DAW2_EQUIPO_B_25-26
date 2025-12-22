<?php

namespace la_cremallera\database\Clases;

class Calendario{

    //Variables de la Clase Calendario
    private $eventoId;
    private $titulo;
    private $descripcion;
    private $fechaInicio;
    private $fechaFin;
    private $usuarioId;
    private $empleadoId;
    private $trabajoId;

    //Constructor para inicializa la clase Calendario
    public function __construct($titulo = null, $descripcion = null, $fechaInicio = null, $fechaFin = null, $usuarioId = null, $empleadoId = null, $trabajoId = null , $eventoId = null)
    {
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->usuarioId = $usuarioId;
        $this->empleadoId = $empleadoId;
        $this->trabajoId = $trabajoId;
        if (!$eventoId == null) {
            $this->eventoId = $eventoId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch(strtolower($nombreVariable)){
            case "eventoid":
            case "evento":
            case "even":
                return $this->eventoId;
            case "titulo":
            case "tit":
                return $this->titulo;
            case "descripcion":
            case "des":
                return $this->descripcion;
            case "fecha inicio":
            case "fecini":
                return $this->fechaInicio;
            case "fecha fin":
            case "fecfin":
                return $this->fechaFin;
            case "usuarioid":
            case "usuario":
            case "usu":
                return $this->usarioId;
            case "empleadoid":
            case "empleado":
            case "emp":
                return $this->empleadoId;
            case "trabajoid":
            case "trabajo":
            case "tra":
                return $this->trabajoId;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch(strtolower($nombreVariable)){
            case "titulo":
            case "tit":
                $this->titulo = $nuevaVariable;
                return true;
            case "descripcion":
            case "des":
                $this->descripcion = $nuevaVariable;
                return true;
            case "fecha inicio":
            case "fecini":
                $this->fechaInicio = $nuevaVariable;
                return true;
            case "fecha fin":
            case "fecfin":
                $this->fechaFin = $nuevaVariable;
                return true;
            case "usuarioid":
            case "usuario":
            case "usu":
                $this->usarioId = $nuevaVariable;
                return true;
            case "empleadoid":
            case "empleado":
            case "emp":
                $this->empleadoId = $nuevaVariable;
                return true;
            case "trabajoid":
            case "trabajo":
            case "tra":
                $this->trabajoId = $nuevaVariable;
                return true;
            default:
                return null;
        }

    }

}


?>