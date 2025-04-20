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

    public function toggleEstado($id)
    {
        $lista = ListasCotizar::find($id);

        if (!$lista) return;

        // Si la lista está inactiva, la activamos y desactivamos todas las demás del usuario
        if ($lista->estado != 1) {
            // Desactivar todas las demás listas del mismo usuario
            ListasCotizar::where('usuario_id', $lista->user_id)
                ->where('id', '!=', $lista->id)
                ->update(['estado' => 2]);

            // Activar la lista actual
            $lista->estado = 1;
        } else {
            // Si ya está activa, la desactivamos
            $lista->estado = 2;
        }

        $lista->save();
    }

    public function editarProyecto()
    {
        // Lógica para editar el proyecto
        dd('Método editarProyecto ejecutado correctamente');
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
        return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $listaACotizar->id]);
    }

    public function cancelar($id)
    {
        $lista = ListasCotizar::find($id);

        if ($lista) {
            $lista->estado = 4; // Estado 5 = Cancelado
            $lista->save();

            session()->flash('message', 'La lista ha sido cancelada correctamente.');
        } else {
            session()->flash('error', 'La lista no fue encontrada.');
        }
    }

    public function editarlista($id)
    {
        // Buscar la lista actual
        $lista = ListasCotizar::find($id);

        if ($lista) {
            $lista->estado = 1; // Estado 5 = Cancelado
            $lista->save();
            Auth::user()->update(['lista' => $lista->id]);

            session()->flash('message', 'La lista ha sido cancelada correctamente.');
        } else {
            session()->flash('error', 'La lista no fue encontrada.');
        }

        // Cerrar el modal
        return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $lista->id]);
    }
}
