<?php

namespace App\Livewire;

use App\Models\Proyecto;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCancelaciones extends Component
{
    use WithPagination;

    public $openModalRespuestaCancelacion = false;

    public $searchTerm = '';

    public $idProyectoActual;
    public $nombreProyecto;
    public $motivo_finalizacion;
    public $motivo_finalizacion_alterno;
    public $motivo_detallado;

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Proyecto::all();
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%");
            });
        }

        // // Filtro de estado
        // if ($this->statusFiltroDeBusqueda !== "2" && $this->statusFiltroDeBusqueda !== null) {
        //     $query->where('status', $this->statusFiltroDeBusqueda);
        // }

        $proyectos = Proyecto::with('cliente.user')->select(
            'id',
            'nombre',
            'cliente_id',
            'tipo',
            'proceso',
            'estado',
            'fecha'
        )->paginate(10);

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

    public function evaluarCancelacion($idProyecto)
    {
        $this->idProyectoActual = $idProyecto;

        $proyecto = Proyecto::find($idProyecto);

        if (!$proyecto) {
            session()->flash('error', 'El proyecto no fue encontrado.');
            return;
        }

        $this->nombreProyecto = $proyecto->nombre;
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

    public function removeEvaluarCancelacion()
    {
        $this->reset('openModalRespuestaCancelacion');
        $this->resetValidation();
        $this->openModalRespuestaCancelacion = false;
    }

    public function rechazarCancelacion()
    {
        // Validar los campos del formulario
        // $this->validate(Proyecto::rulesSolicitoCancelacion(), Proyecto::messagesSolicitoCancelacion());
        // Obtener el proyecto existente en la base de datos
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

        // Limpiar valores del modal
        $this->reset('openModalRespuestaCancelacion', 'motivo_finalizacion', 'motivo_detallado');
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
            'proceso' => 5,
        ]);
        $proyecto->save();

        // Limpiar valores del modal
        $this->reset('openModalRespuestaCancelacion', 'motivo_finalizacion', 'motivo_detallado');
        $this->dispatch('refresh');
        $this->resetValidation();
    }
}
