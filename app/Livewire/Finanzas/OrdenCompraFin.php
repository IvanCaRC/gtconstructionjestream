<?php

namespace App\Livewire\Finanzas;

use App\Models\Cotizacion;
use App\Models\ListasCotizar;
use App\Models\ordenCompra;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class OrdenCompraFin extends Component
{
    use WithPagination;
    use WithFileUploads;

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
    public $archivoSubido;
    public $datosParaPagra;
    public $fileNamePdf;


    public function abrirModalPagar($ordenCompraId)
    {
        $this->ordenCompraSeleccionada = ordenCompra::findOrFail($ordenCompraId);
        $this->montoPagar = $this->ordenCompraSeleccionada->montoPagar;
        $this->openModalPagar = true;
        $this->datosParaPagra = json_decode($this->ordenCompraSeleccionada->historial, true) ?: [];
    }

    public function cerrarModal()
    {
        $this->reset(['openModalPagar', 'ordenCompraSeleccionada', 'cantidadPagar', 'montoPagar', 'archivoSubido', 'datosParaPagra', 'fileNamePdf']);
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
            $this->reset(['openModalPagar', 'ordenCompraSeleccionada', 'cantidadPagar', 'montoPagar', 'archivoSubido', 'datosParaPagra', 'fileNamePdf']);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('mostrarAlerta', [
                'icono' => 'error',
                'titulo' => 'Error',
                'texto' => 'Ocurrió un error: ' . $e->getMessage()
            ]);
            $this->reset(['openModalPagar', 'ordenCompraSeleccionada', 'cantidadPagar', 'montoPagar', 'archivoSubido', 'datosParaPagra', 'fileNamePdf']);
            return false;
        }
    }

    public function liquidar()
    {
        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : null;


        $this->datosParaPagra[] = [
            'monto' => number_format($this->ordenCompraSeleccionada->montoPagar, 2, '.', ''), // genera "1.30" como string
            'Archivo' => $archivoSubido,
        ];

        $this->ordenCompraSeleccionada->update([
            'montoPagar' => 0,
            'estado' => 1, // 1 = Liquidada
            'historial' => json_encode($this->datosParaPagra),
        ]);
    }

    public function Abonar4cantidad($cantidad)
    {
        $cantidad2 = round(floatval($cantidad), 2);
        $montoActual = round(floatval($this->ordenCompraSeleccionada->montoPagar), 2);
        $nuevoMonto = round($montoActual - $cantidad2, 2);
        if (abs($nuevoMonto) < 0.01) {
            $nuevoMonto = 0.00;
        }
        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : null;
        $this->datosParaPagra[] = [
            'monto' => number_format($cantidad2, 2, '.', ''), // genera "1.30" como string
            'Archivo' => $archivoSubido,
        ];
        $this->ordenCompraSeleccionada->update([
            'montoPagar' => $nuevoMonto,
            'estado' => 0, // 0 = Pendiente
            'historial' => json_encode($this->datosParaPagra),
        ]);
    }
    public function viewOrden($idProyecto)
    {
        $proyecto = ordenCompra::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('finanzas.verPagos.verPagosOrdenCompra', ['id' => $idProyecto]);
    }
}
