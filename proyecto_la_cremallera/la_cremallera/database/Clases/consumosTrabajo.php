<?php

namespace la_cremallera\database\Clases;


class ConsumosTrabajo{

    //Variables de la Clase ConsumosTrabajo
    private $trabajoId;
    private $itemId;
    private $cantidadUsada;

    //Constructor para inicializa la clase ConsumosTrabajo
    public function __construct($itemId = null, $cantidadUsada = null, $trabajoId = null)
    {
        $this->itemId = $itemId;
        $this->cantidadUsada = $cantidadUsada;
        if (!$trabajoId == null) {
            $this->trabajoId = $trabajoId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
     public function __get($nombreVariable)
    {
        switch(strtolower($nombreVariable)){
            case "trabajoid":
            case "trabajo":
                return $this->trabajoId;
            case "itemid":
            case "item":
                return $this->itemId;
            case "cantidad usada":
            case "cantidad":
                return $this->cantidadUsada;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch(strtolower($nombreVariable)){
            case "itemid":
            case "item":
                $this->itemId = $nuevaVariable;
                return true;
            case "cantidad usada":
            case "cantidad":
                $this->cantidadUsada = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }


}

?>