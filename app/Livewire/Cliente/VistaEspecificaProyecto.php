<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\ListasCotizar;
use App\Models\Proyecto;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf; // Asegúrate de importar la fachada de DomPDF
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VistaEspecificaProyecto extends Component
{
    public $proyecto;
    public $searchTerm = '';
    public $statusFiltro = 0; // Filtro de estado
    public $clienteEspecifico;
    public $listaPreliminar;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];
    public $datosGenrales = [['frente' => '', 'fondo' => '', 'alturaTecho' => '', 'areaTotal' => '', 'alturaMuros' => '', 'canalon' => '', 'perimetral' => '', 'caballete' => '']];
    public $adicionales = [['estructura' => '', 'cantidad' => '']];

    public function mount($idProyecto)
    {
        $this->proyecto = Proyecto::findOrFail($idProyecto);
        $this->clienteEspecifico = Cliente::findOrFail($this->proyecto->cliente_id);
        $this->listaPreliminar = $this->proyecto->items_cotizar;

        // Procesar teléfonos
        $telefonos = json_decode($this->clienteEspecifico->telefono, true);
        $telefonos = array_filter($telefonos, function ($telefono) {
            return !empty($telefono['nombre']) || !empty($telefono['numero']);
        });
        $this->telefonos = !empty($telefonos) ? $telefonos : null;

        // Procesar datos bancarios
        $bancarios = json_decode($this->clienteEspecifico->bancarios, true);
        $bancarios = array_filter($bancarios, function ($bancario) {
            return !empty($bancario['banco']) || !empty($bancario['titular']) || !empty($bancario['cuenta']) || !empty($bancario['clave']);
        });
        $this->bancarios = !empty($bancarios) ? $bancarios : null;

        $datosGenrales = json_decode($this->proyecto->datos_medidas, true);
        $datosGenrales = array_filter($datosGenrales, function ($datosGenrale) {
            return !empty($datosGenrale['frente']) || !empty($datosGenrale['fondo']) || !empty($datosGenrale['alturaTecho']) || !empty($datosGenrale['areaTotal']) || !empty($datosGenrale['alturaMuros']) || !empty($datosGenrale['canalon']) || !empty($datosGenrale['perimetral']) || !empty($datosGenrale['caballete']);
        });
        $this->datosGenrales = !empty($datosGenrales) ? $datosGenrales : null;

        $adicionales = json_decode($this->proyecto->datos_adicionales, true);
        $adicionales = array_filter($adicionales, function ($adicionale) {
            return !empty($adicionale['estructura']) || !empty($adicionale['cantidad']);
        });
        $this->adicionales = !empty($adicionales) ? $adicionales : null;
    }

    public function render()
    {
        $listas = ListasCotizar::where('proyecto_id', $this->proyecto->id)
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('estado', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->statusFiltro != 0, function ($query) {
                $query->where('estado', $this->statusFiltro);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Usar paginate() con el trait WithPagination

        // Pasar los proyectos a la vista

        return view('livewire.cliente.vista-especifica-proyecto', [
            'pdfUrl' => route('proyecto.pdf', ['id' => $this->proyecto->id]),
        ], ['listas' => $listas]);
    }

    public function editCliente($idCliente)
    {
        return redirect()->route('ventas.cliente.EditCliente', ['idcliente' => $idCliente]);
    }

    public function regresarGestionClientes()
    {
        return redirect()->route('ventas.clientes.gestionClientes');
    }

    public function saveListaNueva()
    {
        $proyecto  = $this->proyecto->id;
        $listasEnEstado1 = ListasCotizar::where('proyecto_id', $proyecto)
            ->where('estado', 1)
            ->get();

        foreach ($listasEnEstado1 as $lista) {
            $lista->update(['estado' => 2]);
        }

        $proyectoLista  = $this->proyecto->listas;
        $user = Auth::user();
        $idUser = $user->id;

        $resultado = 'numero ' . strval($proyectoLista + 1);



        $listaACotizar = ListasCotizar::create([
            'proyecto_id' => $proyecto,
            'usuario_id' => $idUser,
            'nombre' => $resultado,
            'estado' => 1,
        ]);
        
        Auth::user()->update(['lista' => $listaACotizar->id]);
        
        $this->proyecto->increment('listas'); // Incrementa el campo "proyectos" en 1


        $this->dispatch('refresh');
        // return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $proyecto->id]);
        return true;
    }

    public function activar($idLista)
    {
        // Buscar la lista actual
        $lista = ListasCotizar::find($idLista);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        // Obtener el proyecto seleccionado


        // Actualizar la lista
        $lista->update([
            'estado' => 1,
        ]);

        $this->dispatch('refresh');
        return true;
    }

    public function Dsactivar($proyectoId)
    {
        // Buscar la lista actual
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        // Obtener el proyecto seleccionado


        // Actualizar la lista
        $lista->update([
            'estado' => 3,
        ]);


        // Mensaje de éxito
        session()->flash('success', 'Lista fue enviada correctamente a la cotisacion.');

        // Cerrar el modal
        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $proyectoId]);
    }

    public function cancelar($proyectoId)
    {
        // Buscar la lista actual
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        // Obtener el proyecto seleccionado


        // Actualizar la lista
        $lista->update([
            'estado' => 3,
        ]);


        // Mensaje de éxito
        session()->flash('success', 'Lista fue enviada correctamente a la cotisacion.');

        // Cerrar el modal
        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $proyectoId]);
    }

    public function editar($proyectoId)
    {
        // Buscar la lista actual
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        // Obtener el proyecto seleccionado


        // Actualizar la lista
        $lista->update([
            'estado' => 3,
        ]);


        // Mensaje de éxito
        session()->flash('success', 'Lista fue enviada correctamente a la cotisacion.');

        // Cerrar el modal
        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $proyectoId]);
    }
}
