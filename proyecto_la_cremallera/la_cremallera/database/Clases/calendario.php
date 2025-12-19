<?php

namespace la_cremallera\database\Clases;

class calendario{
    private $eventoId;
    private $titulo;
    private $descripcion;
    private $fecha_inicio;
    private $fecha_fin;
    private $empleadoId;
    private $trabajoId;


    public function __construct($eventoId, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $empleadoId, $trabajoId)
    {
        $this->eventoId = $eventoId;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->empleadoId = $empleadoId;
        $this->trabajoId = $trabajoId;
    }

    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "eventoId":
            case "evento":
                $this->eventoId;
            case "titulo":
                $this->titulo;
            case "descripcion":
                $this->descripcion;
            case "fecha inicio":
            case "fecini":
                $this->fecha_inicio;
            case "fecha fin":
            case "fecfin":
                $this->fecha_fin;
            case "empleadoId":
            case "empleado":
                $this->empleadoId;
            case "trabajoId":
            case "trabajo":
                $this->trabajoId;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "eventoId":
            case "evento":
                $this->eventoId = $value;
            case "titulo":
                $this->titulo = $value;
            case "descripcion":
                $this->descripcion = $value;
            case "fecha inicio":
            case "fecini":
                $this->fecha_inicio = $value;
            case "fecha fin":
            case "fecfin":
                $this->fecha_fin = $value;
            case "empleadoId":
            case "empleado":
                $this->empleadoId = $value;
            case "trabajoId":
            case "trabajo":
                $this->trabajoId = $value;
        }
    }

}


?>