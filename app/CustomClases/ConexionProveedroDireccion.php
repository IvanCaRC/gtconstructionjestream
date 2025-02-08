<?php

namespace App\CustomClases;

class ConexionProveedroDireccion
{
    public $proveedor_id;
    public $cp;
    public $estado;
    public $ciudad;
    public $municipio;
    public $colonia;
    public $calle;    
    public $numero;   
    public $refernecia;   
    public $latitud;   
    public $longitud;            

    public function __construct($proveedor_id,$cp, $estado, $ciudad, $municipio, $colonia, $calle,$numero,$refernecia, $latitud, $longitud )
    {
        $this->proveedor_id = $proveedor_id;
        $this->cp = $cp;
        $this->estado = $estado;
        $this->ciudad = $ciudad;
        $this->municipio = $municipio;
        $this->colonia = $colonia;
        $this->calle = $calle;
        $this->numero = $numero;
        $this->refernecia = $refernecia;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
    }
}
