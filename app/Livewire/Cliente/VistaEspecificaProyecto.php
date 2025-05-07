<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Direccion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemTemporal;
use App\Models\ListasCotizar;
use App\Models\Proyecto;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf; // Asegúrate de importar la fachada de DomPDF
use Illuminate\Support\Facades\Session; //Implementar session para almacenar datos para el pdf
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VistaEspecificaProyecto extends Component
{
    protected $listeners = ['refresh' => 'render'];

    public $proyecto;
    public $searchTerm = '';
    public $statusFiltro = 0; // Filtro de estado
    public $clienteEspecifico;
    public $listaPreliminar;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];
    public $datosGenrales = [['frente' => '', 'fondo' => '', 'alturaTecho' => '', 'areaTotal' => '', 'alturaMuros' => '', 'canalon' => '', 'perimetral' => '', 'caballete' => '']];
    public $adicionales = [['estructura' => '', 'cantidad' => '']];
    public $openModalActualizarProyecto = false;
    public $idProyectoActual;
    public $nombreProyecto;
    public $preferencia;
    public $tipoDeProyectoSelecionado;
    public $idDireccionParaProyecto;
    public $listaACotizarTxt;
    public $archivoSubido;


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
            'pdfListaUrl' => route('proyecto.pdf-lista', ['id' => $this->proyecto->id]),
        ], ['listas' => $listas]);
    }
    //Visualizar PDF.
    public function prepararPDFLista()
    {
        //Cliente asociado al proyecto
        $cliente = Cliente::findOrFail($this->proyecto->cliente_id);
        // Lista de cotización vinculada al proyecto
        $lista = ListasCotizar::where('proyecto_id', $this->proyecto->id)->first();
        //Obtener valor de los numeros de telefono registrados:
        $telefonos = json_decode($cliente->telefono, true);

        // Verificamos si hay al menos dos registros en $telefonos
        if (!empty($telefonos) && is_array($telefonos)) {
            $nombre_contacto_1 = $telefonos[0]['nombre'] ?? 'No registrado';
            $numero_1 = $telefonos[0]['numero'] ?? 'No registrado';

            // Si existe un segundo contacto, lo recuperamos
            $nombre_contacto_2 = isset($telefonos[1]) ? $telefonos[1]['nombre'] ?? 'No registrado' : 'No registrado';
            $numero_2 = isset($telefonos[1]) ? $telefonos[1]['numero'] ?? 'No registrado' : 'No registrado';
        } else {
            // Si no hay datos, asignamos "No registrado" por defecto
            $nombre_contacto_1 = 'No registrado';
            $numero_1 = 'No registrado';
            $nombre_contacto_2 = 'No registrado';
            $numero_2 = 'No registrado';
        }

        //Obtener la direccion en funcion del id del cliente
        $direccion = Direccion::where('cliente_id', $cliente->id)->first();
        // Recuperar items de la lista a cotizar
        $items = json_decode($lista?->items_cotizar ?? '[]', true);
        $items_data = [];
        //Recuperar los datos de medidas en un formato para presentar en el pdf
        $datos_medidas_decodificados = json_decode($this->proyecto->datos_medidas ?? '[]', true);
        $frentes = [];
        $fondos = [];
        $alturasTecho = [];
        $areasTotales = [];
        $alturasMuros = [];
        $canalones = [];
        $perimetrales = [];
        $caballetes = [];
        //Ciclo para obtener los datos de las medidas descritas en el proyecto
        foreach ($datos_medidas_decodificados as $dato) {
            $frentes[] = $dato['frente'] ?? 'No especificado';
            $fondos[] = $dato['fondo'] ?? 'No especificado';
            $alturasTecho[] = $dato['alturaTecho'] ?? 'No especificado';
            $areasTotales[] = $dato['areaTotal'] ?? 'No especificado';
            $alturasMuros[] = $dato['alturaMuros'] ?? 'No especificado';
            $canalones[] = $dato['canalon'] ?? 'No especificado';
            $perimetrales[] = $dato['perimetral'] ?? 'No especificado';
            $caballetes[] = $dato['caballete'] ?? 'No especificado';
        }
        //Recuperar los datos adicionales en un formato para presentar en el pdf
        $datos_adicionales_decodificados = json_decode($this->proyecto->datos_adicionales ?? '[]', true);
        $estructuras = [];
        $cantidades = [];
        //Ciclo para obtener los datos adicionales descritos en el proyecto
        foreach ($datos_adicionales_decodificados as $dato) {
            $estructuras[] = $dato['estructura'] ?? 'No especificado';
            $cantidades[] = $dato['cantidad'] ?? 'No definida';
        }
        //Almanecar con formato de presentacion para pdf
        $estructura_cantidad = [];
        //Ciclo para unir el tipo de estructura y su cantidad
        foreach ($estructuras as $index => $estructura) {
            $cantidad = $cantidades[$index] ?? 'Cantidad no definida'; // Evitar errores si falta una cantidad
            $estructura_cantidad[] = "{$estructura} - {$cantidad}";
        }

        //Variables correspondientes a la direccion
        $componentes_direccion = [
            $direccion->calle ?? '',
            $direccion->numero ?? '',
            $direccion->colonia ?? '',
            $direccion->municipio ?? '',
            $direccion->ciudad ?? '',
            $direccion->estado ?? '',
            $direccion->pais ?? '',
            "CP: {$direccion->cp}" ?? ''
        ];

        // Eliminamos "Campo no recuperado" y espacios en blanco innecesarios
        $componentes_direccion_filtrados = array_filter($componentes_direccion, function ($valor) {
            return $valor !== 'Campo no recuperado' && trim($valor) !== '';
        });

        // Recuperar items temporales de la lista a cotizar
        $items_temporales = json_decode($lista?->items_cotizar_temporales ?? '[]', true);
        $items_temporales_data = [];
        // Ciclo para recuperación de items en la lista a cotizar
        foreach ($items as $item) {
            // Buscar primero el ItemEspecifico usando el id del ítem en la lista
            $item_especifico = ItemEspecifico::where('id', $item['id'])->first();

            // Recuperar el nombre correctamente desde la tabla Item usando item_id
            $item_base = $item_especifico
                ? Item::where('id', $item_especifico->item_id)->first()
                : null;

            // Ruta correcta de la imagen
            $imagen = asset('storage/' . explode(',', $item_especifico->image)[0]);

            // Guardar en el array con la fuente correcta para cada dato
            $items_data[] = [
                'imagen' => $imagen,
                'nombre' => $item_base?->nombre ?? 'Nombre no disponible',  // ✅ Ahora viene de Item y está correctamente relacionado
                'marca' => $item_especifico?->marca ?? 'Marca no registrada', // ✅ Sigue viniendo de ItemEspecifico
                'descripcion' => $item_base?->descripcion ?? 'Sin descripción', // ✅ Ahora se agrega la descripción
                'cantidad' => $item['cantidad'] . ' ' . ($item_especifico?->unidad ?? 'unidad no especificada') // ✅ También de ItemEspecifico
            ];
        }
        //Ciclo de recuperacion de items temporales
        foreach ($items_temporales as $item) {
            // Buscar primero el ItemTemporal usando el id del ítem en la lista
            $item_temporal = ItemTemporal::where('id', $item['id'])->first();

            // Recuperar el nombre y descripción desde la tabla Item usando item_id
            $item_base = $item_temporal
                ? Item::where('id', $item_temporal->item_id)->first()
                : null;

            // Guardar en el array con la fuente correcta para cada dato
            $items_temporales_data[] = [
                'nombre' => $item_base?->nombre ?? 'Nombre no disponible',  // ✅ Ahora viene de Item
                'descripcion' => $item_base?->descripcion ?? 'Descripción no disponible', // ✅ También de Item
                'unidad' => $item_temporal?->unidad ?? 'Unidad no especificada' // ✅ Desde ItemTemporal
            ];
        }

        //Datos para el PDF
        Session::put('proyecto_nombre', $this->proyecto->nombre);
        Session::put('proyecto_fecha', $this->proyecto->created_at->format('d/m/Y'));
        Session::put('proyecto_tipo', $this->proyecto->tipo == 1 ? 'Suministro' : 'Obra');
        Session::put('frentes', implode(', ', $frentes));
        Session::put('fondos', implode(', ', $fondos));
        Session::put('alturasTecho', implode(', ', $alturasTecho));
        Session::put('areasTotales', implode(', ', $areasTotales));
        Session::put('alturasMuros', implode(', ', $alturasMuros));
        Session::put('canalones', implode(', ', $canalones));
        Session::put('perimetrales', implode(', ', $perimetrales));
        Session::put('caballetes', implode(', ', $caballetes));
        Session::put('estructuras', implode(', ', $estructuras));
        Session::put('cantidades', implode(', ', $cantidades));
        Session::put('estructura_cantidad', implode(', ', $estructura_cantidad));
        Session::put('usuario', auth()->user()->name);
        Session::put('usuario_first_last_name', auth()->user()->first_last_name);
        Session::put('usuario_second_last_name', auth()->user()->second_last_name);
        Session::put('cliente_nombre', $cliente->nombre);
        Session::put('cliente_correo', $cliente->correo ?? 'No disponible');
        Session::put('cliente_direccion', implode(', ', $componentes_direccion_filtrados));
        Session::put('cliente_contacto_1', $nombre_contacto_1);
        Session::put('cliente_telefono_1', $numero_1);
        Session::put('cliente_contacto_2', $nombre_contacto_2);
        Session::put('cliente_telefono_2', $numero_2);
        Session::put('items_cotizar', $lista?->items_cotizar ?? 'No hay ítems registrados');
        Session::put('items_cotizar_data', $items_data);
        Session::put('items_cotizar_temporales', $lista?->items_cotizar_temporales ?? 'No hay ítems temporales');
        Session::put('items_cotizar_temporales_data', $items_temporales_data);

        return redirect()->route('proyecto.pdf-lista', ['id' => $this->proyecto->id]);
    }

    //Descargar PDF.
    public function generarPDFLista()
    {
        $data = [
            'title' => 'Lista de Ítems a Cotizar',
            'proyecto' => $this->proyecto->nombre, // Nombre del proyecto
            'usuario' => auth()->user()->name, // Nombre del usuario
        ];

        $pdf = Pdf::loadView('pdf.lista', $data)->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'lista_items.pdf');
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

    public function editarProyecto($idProyecto)
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
        // dd($this->openModalActualizarProyecto);
    }

    //Metodos de actualizacion de proyecto
    public function asignarTipoDeProyecto($tipo)
    {
        $this->tipoDeProyectoSelecionado = $tipo;
    }

    public function asignarDireccion($idDIreccion)
    {
        $this->idDireccionParaProyecto = $idDIreccion;
    }

    public function asiganrPreferencia($preferencia)
    {
        $this->preferencia = $preferencia;
    }

    public function cancelarUpdate()
    {
        $this->reset('openModalActualizarProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->resetValidation();
        $this->openModalActualizarProyecto = false;
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
            if (Auth::user()->lista == $id) {
                Auth::user()->update(['lista' => null]);
            }
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

    public function enviarListaCotizar($lista)
    {
        $proyectoModi = Proyecto::find($this->proyecto->id);
        // Buscar la lista actual
        $lista = ListasCotizar::find($lista);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        // Obtener el proyecto seleccionado

        $proyectoModi->update([
            'proceso' => 1,
        ]);

        // Actualizar la lista
        $lista->update([
            'estado' => 2,
        ]);

        Auth::user()->update(['lista' => null]);
        // Mensaje de éxito
        session()->flash('success', 'Lista fue enviada correctamente a la cotisacion.');

        // Cerrar el modal
        $this->dispatch('refresh');
        return;
    }

    public function aceptarCotisacion($cotisacionId)
    {
        $proyectoModi = Proyecto::find($this->proyecto->id);

        $cotisacion = Cotizacion::find($cotisacionId);
        $lista = ListasCotizar::find($cotisacion->lista_cotizar_id);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }
        if (!$proyectoModi) {
            session()->flash('error', 'No se encontró el proyecto.');
            return;
        }
        if (!$cotisacion) {
            session()->flash('error', 'No se encontró la cotisacion.');
            return;
        }

        // Obtener el proyecto seleccionado

        $proyectoModi->update([
            'proceso' => 3,
        ]);

        // Actualizar la lista
        $lista->update([
            'estado' => 5,
        ]);

        $cotisacion->update([
            'estado' => 2,
        ]);

        // Mensaje de éxito
        session()->flash('success', 'Lista fue enviada correctamente a la cotisacion.');

        // Cerrar el modal
        $this->dispatch('refresh');
        return;
    }
}
