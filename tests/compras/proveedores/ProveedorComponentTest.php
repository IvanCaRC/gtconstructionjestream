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
        $proveedor = Proveedor::factory()->create([
            'nombre' => 'Proveedor Test',
            'correo' => 'test@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->set('searchTerm', 'Proveedor Test')
            ->call('render')
            ->assertSee('Proveedor Test')
            ->assertSee('test@correo.com')
            ->assertSee('GARC840215HDF');
    }

    /** @test */
    public function puede_filtrar_por_familia()
    {
        $familia = Familia::factory()->create(['nombre' => 'Familia Test', 'estadoEliminacion' => 0]);
        $proveedor = Proveedor::factory()->create(['estado_eliminacion' => 1]);
        ProveedorHasFamilia::create(['proveedor_id' => $proveedor->id, 'familia_id' => $familia->id]);

        Livewire::test(ProveedorComponent::class)
            ->set('familiasSeleccionadas', [$familia->id])
            ->call('render')
            ->assertSee('Familia Test');
    }

    /** @test */
    public function puede_ordenar_proveedores()
    {
        $proveedor1 = Proveedor::factory()->create(['nombre' => 'Proveedor A', 'estado_eliminacion' => 1]);
        $proveedor2 = Proveedor::factory()->create(['nombre' => 'Proveedor B', 'estado_eliminacion' => 1]);

        Livewire::test(ProveedorComponent::class)
            ->set('sort', 'nombre')
            ->set('direction', 'asc')
            ->call('render')
            ->assertSeeInOrder(['Proveedor A', 'Proveedor B']);
    }

    /** @test */
    public function puede_eliminar_proveedor()
    {
        $proveedor = Proveedor::factory()->create(['estado_eliminacion' => 1]);

        Livewire::test(ProveedorComponent::class)
            ->call('eliminar', $proveedor->id)
            ->assertDontSee($proveedor->nombre);

        $this->assertFalse(Proveedor::find($proveedor->id)->estado_eliminacion);
    }

    /** @test */
    public function busqueda_no_devuelve_resultados()
    {
        Livewire::test(ProveedorComponent::class)
            ->set('searchTerm', 'NoExistente')
            ->call('render')
            ->assertDontSee('Proveedor Test');
    }

    /** @test */
    public function no_puede_seleccionar_familia_no_existente()
    {
        Livewire::test(ProveedorComponent::class)
            ->call('seleccionarFamilia', 999) // ID de familia no existente
            ->assertDontSee('Familia Test');
    }
}
