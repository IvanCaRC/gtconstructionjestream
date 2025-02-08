<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\ItemEspecifico;
use Livewire\Component;

use App\Models\Familia;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\Proveedor;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\ItemEspecificoProveedor;
use Illuminate\Support\Facades\DB;

class EditItem extends Component
{
    public $item;
    public $itemEspecifico;
    use WithFileUploads;
    public $openModalProveedores = false;
    public $openModalFamilias = false;

    public $nombre, $descripcion, $marca, $stock, $pz_Mayoreo,
        $pz_Minorista, $porcentaje_venta_minorista, $porcentaje_venta_mayorista,
        $dtock, $precio_venta_minorista, $precio_venta_mayorista, $unidad, $ficha_Tecnica_pdf;
    public $proveedores = [];
    public $especificaciones = [['enunciado' => '', 'concepto' => '']];
    public $familiasSeleccionadas = [''];

    public $niveles = []; // Array para almacenar las familias de cada nivel
    public $seleccionadas = []; // Array para almacenar las opciones seleccionadas de familia
    public $image = [];
    public $imagenesCargadas = [];

    public $fileNamePdf;
    public $unidadSeleccionada;
    public $seleccionProvedorModal;
    public $seleccionProvedorModalNombre;
    public $ficha_Tecnica_pdf_actual;
    public $searchTerm = '';
    public $nuevasImagenes = [];
    public $itemEdit = [
        'id' => '', // Identificador del proveedor
        'nombre' => '', // Nombre del proveedor
        'descripcion' => '', // Descripción del proveedor

    ];
    public $itemEspecificoEdit = [
        'id' => '', // Identificador del proveedor
        'iitem_id' => '', // Identificador del proveedor    
        'marca' => '', // Descripción del proveedor
        'cantidad_piezas_mayoreo' => '', // Correo del proveedor
        'cantidad_piezas_minorista' => '', // RFC del proveedor
        'stock' => '',
        'ficha_tecnica_pdf' => '',
        'estado' => '',
        'estado_eliminacion' => '',
    ];
    public $ficahaTecnicaArchivoActual;

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
            if ($proveedor->estado == 1) {
                $this->provedorSeleccionadoDeLaTabla = $proveedor->proveedor->nombre;
                $this->precioSeleccionadoEnTabla =  $proveedor->precio_compra;
                $this->unidadSeleccionadaEnTabla =  $proveedor->unidad;
                $this->calcularPrecios();
            }
            $this->ProvedoresAsignados[] = (array) $conexion; // Convertir el objeto a un array y agregarlo al arreglo
        }
    }

    public function mount($idItem)
    {
        // Buscar el registro de ItemEspecifico
        $this->itemEspecifico = ItemEspecifico::findOrFail($idItem);
        // Buscar el registro de Item relacionado
        $this->item = Item::findOrFail($this->itemEspecifico->item_id);
        //falta cargar imagenes
        //Falta cargar especificaciones
        $this->itemEdit['id'] = $this->item->id;
        $this->itemEdit['nombre'] = $this->item->nombre;
        $this->itemEdit['descripcion'] = $this->item->descripcion;
        $this->itemEspecificoEdit['id'] = $this->itemEspecifico->id;
        $this->itemEspecificoEdit['item_id'] = $this->itemEspecifico->item_id;
        $this->imagenesCargadas = explode(',', $this->itemEspecifico->image); // Dividir la cadena en un array
        $this->itemEspecificoEdit['marca'] = $this->itemEspecifico->marca;
        $this->cargarProvedoresParaEditar($idItem);
        $this->familiasSeleccionadas = ItemEspecificoHasFamilia::where('item_especifico_id', $idItem)
            ->with('familia')
            ->get()
            ->pluck('familia')
            ->toArray();
        $this->itemEspecificoEdit['stock'] = $this->itemEspecifico->stock;
        $this->itemEspecificoEdit['cantidad_piezas_mayoreo'] = $this->itemEspecifico->cantidad_piezas_mayoreo;
        $this->itemEspecificoEdit['cantidad_piezas_minorista'] = $this->itemEspecifico->cantidad_piezas_minorista;
        $this->porcentaje_venta_minorista = $this->itemEspecifico->porcentaje_venta_minorista;
        $this->porcentaje_venta_mayorista = $this->itemEspecifico->porcentaje_venta_mayorista;
        $this->precio_venta_minorista = $this->itemEspecifico->precio_venta_minorista;
        $this->precio_venta_mayorista = $this->itemEspecifico->precio_venta_mayorista;
        $this->especificaciones = json_decode($this->itemEspecifico->especificaciones, true);
        $this->ficahaTecnicaArchivoActual = $this->itemEspecifico->ficha_tecnica_pdf;
        $this->ficha_Tecnica_pdf = $this->itemEspecifico->ficha_tecnica_pdf;
        $this->ficha_Tecnica_pdf_actual = $this->itemEspecifico->ficha_tecnica_pdf;
        $this->itemEspecificoEdit['estado'] = $this->itemEspecifico->estado;
        $this->itemEspecificoEdit['estado_eliminacion'] = $this->itemEspecifico->estado_eliminacion;
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
        $this->actualizarProveedores();
    }

    public function render()
    {
        return view('livewire.item.edit-item');
    }

    public function calcularSubfamilias($idFamiliaSeleccionada, $nivel)
    {
        // Llama al método del modelo y actualiza las propiedades locales
        $resultado = Familia::calcularSubfamilias($idFamiliaSeleccionada, $nivel, $this->niveles, $this->seleccionadas);

        $this->niveles = $resultado['niveles'];
        $this->seleccionadas = $resultado['seleccionadas'];
    }

    public function addFamilia()
    {
        $idFamiliaPadre = null;
        foreach (array_reverse($this->seleccionadas) as $seleccionada) {
            if ($seleccionada != 0) {
                $idFamiliaPadre = $seleccionada;
                break;
            }
        }
        if ($idFamiliaPadre) {
            $familia = Familia::find($idFamiliaPadre);
            $this->familiasSeleccionadas[] = $familia;
        }
    }

    public function updatedFichaTecnicaPdf()
{
    if ($this->ficha_Tecnica_pdf) {
        $this->fileNamePdf = $this->ficha_Tecnica_pdf->getClientOriginalName();
    }
}


    public function confirmFamilia()
    {
        $this->addFamilia();
        $this->openModalFamilias = false;
        $this->reset('seleccionadas', 'niveles');
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
    }

    public function removeFamilia($index)
    {
        unset($this->familiasSeleccionadas[$index]);
        $this->familiasSeleccionadas = array_values($this->familiasSeleccionadas); // Reindexar el arreglo
    }

    public function cerrarModalProvedore()
    {
        $this->reset(['searchTerm', 'seleccionProvedorModalNombre', 'seleccionProvedorModal', 'tiempoMinEntrega', 'tiempoMaxEntrega', 'precioCompra', 'unidadSeleccionada', 'openModalProveedores']);
    }

    public function save()
    {
        $itemActual = Item::findOrFail($this->item->id);
        $itemEspecificoActual = ItemEspecifico::findOrFail($this->itemEspecifico->id);

        $porcentajeVentaMinorista = (float) ($this->porcentaje_venta_minorista ?? 0);
        $porcentajeVentaMayorista = (float) ($this->porcentaje_venta_mayorista ?? 0);

        $ficha_Tecnica_pdf = null;
        if ($this->ficha_Tecnica_pdf) {
            if ($this->ficha_Tecnica_pdf == $this->ficahaTecnicaArchivoActual) {
                $ficha_Tecnica_pdf = $this->ficha_Tecnica_pdf;
            } else {
                $ficha_Tecnica_pdf = $this->ficha_Tecnica_pdf->store('archivosFacturacionProveedores', 'public');
            }
        }

        $itemActual->update([
            'nombre' => $this->itemEdit['nombre'],
            'descripcion' => $this->itemEdit['descripcion'],
        ]);

        $imagenes = [];

        if ($this->image) {
            foreach ($this->image as $key => $imageI) {
                $imagenes[] = $imageI->store('imagenesItems', 'public');
            }
        }


        $areglosumado = array_merge($this->imagenesCargadas, $this->image);

        // Convertir el array combinado a una cadena delimitada por comas o null si está vacío
        $imagenesString = !empty($areglosumado) ? implode(',', $areglosumado) : null;


        $itemEspecificoActual->update([
            'image' => $imagenesString,
            'marca' => $this->itemEspecificoEdit['marca'],

            'cantidad_piezas_mayoreo' => $this->itemEspecificoEdit['cantidad_piezas_mayoreo'],
            'cantidad_piezas_minorista' => $this->itemEspecificoEdit['cantidad_piezas_mayoreo'] - 1,
            'porcentaje_venta_minorista' => $porcentajeVentaMinorista,
            'porcentaje_venta_mayorista' => $porcentajeVentaMayorista,
            'precio_venta_minorista' => $this->precio_venta_minorista,
            'precio_venta_mayorista' => $this->precio_venta_mayorista,

            'unidad' => $this->unidadSeleccionadaEnTabla,
            'stock' => $this->itemEspecificoEdit['stock'],
            'especificaciones' => json_encode($this->especificaciones), // Guardar como JSON
            'ficha_tecnica_pdf' => $ficha_Tecnica_pdf,
            'estado' => true,
        ]);

        $famliasActualesSelecionadas = $this->familiasSeleccionadas;


        ItemEspecificoProveedor::where('item_especifico_id', $itemEspecificoActual->id)->delete();




        // Obtener las familias actuales relacionadas con el item específico
        $familiasActuales = ItemEspecificoHasFamilia::where('item_especifico_id', $itemEspecificoActual->id)
            ->pluck('familia_id')
            ->toArray();

        // Obtener las IDs de las familias seleccionadas
        $familiasSeleccionadasIds = array_map(function ($familia) {
            return $familia['id'];
        }, $this->familiasSeleccionadas);



        // Comparar y actualizar familias si hay cambios
        if ($familiasActuales !== $familiasSeleccionadasIds) {
            // Eliminar relaciones antiguas
            ItemEspecificoHasFamilia::where('item_especifico_id', $itemEspecificoActual->id)->delete();

            // Crear las nuevas relaciones
            foreach ($familiasSeleccionadasIds as $familia_id) {
                ItemEspecificoHasFamilia::create([
                    'item_especifico_id' => $itemEspecificoActual->id,
                    'familia_id' => $familia_id,
                ]);
            }
        }




        foreach ($this->ProvedoresAsignados as $proveedor) {
            // Asegurarnos de que el arreglo contiene los datos necesarios
            if (isset($proveedor['proveedor_id'], $proveedor['tiempo_minimo_entrega'], $proveedor['tiempo_maximo_entrega'], $proveedor['precio_compra'], $proveedor['unidad'], $proveedor['estado'])) {
                DB::table('item_especifico_proveedor')->insert([
                    'item_especifico_id' => $itemEspecificoActual->id,
                    'proveedor_id' => $proveedor['proveedor_id'],
                    'tiempo_min_entrega' => $proveedor['tiempo_minimo_entrega'],
                    'tiempo_max_entrega' => $proveedor['tiempo_maximo_entrega'],
                    'unidad' => $proveedor['unidad'],
                    'precio_compra' => $proveedor['precio_compra'],
                    'estado' => $proveedor['estado'], // Estado actual del proveedor
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return true;
    }

    public function addLineaTecnica()
    {
        $this->especificaciones[] = ['enunciado' => '', 'concepto' => ''];
    }

    public function removeLineaTecnica($index)
    {
        unset($this->especificaciones[$index]);
        $this->especificaciones = array_values($this->especificaciones); // Reindexar el array
    }

    public function updatedNuevasImagenes()
    {
        // Agregar nuevas imágenes al arreglo existente
        if ($this->nuevasImagenes) {
            foreach ($this->nuevasImagenes as $nuevaImagen) {
                $this->image[] = $nuevaImagen;
            }
        }

        // Limpiar el campo de selección después de agregar
        $this->reset('nuevasImagenes');
    }

    public function eliminarImagen($index)
    {
        // Eliminar la imagen específica del arreglo
        unset($this->image[$index]);

        // Reindexar el arreglo para mantener consistencia
        $this->image = array_values($this->image);
    }

    public function eliminarImagenActual($index)
    {
        // Eliminar la imagen específica del arreglo
        unset($this->imagenesCargadas[$index]);

        // Reindexar el arreglo para mantener consistencia
        $this->imagenesCargadas = array_values($this->imagenesCargadas);
    }


    public function montarModalProveedores()
    {

        $this->openModalProveedores = true;
    }

    public function actualizarProveedores()
    {
        if ($this->searchTerm) {
            $this->proveedores = Proveedor::where('estado', 1)
                ->where(function ($query) {
                    $query->where('nombre', 'LIKE', "%{$this->searchTerm}%")
                        ->orWhere('rfc', 'LIKE', "%{$this->searchTerm}%");
                })
                ->get();
        } else {
            $this->proveedores = [];
        }
    }


    public function asignarValor($id, $name)
    {
        $this->seleccionProvedorModal = $id;
        $this->seleccionProvedorModalNombre = $name;
    }

    public function asignarUnidad($unidad)
    {
        $this->unidadSeleccionada = $unidad;
    }

    public $ProvedoresAsignados = [];
    public $tiempoMinEntrega, $tiempoMaxEntrega, $precioCompra, $unidadPersonalizada;


    public function asignarProvedorArregloProvedor()
    {
        $conexion = new ConexionProveedorItemTemporal(
            $this->seleccionProvedorModal,
            $this->seleccionProvedorModalNombre,
            $this->tiempoMinEntrega,
            $this->tiempoMaxEntrega,
            $this->precioCompra,
            $this->unidadSeleccionada === 'otro' ? $this->unidadPersonalizada : $this->unidadSeleccionada,
            0
        );

        // Convertir el objeto a un array
        $conexionArray = (array) $conexion;

        // Almacenar el array en la propiedad de Livewire
        $this->ProvedoresAsignados[] = $conexionArray;

        // Limpiar los campos del formulario
        $this->reset(['searchTerm', 'seleccionProvedorModalNombre', 'seleccionProvedorModal', 'tiempoMinEntrega', 'tiempoMaxEntrega', 'precioCompra', 'unidadSeleccionada', 'openModalProveedores']);
    }


    public $unidadSeleccionadaEnTabla;
    public $precioSeleccionadoEnTabla;
    public $provedorSeleccionadoDeLaTabla;


    public function seleccionarProveedor($index, $nombre)
    {
        foreach ($this->ProvedoresAsignados as $key => $proveedor) {
            if ($key === $index) {
                // Alternar el estado del proveedor seleccionado
                if ($this->ProvedoresAsignados[$key]['estado'] == 1) {
                    $this->ProvedoresAsignados[$key]['estado'] = 0;
                    $this->provedorSeleccionadoDeLaTabla = null;
                    $this->unidadSeleccionadaEnTabla = null;
                    $this->precioSeleccionadoEnTabla = null;

                    $this->itemEspecificoEdit['cantidad_piezas_mayoreo'] = null;
                    $this->itemEspecificoEdit['cantidad_piezas_minorista'] = null;
                    $this->porcentaje_venta_minorista = null;
                    $this->porcentaje_venta_mayorista = null;

                    $this->precio_venta_minorista = null;
                    $this->precio_venta_mayorista = null;
                } else {
                    $this->ProvedoresAsignados[$key]['estado'] = 1;
                    $this->provedorSeleccionadoDeLaTabla = $nombre;
                    $this->unidadSeleccionadaEnTabla = $this->ProvedoresAsignados[$key]['unidad'];
                    $this->precioSeleccionadoEnTabla = $this->ProvedoresAsignados[$key]['precio_compra'];
                    $this->handleKeydown($index);
                }
            } else {
                // Cambiar el estado de todos los demás a 0
                $this->ProvedoresAsignados[$key]['estado'] = 0;
            }
        }
    }

    public function edcionDeTabalaProveedorPrecio($index)
    {
        if (isset($this->ProvedoresAsignados[$index]) && $this->ProvedoresAsignados[$index]['estado'] == 1) {
            // Asignar el precio de compra del proveedor al precio seleccionado
            $this->precioSeleccionadoEnTabla = $this->ProvedoresAsignados[$index]['precio_compra'];
        }
    }

    public function eliminarProveedor($index)
    {
        unset($this->ProvedoresAsignados[$index]);
        $this->ProvedoresAsignados = array_values($this->ProvedoresAsignados); // Reindexar el array
        // Si el proveedor eliminado estaba seleccionado, resetear los datos seleccionados
        if ($this->provedorSeleccionadoDeLaTabla === $index) {
            $this->unidadSeleccionadaEnTabla = null;
            $this->precioSeleccionadoEnTabla = null;
            $this->provedorSeleccionadoDeLaTabla = null;
        }
    }

    public function calcularPrecios()
    {
        // Manejar valores vacíos y convertirlos a números
        $precioBase = (float) ($this->precioSeleccionadoEnTabla ?? 0);
        $porcentajeMayorista = (float) ($this->porcentaje_venta_mayorista ?? 0);
        $porcentajeMinorista = (float) ($this->porcentaje_venta_minorista ?? 0);

        if ($precioBase > 0) {
            // Calcular los precios mayorista y minorista
            $this->precio_venta_mayorista = $precioBase + ($precioBase * ($porcentajeMayorista / 100));
            $this->precio_venta_minorista = $precioBase + ($precioBase * ($porcentajeMinorista / 100));
        } else {
            // Si no hay precio base, los resultados son nulos
            $this->precio_venta_mayorista = null;
            $this->precio_venta_minorista = null;
        }
    }

    public function handleKeydown($index)
    {
        // Ejecutar ambos métodos
        $this->edcionDeTabalaProveedorPrecio($index);
        $this->calcularPrecios();
    }
}
