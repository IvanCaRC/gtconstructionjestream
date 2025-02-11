<?php

namespace App\Livewire\Proveedor;

use App\CustomClases\ConexionProveedroDireccion;
use App\Models\Direccion;
use App\Models\Proveedor;
use Livewire\Component;
use App\Models\Telefono;  // Importar el modelo de Telefonos
use App\Models\Familia;   // Importar el modelo de Familia
use App\Models\ProveedorHasFamilia;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class EditProveedor extends Component
{
    use WithFileUploads; // Habilita la subida de archivos en el componente Livewire

    // Propiedades públicas utilizadas en el componente
    public $proveedor; // Almacena la instancia del proveedor actual
    public $proveedorActual; // Almacena la instancia actual del proveedor
    public $provedprEditId, $facturacion, $bancarios; // Identificador del proveedor y propiedades de archivos
    public $openModalFamilias = false; // Controla la visibilidad del modal de familias
    public $openModalDireccion = false; // Controla la visibilidad del modal de dirección
    public $proveedorEdit = [
        'id' => '', // Identificador del proveedor
        'nombre' => '', // Nombre del proveedor
        'descripcion' => '', // Descripción del proveedor
        'correo' => '', // Correo del proveedor
        'rfc' => '', // RFC del proveedor
        'archivo_facturacion_pdf' => '', // Archivo de facturación del proveedor
        'datos_bancarios_pdf' => '', // Archivo de datos bancarios del proveedor
    ];
    public $familias, $familiasSeleccionadas = [''], $telefonos = [['nombre' => '', 'numero' => '']]; // Arrays para familias y teléfonos
    public $fileNameFacturacion, $fileNameBancarios; // Nombres de los archivos de facturación y bancarios

    public $niveles = []; // Array para almacenar las familias de cada nivel
    public $seleccionadas = []; // Array para almacenar las opciones seleccionadas
    public $facturacionDatosActual;
    public $bancariosDatoActual;
    public $direccionesAsignadas = [];

    /**
     * Método mount
     * Se ejecuta cuando se monta el componente
     *
     * @param int $idproveedor
     */
    public function cargarDireccionesParaEditar($idProveedor)
    {
        $direcciones = Direccion::where('proveedor_id', $idProveedor)->get();


        foreach ($direcciones as $direccion) {
            $conexion = new ConexionProveedroDireccion(
                $direccion->proveedor_id,
                $direccion->pais,
                $direccion->cp,
                $direccion->estado,
                $direccion->ciudad,
                $direccion->municipio,
                $direccion->colonia,
                $direccion->calle,
                $direccion->numero,
                $direccion->referencia,
                $direccion->Latitud,
                $direccion->Longitud
            );

            $this->direccionesAsignadas[] = (array) $conexion; // Convertir el objeto a un array y agregarlo al arreglo
        }
    }

    public function mount($idproveedor)
    {
        $this->proveedor = Proveedor::findOrFail($idproveedor); // Obtiene el proveedor por ID
        $this->proveedorActual = Proveedor::findOrFail($idproveedor); // Obtiene el proveedor actual por ID
        $this->provedprEditId = $idproveedor; // Asigna el ID del proveedor a una propiedad
        $this->proveedorEdit['id'] = $this->proveedor->id;
        $this->proveedorEdit['nombre'] = $this->proveedor->nombre;
        $this->proveedorEdit['descripcion'] = $this->proveedor->descripcion;
        $this->proveedorEdit['correo'] = $this->proveedor->correo;
        $this->proveedorEdit['rfc'] = $this->proveedor->rfc;
        $this->proveedorEdit['archivo_facturacion_pdf'] = $this->proveedor->archivo_facturacion_pdf;
        $this->proveedorEdit['datos_bancarios_pdf'] = $this->proveedor->datos_bancarios_pdf;
        $this->facturacionDatosActual = $this->proveedor->archivo_facturacion_pdf;
        $this->bancariosDatoActual = $this->proveedor->datos_bancarios_pdf;
        $this->fileNameFacturacion = $this->proveedorEdit['archivo_facturacion_pdf'];
        $this->facturacion = $this->proveedorEdit['archivo_facturacion_pdf'];
        $this->fileNameBancarios = $this->proveedorEdit['datos_bancarios_pdf'];
        $this->bancarios = $this->proveedorEdit['datos_bancarios_pdf'];
        $this->cargarDireccionesParaEditar($idproveedor);
        // Obtiene los teléfonos del proveedor actual
        $this->telefonos = Telefono::where('proveedor_id', $idproveedor)
            ->get(['nombre', 'numero'])
            ->map(function ($telefono) {
                return [
                    'nombre' => $telefono->nombre,
                    'numero' => $telefono->numero,
                ];
            })
            ->toArray();


        // Obtiene las familias seleccionadas del proveedor actual
        $this->familiasSeleccionadas = ProveedorHasFamilia::where('proveedor_id', $idproveedor)
            ->with('familia')
            ->get()
            ->pluck('familia')
            ->toArray();

        // Inicializa los niveles de familias
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
    }


    /**
     * Método render
     * Renderiza la vista del componente
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.proveedor.edit-proveedor');
    }
    public function removeDireccion($index)
    {
        unset($this->direccionesAsignadas[$index]);
        $this->direccionesAsignadas = array_values($this->direccionesAsignadas); // Reindexar el array
    }


    /**
     * Elimina el archivo de facturación
     */
    public function eliminarArchivoFacturacion()
    {
        $this->proveedorEdit['archivo_facturacion_pdf'] = null;
        $this->fileNameFacturacion = null;
        $this->facturacion = null;
    }

    /**
     * Elimina el archivo de datos bancarios
     */
    public function eliminarArchivoBancarios()
    {
        $this->proveedorEdit['datos_bancarios_pdf'] = null;
        $this->fileNameBancarios = null;
        $this->bancarios = null;
    }

    /**
     * Calcula las subfamilias y actualiza las propiedades locales
     *
     * @param int $idFamiliaSeleccionada
     * @param int $nivel
     */
    public function calcularSubfamilias($idFamiliaSeleccionada, $nivel)
    {
        $resultado = Familia::calcularSubfamilias($idFamiliaSeleccionada, $nivel, $this->niveles, $this->seleccionadas);

        $this->niveles = $resultado['niveles'];
        $this->seleccionadas = $resultado['seleccionadas'];
    }

    /**
     * Añade un teléfono al array de teléfonos
     */
    public function addTelefono()
    {
        $this->telefonos[] = ['nombre' => '', 'numero' => ''];
    }

    /**
     * Elimina un teléfono del array de teléfonos
     *
     * @param int $index
     */
    public function removeTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos); // Reindexa el array
    }

    /**
     * Añade una familia al array de familias seleccionadas
     */
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

    /**
     * Confirma la selección de la familia y resetea los niveles
     */
    public function confirmFamilia()
    {
        $this->addFamilia();
        $this->openModalFamilias = false;
        $this->reset('seleccionadas', 'niveles');
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0)
            ->get();
    }

    /**
     * Elimina una familia del array de familias seleccionadas
     *
     * @param int $index
     */
    public function removeFamilia($index)
    {
        unset($this->familiasSeleccionadas[$index]);
        $this->familiasSeleccionadas = array_values($this->familiasSeleccionadas); // Reindexa el array
    }

    /**
     * Actualiza el nombre del archivo de facturación cuando se sube un nuevo archivo
     */
    public function updatedFacturacion()
    {
        if ($this->facturacion) {
            $this->fileNameFacturacion = $this->facturacion->getClientOriginalName();
        } else {
            $this->fileNameFacturacion = $this->proveedorEdit['archivo_facturacion_pdf'];
        }
    }

    /**
     * Actualiza el nombre del archivo de datos bancarios cuando se sube un nuevo archivo
     */
    public function updatedBancarios()
    {
        if ($this->bancarios) {
            $this->fileNameBancarios = $this->bancarios->getClientOriginalName();
        } else {
            $this->fileNameBancarios = $this->proveedorEdit['datos_bancarios_pdf'];
        }
    }

    public function updateProveedor()
    {
        // Obtener datos actuales del proveedor
        $proveedorActual = Proveedor::findOrFail($this->provedprEditId);
        //     public $facturacionDatosActual;
        // public $bancariosDatoActual;
        $this->validate(
            Proveedor::updateRules('proveedorEdit.', $this->provedprEditId), 
            Proveedor::updateMessages('proveedorEdit.')
        );
        $this->validate(Telefono::rules(), Telefono::messages());
        // $this->validate([
        //     'proveedorEdit.nombre' => 'required|string|max:255',
        //     'proveedorEdit.descripcion' => 'nullable|string',
        //     'proveedorEdit.correo' => 'required|email|unique:proveedores,correo,' . $this->proveedorEdit['id'],
        //     'proveedorEdit.rfc' => 'required|string|unique:proveedores,rfc,' . $this->proveedorEdit['id'],
        //     'proveedorEdit.archivo_facturacion_pdf' => 'nullable|max:2048',
        //     'proveedorEdit.datos_bancarios_pdf' => 'nullable|max:2048',
        // ], [
        //     'proveedorEdit.nombre.required' => 'Registrar un nombre es obligatorio.',
        //     'proveedorEdit.nombre.string' => 'Registra un nombre valido.',
        //     'proveedorEdit.nombre.max' => 'No puedes registrar un nombre tan largo.',
        //     'proveedorEdit.descripcion.string' => 'La descripción debe ser una cadena de texto.',
        //     'proveedorEdit.correo.required' => 'Registrar un correo electronico es obligatorio.',
        //     'proveedorEdit.correo.email' => 'La direccion de correo electronico registrada no es valida.',
        //     'proveedorEdit.correo.unique' => 'Esta dirección de correo electrónico ya está registrada.',
        //     'proveedorEdit.rfc.required' => 'Registrar el RFC es obligatorio.',
        //     'proveedorEdit.rfc.string' => 'El RFC registrado no es valido.',
        //     'proveedorEdit.rfc.unique' => 'Este RFC ya se encuentra registrado.',
        //     'proveedorEdit.archivo_facturacion_pdf.mimes' => 'El archivo de facturación debe ser un archivo PDF.',
        //     'proveedorEdit.archivo_facturacion_pdf.max' => 'El archivo de facturación no puede exceder los 2048KB.',
        //     'proveedorEdit.datos_bancarios_pdf.mimes' => 'El archivo de datos bancarios debe ser un archivo PDF.',
        //     'proveedorEdit.datos_bancarios_pdf.max' => 'El archivo de datos bancarios no puede exceder los 2048KB.',
        // ]);
        // Manejar los archivos de facturación y datos bancarios
        if ($this->facturacion) {
            if ($this->facturacion == $this->facturacionDatosActual) {
                $archivoFacturacion = $this->facturacion;
            } else {
                $archivoFacturacion = $this->facturacion->store('archivosFacturacionProveedores', 'public');
            }
        } else {
            $archivoFacturacion = $this->fileNameFacturacion ? $this->proveedorEdit['archivo_facturacion_pdf'] : null;
        }

        if ($this->bancarios) {
            if ($this->bancarios == $this->bancariosDatoActual) {
                $archivoBancarios = $this->bancarios;
            } else {
                $archivoBancarios = $this->bancarios->store('archivosBancariosProveedores', 'public');
            }
        } else {
            $archivoBancarios = $this->fileNameBancarios ? $this->proveedorEdit['datos_bancarios_pdf'] : null;
        }

        // Actualizar campos básicos
        $proveedorActual->update([
            'nombre' => $this->proveedorEdit['nombre'],
            'descripcion' => $this->proveedorEdit['descripcion'],
            'correo' => $this->proveedorEdit['correo'],
            'rfc' => $this->proveedorEdit['rfc'],
            'archivo_facturacion_pdf' => $archivoFacturacion,
            'datos_bancarios_pdf' => $archivoBancarios,
            'estado' => true,
        ]);

        // Obtener teléfonos actuales del proveedor
        $telefonosActuales = Telefono::where('proveedor_id', $this->provedprEditId)->pluck('numero')->toArray();

        // Comparar y actualizar teléfonos

        if ($telefonosActuales !== $this->telefonos) {
            Telefono::where('proveedor_id', $this->provedprEditId)->delete();
            foreach ($this->telefonos as $telefono) {
                Telefono::create([
                    'nombre' => $telefono['nombre'], // Guardar nombre de contacto
                    'numero' => $telefono['numero'], // Guardar número de teléfono
                    'proveedor_id' => $this->provedprEditId,
                ]);
            }
        }

        // Obtener familias actuales del proveedor
        $familiasActuales = ProveedorHasFamilia::where('proveedor_id', $this->provedprEditId)->pluck('familia_id')->toArray();

        // Comparar y actualizar familias
        $familiasSeleccionadasIds = array_map(function ($familia) {
            return $familia['id'];
        }, $this->familiasSeleccionadas);

        if ($familiasActuales !== $familiasSeleccionadasIds) {
            ProveedorHasFamilia::where('proveedor_id', $this->provedprEditId)->delete();
            foreach ($familiasSeleccionadasIds as $familia_id) {
                ProveedorHasFamilia::create([
                    'proveedor_id' => $this->provedprEditId,
                    'familia_id' => $familia_id,
                ]);
            }
        }

        Direccion::where('proveedor_id', $this->provedprEditId)->delete();

        // Iterar sobre las direcciones asignadas y guardarlas en la base de datos
        foreach ($this->direccionesAsignadas as $direccion) {
            // Asegurarnos de que el arreglo contiene los datos necesarios
            
                // Insertar la nueva dirección
                DB::table('direcciones')->insert(
                    [
                        'proveedor_id' => $direccion['proveedor_id'] ?? '',
                'calle' => $direccion['calle'] ?? '',
                'pais' => $direccion['pais'] ?? '',
                'numero' => $direccion['numero'] ?? '',
                'colonia' => $direccion['colonia'] ?? '',
                'municipio' => $direccion['municipio'] ?? '',
                'ciudad' => $direccion['ciudad'] ?? '',
                'estado' => $direccion['estado'] ?? '',
                'cp' => $direccion['cp'] ?? '',
                'referencia' => $direccion['referencia'] ?? '',
                'Latitud' => $direccion['Latitud'] ?? '',
                'Longitud' => $direccion['Longitud'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            
                    ]
                );
            
        }


        // Feedback al usuario
        session()->flash('message', 'Proveedor actualizado exitosamente.');
        return ['proveedor_id' => $proveedorActual->id];
    }
}
