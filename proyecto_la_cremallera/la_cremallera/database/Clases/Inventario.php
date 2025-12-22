<?php

namespace la_cremallera\database\Clases;

class Inventario{

    //Variables de la clase Inventario
    private $itemId;
    private $nombre;
    private $descripcion;
    private $cantidad;
    private $stockMinimo;

    //Constructor que incializa la clase Inventario
    public function __construct($nombre = null, $descripcion = null, $cantidad = null, $stockMinimo = null, $itemId = null )
    {
        $this->itemId = $itemId;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->stockMinimo = $stockMinimo; 
        if (!$itemId == null) {
            $this->itemId = $itemId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch(strtolower($nombreVariable)){
            case "itemId":
            case "item":
                return $this->itemId;
            case "nombre":
            case "nom":
                return $this->nombre;
            case "descripcion":
            case "des":
                return $this->descripcion;
            case "cantidad":
            case "cant":
                return $this->cantidad;
            case "stock minimo":
            case "stock":
                return $this->stock_minimo;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch(strtolower($nombreVariable)){
            case "nombre":
            case "nom":
                $this->nombre = $nuevaVariable;
                return true;
            case "descripcion":
            case "des":
                $this->descripcion = $nuevaVariable;
                return true;
            case "cantidad":
            case "cant":
                $this->cantidad = $nuevaVariable;
                return true;
            case "stock minimo":
            case "stock":
                $this->stockMinimo = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }

}

?>