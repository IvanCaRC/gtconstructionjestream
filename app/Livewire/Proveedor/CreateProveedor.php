<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use App\Models\Telefono;  // Importar el modelo de Telefonos
use App\Models\Familia;   // Importar el modelo de Familia
use App\Models\ProveedorHasFamilia;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Component;

class CreateProveedor extends Component
{

    use WithFileUploads;
    public $openModalFamilias = false;
    public $openModalDireccion = false;
    public $nombre, $descripcion, $correo, $rfc, $facturacion, $bancarios;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    // Inicializar con un campo de teléfono
    public $familias, $familiasSeleccionadas = [''];  // Inicializar con un campo de familia
    public $fileNameFacturacion, $fileNameBancarios;

    public $niveles = []; // Array para almacenar las familias de cada nivel
    public $seleccionadas = []; // Array para almacenar las opciones seleccionadas

    public function mount()
    {
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
        $this->familiasSeleccionadas = []; // Inicializar como arreglo vacío
    }

    public function addTelefono()
    {
        $this->telefonos[] = ['nombre' => '', 'numero' => ''];
    }

    public function removeTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos); // Reindexar el array
    }


    public function addFamilia()
    {
        $idFamiliaPadre = null;
        foreach (array_reverse($this->seleccionadas) as $seleccionada) {
            if ($seleccionada != 0) {
                $idFamiliaPadre = $seleccionada;
                break;
            }
        }
        if ($idFamiliaPadre) {
            $familia = Familia::find($idFamiliaPadre);
            $this->familiasSeleccionadas[] = $familia;
        }
    }


    public function confirmFamilia()
    {
        $this->addFamilia();
        $this->openModalFamilias = false;
        $this->reset('seleccionadas', 'niveles');
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
    }

    public function removeFamilia($index)
    {
        unset($this->familiasSeleccionadas[$index]);
        $this->familiasSeleccionadas = array_values($this->familiasSeleccionadas); // Reindexar el arreglo
    }

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
        $this->validate(Proveedor::rules(), Proveedor::messages());

        $facturacion = null;
        if ($this->facturacion) {
            $facturacion = $this->facturacion->store('archivosFacturacionProveedores', 'public');
        }

        $bancarios = null;
        if ($this->bancarios) {
            $bancarios = $this->bancarios->store('archivosBancariosProveedores', 'public');
        }

        $proveedor = Proveedor::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'correo' => $this->correo,
            'rfc' => $this->rfc,
            'archivo_facturacion_pdf' => $facturacion,
            'datos_bancarios_pdf' => $bancarios,
        ]);

        // Guardar los teléfonos en el modelo Telefono
        foreach ($this->telefonos as $telefono) {
            Telefono::create([
                'nombre' => $telefono['nombre'], // Guardar nombre de contacto
                'numero' => $telefono['numero'], // Guardar número de teléfono
                'proveedor_id' => $proveedor->id,
            ]);
        }

        foreach ($this->familiasSeleccionadas as $familia) {
            if (is_object($familia) && get_class($familia) === Familia::class) {
                ProveedorHasFamilia::create([
                    'proveedor_id' => $proveedor->id,
                    'familia_id' => $familia->id, // Acceder al ID de la familia
                ]);
            }
        }



        $this->reset('openModalFamilias', 'nombre', 'descripcion', 'correo', 'rfc', 'facturacion', 'bancarios', 'telefonos');

        
    return ['proveedor_id' => $proveedor->id];
    }

    public function calcularSubfamilias($idFamiliaSeleccionada, $nivel)
    {
        // Llama al método del modelo y actualiza las propiedades locales
        $resultado = Familia::calcularSubfamilias($idFamiliaSeleccionada, $nivel, $this->niveles, $this->seleccionadas);

        $this->niveles = $resultado['niveles'];
        $this->seleccionadas = $resultado['seleccionadas'];
    }
}
