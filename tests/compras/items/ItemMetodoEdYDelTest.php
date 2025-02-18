<?php

namespace Tests\Feature;

use App\Livewire\Item\ItemComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\Familia;
use App\Models\Proveedor;

class ItemMetodoEdYDelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function llamaRutaDeEdicion()
    {
        $item = Item::create([
            'nombre' => 'Item a editar',
            'descripcion' => 'Descripción a editar',
        ]);

        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,
            'marca' => 'Marca Test',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 90.00,
            'unidad' => 'pieza',
            'estado_eliminacion' => 1,
        ]);

        Livewire::test(ItemComponent::class)
            ->call('editItem', $itemEspecifico->id)
            ->assertRedirect(route('compras.items.edicionItem', ['idItem' => $itemEspecifico->id]));
    }

    /** @test */
    public function llamarRutaDeEdicionDeIdNoExistente()
    {
        $nonExistentItemId = 999;

        Livewire::test(ItemComponent::class)
            ->call('editItem', $nonExistentItemId)
            ->assertStatus(404); // Ajusta esto según la forma en que manejes errores
    }

    /** @test */
    public function llamarRutaDeEdicionDeIdNulo()
    {
        Livewire::test(ItemComponent::class)
            ->call('editItem', null)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }

    /** @test */
    public function llamarRutaDeEdicionDeIdInvalido()
    {
        $invalidItemId = 'invalid-id';

        Livewire::test(ItemComponent::class)
            ->call('editItem', $invalidItemId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }

    /** @test */
    public function llamaRutaDeEliminacion()
    {
        $item = Item::create([
            'nombre' => 'Item a eliminar',
            'descripcion' => 'Descripción a eliminar',
        ]);

        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,
            'marca' => 'Marca Test',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 90.00,
            'unidad' => 'pieza',
            'estado_eliminacion' => 1,
        ]);

        Livewire::test(ItemComponent::class)
            ->call('eliminar', $itemEspecifico->id);

        $this->assertDatabaseHas('item_especifico', [
            'id' => $itemEspecifico->id,
            'estado_eliminacion' => false,
        ]);
    }
}
