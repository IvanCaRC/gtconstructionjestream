<?php

namespace App\Livewire\Cotisaciones\OrdeneCompra;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Cotizacion;

use App\Models\ListasCotizar;
use App\Models\ordenCompra;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenCompraVistaUno extends Component
{

    use WithPagination;
    
    public $usosCfdi = [
        'G01' => 'Adquisición de mercancías',
        'G02' => 'Devoluciones, descuentos o bonificaciones',
        'G03' => 'Gastos en general',
        'I01' => 'Construcciones',
        'I02' => 'Mobiliario y equipo de oficina por inversiones',
        'I03' => 'Equipo de transporte',
        'I04' => 'Equipo de cómputo y accesorios',
        'I05' => 'Dados, troqueles, moldes, matrices y herramental',
        'I06' => 'Comunicaciones telefónicas',
        'I07' => 'Comunicaciones satelitales',
        'I08' => 'Otra maquinaria y equipo',
        'D01' => 'Honorarios médicos, dentales y gastos hospitalarios',
        'D02' => 'Gastos médicos por incapacidad o discapacidad',
        'D03' => 'Gastos funerales',
        'D04' => 'Donativos',
        'D05' => 'Intereses reales por créditos hipotecarios',
        'D06' => 'Aportaciones voluntarias al SAR',
        'D07' => 'Primas por seguros de gastos médicos',
        'D08' => 'Gastos de transportación escolar obligatoria',
        'D09' => 'Depósitos para el ahorro, planes de pensiones',
        'D10' => 'Pagos por servicios educativos (colegiaturas)',
        'S01' => 'Sin efectos fiscales',
        'CP01' => 'Pagos',
        'CN01' => 'Nómina',
    ];
    public $usoCfdi = '';


    public $searchTerm = ''; // Término de búsqueda
    public $estado;
    public $searchTerm2;

    public function search()
    {
        $this->resetPage(); // Reinicia la paginación al realizar una búsqueda
    }

    public function render()
    {
        if (Auth::user()->hasAnyRole(['Administrador', 'Compras'])) {
            $query = Cotizacion::with('proyecto')
                ->whereIn('estado', [3])
                ->orderBy('created_at', 'desc');
        } else {
            $usuarioId = Auth::id();
            // Obtener las listas a cotizar con estado igual a 3
            $query = Cotizacion::where('id_usuario_compras', $usuarioId)
                ->with('proyecto')
                ->orderBy('created_at', 'desc')
                ->whereIn('estado', [3]);
        }
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereDate('created_at', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Paginar los resultados
        $listasCotizar = $query->paginate(10);

        if (Auth::user()->hasRole('Administrador')) {
            $query2 = Cotizacion::with('proyecto')
                ->whereHas('ordenesCompra') // Solo cotizaciones con órdenes de compra
                ->orderBy('created_at', 'desc');
        } else {
            $usuarioId = Auth::id();
            $query2 = Cotizacion::where('id_usuario_compras', $usuarioId)
                ->with('proyecto')
                ->whereHas('ordenesCompra') // Solo cotizaciones con órdenes de compra
                ->orderBy('created_at', 'desc');
        }
        if (!empty($this->searchTerm2)) {
            $query2->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm2 . '%')
                    ->orWhereDate('created_at', 'like', '%' . $this->searchTerm2 . '%');
            });
        }

        $listasCotizarCompradas = $query2->paginate(10);

        return view('livewire.cotisaciones.ordene-compra.orden-compra-vista-uno', compact('listasCotizar', 'listasCotizarCompradas'));
    }

    public function cancelar($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 7; // Estado "Cancelada"
            $cotizacion->save();
            if (Auth::user()->cotizaciones == $id) {
                Auth::user()->update(['cotizaciones' => null]);
            }
        }
    }

    public $openModalCrearOrdenCompra = false;
    public $cotisacionSelecionada;
    public $metodoPago = 1;
    public $cantidadPagar;
    public $modalida=1;

    public function abrirModal($id)
    {
        $cotisacion = Cotizacion::find($id);
        $this->openModalCrearOrdenCompra = true;
        $this->cotisacionSelecionada = $cotisacion;
    }

    public function cerrarModal()
    {
        $this->reset('openModalCrearOrdenCompra', 'cotisacionSelecionada', 'metodoPago', 'cantidadPagar');
    }

    public function asignarMetodoPago($valor)
    {
        $this->metodoPago = $valor;
    }

    public function asignarModalida($valor)
    {
        $this->modalida = $valor;
    }

    public function createOrdenCompra()
    {
        $this->validate([
            'usoCfdi' => 'required',
            'metodoPago' => 'required',
            'modalida' => 'required',
        ], [
            'usoCfdi.required' => 'Debes seleccionar un uso de CFDI.',
            'metodoPago.required' => 'Debes seleccionar una forma de pago.',
            'modalida.required' => 'Debes seleccionar la modalidad.',
        ]);
        $cotizacion = Cotizacion::findOrFail($this->cotisacionSelecionada->id);

        $items = json_decode($cotizacion->items_cotizar_proveedor, true);

        // Mapear cada item para agregar el proveedor real y el precio_compra
        $itemsConProveedorReal = collect($items)->map(function ($item) {
            $pivot = DB::table('item_especifico_proveedor')->where('id', $item['proveedor_id'])->first();

            if (!$pivot) {
                throw new \Exception("No se encontró item_especifico_proveedor con id {$item['proveedor_id']}");
            }

            // Usar el proveedor real y el precio de compra de la tabla pivote
            $item['proveedor_real_id'] = $pivot->proveedor_id;
            $item['precio_compra'] = ceil($pivot->precio_compra * 10) / 10;


            return $item;
        });

        // Agrupar por proveedor real
        $itemsPorProveedor = $itemsConProveedorReal->groupBy('proveedor_real_id');

        $proyectoConsulta = Proyecto::findOrFail($cotizacion->proyecto_id ?? null);
        $nombre = strtoupper(substr(strval($proyectoConsulta->nombre), 0, 1)) . 'ODC';

        foreach ($itemsPorProveedor as $proveedorId => $itemsProveedor) {
            // Calcular el monto total usando precio_compra
            $monto = collect($itemsProveedor)->reduce(function ($carry, $item) {
                return $carry + ($item['precio_compra'] * $item['cantidad']);
            }, 0);



            ordenCompra::create([
                'id_provedor' => $proveedorId,
                'id_cotizacion' => $cotizacion->id,
                'id_usuario' => Auth::id(),
                'nombre' => $nombre . strval($proveedorId),
                'formaPago' => $this->metodoPago,
                'modalidad' => $this->modalida,
                'cfdi' => $this->usoCfdi,
                'monto' => $monto,
                'montoPagar' => $monto,
                'items_cotizar_proveedor' => json_encode($itemsProveedor),
                'estado' => 0,
            ]);
        }

        $cotizacion->update(['estado' => 4]);

        $ListaCotisar = ListasCotizar::findOrFail($cotizacion->lista_cotizar_id);
        $ListaCotisar->update(['estado' => 6]);

        $proyecto = Proyecto::findOrFail($ListaCotisar->proyecto_id);
        $proyecto->update(['proceso' => 5]);

        $this->reset('openModalCrearOrdenCompra', 'cotisacionSelecionada', 'metodoPago', 'cantidadPagar');
    }


    public function verOrdenCOmpra($idCotisacion)
    {
        $cotizacion = Cotizacion::findOrFail($idCotisacion);

        if ($cotizacion === null) {
            abort(404, 'item no encontrada');
        }
        return redirect()->route('compras.cotisaciones.vistaEspecificaOrdenesCompra', ['idCotisacion' => $cotizacion]);
    }
}
