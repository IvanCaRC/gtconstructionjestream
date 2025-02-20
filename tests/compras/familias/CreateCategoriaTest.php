<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Familia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Familia\CreateCategoria;

class CreateCategoriaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::test(CreateCategoria::class)
            ->assertStatus(200); // Cambia esto por un texto relevante en la vista
    }

    /** @test */
    public function test_validacion_campos_requeridos()
    {
        Livewire::test(CreateCategoria::class)
            ->call('save')
            ->assertHasErrors(['nombre' => 'required']);
    }

    /** @test */
    public function test_crear_familia()
    {
        Livewire::test(CreateCategoria::class)
            ->set('nombre', 'Nueva Categoría')
            ->set('descripcion', 'Descripción de la categoría')
            ->call('save');

        $this->assertDatabaseHas('familias', [
            'nombre' => 'Nueva Categoría',
            'descripcion' => 'Descripción de la categoría',
            'estadoEliminacion' => false,
        ]);
    }
    /** @test */
    public function no_puede_crear_familia_sin_nombre()
    {
        Livewire::test(CreateCategoria::class)
            ->set('nombre', '')
            ->call('save')
            ->assertHasErrors(['nombre' => 'required']);
    }


    /** @test */
    public function no_puede_crear_familia_con_nombre_muy_largo()
    {
        Livewire::test(CreateCategoria::class)
            ->set('nombre', str_repeat('A', 256)) // Nombre de 256 caracteres
            ->call('save')
            ->assertHasErrors(['nombre' => 'max']);
    }

    /** @test */
    public function no_puede_crear_familia_con_descripcion_invalida()
    {
        Livewire::test(CreateCategoria::class)
            ->set('nombre', 'Familia válida')
            ->set('descripcion', ['esto', 'no', 'es', 'una', 'string'])
            ->call('save')
            ->assertHasErrors(['descripcion' => 'string']);
    }

    /** @test */
    public function maneja_datos_excesivos()
    {
        Livewire::test(CreateCategoria::class)
            ->set('nombre', str_repeat('X', 10000)) // 10,000 caracteres
            ->call('save')
            ->assertHasErrors(['nombre' => 'max']);
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

        Livewire::test(CreateCategoria::class)
            ->call('calcularSubfamilias', $familiaPadre->id, 1)
            ->assertSee($subfamilia->nombre);
    }
}
