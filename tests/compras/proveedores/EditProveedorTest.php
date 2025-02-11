<?php

namespace Tests\Feature\Livewire\Proveedor;

use App\Livewire\Proveedor\CreateProveedor;
use App\Livewire\Proveedor\EditProveedor;
use App\Models\Proveedor;
use App\Models\Telefono;
use App\Models\Familia;
use App\Models\ProveedorHasFamilia;
use App\Models\Direccion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class EditProveedorTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function el_componente_se_monta_correctamente()
    {
        // Crear un proveedor sin factory
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'descripcion' => 'Descripción de prueba',
            'correo' => 'test@correo.com',
            'rfc' => 'RFC123456789',
        ]);

        // Probar que el componente se renderiza correctamente
        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedor->id])
            ->assertSet('proveedorEdit.id', $proveedor->id)
            ->assertSet('proveedorEdit.nombre', 'Proveedor Test');
    }

    /** @test */
    public function puede_actualizar_un_proveedor()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Original',
            'descripcion' => 'Descripción original',
            'correo' => 'original@correo.com',
            'rfc' => 'RFCORIG123456',
        ]);

        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedor->id])
            ->set('proveedorEdit.nombre', 'Proveedor Actualizado')
            ->set('proveedorEdit.descripcion', 'Nueva descripción')
            ->set('proveedorEdit.rfc', 'RFC123456XXX')
            ->set('telefonos', [
                ['nombre' => 'Contacto 1', 'numero' => '1234567890'],
                ['nombre' => 'Contacto 2', 'numero' => '0987654321'],
            ])
            ->call('updateProveedor')
            ->assertHasNoErrors();

        // Verificar que los cambios se guardaron en la base de datos
        $this->assertDatabaseHas('proveedores', [
            'id' => $proveedor->id,
            'nombre' => 'Proveedor Actualizado',
            'descripcion' => 'Nueva descripción',
        ]);
    }


    /** @test */
    public function no_puede_crear_un_proveedor_sin_nombre()
    {
        // Crear un proveedor para realizar la prueba
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'descripcion' => 'Descripción del proveedor',
            'correo' => 'correo@prueba.com',
            'rfc' => 'GARC840215HDF',
            'archivo_facturacion_pdf' => null,
            'datos_bancarios_pdf' => null,
            'estado' => true
        ]);

        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedor->id])
            ->set('proveedorEdit.nombre', '')  // Dejar el nombre vacío
            ->set('proveedorEdit.descripcion', 'Descripción del proveedor')
            ->set('proveedorEdit.correo', 'correo@prueba.com')
            ->set('proveedorEdit.rfc', 'GARC840215HDF')
            ->call('updateProveedor')
            ->assertHasErrors(['proveedorEdit.nombre' => 'required']); // Verifica que se muestre el error de validación
    }
    /////////////////////////
    /** @test */
    public function no_puede_crear_un_proveedor_con_correo_invalido()
    {
        // Crear un proveedor para realizar la prueba
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'descripcion' => 'Descripción del proveedor',
            'correo' => 'correo-invalido',  // Correo inválido
            'rfc' => 'GARC840215HDF',
            'archivo_facturacion_pdf' => null,
            'datos_bancarios_pdf' => null,
            'estado' => true
        ]);

        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedor->id])
            ->set('proveedorEdit.nombre', 'Proveedor Test')
            ->set('proveedorEdit.descripcion', 'Descripción del proveedor')
            ->set('proveedorEdit.correo', 'correo-invalido')  // Correo inválido
            ->set('proveedorEdit.rfc', 'GARC840215HDF')
            ->call('updateProveedor')
            ->assertHasErrors(['proveedorEdit.correo' => 'email']);
    }


    /** @test */
    public function no_puede_crear_un_proveedor_con_rfc_invalido()
    {
        // Crear un proveedor con un RFC inválido
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'descripcion' => 'Descripción del proveedor',
            'correo' => 'correo@prueba.com',
            'rfc' => 'RFC-INVALIDO',  // RFC inválido
            'archivo_facturacion_pdf' => null,
            'datos_bancarios_pdf' => null,
            'estado' => true
        ]);

        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedor->id])
            ->set('proveedorEdit.nombre', 'Proveedor Test')
            ->set('proveedorEdit.descripcion', 'Descripción del proveedor')
            ->set('proveedorEdit.correo', 'correo@prueba.com')
            ->set('proveedorEdit.rfc', 'RFC-INVALIDO')  // RFC inválido
            ->call('updateProveedor')
            ->assertHasErrors(['proveedorEdit.rfc']);  // Asegúrate de que el nombre es correcto
    }


    /** @test */
    public function puede_agregar_y_eliminar_telefonos()
    {
        // Crear un proveedor sin teléfonos
        $proveedor = \App\Models\Proveedor::create([
            'nombre' => 'Proveedor Test',
            'rfc' => 'RFC123456789',
            'correo' => 'proveedor@test.com',
        ]);

        $proveedorId = $proveedor->id;

        // Ejecutar la prueba Livewire
        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedorId])
            ->assertSet('telefonos', []) // Inicialmente, el array de telefonos debe estar vacío
            ->call('addTelefono') // Agregar un teléfono
            ->assertSet('telefonos', [
                ['nombre' => '', 'numero' => '']   // Un teléfono vacío debe ser añadido
            ])
            ->call('removeTelefono', 0) // Eliminar el primer teléfono
            ->assertSet('telefonos', []);  // Después de eliminar, el array debe quedar vacío
    }


    /** @test */
    public function puede_agregar_y_eliminar_familias()
    {
        // Crear un proveedor
        $proveedor = \App\Models\Proveedor::create([
            'nombre' => 'Proveedor de prueba',
            'rfc' => 'RFC123456789',
            'correo' => 'proveedor@test.com',
        ]);

        // Crear una familia
        $familia = \App\Models\Familia::create(['nombre' => 'Familia de prueba', 'estadoEliminacion' => 0]);

        // Ejecutar la prueba Livewire
        Livewire::test(EditProveedor::class, ['idproveedor' => $proveedor->id])
            ->set('seleccionadas', [$familia->id]) // Asignar la familia al proveedor
            ->call('addFamilia') // Llamar a la función para agregar la familia
            ->assertSet('familiasSeleccionadas', function ($familiasSeleccionadas) use ($familia) {
                return $familiasSeleccionadas[0]->id === $familia->id;
            })
            ->call('removeFamilia', 0) // Eliminar la familia agregada
            ->assertSet('familiasSeleccionadas', []); // Verificar que las familias seleccionadas están vacías
    }
}
