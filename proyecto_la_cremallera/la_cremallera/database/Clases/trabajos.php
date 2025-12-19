<?php

namespace la_cremallera\database\Clases;

class trabajos
{

    private $trabajoId;
    private $prendaId;
    private $empleadoId;
    private $descripcion;
    private $fecha_inicio;
    private $fecha_entrega;
    private $estado;
    private $precio;


    public function __construct($trabajoId, $prendaId, $empleadoId, $descripcion, $fecha_inicio, $fecha_entrega, $estado, $precio)
    {
        $this->trabajoId = $trabajoId;
        $this->prendaId = $prendaId;
        $this->empleadoId = $empleadoId;
        $this->descripcion = $descripcion;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_entrega = $fecha_entrega;
        $this->estado = $estado;
        $this->precio = $precio;
    }

    public function __get($nombreVariable)
    {
        switch ($nombreVariable) {
            case "trabajoId":
            case "trabajo":
                $this->trabajoId;
            case "prendaId":
            case "prenda":
                $this->prendaId;
            case "empleadoId":
            case "empleado":
                $this->empleadoId;
            case "descripcion":
                $this->descripcion;
            case "fecha inicio":
            case "fecini":
                $this->fecha_inicio;
            case "fecha entrega":
            case "fecent":
                $this->fecha_entrega;
            case "estado":
                $this->estado;
            case "precio":
                $this->precio;
            default:
                return null;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch ($nombreVariable) {
            case "trabajoId":
            case "trabajo":
                $this->trabajoId = $value;
            case "prendaId":
            case "prenda":
                $this->prendaId = $value;
            case "empleadoId":
            case "empleado":
                $this->empleadoId = $value;
            case "descripcion":
                $this->descripcion = $value;
            case "fecha inicio":
            case "fecini":
                $this->fecha_inicio = $value;
            case "fecha entrega":
            case "fecent":
                $this->fecha_entrega = $value;
            case "estado":
                $this->estado = $value;
            case "precio":
                $this->precio = $value;
            default:
                return null;
        }
    }
}
