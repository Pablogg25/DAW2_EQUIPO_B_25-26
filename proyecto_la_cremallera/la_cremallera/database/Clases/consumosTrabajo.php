<?php

namespace la_cremallera\database\Clases;


class consumosTrabajo{

    private $trabajoId;
    private $itemId;
    private $cantidad_usada;

    public function __construct($trabajoId, $itemId, $cantidad_usada)
    {
        $this->trabajoId = $trabajoId;
        $this->itemId = $itemId;
        $this->cantidad_usada = $cantidad_usada;

    }


    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "trabajoId":
            case "trabajo":
                $this->trabajoId;
            case "itemId":
            case "item":
                $this->itemId;
            case "cantidad usada":
            case "cantidad":
                $this->cantidad_usada;
            default:
                return null;
        }
    }
    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "trabajoId":
            case "trabajo":
                $this->trabajoId = $value;
            case "itemId":
            case "item":
                $this->itemId = $value;
            case "cantidad usada":
            case "cantidad":
                $this->cantidad_usada = $value;
            default:
                return null;
        }
    }


}



?>