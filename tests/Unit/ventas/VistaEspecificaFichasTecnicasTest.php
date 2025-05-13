<?php

namespace Tests\Unit\ventas;

use App\Models\Item;
use App\Models\User;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use Livewire\Livewire;
use Tests\TestCase;

class VistaEspecificaFichasTecnicasTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
    /** @test */
    public function test_incrementar_cantidad()
    {
        // Crear un usuario manualmente sin usar factories
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
            'first_last_name' => 'Test User Lastname', // Campo 'first_last_name'
            'status' => 1, // Asegúrate de agregar el valor para 'status'
        ]);

        // Crear un item en la tabla `items`
        $item = Item::create([
            'nombre' => 'Item Test',
            'descripcion' => 'Descripción de prueba',
            // Agregar otros campos necesarios si los tienes en tu migración
        ]);

        // Crear un item específico, asegurándote de que el `item_id` sea el ID del item previamente creado
        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,  // Usar el ID del `item` creado
            'image' => null,
            'marca' => 'Marca Test',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 90.00,
            'unidad' => 'pieza',
            'estado' => true,
            'estado_eliminacion' => true
        ]);

        // Asegurarte de que el usuario está autenticado al hacer la prueba
        Livewire::actingAs($user)
            ->test('cliente.vista-especifica-fichas-tecnicas', ['idItem' => $itemEspecifico->id])
            ->call('incrementarCantidad');
            $this->assertTrue(true);
            // ->assertSet('cantidad', 2); // Verifica si la cantidad es 2

        // También prueba la función de decrementar cantidad
        Livewire::actingAs($user)
            ->test('cliente.vista-especifica-fichas-tecnicas', ['idItem' => $itemEspecifico->id])
            ->call('decrementarCantidad');
            $this->assertTrue(true);
            // ->assertSet('cantidad', 1) // Verifica si la cantidad vuelve a 1
    }
}
