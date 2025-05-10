<?php

namespace App\Livewire;

use App\Models\Proyecto;
use App\Notifications\ConfirmacionCancelacionNotification;
use Livewire\Component;
use Livewire\WithPagination;
use App\Notifications\RechazoCancelacionNotification;

class ShowCancelaciones extends Component
{
    use WithPagination;

    public $openModalRespuestaCancelacion = false;
    public $openModalRespuestaCulminacion = false;

    public $searchTerm = '';
    public $statusFiltroDeBusqueda;
    public $tipoFiltroDeBusqueda;

    public $idProyectoActual;
    public $nombreProyecto;
    public $estadoProyecto;

    public $motivo_finalizacion;
    public $motivo_finalizacion_alterno;
    public $motivo_detallado;



    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['statusFiltroDeBusqueda', 'tipoFiltroDeBusqueda'])) {
            $this->filter();  // Llama al método de filtro para restablecer la página y aplicar los filtros
        }
    }

    public function filter()
    {
        $this->resetPage();  // Asegura que la paginación se restablezca
    }

    public function render()
    {

        $query = Proyecto::whereNotNull('culminacion');
        //Aplicar busqueda
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%");
            });
        }
        //Filtro en funcion del estado del proyecto 
        $query->when($this->statusFiltroDeBusqueda != 0, function ($query) {
            if ($this->statusFiltroDeBusqueda == 1) {
                $query->where('estado', 1); // Proyectos Activos
            } elseif ($this->statusFiltroDeBusqueda == 2) {
                $query->where('estado', 2); // Proyectos inactivos
            } elseif ($this->statusFiltroDeBusqueda == 3) {
                $query->where('estado', 3); // Proyectos Cancelados
            }
        });
        //Filtro en funcion del tipo de solicitud
        $query->when($this->tipoFiltroDeBusqueda != 0, function ($query) {
            if ($this->tipoFiltroDeBusqueda == 1) {
                $query->where('culminacion', 0); // Culminacion por cancelacion
            } elseif ($this->tipoFiltroDeBusqueda == 2) {
                $query->where('culminacion', 1); // PCulminacion por concretacion
            }
        });

        // **Ordenar resultados**
        $query->orderBy('created_at', 'desc'); // Orden descendente (más recientes primero)

        $proyectos =  $query->paginate(10);

        return view('livewire.show-cancelaciones', [
            'proyectos' => $proyectos
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function viewProyecto($idProyecto)
    {
        $proyecto = Proyecto::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $idProyecto]);
    }

    public $mensajeError = null;

    public function evaluarSolicitud($idProyecto)
    {
        $proyecto = Proyecto::findOrFail($idProyecto);

        if ($proyecto->culminacion === 0) {
            return $this->evaluarCancelacion($idProyecto);
        } elseif ($proyecto->culminacion === 1) {
            return $this->evaluarCulminacion($idProyecto);
        } else {
            $this->mensajeError = 'Estado de culminación inválido para el proyecto.';
        }
    }

    public function evaluarCancelacion($idProyecto)
    {
        $this->idProyectoActual = $idProyecto;

        $proyecto = Proyecto::find($idProyecto);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return;
        }

        $this->nombreProyecto = $proyecto->nombre;
        $this->estadoProyecto = $proyecto->estado;
        // Verificar si el motivo tiene el formato "Otro: ..."
        if (str_starts_with($proyecto->motivo_finalizacion, 'Otro: ')) {
            $this->motivo_finalizacion = 'otro';
            $this->motivo_finalizacion_alterno = substr($proyecto->motivo_finalizacion, 6); // Extrae solo la parte alterna
        } else {
            $this->motivo_finalizacion = $proyecto->motivo_finalizacion;
            $this->motivo_finalizacion_alterno = ''; // En caso contrario, no hay motivo alterno
        }
        $this->motivo_detallado = $proyecto->motivo_detallado;
        $this->openModalRespuestaCancelacion = true;
    }

    public function evaluarCulminacion($idProyecto)
    {
        $this->idProyectoActual = $idProyecto;

        $proyecto = Proyecto::find($idProyecto);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return;
        }

        $this->nombreProyecto = $proyecto->nombre;
        $this->estadoProyecto = $proyecto->estado;
        // Verificar si el motivo tiene el formato "Otro: ..."
        if (str_starts_with($proyecto->motivo_finalizacion, 'Otro: ')) {
            $this->motivo_finalizacion = 'otro';
            $this->motivo_finalizacion_alterno = substr($proyecto->motivo_finalizacion, 6); // Extrae solo la parte alterna
        } else {
            $this->motivo_finalizacion = $proyecto->motivo_finalizacion;
            $this->motivo_finalizacion_alterno = ''; // En caso contrario, no hay motivo alterno
        }
        $this->motivo_detallado = $proyecto->motivo_detallado;
        $this->openModalRespuestaCulminacion = true;
    }

    public function removeEvaluarCancelacion()
    {
        $this->reset('openModalRespuestaCancelacion');
        $this->resetValidation();
        $this->openModalRespuestaCancelacion = false;
    }

    public function removeEvaluarCulminacion()
    {
        $this->reset('openModalRespuestaCulminacion');
        $this->resetValidation();
        $this->openModalRespuestaCulminacion = false;
    }

    public function rechazarCancelacion()
    {
        $proyecto = Proyecto::find($this->idProyectoActual);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return redirect()->route('ventas.clientes.vistaProyectos');
        }

        // Remover los campos de la cancelacion para reactivar el proyecto
        $proyecto->fill([
            'culminacion' => null,
            'motivo_finalizacion' => null,
            'motivo_detallado' => null,
            'estado' => 1,
        ]);
        $proyecto->save();

        //Procedo de envio de notificacion al usuario
        //Obtener al usuario al que sera enviada la notifiacion.
        $cliente = $proyecto->cliente;
        $usuario = $cliente->user;
        //Confirmar que el usuario existe y enviar la notificacion
        if ($usuario) {
            $usuario->notify(new RechazoCancelacionNotification($proyecto->id, $proyecto->nombre, $cliente->id));
        }

        // Limpiar valores del modal
        $this->reset('openModalRespuestaCancelacion', 'motivo_finalizacion', 'motivo_detallado');
        $this->dispatch('refresh');
        $this->resetValidation();
    }

    public function rechazarCulminacion()
    {
        $proyecto = Proyecto::find($this->idProyectoActual);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return redirect()->route('ventas.clientes.vistaProyectos');
        }

        // Remover los campos de la culminacion para reactivar el proyecto
        $proyecto->fill([
            'culminacion' => null,
            'motivo_finalizacion' => null,
            'motivo_detallado' => null,
            'estado' => 1,
        ]);
        $proyecto->save();

        //Procedo de envio de notificacion al usuario
        //Obtener al usuario al que sera enviada la notifiacion.
        $cliente = $proyecto->cliente;
        $usuario = $cliente->user;
        //Confirmar que el usuario existe y enviar la notificacion
        // if ($usuario) {
        //     $usuario->notify(new RechazoCancelacionNotification($proyecto->id, $proyecto->nombre, $cliente->id));
        // }

        // Limpiar valores del modal
        $this->reset('openModalRespuestaCulminacion', 'motivo_finalizacion', 'motivo_detallado');
        $this->dispatch('refresh');
        $this->resetValidation();
    }

    public function aceptarCancelacion()
    {
        //Validar los campos del formulario antes de marcarlo como cancelado.
        $this->validate(Proyecto::rulesSolicitoCancelacion(), Proyecto::messagesSolicitoCancelacion());
        // Obtener el proyecto existente en la base de datos
        $proyecto = Proyecto::find($this->idProyectoActual);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return redirect()->route('ventas.clientes.vistaProyectos');
        }

        // Remover los campos de la cancelacion para reactivar el proyecto
        $proyecto->fill([
            'estado' => 3,
        ]);
        $proyecto->save();

        // Obtener el usuario asignado al proyecto
        $cliente = $proyecto->cliente;
        $usuario = $cliente->user;

        // Enviar la notificación si el usuario existe
        if ($usuario) {
            $usuario->notify(new ConfirmacionCancelacionNotification($proyecto->id, $proyecto->nombre, $cliente->id));
        }

        session()->flash('success', 'La cancelacion a sido realizada exitosamente.');

        // Limpiar valores del modal
        $this->reset('openModalRespuestaCancelacion', 'motivo_finalizacion', 'motivo_detallado');
        $this->dispatch('refresh');
        $this->resetValidation();
    }

    public function aceptarCulminacion()
    {
        //Validar los campos del formulario antes de marcarlo como cancelado.
        $this->validate(Proyecto::rulesSolicitoCancelacion(), Proyecto::messagesSolicitoCulminacion());
        // Obtener el proyecto existente en la base de datos
        $proyecto = Proyecto::find($this->idProyectoActual);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return redirect()->route('ventas.clientes.vistaProyectos');
        }

        // Remover los campos de la cancelacion para reactivar el proyecto
        $proyecto->fill([
            'estado' => 4,
        ]);
        $proyecto->save();

        // Obtener el usuario asignado al proyecto
        $cliente = $proyecto->cliente;
        $usuario = $cliente->user;

        // Enviar la notificación si el usuario existe
        // if ($usuario) {
        //     $usuario->notify(new ConfirmacionCancelacionNotification($proyecto->id, $proyecto->nombre, $cliente->id));
        // }

        session()->flash('success', 'La cancelacion a sido realizada exitosamente.');

        // Limpiar valores del modal
        $this->reset('openModalRespuestaCulminacion', 'motivo_finalizacion', 'motivo_detallado');
        $this->dispatch('refresh');
        $this->resetValidation();
    }
}
