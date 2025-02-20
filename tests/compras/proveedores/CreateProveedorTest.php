<?php

namespace Tests\Feature\Livewire\Proveedor;

use App\Livewire\Proveedor\CreateProveedor;
use App\Models\Proveedor;
use App\Models\Telefono;
use App\Models\Familia;
use App\Models\ProveedorHasFamilia;
use App\Rules\ValidaRfc;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File as UploadedFile;


class CreateProveedorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::test(CreateProveedor::class)
            ->assertStatus(200); // Cambia esto por un texto relevante en la vista
    }

    /** @test */
    public function it_creates_a_proveedor_correctly()
    {
        // Crear una familia que se pueda asociar al proveedor
        $familia = Familia::create([
            'nombre' => 'Familia Test',
            'descripcion' => 'Descripción de la familia',
            'nivel' => 1,
        ]);
        // Crear algunos teléfonos de ejemplo
        $telefonos = [
            ['nombre' => 'Telefono 1', 'numero' => '123456789'],
            ['nombre' => 'Telefono 2', 'numero' => '987654321'],
        ];
        // Simular la subida de archivos
        $facturacion = UploadedFile::fake()->create('facturacion.pdf', 100);
        $bancarios = UploadedFile::fake()->create('bancarios.pdf', 100);
        // Instanciar el componente Livewire
        $component = Livewire::test('proveedor.create-proveedor')
            ->set('nombre', 'Proveedor Test')
            ->set('descripcion', 'Descripción del proveedor')
            ->set('correo', 'correo@proveedor.com')
            ->set('rfc', 'GARC840215HDF')
            ->set('telefonos', $telefonos)
            ->set('facturacion', $facturacion)
            ->set('bancarios', $bancarios);
        // Llamar al método `save()` del componente
        $component->call('save');
        // Verificar que el proveedor se ha creado correctamente
        $proveedor = Proveedor::first();
        $this->assertEquals('Proveedor Test', $proveedor->nombre);
        $this->assertEquals('correo@proveedor.com', $proveedor->correo);
        $this->assertEquals('GARC840215HDF', $proveedor->rfc);
        // Verificar que los teléfonos se han creado
        $this->assertCount(2, $proveedor->telefonos);
        $this->assertEquals('123456789', $proveedor->telefonos[0]->numero);
        $this->assertEquals('987654321', $proveedor->telefonos[1]->numero);

        // Verificar que la familia ha sido asociada al proveedor

        // Verificar que los archivos se han guardado

    }


    /** @test */
    public function no_puede_crear_un_proveedor_sin_nombre()
    {
        Livewire::test(CreateProveedor::class)
            ->set('nombre', '')
            ->set('descripcion', 'Descripción del proveedor')
            ->set('correo', 'proveedor@prueba.com')
            ->set('rfc', 'GARC840215HDF')
            ->call('save')
            ->assertHasErrors(['nombre' => 'required']);
    }

    /** @test */
    public function puede_agregar_telefonos()
    {
        Livewire::test(CreateProveedor::class)
            ->call('addTelefono')
            ->assertSet('telefonos', [['nombre' => '', 'numero' => ''], ['nombre' => '', 'numero' => '']]);
    }

    /** @test */
    public function puede_eliminar_telefonos()
    {
        Livewire::test(CreateProveedor::class)
            ->call('addTelefono') // Añade un teléfono extra para eliminar
            ->call('removeTelefono', 0)
            ->assertSet('telefonos', [['nombre' => '', 'numero' => '']]);
    }


    /** @test */
    public function puede_agregar_familias_a_un_proveedor()
    {
        $familia = Familia::create(['nombre' => 'Familia de prueba', 'estadoEliminacion' => 0]);

        Livewire::test(CreateProveedor::class)
            ->set('seleccionadas', [$familia->id])
            ->call('addFamilia')
            ->assertSet('familiasSeleccionadas', function ($familiasSeleccionadas) use ($familia) {
                return $familiasSeleccionadas[0]->id === $familia->id;
            });
    }


    /** @test */
    public function puede_eliminar_familias_seleccionadas()
    {
        $familia = Familia::create(['nombre' => 'Familia de prueba']);

        Livewire::test(CreateProveedor::class)
            ->set('familiasSeleccionadas', [$familia])
            ->call('removeFamilia', 0)
            ->assertSet('familiasSeleccionadas', []);
    }

    /** @test */
    public function test_calcular_subfamilias()
    {
        $familiaPadre = Familia::create([
            'nombre' => 'Categoría Padre',
            'descripcion' => 'Descripción',
            'nivel' => 1,
            'estadoEliminacion' => false,
        ]);

        $subfamilia = Familia::create([
            'nombre' => 'Subfamilia',
            'descripcion' => 'Subcategoría',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familiaPadre->id,
        ]);

        Livewire::test(CreateProveedor::class)
            ->call('calcularSubfamilias', $familiaPadre->id, 1)
            ->assertSee($subfamilia->nombre);
    }
    /** @test */
    public function no_puede_crear_un_proveedor_con_correo_invalido()
    {
        Livewire::test(CreateProveedor::class)
            ->set('nombre', 'Proveedor Test')
            ->set('descripcion', 'Descripción del proveedor')
            ->set('correo', 'correo_no_valido') // Correo inválido
            ->set('rfc', 'GARC840215HDF')
            ->call('save')
            ->assertHasErrors(['correo' => 'email']);
    }
    /** @test */
    public function no_puede_crear_un_proveedor_con_rfc_invalido()
    {
        Livewire::test(CreateProveedor::class)
            ->set('nombre', 'Proveedor Test')
            ->set('descripcion', 'Descripción del proveedor')
            ->set('correo', 'correo@prueba.com')
            ->set('rfc', 'RFCINVALIDO') // RFC inválido
            ->call('save')
            ->assertHasErrors(['rfc' => 'Este RFC no es válido, verificalo.']);
    }





    /** @test */
    /** @test */
    public function no_puede_crear_un_proveedor_con_numero_de_telefono_invalido()
    {
        Livewire::test(CreateProveedor::class)
            ->set('nombre', 'Proveedor Test')
            ->set('descripcion', 'Descripción del proveedor')
            ->set('correo', 'correo@prueba.com')
            ->set('rfc', 'GARC840215HDF')
            ->set('telefonos', [['nombre' => 'Telefono 1', 'numero' => 'abcde']]) // Número de teléfono inválido
            ->call('save')
            ->assertHasErrors(['telefonos.0.numero' => 'numeric']);
    }
}
