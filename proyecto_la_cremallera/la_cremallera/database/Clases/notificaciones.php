<?php

namespace la_cremallera\database\Clases;

class notificaciones{
    private $notificacionesId;
    private $receptorId;
    private $remitenteId;
    private $tipo;
    private $asunto;
    private $mensaje;
    private $fecha_envio;

    public function __construct($notificacionesId, $receptorId, $remitenteId, $tipo, $asunto, $mensaje, $fecha_envio)
    {
        $this->notificacionesId = $notificacionesId;
        $this->receptorId = $receptorId;
        $this->remitenteId = $remitenteId;
        $this->tipo = $tipo;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->fecha_envio = $fecha_envio;
    }

    public function __get($nombreVariable)
    {
        switch($nombreVariable){
            case "notificacionesId":
            case "notificaciones":
                $this->notificacionesId;
            case "receptorId":
            case "receptor":
                $this->receptorId;
            case "remitenteId":
            case "remitente":
                $this->remitenteId;
            case "tipo":
                $this->tipo;
            case "asunto":
                $this->asunto;
            case "mensaje":
                $this->mensaje;
            case "fecha envio":
            case "fecha":
                $this->fecha_envio;
            default:
                return null;
        }
    }

    public function __set($nombreVariable, $value)
    {
        switch($nombreVariable){
            case "notificacionesId":
            case "notificaciones":
                $this->notificacionesId = $value;
            case "receptorId":
            case "receptor":
                $this->receptorId = $value;
            case "remitenteId":
            case "remitente":
                $this->remitenteId = $value;
            case "tipo":
                $this->tipo = $value;
            case "asunto":
                $this->asunto = $value;
            case "mensaje":
                $this->mensaje = $value;
            case "fecha envio":
            case "fecha":
                $this->fecha_envio = $value;
            default:
                return null;
        }
    }


}

?>