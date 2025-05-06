<?php

namespace App\Livewire\ItemsCotizar;

use App\Models\Cotizacion;
use Livewire\Component;


use App\Models\ListasCotizar;
use App\Models\Proyecto;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CotizacionEnviada;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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

    public function enviar()
    {
        $cotizacionActiva = Cotizacion::find($this->idCotizaciones);

        // Verificar si la cotización existe antes de modificarla
        if ($cotizacionActiva) {
            $cotizacionActiva->estado = 1;
            $cotizacionActiva->save(); // Guardar el cambio en la base de datos
            $lista = ListasCotizar::find($cotizacionActiva->lista_cotizar_id);
        }

        if ($lista) {
            $lista->estado = 3;
            $lista->save(); // Guardar el cambio en la base de datos
            $proyecto = Proyecto::find($lista->proyecto_id);
        }

        if ($proyecto) {
            $proyecto->proceso = 2;
            $proyecto->save(); // Guardar el cambio en la base de datos
        }

        // **Enviar notificación al usuario asociado (`usuario_id`)**
        if ($cotizacionActiva->usuario_id) {
            $usuarioCompras = User::find($cotizacionActiva->usuario_id); // Obtener usuario específico

            if ($usuarioCompras) {
                $usuarioCompras->notify(new CotizacionEnviada($proyecto->nombre, $lista->nombre));
            }
        }

        // $adminUsers = Role::where('name', 'Administrador')->first()->users;
        // Notification::send($adminUsers, new CotizacionEnviada($proyecto->id, $proyecto->nombre, auth()->user()->name));

        // Limpiar cotizaciones del usuario autenticado
        Auth::user()->update(['cotizaciones' => null]);

        // Redirigir al usuario a la ruta específica
        return true;
    }

    public function desactivarLista($id)
    {
        Auth::user()->update(['cotizaciones' => null]);
        return redirect()->route('compras.catalogoCotisacion.catalogoItem');
    }
}
