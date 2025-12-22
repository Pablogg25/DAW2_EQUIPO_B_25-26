<?php

namespace la_Cremallera\database\Clases\Trabajo;

class Trabajo{

    //Variables de la clase Trabajo
    private $trabajoId;
    private $prendaId;
    private $empleadoId;
    private $descripcion;
    private $fechaInicio;
    private $fechaEntrega;
    private $estado;
    private $precio;

    //Constructor que inicializa la clase Trabajo
    public function __construct($prendaId = null, $empleadoId = null, $descripcion = null, $fechaInicio = null, $fechaEntrega = null, $estado = null, $precio = null, $trabajoId = null)
    {    
        $this->prendaId = $prendaId;
        $this->empleadoId = $empleadoId;
        $this->descripcion = $descripcion;
        $this->fechaInicio = $fechaInicio;
        $this->fechaEntrega = $fechaEntrega;
        $this->estado = $estado;
        $this->precio = $precio;
        if (!$trabajoId == null) {
            $this->trabajoId = $trabajoId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'trabajoid':
            case 'trabajo':
            case 'tra':
                return $this->trabajoId;
            case 'prendaid':
            case 'prenda':
                return $this->prendaId;
            case 'empleadoid':
            case 'empleado':
            case 'emp':
                return $this->empleadoId;
            case 'descripcion':
            case 'descripción':
            case 'des':
                return $this->descripcion;
            case 'fecha inicio':
            case 'fechainicio':
            case 'fec ini':
            case 'inicio':
                return $this->fechaInicio;
            case 'fecha entrega':
            case 'fechaentrega':
            case 'fec ent':
            case 'entrega':
                return $this->fechaEntrega;
            case 'estado':
            case 'est':
            case 'e':
                return $this->estado;
            case 'precio':
            case 'pre':
            case 'p':
                return $this->precio;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'prendaid':
            case 'prenda':
                $this->prendaId = $nuevaVariable;
                return true;
            case 'empleadoid':
            case 'empleado':
                $this->empleadoId = $nuevaVariable;
                return true;
            case 'descripcion':
            case 'descripción':
            case 'des':
                $this->descripcion = $nuevaVariable;
                return true;
            case 'fecha inicio':
            case 'fechainicio':
            case 'fec ini':
            case 'inicio':
                $this->fechaInicio = $nuevaVariable;
                return true;
            case 'fecha entrega':
            case 'fechaentrega':
            case 'fec ent':
            case 'entrega':
                $this->fechaEntrega = $nuevaVariable;
                return true;
            case 'estado':
            case 'est':
            case 'e':
                $this->estado = $nuevaVariable;
                return true;
            case 'precio':
            case 'pre':
            case 'p':
                $this->precio = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }

}

?>