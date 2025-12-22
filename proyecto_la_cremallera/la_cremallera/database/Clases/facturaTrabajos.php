<?php


namespace la_cremallera\database\Clases;

class FacturaTrabajos{

    //Variables de la clase FacturaTrabajos
    private int $facturaId;
    private int $trabajoId;

    //Constructor que incializa la clase FacturaTrabajos
    public function __construct($facturaId, $trabajoId)
    {
        $this->facturaId = $facturaId;
        $this->trabajoId = $trabajoId;
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch(strtolower($nombreVariable)){
            case "facturaid":
            case "factura":
                return $this->facturaId;
            case "trabajoid":
            case "trabajo":
                return $this->trabajoId;
            default:
                return null;
        }
    }

}

?>