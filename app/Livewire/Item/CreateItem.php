<?php

namespace App\Livewire\Item;

use App\Models\Familia;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\CustomClases\ConexionProveedorItemTemporal;

class CreateItem extends Component
{
    use WithFileUploads;
    public $openModalProveedores = false;
    public $openModalFamilias = false;
    public $nombre, $descripcion, $marca, $pz_Mayoreo, $pz_Minorista, $porcentaje_venta_minorista, $porcentaje_venta_mayorista, $dtock, $precio_venta_minorista, $precio_venta_mayorista, $unidad, $ficha_Tecnica_pdf;
    public $proveedores = [];
    public $especificaciones = [['enunciado' => '', 'concepto' => '']];
    public $familias, $familiasSeleccionadas = [''];
    public $niveles = []; // Array para almacenar las familias de cada nivel
    public $seleccionadas = []; // Array para almacenar las opciones seleccionadas de familia
    public $image = [];
    public $fileNamePdf;
    public $unidadSeleccionada;
    public $seleccionProvedorModal;
    public $seleccionProvedorModalNombre;
    public $searchTerm = '';



    public function mount()
    {
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
        $this->familiasSeleccionadas = []; // Inicializar como arreglo vacío
        $this->actualizarProveedores();
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

    public function updatedficha_Tecnica_pdf()
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



    public function render()
    {
        return view('livewire.item.create-item');
    }


    public function save()
    {


        $ficha_Tecnica_pdf = null;
        if ($this->ficha_Tecnica_pdf) {
            $ficha_Tecnica_pdf = $this->ficha_Tecnica_pdf->store('archivosFacturacionProveedores', 'public');
        }
        // Crear el nuevo Item
        $item = Item::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        $imagenes = [];

        if ($this->image) {
            foreach ($this->image as $key => $imageI) {
                $imagenes[] = $imageI->store('imagenesItems', 'public');
            }
        }

        // Convertir el array de imágenes a una cadena delimitada por comas o null si está vacío
        $imagenesString = !empty($imagenes) ? implode(',', $imagenes) : null;


        $itemEspe = ItemEspecifico::create([
            'item_id' => $item->id,
            'image' => $imagenesString,
            'marca' => $this->marca,
            'cantidad_piezas_mayoreo' => $this->pz_Mayoreo,
            'cantidad_piezas_minorista' => $this->pz_Mayoreo - 1,
            'porcentaje_venta_minorista' => $this->porcentaje_venta_minorista,
            'porcentaje_venta_mayorista' => $this->porcentaje_venta_mayorista,
            'precio_venta_minorista' => $this->precio_venta_minorista,
            'precio_venta_mayorista' => $this->precio_venta_mayorista,
            'unidad' => $this->unidad,
            'stock' => $this->stock,
            'especificaciones' => json_encode($this->especificaciones), // Guardar como JSON
            'ficha_tecnica_pdf' => $ficha_Tecnica_pdf,
            'estado' => true,
            'estado_eliminacion' => true,
        ]);

        foreach ($this->familiasSeleccionadas as $familia) {
            if (is_object($familia) && get_class($familia) === Familia::class) {
                ItemEspecificoHasFamilia::create([
                    'item_especifico_id' => $itemEspe->id,
                    'familia_id' => $familia->id, // Acceder al ID de la familia
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

    public function eliminarImagenes()
    {
        $this->image = null;
    }

    public function montarModalProveedores()
    {

        $this->openModalProveedores = true;
    }

    public function actualizarProveedores()
    {
        if ($this->searchTerm) {
            $this->proveedores = Proveedor::where('estado', 1)
                ->where('nombre', 'LIKE', "%{$this->searchTerm}%")
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
    public $tiempoMinEntrega, $tiempoMaxEntrega, $precioCompra;


    public function asignarProvedorArregloProvedor()
    {
        $conexion = new ConexionProveedorItemTemporal(
            $this->seleccionProvedorModal,
            $this->seleccionProvedorModalNombre,
            $this->tiempoMinEntrega,
            $this->tiempoMaxEntrega,
            $this->precioCompra,
            $this->unidadSeleccionada,
            0
        );

        // Convertir el objeto a un array
        $conexionArray = (array) $conexion;

        // Almacenar el array en la propiedad de Livewire
        $this->ProvedoresAsignados[] = $conexionArray;

        // Limpiar los campos del formulario
        $this->reset(['seleccionProvedorModalNombre', 'seleccionProvedorModal', 'tiempoMinEntrega', 'tiempoMaxEntrega', 'precioCompra', 'unidadSeleccionada', 'openModalProveedores']);
    }


    public $unidadSeleccionadaEnTabla;
    public $precioSeleccionadoEnTabla;
    public $provedorSeleccionadoDeLaTabla;


    public function seleccionarProveedor($index,$nombre)
    {
        foreach ($this->ProvedoresAsignados as $key => $proveedor) {
            if ($key === $index) {
                // Alternar el estado del proveedor seleccionado
                if ($this->ProvedoresAsignados[$key]['estado'] == 1) {
                    $this->ProvedoresAsignados[$key]['estado'] = 0;
                    $this->provedorSeleccionadoDeLaTabla = null;
                    $this->unidadSeleccionadaEnTabla = null;
                    $this->precioSeleccionadoEnTabla = null;
                } else {
                    $this->ProvedoresAsignados[$key]['estado'] = 1;
                    $this->provedorSeleccionadoDeLaTabla = $nombre;
                    $this->unidadSeleccionadaEnTabla = $this->ProvedoresAsignados[$key]['unidad'];
                    $this->precioSeleccionadoEnTabla = $this->ProvedoresAsignados[$key]['precio_compra'];
                }
            } else {
                // Cambiar el estado de todos los demás a 0
                $this->ProvedoresAsignados[$key]['estado'] = 0;
            }
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
}
