<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Proveedor;
use App\Models\Familia;
use App\Models\ProveedorHasFamilia;
use App\Livewire\Proveedor\ProveedorComponent;

class ProveedorComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function el_componente_se_renderiza_correctamente()
    {
        Livewire::test(ProveedorComponent::class)
            ->assertStatus(200); // Puedes verificar contenido específico si lo prefieres
    }

    /** @test */
    public function puede_buscar_proveedores()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'correo' => 'test@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->set('searchTerm', 'Proveedor Test')
            ->call('search')
            ->assertSet('searchTerm', 'Proveedor Test');
    }

    /** @test */
    public function puede_filtrar_por_familia()
    {
        $familia = Familia::create(['nombre' => 'Familia Test', 'estadoEliminacion' => 0]);
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'correo' => 'test@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);
        ProveedorHasFamilia::create(['proveedor_id' => $proveedor->id, 'familia_id' => $familia->id]);

        Livewire::test(ProveedorComponent::class)
            ->set('familiasSeleccionadas', [$familia->id])
            ->call('filter')
            ->assertSee('Proveedor Test');
    }

    /** @test */
    public function puede_ordenar_proveedores()
    {
        $proveedor1 = Proveedor::create([
            'nombre' => 'Proveedor A',
            'correo' => 'a@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        $proveedor2 = Proveedor::create([
            'nombre' => 'Proveedor B',
            'correo' => 'b@correo.com',
            'rfc' => 'GARB840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->set('sort', 'nombre')
            ->set('direction', 'asc')
            ->call('search') // Utiliza el método adecuado en lugar de 'render'
            ->assertSeeInOrder(['Proveedor A', 'Proveedor B']);
    }


    /** @test */
    public function puede_eliminar_proveedor()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'correo' => 'test@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->call('eliminar', $proveedor->id)
            ->assertDontSee('Proveedor Test');

        $this->assertEquals(0, Proveedor::find($proveedor->id)->estado_eliminacion);
    }



    /** @test */
    public function busqueda_no_devuelve_resultados()
    {
        Livewire::test(ProveedorComponent::class)
            ->set('searchTerm', 'NoExistente')
            ->call('search')
            ->assertDontSee('Proveedor Test');
    }


    /** @test */
    public function no_puede_seleccionar_familia_no_existente()
    {
        Livewire::test(ProveedorComponent::class)
            ->call('seleccionarFamilia', 999) // ID de familia no existente
            ->assertDontSee('Familia Test');
    }

    /** @test */
    public function llamaRutaDeVistaEspecifica()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor a ver',
            'correo' => 'ver@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->call('viewProveedor', $proveedor->id)
            ->assertRedirect(route('compras.proveedores.viewProveedorEspecifico', ['idproveedor' => $proveedor->id]));
    }
    /** @test */
    public function llamaRutaDeVistaEspecificaConIdNoExistente()
    {
        $nonExistentProveedorId = 999;

        Livewire::test(ProveedorComponent::class)
            ->call('viewProveedor', $nonExistentProveedorId)
            ->assertStatus(404);
    }
    /** @test */
    public function llamaRutaDeVistaEspecificaConIdNullo()
    {
        Livewire::test(ProveedorComponent::class)
            ->call('viewProveedor', null)
            ->assertStatus(404);
    }
    /** @test */
    public function llamaRutaDeVistaEspecificaConIdInvalido()
    {
        $invalidProveedorId = 'invalid-id';

        Livewire::test(ProveedorComponent::class)
            ->call('viewProveedor', $invalidProveedorId)
            ->assertStatus(404);
    }

}
