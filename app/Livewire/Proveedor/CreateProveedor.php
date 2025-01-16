<?php
namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use App\Models\Telefono;  // Importar el modelo de Telefonos
use App\Models\Familia;   // Importar el modelo de Familia
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Component;

class CreateProveedor extends Component
{
    use WithFileUploads;
    public $open = false;
    public $nombre, $descripcion, $correo, $rfc, $facturacion, $bancarios, $telefonos = [''];  // Inicializar con un campo de teléfono
    public $familias, $familiasSeleccionadas = [''];  // Inicializar con un campo de familia
    public $fileNameFacturacion, $fileNameBancarios;

    public function mount()
    {
        $this->familias = Familia::where('estadoEliminacion', 0)->get();
    }

    public function addTelefono()
    {
        $this->telefonos[] = '';
    }

    public function removeTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos); // Reindexar el array
    }

    public function addFamilia()
    {
        $this->familiasSeleccionadas[] = '';
    }

    public function removeFamilia($index)
    {
        unset($this->familiasSeleccionadas[$index]);
        $this->familiasSeleccionadas = array_values($this->familiasSeleccionadas); // Reindexar el array
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
                'numero' => $telefono,
                'proveedor_id' => $proveedor->id,
            ]);
        }

        // Guardar las familias en la tabla proveedor_has_familia
        foreach ($this->familiasSeleccionadas as $familiaId) {
            if ($familiaId) {
                $proveedor->familias()->attach($familiaId);
            }
        }

        $this->reset('open', 'nombre', 'descripcion', 'correo', 'rfc', 'facturacion', 'bancarios', 'telefonos', 'familiasSeleccionadas');
        
        return true;
    }
}
