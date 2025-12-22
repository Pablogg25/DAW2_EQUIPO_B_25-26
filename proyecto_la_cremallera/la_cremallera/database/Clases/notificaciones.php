<?php

namespace la_cremallera\database\Clases;

class Notificaciones{

    //Variables de la clase Notificaciones
    private $notificacionesId;
    private $receptorId;
    private $remitenteId;
    private $trabajoId;
    private $tipo;
    private $asunto;
    private $mensaje;
    private $fechaEnvio;

    //Constructor que incializa la clase Notificaciones
    public function __construct($receptorId = null, $remitenteId = null, $tipo = null, $asunto = null, $mensaje = null, $fechaEnvio = null, $notificacionesId = null)
    {     
        $this->receptorId = $receptorId;
        $this->remitenteId = $remitenteId;
        $this->tipo = $tipo;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->fechaEnvio = $fechaEnvio;
        if (!$notificacionesId == null) {
            $this->notificacionesId = $notificacionesId;
        }
    }

    //Funcion get magico que devuelve la variable pasando el nombre de la variable
    public function __get($nombreVariable)
    {
        switch(strtolower($nombreVariable)){
            case "notificacionesid":
            case "notificaciones":
            case "noti":
                return $this->notificacionesId;
            case "receptorId":
            case "receptor":
            case "recep":
                return $this->receptorId;
            case "remitenteId":
            case "remitente":
            case "remi":
                return $this->remitenteId;
            case "tipo":
                return $this->tipo;
            case "asunto":
            case "asun":
                return $this->asunto;
            case "mensaje":
            case "men":
                return $this->mensaje;
            case "fecha envio":
            case "fecha":
            case "fec":
                return $this->fechaEnvio;
            default:
                return null;
        }
    }

    //Funcion set magico que modifica la variable pasando el nombre de la variable a modificar y el nuevo valor de la variable
    public function __set($nombreVariable, $nuevaVariable)
    {
        switch(strtolower($nombreVariable)){
            case "receptorId":
            case "receptor":
            case "recep":
                $this->receptorId = $nuevaVariable;
                return true;
            case "remitenteId":
            case "remitente":
            case "remi":
                $this->remitenteId = $nuevaVariable;
                return true;
            case "tipo":
                $this->tipo = $nuevaVariable;
                return true;
            case "asunto":
            case "asun":
                $this->asunto = $nuevaVariable;
                return true;
            case "mensaje":
            case "men":
                $this->mensaje = $nuevaVariable;
                return true;
            case "fecha envio":
            case "fecha":
            case "fec":
                $this->fecha_envio = $nuevaVariable;
                return true;
            default:
                return null;
        }
    }


}

?>