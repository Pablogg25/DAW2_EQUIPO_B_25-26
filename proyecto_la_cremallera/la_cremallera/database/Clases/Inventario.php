<?php

namespace la_cremallera\database\Clases;


class Inventario{
    private $itemId;
    private $nombre;
    private $descripcion;
    private $cantidad;
    private $stock_minimo;

    public function __construct($itemId, $nombre, $descripcion, $cantidad, $stock_minimo)
    {
        $this->itemId = $itemId;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->stock_minimo = $stock_minimo; 
    }

    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "itemId":
            case "item":
                $this->itemId;
            case "nombre":
                $this->nombre;
            case "descripcion":
                $this->descripcion;
            case "cantidad":
                $this->cantidad;
            case "stock minimo":
            case "stock":
                $this->stock_minimo;
            default:
                return null;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "itemId":
                case "item":
                    $this->itemId = $value;
            case "nombre":
                $this->nombre = $value;
            case "descripcion":
                $this->descripcion = $value;
            case "cantidad":
                $this->cantidad = $value;
            case "stock minimo":
            case "stock":
                $this->stock_minimo = $value;
            default:
                return null;
        }
    }

}

?>