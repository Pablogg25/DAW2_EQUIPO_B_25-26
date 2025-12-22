<?php

namespace la_Cremallera\database\Clases;

class Factura{

    //Variables de la clase Factura
    private $facturaId;
    private $usuarioId;
    private $fecha;
    private $pagado;
    private $totalCalculado;

    //Constructor que incializa la clase Factura
    public function __construct($usuarioId = null, $fecha = null, $pagado = null, $totalCalculado = null, $facturaId = null)
    {
        $this->usuarioId = $usuarioId;
        $this->fecha = $fecha;
        $this->pagado = $pagado;
        $this->totalCalculado = $totalCalculado;
        if (!$facturaId == null) {
            $this->facturaId = $facturaId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'facturaid':
            case 'factura':
                return $this->facturaId;
            case 'usuarioid':
            case 'usuario':
                return $this->usuarioId;
            case 'fecha':
            case 'fec':
                return $this->fecha;
            case 'pagado':
            case 'estado':
                return $this->pagado;
            case 'total calculado':
            case 'total':
                return $this->totalCalculado;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'usuarioid':
            case 'usuario':
                $this->usuarioId = $nuevaVariable;
                return true;
            case 'fecha':
            case 'fec':
                $this->fecha = $nuevaVariable;
                return true;
            case 'pagado':
            case 'estado':
                $this->pagado = $nuevaVariable;
                return true;
            case 'total calculado':
            case 'total':
                $this->totalCalculado = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }

}

?>