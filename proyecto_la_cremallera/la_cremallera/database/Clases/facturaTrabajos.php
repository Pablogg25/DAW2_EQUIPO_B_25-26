<?php


namespace la_cremallera\database\Clases;

class facturaTrabajos{

    private int $facturaId;
    private int $trabajoId;


    public function __construct($facturaId, $trabajoId)
    {
        $this->facturaId = $facturaId;
        $this->trabajoId = $trabajoId;
    }

    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "facturaId":
            case "factura":
                $this->facturaId;
            case "trabajoID":
            case "trabajo":
                $this->trabajoId;
            default:
                return null;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "facturaID":
            case "factura":
                $this->facturaId = $value;
            case "trabajoID":
            case "trabajo":
                $this->trabajoId = $value ;
            default:
                return null;
        }
    }



}