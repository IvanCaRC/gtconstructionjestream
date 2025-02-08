<?php

namespace App\CustomClases;

class ConexionProveedorItemTemporal
{
    public $proveedor_id;
    public $proveedor_nombre;
    public $tiempo_minimo_entrega;
    public $tiempo_maximo_entrega;
    public $precio_compra;
    public $unidad;
    public $estado;

    public function __construct($proveedor_id,$proveedor_nombre, $tiempo_minimo_entrega, $tiempo_maximo_entrega, $precio_compra, $unidad, $estado)
    {
        $this->proveedor_id = $proveedor_id;
        $this->proveedor_nombre = $proveedor_nombre;
        $this->tiempo_minimo_entrega = $tiempo_minimo_entrega;
        $this->tiempo_maximo_entrega = $tiempo_maximo_entrega;
        $this->precio_compra = $precio_compra;
        $this->unidad = $unidad;
        $this->estado = $estado;
    }
}
