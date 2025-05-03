<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\Role;
use App\Notifications\SolicitudCancelacion;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class VistaEspecifica extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $searchTerm = '';
    protected $listeners = ['refresh' => 'render'];

    public $statusFiltro = 0; // Filtro de estado
    public $statusProcesos = 6; // Filtro de procesos
    public $statusTipos = 3; // Filtro de tipos

    public $clienteEspecifico;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];

    public $openModalCreacionProyecto = false;

    public $openModalActualizarProyecto = false;
    public $idProyectoActual;

    public $openModalCancelarProyecto = false;
    public $culminacion;
    public $motivo_finalizacion;
    public $motivo_finalizacion_alterno;
    public $motivo_detallado;


    public $tipoDeProyectoSelecionado;
    public $fileNamePdf;
    public $archivoSubido;
    public $idDireccionParaProyecto;
    public $nombreProyecto;
    public $datosGenrales = [['frente' => '', 'fondo' => '', 'alturaTecho' => '', 'areaTotal' => '', 'alturaMuros' => '', 'canalon' => '', 'perimetral' => '', 'caballete' => '']];
    public $adicionales = [['estructura' => '', 'cantidad' => '']];
    public $preferencia;
    public $listaACotizarTxt;

    public function mount($idCliente)
    {
        $this->clienteEspecifico = Cliente::findOrFail($idCliente);

        // Procesar teléfonos
        $telefonos = json_decode($this->clienteEspecifico->telefono, true);
        $telefonos = array_filter($telefonos, function ($telefonos) {
            return !empty($telefonos['nombre']) || !empty($telefonos['numero']);
        });
        $this->telefonos = !empty($telefonos) ? $telefonos : null;

        // Procesar datos bancarios
        $bancarios = json_decode($this->clienteEspecifico->bancarios, true);
        $bancarios = array_filter($bancarios, function ($bancarios) {
            return !empty($bancarios['banco']) || !empty($bancarios['titular']) || !empty($bancarios['cuenta']) || !empty($bancarios['clave']);
        });
        $this->bancarios = !empty($bancarios) ? $bancarios : null;
    }

    public function render()
    {
        // Obtener los proyectos del cliente específico y aplicar los filtros y la búsqueda
        $proyectos = Proyecto::where('cliente_id', $this->clienteEspecifico->id)
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('tipo', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('estado', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->statusFiltro != 0, function ($query) {
                $query->where('estado', $this->statusFiltro);
            })
            ->when($this->statusProcesos != 6, function ($query) {
                $query->where('proceso', $this->statusProcesos);
            })
            ->when($this->statusTipos != 3, function ($query) {
                $query->where('tipo', $this->statusTipos);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Usar paginate() con el trait WithPagination

        // Pasar los proyectos a la vista
        return view('livewire.cliente.vista-especifica', [
            'proyectos' => $proyectos,
        ]);
    }
    public function search()
    {
        $this->resetPage(); // Reiniciar la paginación al realizar una búsqueda
    }

    public function cancelar()
    {
        $this->reset('openModalCreacionProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->resetValidation();
        $this->dispatch('refresh');
    }
    //Cargar los datos del proyecto a editar
    public function cargarDatosProyecto($idProyecto)
    {
        $this->idProyectoActual = $idProyecto; // Ahora la propiedad tiene el ID correcto

        $proyecto = Proyecto::find($idProyecto);

        if (!$proyecto) {
            abort(404, 'Proyecto no encontrado');
        }

        $this->nombreProyecto = $proyecto->nombre;
        $this->preferencia = $proyecto->preferencia;
        $this->tipoDeProyectoSelecionado = $proyecto->tipo;
        $this->idDireccionParaProyecto = $proyecto->direccion_id;
        $this->listaACotizarTxt = $proyecto->items_cotizar;
        $this->datosGenrales = json_decode($proyecto->datos_medidas, true) ?: [['frente' => '', 'fondo' => '', 'alturaTecho' => '', 'areaTotal' => '', 'alturaMuros' => '', 'canalon' => '', 'perimetral' => '', 'caballete' => '']];
        $this->adicionales = json_decode($proyecto->datos_adicionales, true) ?: [['estructura' => '', 'cantidad' => '']];

        $this->openModalActualizarProyecto = true;
    }

    public function cancelarUpdate()
    {
        $this->reset('openModalActualizarProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->resetValidation();
        $this->openModalActualizarProyecto = false;
    }

    //Metodos para solicitar cancelacion de proyecto
    public function solicitarCancelacion($idProyecto)
    {
        $this->idProyectoActual = $idProyecto;

        $proyecto = Proyecto::find($idProyecto);

        $this->nombreProyecto = $proyecto->nombre;
        $this->motivo_finalizacion = '';
        $this->motivo_detallado = '';
        $this->openModalCancelarProyecto = true;
    }

    public function removeCancelacion()
    {
        $this->reset('openModalCancelarProyecto');
        $this->resetValidation();
        $this->openModalCancelarProyecto = false;
    }

    //Asociar los motivos de la cancelacion al proyecto actual mediante su ID

    public function enviarSolicitudCancelar()
    {
        // Validar los campos del formulario
        $this->validate(Proyecto::rulesSolicitoCancelacion(), Proyecto::messagesSolicitoCancelacion());
        // Obtener el proyecto existente en la base de datos
        $proyecto = Proyecto::find($this->idProyectoActual);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return redirect()->route('ventas.clientes.vistaProyectos');
        }
        //Almacenar el motivo de finalizacion en particularo
        $motivoFinalizacion = $this->motivo_finalizacion === 'otro'
            ? 'Otro: ' . $this->motivo_finalizacion_alterno
            : $this->motivo_finalizacion;

        // Insertar los valores en los campos específicos sin crear un nuevo registro
        $proyecto->fill([
            'culminacion' => 0,
            'motivo_finalizacion' => $motivoFinalizacion,
            'motivo_detallado' => $this->motivo_detallado,
            'estado' => 2,
        ]);
        $proyecto->save();

        //Crear notificacion para el administrador
        $adminUsers = Role::where('name', 'Administrador')->first()->users;
        Notification::send($adminUsers, new SolicitudCancelacion($proyecto->id, $proyecto->nombre, auth()->user()->name));

        // Limpiar valores después de la actualización
        $this->reset('openModalCancelarProyecto', 'culminacion', 'motivo_finalizacion', 'motivo_detallado');
        $this->dispatch('refresh');
        $this->resetValidation();
    }


    public function editCliente($idCliente)
    {
        return redirect()->route('ventas.cliente.EditCliente', ['idcliente' => $idCliente]);
    }

    public function regresarGestionClientes()
    {
        return redirect()->route('ventas.clientes.gestionClientes');
    }

    public function asignarTipoDeProyecto($tipo)
    {
        $this->tipoDeProyectoSelecionado = $tipo;
    }

    public function updatedArchivoSubido()
    {
        $this->fileNamePdf = $this->archivoSubido ? $this->archivoSubido->getClientOriginalName() : '';
    }

    public function addAdicionales()
    {
        $this->adicionales[] = ['estructura' => '', 'cantidad' => ''];
    }

    public function removeAdicionales($index)
    {
        unset($this->adicionales[$index]);
        $this->adicionales = array_values($this->adicionales); // Reindexar el array
    }

    public function saveProyecto()
    {
        //Reglas de validacion
        $this->validate(Proyecto::rules(), Proyecto::messages());

        $clienteId = $this->clienteEspecifico->id;
        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : null;

        $proyecto = Proyecto::create([
            'cliente_id' => $clienteId,
            'direccion_id' => $this->idDireccionParaProyecto,
            'proceso' => 0,
            'nombre' => $this->nombreProyecto,
            'preferencia' => $this->preferencia,
            'listas' => 0,
            'cotisaciones' => 0,
            'ordenes' => 0,
            'tipo' => $this->tipoDeProyectoSelecionado,
            'estado' => 1,
            'archivo' => $archivoSubido,
            'items_cotizar' => $this->listaACotizarTxt,
            'datos_medidas' => json_encode($this->datosGenrales),
            'datos_adicionales' => json_encode($this->adicionales),
            'fecha' => now(),
        ]);
        $this->clienteEspecifico->increment('proyectos'); // Incrementa el campo "proyectos" en 1
        $this->clienteEspecifico->increment('proyectos_activos');

        $this->reset('openModalCreacionProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->dispatch('refresh');
        $this->resetValidation();
        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $proyecto->id]);
    }

    public function actualizarProyecto()
    {
        // Validamos los datos antes de actualizar
        $this->validate(Proyecto::rules(), Proyecto::messages());

        // Obtener el proyecto por su ID
        $proyecto = Proyecto::find($this->idProyectoActual);

        if (!$proyecto) {
            abort(404, 'Proyecto no encontrado');
        }

        // Si hay un nuevo archivo, almacenarlo
        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : $proyecto->archivo;

        $this->idDireccionParaProyecto = !empty($this->idDireccionParaProyecto) ? $this->idDireccionParaProyecto : null;

        $preferencia = !empty($this->preferencia) ? $this->preferencia : null;

        // Actualizar los datos del proyecto
        $proyecto->update([
            'direccion_id' => $this->idDireccionParaProyecto,
            'nombre' => $this->nombreProyecto,
            'preferencia' => $preferencia,
            'tipo' => $this->tipoDeProyectoSelecionado,
            'archivo' => $archivoSubido,
            'items_cotizar' => $this->listaACotizarTxt,
            'datos_medidas' => json_encode($this->datosGenrales),
            'datos_adicionales' => json_encode($this->adicionales),
            'fecha' => now(),
        ]);

        // Resetear variables y cerrar el modal
        $this->reset('openModalActualizarProyecto', 'archivoSubido', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->dispatch('refresh');
        $this->resetValidation();
    }

    public function asignarDireccion($idDIreccion)
    {
        $this->idDireccionParaProyecto = $idDIreccion;
    }

    public function asiganrPreferencia($preferencia)
    {
        $this->preferencia = $preferencia;
    }

    public function viewProyecto($idProyecto)
    {
        $proyecto = Proyecto::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $idProyecto]);
    }
}
