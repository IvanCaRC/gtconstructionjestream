<?php

namespace Tests\Feature;

use App\Livewire\Familia\FamiliaEdicion;
use App\Models\Familia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FamiliaEdicionTest extends TestCase
{
    use RefreshDatabase;
    public function renderizaElComponenete()
    {
        Livewire::test(FamiliaEdicion::class)
            ->assertStatus(200);
    }
    /** @test */
    public function puede_editar_una_familia_existente()
    {
        // Crear una familia
        $familia = Familia::create([
            'nombre' => 'Familia Original',
            'descripcion' => 'Descripción Original',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        // Probar edición con Livewire
        Livewire::test(FamiliaEdicion::class, ['idfamilia' => $familia->id])
            ->set('familiaEdit.nombre', 'Familia Editada')
            ->set('familiaEdit.descripcion', 'Descripción Editada')
            ->call('update')
            ->assertHasNoErrors(); 

        // Verificar que los cambios se guardaron en la base de datos
        $this->assertDatabaseHas('familias', [
            'id' => $familia->id,
            'nombre' => 'Familia Editada',
            'descripcion' => 'Descripción Editada',
        ]);
    }

    /** @test */
    public function no_puede_asignar_familia_padre_inexistente()
    {
        $familia = Familia::create([
            'nombre' => 'Familia Principal',
            'descripcion' => 'Descripción',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        Livewire::test(FamiliaEdicion::class, ['idfamilia' => $familia->id])
            ->set('seleccionadas', [999]) // ID inexistente
            ->call('update')
            ->assertHasErrors(['id_familia_padre']); // Verifica que Livewire genera un error
    }


    
    /** @test */
    public function test_calcular_subfamilias_en_edicion()
    {
        // Crear la familia padre
        $familiaPadre = Familia::create([
            'nombre' => 'Categoría Padre',
            'descripcion' => 'Descripción',
            'nivel' => 1,
            'estadoEliminacion' => false,
        ]);

        // Crear una subfamilia que depende de la familia padre
        $subfamilia = Familia::create([
            'nombre' => 'Subfamilia',
            'descripcion' => 'Subcategoría',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familiaPadre->id,
        ]);

        // Llamar al método que calcula las subfamilias
        Livewire::test(FamiliaEdicion::class, ['idfamilia' => $familiaPadre->id])
            ->call('calcularSubfamilias', $familiaPadre->id, 1) // Llama al método que calcula las subfamilias
            ->assertSee($subfamilia->nombre); // Verifica que la subfamilia aparece en la vista
    }
}
