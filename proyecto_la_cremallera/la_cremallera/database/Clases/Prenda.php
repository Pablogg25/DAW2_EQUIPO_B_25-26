<?php

namespace la_Cremallera\database\Clases;

class Prenda{

    //Variables de la clase Prenda
    private $prendaId;
    private $usuarioId;
    private $tipo;
    private $descripcion;
    private $color;
    private $talla;

    //Constructor que inicializa la clase Prenda
    public function __construct($usuarioId = null, $tipo = null, $descripcion = null, $color = null, $talla = null, $prendaId = null ){
        $this->usuarioId = $usuarioId;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion;
        $this->color = $color;
        $this->talla = $talla;
        if (!$prendaId == null) {
            $this->prendaId = $prendaId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'prendaid':
            case 'prenda':
                return $this->prendaId;
            case 'usuarioId':
            case 'usuario':
                return $this->usuarioId;
            case 'tipo':
                return $this->tipo;
            case 'descripcion':
            case 'descripción':
            case 'des':
                return $this->descripcion;
            case 'color':
            case 'c':
                return $this->color;
            case 'talla':
                return $this->talla;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch (strtolower($nombreVariable)) {
            case 'usuarioId':
            case 'usuario':
                $this->usuarioId = $nuevaVariable;
                return true;
            case 'tipo':
                $this->tipo = $nuevaVariable;
                return true;
            case 'descripcion':
            case 'descripción':
            case 'des':
                $this->descripcion = $nuevaVariable;
                return true;
            case 'color':
            case 'c':
                $this->color = $nuevaVariable;
                return true;
            case 'talla':
                $this->talla = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }
}

?>