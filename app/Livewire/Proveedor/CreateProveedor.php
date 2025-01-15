<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Component;

class CreateProveedor extends Component
{
    use WithFileUploads;
    public $open = false;
    public $nombre, $descripcion, $correo, $rfc, $facturacion, $bancarios;
    public $fileNameFacturacion, $fileNameBancarios;


    public function updatedFacturacion()
    {
        if ($this->facturacion) {
            $this->fileNameFacturacion = $this->facturacion->getClientOriginalName();
        }
    }

    public function updatedBancarios()
    {
        if ($this->bancarios) {
            $this->fileNameBancarios = $this->bancarios->getClientOriginalName();
        }
    }

    public function render()
    {
        return view('livewire.proveedor.create-proveedor');
    }

    public function save()
    {
        $facturacion = null;
        if ($this->facturacion) {
            $facturacion = $this->facturacion->store('archivosFacturacionProveedores', 'public');
        }

        $bancarios = null;
        if ($this->bancarios) {
            $bancarios = $this->bancarios->store('archivosBancariosProveedores', 'public');
        }

        $user = Proveedor::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'correo' => $this->correo,
            'rfc' => $this->rfc,
            'archivo_facturacion_pdf' => $facturacion,
            'datos_bancarios_pdf' => $bancarios,
        ]);

        $this->reset('open', 'nombre', 'descripcion', 'correo', 'rfc', 'facturacion', 'bancarios');
        // $this->dispatch('userAdded');

        return true;
    }
}
