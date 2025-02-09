<?php

namespace App\CustomClases;

class ConexionProveedroDireccion
{
    public $proveedor_id;
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

    public function __construct($proveedor_id,$pais,$cp, $estado, $ciudad, $municipio, $colonia, $calle,$numero,$refernecia, $Latitud, $Longitud )
    {
        $this->proveedor_id = $proveedor_id;
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
