<?php

namespace App\Livewire\Cliente;


use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;
use Livewire\Component;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;

class VistaEspecificaFichasTecnicas extends Component
{
    public $item, $itemEspecifico;
    public $imagenesCargadas;
    public $familiasSeleccionadas = [''];
    public $ProvedoresAsignados = [];
    public $especificaciones = [['enunciado' => '', 'concepto' => '']];
    public $ficha_Tecnica_pdf;

    public $listadeUsuarioActiva;
    public $usuarioActual;
    public $nombreProyecto;
    public $nombreCliente;
    public $idLista;

    public $cantidad = 1;


    public function incrementarCantidad()
    {
        if ($this->cantidad < 99) {
            $this->cantidad++;
        }
    }

    // Disminuye la cantidad (mínimo 1)
    public function decrementarCantidad()
    {
        if ($this->cantidad > 1) {
            $this->cantidad--;
        }
    }

    private function establecerPropiedadesNulas()
    {
        $this->idLista = null;
        $this->listadeUsuarioActiva = null;
        $this->nombreProyecto = null;
        $this->nombreCliente = null;
        $this->itemsEnLista = [];
    }

    public function mount($idItem)
    {
        // Obtener el usuario autenticado
        $this->usuarioActual = Auth::user();

        // Verificar si el usuario tiene una lista activa asignada
        if ($this->usuarioActual->lista) {
            // Obtener el ID de la lista activa
            $listaId = $this->usuarioActual->lista;

            // Buscar la lista activa usando el ID obtenido
            $listaActiva = ListasCotizar::find($listaId);

            if ($listaActiva) {
                // Si existe la lista activa, obtener sus detalles
                $this->idLista = $listaActiva->id;
                $this->listadeUsuarioActiva = $listaActiva->nombre ?? 'Sin nombre';
                $proyecto = $listaActiva->proyecto ?? 'Sin proyecto';
                $cliente = $proyecto->cliente ?? 'Sin cliente    ';
                $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';

                // Obtener el cliente relacionado con el proyecto
               
                $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';

                // Obtener los IDs de los items en la lista
                $itemsData = json_decode($listaActiva->items_cotizar, true) ?? [];
                $this->itemsEnLista = array_column($itemsData, 'id');
            } else {
                // Si no se encuentra la lista, establecer las propiedades en null
                $this->establecerPropiedadesNulas();
            }
        } else {
            // Si el usuario no tiene una lista asignada, establecer las propiedades en null
            $this->establecerPropiedadesNulas();
        }

        // Buscar el registro de ItemEspecifico
        $this->itemEspecifico = ItemEspecifico::findOrFail($idItem);
        // Buscar el registro de Item relacionado
        $this->item = Item::findOrFail($this->itemEspecifico->item_id);
        if ($this->itemEspecifico->image === null) {
            $this->imagenesCargadas = null;
        } else {
            $this->imagenesCargadas = explode(',', $this->itemEspecifico->image);
        }
        $this->cargarProvedoresParaEditar($idItem);
        $this->familiasSeleccionadas = ItemEspecificoHasFamilia::where('item_especifico_id', $idItem)
            ->with('familia')
            ->get()
            ->pluck('familia')
            ->toArray();

        $especificaciones = json_decode($this->itemEspecifico->especificaciones, true);

        // Filtrar especificaciones vacías
        $especificaciones = array_filter($especificaciones, function ($especificacion) {
            return !empty($especificacion['enunciado']) || !empty($especificacion['concepto']);
        });

        if (!empty($especificaciones)) {
            $this->especificaciones = $especificaciones;
        } else {
            $this->especificaciones = null;
        }
        $this->ficha_Tecnica_pdf = $this->itemEspecifico->ficha_tecnica_pdf;
    }

    public function cargarProvedoresParaEditar($idItem)
    {
        $proveedores = ItemEspecificoProveedor::where('item_especifico_id', $idItem)->get();

        foreach ($proveedores as $proveedor) {
            $conexion = new ConexionProveedorItemTemporal(
                $proveedor->proveedor_id,
                $proveedor->proveedor->nombre,
                $proveedor->tiempo_min_entrega,
                $proveedor->tiempo_max_entrega,
                $proveedor->precio_compra,
                $proveedor->unidad,
                $proveedor->estado
            );

            $this->ProvedoresAsignados[] = (array) $conexion; // Convertir el objeto a un array y agregarlo al arreglo
        }
    }

    public function eliminar($itemId)
    {
        ItemEspecificoHasFamilia::where('item_especifico_id', $itemId)->delete();
        ItemEspecificoProveedor::where('item_especifico_id', $itemId)->delete();
        $ItemEspecifico = ItemEspecifico::findOrFail($itemId);
        $ItemEspecifico->update(['estado_eliminacion' => false]);
        $this->dispatch('renderVistaProv');
    }
    public function render()
    {
        return view('livewire.cliente.vista-especifica-fichas-tecnicas');
    }

    public function verLista($idLista)
    {
        return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $idLista]);
    }

    public $itemsEnLista = [];

    public function agregarItemLista($idItem)
    {
        if ($this->idLista !== null) {
            $lista = ListasCotizar::find($this->idLista);

            if (!$lista) {
                session()->flash('error', 'No se encontró la lista de cotización.');
                return;
            }

            // Decodificar los items ya guardados
            $items = json_decode($lista->items_cotizar, true) ?? [];

            // Buscar si el item ya está en la lista
            $itemKey = array_search($idItem, array_column($items, 'id'));

            if ($itemKey !== false) {
                // Si el item ya existe, aumentar la cantidad
                $items[$itemKey]['cantidad'] += 1;
            } else {
                // Si el item no existe, agregarlo con cantidad inicial 1
                $items[] = [
                    'id' => $idItem,
                    'cantidad' => 1
                ];
            }

            // Guardar la lista actualizada en la base de datos
            $lista->update([
                'items_cotizar' => json_encode($items)
            ]);

            $this->itemsEnLista = array_column($items, 'id');
            return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $lista->id]);
        } else {
            $usuario  = $this->usuarioActual->id;
            $listasEnEstado1 = ListasCotizar::where('usuario_id', $usuario)
                ->where('estado', 1)
                ->get();

            foreach ($listasEnEstado1 as $lista) {
                $lista->update(['estado' => 2]);
            }

            $user = Auth::user();
            $idUser = $user->id;

            $listaACotizar = ListasCotizar::create([
                'usuario_id' => $idUser,
                'estado' => 1,
            ]);
            $this->idLista = $listaACotizar->id;
            Auth::user()->update(['lista' => $listaACotizar->id]);
            //
            $items = json_decode($listaACotizar->items_cotizar, true) ?? [];

            // Buscar si el item ya está en la lista
            $itemKey = array_search($idItem, array_column($items, 'id'));

            if ($itemKey !== false) {
                // Si el item ya existe, aumentar la cantidad
                $items[$itemKey]['cantidad'] += 1;
            } else {
                // Si el item no existe, agregarlo con cantidad inicial 1
                $items[] = [
                    'id' => $idItem,
                    'cantidad' => 1
                ];
            }

            // Guardar la lista actualizada en la base de datos
            $listaACotizar->update([
                'items_cotizar' => json_encode($items)
            ]);

            $this->itemsEnLista = array_column($items, 'id');
            //

            return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $listaACotizar->id]);
        }
    }
}
