<?php

namespace App\Livewire\Finanzas;

use App\Models\Cotizacion;
use App\Models\ListasCotizar;
use App\Models\ordenCompra;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OrdenCompraFin extends Component
{
    use WithPagination;

    public function render()
    {
        $query = Auth::user()->hasAnyRole(['Administrador', 'Finanzas'])
            ? $this->getQueryForAdmin()
            : $this->getQueryForRegularUser();

        $listas = $query->paginate(10);

        return view('livewire.finanzas.orden-compra-fin', compact('listas'));
    }

    private function getQueryForAdmin()
    {
        return ordenCompra::query()
            ->orderBy('created_at', 'desc')
        ;
    }

    /**
     * Construye la consulta para usuarios regulares
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQueryForRegularUser()
    {
        $usuarioId = Auth::id();

        return ordenCompra::query()
            ->where('id_usuario', $usuarioId) // Filtrar por usuario
            ->orderBy('created_at', 'desc')
        ;
    }

    public $openModalPagar = false;
    public $ordenCompraSeleccionada;
    public $cantidadPagar = 0;
    public $montoPagar = 0;

    public function abrirModalPagar($ordenCompraId)
    {
        $this->ordenCompraSeleccionada = ordenCompra::findOrFail($ordenCompraId);
        $this->montoPagar = $this->ordenCompraSeleccionada->montoPagar;
        $this->openModalPagar = true;
    }

    public function cerrarModal()
    {
        $this->reset(['openModalPagar', 'ordenCompraSeleccionada', 'cantidadPagar', 'montoPagar']);
    }

    public function aceptar()
    {
        $this->validate([
            'cantidadPagar' => 'required|numeric|min:0.01|max:' . $this->ordenCompraSeleccionada->montoPagar
        ]);

        try {
            DB::beginTransaction();

            if ($this->cantidadPagar < $this->ordenCompraSeleccionada->montoPagar) {
                $this->Abonar4cantidad($this->cantidadPagar);
                $mensaje = "Abono registrado correctamente";
            } else {
                $this->liquidar();
                $mensaje = "Orden liquidada completamente";
            }

            DB::commit();

            $this->dispatch('mostrarAlerta', [
                'icono' => 'success',
                'titulo' => 'Éxito',
                'texto' => $mensaje
            ]);

            $cotisacion = Cotizacion::findOrFail($this->ordenCompraSeleccionada->id_cotizacion);

            $ordenes = ordenCompra::where('id_cotizacion', $cotisacion->id)->get();

            if ($ordenes->every(fn($orden) => $orden->estado == 1)) {
                $cotisacion->update(['estado' => 5]);
                $ListaCotisar = ListasCotizar::findOrFail($cotisacion->lista_cotizar_id);
                $ListaCotisar->update([
                    'estado' => 7, // 1 = Liquidada
                ]);
                $proyecto = Proyecto::findOrFail($ListaCotisar->proyecto_id);
                $proyecto->update([
                    'proceso' => 6, // 1 = Liquidada
                ]);
            }

            $this->cerrarModal();
            $this->emit('pagoRealizado');
            $this->reset(['openModalPagar', 'ordenCompraSeleccionada', 'cantidadPagar', 'montoPagar']);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('mostrarAlerta', [
                'icono' => 'error',
                'titulo' => 'Error',
                'texto' => 'Ocurrió un error: ' . $e->getMessage()
            ]);
            $this->reset(['openModalPagar', 'ordenCompraSeleccionada', 'cantidadPagar', 'montoPagar']);
            return false;
        }
    }

    public function liquidar()
    {
        $this->ordenCompraSeleccionada->update([
            'montoPagar' => 0,
            'estado' => 1, // 1 = Liquidada
        ]);
    }

    public function Abonar4cantidad($cantidad)
    {
        $cantidad = round(floatval($cantidad), 2);
        $montoActual = round(floatval($this->ordenCompraSeleccionada->montoPagar), 2);
        $nuevoMonto = round($montoActual - $cantidad, 2);
        if (abs($nuevoMonto) < 0.01) {
            $nuevoMonto = 0.00;
        }
        $this->ordenCompraSeleccionada->update([
            'montoPagar' => $nuevoMonto,
            'estado' => 0, // 0 = Pendiente
        ]);
    }


}
