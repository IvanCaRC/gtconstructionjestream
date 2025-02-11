<?php

namespace Tests\Feature;

use App\Livewire\Familia\FamiliaComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Familia;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class FamiliaMetodoEdYDelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function llamaRutaDeEdicion()
    {
        $familia = Familia::create([
            'nombre' => 'Familia a editar',
            'descripcion' => 'Descripción a editar',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        Livewire::test(FamiliaComponent::class)
            ->call('editCategory', $familia->id)
            ->assertRedirect(route('compras.familias.edicionFamilia', ['idfamilia' => $familia->id]));
    }

    /** @test */
    public function llamarRutaDeEdicionDeIdNoExistente()
    {
        $nonExistentFamilyId = 999;

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('editCategory', $nonExistentFamilyId)
            ->assertStatus(404); // Ajusta esto según la forma en que manejes errores
    }

    /** @test */
    public function llamarRutaDeEdicionDeIdNulo()
    {
        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('editCategory', null)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }
    /** @test */
    public function llamarRutaDeEdicionDeIdInvalido()
    {
        $invalidFamilyId = 'invalid-id';

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('editCategory', $invalidFamilyId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }


    /** @test */
    public function llamaRutaDeEliminacion()
    {
        $familia = Familia::create([
            'nombre' => 'Familia a eliminar',
            'descripcion' => 'Descripción a eliminar',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        Livewire::test(FamiliaComponent::class)
            ->call('eliminar', $familia->id);

        $this->assertDatabaseHas('familias', [
            'id' => $familia->id,
            'estadoEliminacion' => true,
        ]);
    }

    public function eliminarFamiliaConSubfamilia()
    {
        // Crear la familia principal
        $familia = Familia::create([
            'nombre' => 'Familia principal',
            'descripcion' => 'Descripción principal',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        // Crear subfamilias
        $subfamilia1 = Familia::create([
            'nombre' => 'Subfamilia 1',
            'descripcion' => 'Descripción de subfamilia 1',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familia->id,
        ]);

        $subfamilia2 = Familia::create([
            'nombre' => 'Subfamilia 2',
            'descripcion' => 'Descripción de subfamilia 2',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familia->id,
        ]);

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->set('subfamilias', [$subfamilia1, $subfamilia2]) // Establecer subfamilias en el componente
            ->call('eliminarFamiliaConSubfamilias', $familia->id);

        // Verificar que la familia principal tiene estadoEliminacion = 1
        $this->assertDatabaseHas('familias', [
            'id' => $familia->id,
            'estadoEliminacion' => 1,
        ]);

        // Verificar que las subfamilias tienen estadoEliminacion = 1
        $this->assertDatabaseHas('familias', [
            'id' => $subfamilia1->id,
            'estadoEliminacion' => 1,
        ]);

        $this->assertDatabaseHas('familias', [
            'id' => $subfamilia2->id,
            'estadoEliminacion' => 1,
        ]);
    }
    /** @test */
    public function eliminarFamiliaConSubfamiliaIdInexistente()
    {
        $nonExistentFamilyId = 999;

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('eliminarFamiliaConSubfamilias', $nonExistentFamilyId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }
    /** @test */
    public function eliminarFamiliaConSubfamiliaIdNulo()
    {
        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('eliminarFamiliaConSubfamilias', null)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }
    /** @test */
    public function eliminarFamiliaConSubfamiliaIdInvalido()
    {
        $invalidFamilyId = 'invalid-id';

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('eliminarFamiliaConSubfamilias', $invalidFamilyId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }

}