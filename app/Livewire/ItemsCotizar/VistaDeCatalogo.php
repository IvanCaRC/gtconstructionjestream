<?php

namespace App\Livewire\ItemsCotizar;

use App\Models\Cotizacion;
use Livewire\Component;


use Livewire\WithPagination;
use App\Models\Familia;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;

class VistaDeCatalogo extends Component
{


    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    public $tipoDeVista = true;
    public $statusFiltroDeBusqueda;
    public $familiasSeleccionadas = [];
    public $desplegables = [];
    public $listadeUsuarioActiva;
    public $usuarioActual;

    protected $listeners = [
        'mountVistaDeLista' => 'mount',
        'renderVistaDeLista' => 'render',
    ];



    public $nombreProyecto;
    public $nombreCliente;
    public $idLista;
    public $idCotizaciones;

    public $itemsEnLista = [];


    public function mount()
    {
        // Obtener el usuario actual
        $this->usuarioActual = Auth::user();

        if ($this->usuarioActual->cotizaciones) {
            // Recuperamos el proyecto relacionado
            // Si se encuentra el registro, guardar el nombre; si no, asignar null
            $cotizacionId = $this->usuarioActual->cotizaciones;
            $cotizacionActiva = Cotizacion::find($cotizacionId);
            if ($cotizacionActiva) {
                // Si existe la lista activa, obtener sus detalles
                $this->idCotizaciones = $cotizacionActiva->id;
                $this->listadeUsuarioActiva = $cotizacionActiva->nombre ?? 'Sin nombre';
            } else {
                // Si no se encuentra la lista, establecer las propiedades en null
                $this->establecerPropiedadesNulas();
            }
        } else {
            // Si el usuario no tiene una lista asignada, establecer las propiedades en null
            $this->establecerPropiedadesNulas();
        }
    }

    private function establecerPropiedadesNulas()
    {
        $this->idCotizaciones = null;
        $this->idLista = null;
        $this->listadeUsuarioActiva = null;
        $this->nombreProyecto = null;
        $this->nombreCliente = null;
        $this->itemsEnLista = [];
    }

    public function verLista($idLista)
    {

        return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', ['idCotisacion' => $idLista]);
    }


    public function render()
    {

        return view('livewire.items-cotizar.vista-de-catalogo');
    }


    public function desactivarLista($id)
    {
        Auth::user()->update(['cotizaciones' => null]);
        return redirect()->route('compras.catalogoCotisacion.catalogoItem');
    }
}
