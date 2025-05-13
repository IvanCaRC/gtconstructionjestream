<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Familia;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;
use App\Livewire\Item\ItemComponent;

class ItemComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function el_componente_se_renderiza_correctamente()
    {
        Livewire::test(ItemComponent::class)
            ->assertStatus(200); // Puedes verificar contenido específico si lo prefieres
    }

    /** @test */
    public function puede_buscar_items()
    {
        $item = Item::create([
            'nombre' => 'Item Test',
            'descripcion' => 'Descripción del item de prueba',
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
            ->set('searchTerm', 'Item Test')
            ->call('search')
            ->assertSet('searchTerm', 'Item Test');
    }

    /** @test */
    public function puede_filtrar_por_familia()
    {
        $familia = Familia::create(['nombre' => 'Familia Test', 'estadoEliminacion' => 0]);
        $item = Item::create(['nombre' => 'Item Test']);
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
        ItemEspecificoHasFamilia::create(['item_especifico_id' => $itemEspecifico->id, 'familia_id' => $familia->id]);

        Livewire::test(ItemComponent::class)
            ->set('familiasSeleccionadas', [$familia->id])
            ->call('filter')
            ->assertSee('Item Test');
    }

    /** @test */
    public function puede_ordenar_items()
    {
        $item1 = Item::create(['nombre' => 'Item A']);
        $itemEspecifico1 = ItemEspecifico::create([
            'item_id' => $item1->id,
            'marca' => 'Marca A',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 90.00,
            'unidad' => 'pieza',
            'estado_eliminacion' => 1,
        ]);

        $item2 = Item::create(['nombre' => 'Item B']);
        $itemEspecifico2 = ItemEspecifico::create([
            'item_id' => $item2->id,
            'marca' => 'Marca B',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'precio_venta_minorista' => 120.00,
            'precio_venta_mayorista' => 110.00,
            'unidad' => 'pieza',
            'estado_eliminacion' => 1,
        ]);

        Livewire::test(ItemComponent::class)
            ->set('sort', 'nombre')
            ->set('direction', 'asc')
            ->call('search')
            ->assertSeeInOrder(['Item A', 'Item B']);
    }

    /** @test */
    // public function puede_eliminar_item()
    // {
    //     $item = Item::create(['nombre' => 'Item Test']);
    //     $itemEspecifico = ItemEspecifico::create([
    //         'item_id' => $item->id,
    //         'marca' => 'Marca Test',
    //         'cantidad_piezas_mayoreo' => 10,
    //         'cantidad_piezas_minorista' => 5,
    //         'precio_venta_minorista' => 100.00,
    //         'precio_venta_mayorista' => 90.00,
    //         'unidad' => 'pieza',
    //         'estado_eliminacion' => 1,
    //     ]);

    //     Livewire::test(ItemComponent::class)
    //         ->call('eliminar', $itemEspecifico->id)
    //         ->assertDontSee('Item Test');

    //     $this->assertEquals(0, ItemEspecifico::find($itemEspecifico->id)->estado_eliminacion);
    // }

    /** @test */
    public function busqueda_no_devuelve_resultados()
    {
        Livewire::test(ItemComponent::class)
            ->set('searchTerm', 'NoExistente')
            ->call('search')
            ->assertDontSee('Item Test');
    }

    /** @test */
    public function no_puede_seleccionar_familia_no_existente()
    {
        Livewire::test(ItemComponent::class)
            ->call('seleccionarFamilia', 999) // ID de familia no existente
            ->assertDontSee('Familia Test');
    }

    /** @test */
    public function llamaRutaDeVistaEspecifica()
    {
        $item = Item::create(['nombre' => 'Item Test']);
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
            ->call('viewItem', $itemEspecifico->id)
            ->assertRedirect(route('compras.items.vistaEspecificaItem', ['idItem' => $itemEspecifico->id]));
    }

    /** @test */
    public function llamaRutaDeVistaEspecificaConIdNoExistente()
    {
        $nonExistentItemId = 999;

        Livewire::test(ItemComponent::class)
            ->call('viewItem', $nonExistentItemId)
            ->assertStatus(404);
    }

    /** @test */
    public function llamaRutaDeVistaEspecificaConIdNullo()
    {
        Livewire::test(ItemComponent::class)
            ->call('viewItem', null)
            ->assertStatus(404);
    }

    /** @test */
    public function llamaRutaDeVistaEspecificaConIdInvalido()
    {
        $invalidItemId = 'invalid-id';

        Livewire::test(ItemComponent::class)
            ->call('viewItem', $invalidItemId)
            ->assertStatus(404);
    }
}
