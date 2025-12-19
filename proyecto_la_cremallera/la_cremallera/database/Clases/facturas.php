<?php

namespace la_cremallera\database\Clases;


class facturas{

    private $facturaId;
    private $usuarioId;
    private $fecha;
    private $pagado;
    private $total_calculado;

    public function __construct($facturaId, $usuarioId, $fecha, $pagado, $total_calculado)
    {
        $this->facturaId = $facturaId;
        $this->usuarioId = $usuarioId;
        $this->fecha = $fecha;
        $this->pagado = $pagado;
        $this->total_calculado = $total_calculado;
    }


    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "facturaId":
            case "factura":
                $this->facturaId;
            case "usuarioId":
            case "usuario":
                $this->usuarioId;
            case "fecha":
                $this->fecha;
            case "pagado":
                $this->pagado;
            case "total calculado":
            case "total":
                $this->total_calculado;
            default:
                return null;
        }
    }

     public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "facturaId":
            case "factura":
                $this->facturaId = $value;
            case "usuarioId":
            case "usuario":
                $this->usuarioId = $value;
            case "fecha":
                $this->fecha = $value;
            case "pagado":
                $this->pagado = $value;
            case "total calculado":
            case "total":
                $this->total_calculado = $value;
            default:
                return null;
        }
    }
}


?>