<?php

namespace App\CustomClases;

class ConexionClienteDireccion
{
    public $cliente_id;
    public $pais;
    public $cp;
    public $estado;
    public $ciudad;
    public $municipio;
    public $colonia;
    public $calle;    
    public $numero;   
    public $refernecia;   
    public $Latitud;   
    public $Longitud;            

    public function __construct($cliente_id,$pais,$cp, $estado, $ciudad, $municipio, $colonia, $calle,$numero,$refernecia, $Latitud, $Longitud )
    {
        $this->cliente_id = $cliente_id;
        $this->pais = $pais;
        $this->cp = $cp;
        $this->estado = $estado;
        $this->ciudad = $ciudad;
        $this->municipio = $municipio;
        $this->colonia = $colonia;
        $this->calle = $calle;
        $this->numero = $numero;
        $this->refernecia = $refernecia;
        $this->Latitud = $Latitud;
        $this->Longitud = $Longitud;
    }
}