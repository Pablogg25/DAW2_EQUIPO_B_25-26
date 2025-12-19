<?php

namespace la_cremallera\database\Clases;


class prendas{

    private $prendaId;
    private $usuarioId;
    private $tipo;
    private $descripcion;
    private $color;
    private $talla;

    public function __construct($prendaId, $usuarioId, $tipo, $descripcion, $color, $talla)
    {
        $this->prendaId = $prendaId;
        $this->usuarioId = $usuarioId;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion;
        $this->color = $color;
        $this->talla = $talla;
    }

    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "prendaId":
            case "prenda":
                $this->prendaId;
            case "usuarioId":
            case "usuario":
                $this->usuarioId;
            case "descripcion":
                $this->descripcion;
            case "color":
                $this->color;
            case "talla":
                $this->talla;
            default:
                return null;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "prendaId":
            case "prenda":
                $this->prendaId = $value;
            case "usuarioId":
            case "usuario":
                $this->usuarioId = $value;
            case "descripcion":
                $this->descripcion = $value;
            case "color":
                $this->color = $value;
            case "talla":
                $this->talla = $value;
            default:
                return null;
        }
    }

}

?>