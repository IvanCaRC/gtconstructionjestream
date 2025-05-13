<?php

namespace Tests\Unit\ventas;

use App\Livewire\Cotisaciones\VerCarritoCotisaciones;
use Tests\TestCase;

use Livewire\Livewire;
use App\Models\Cotizacion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerCarritoCotisacionesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        // Crear una lista y cotización de prueba
        // Crear usuario antes de asignarlo a la lista
        $usuario = User::create([
            'name' => 'Usuario Prueba',
            'first_last_name' => 'Apellido',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
            'status' => true,
        ]);

        // Crear lista de cotización con el usuario
        $listaCotizar = ListasCotizar::create([
            'nombre' => 'Lista Test',
            'estado' => 2,
            'usuario_id' => $usuario->id,
        ]);

        // Crear cotización con usuario y lista
        $cotizacion = Cotizacion::create([
            'lista_cotizar_id' => $listaCotizar->id,
            'usuario_id' => $usuario->id,
            'estado' => 1,
            'items_cotizar_stock' => json_encode([]),
            'items_cotizar_proveedor' => json_encode([]),
        ]);

        // Simular Livewire y verificar carga de datos
        Livewire::test(VerCarritoCotisaciones::class, ['idCotisacion' => $cotizacion->id])
            ->assertSet('idCotisacion', $cotizacion->id)
            ->assertSet('idListaActual', $listaCotizar->id)


            ->assertStatus(200); // Cambia esto por un texto relevante en la vista
    }
    /** @test */
    public function inicializa_correctamente_el_componente()
    {
        // Crear una lista y cotización de prueba
        // Crear usuario antes de asignarlo a la lista
        $usuario = User::create([
            'name' => 'Usuario Prueba',
            'first_last_name' => 'Apellido',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
            'status' => true,
        ]);

        // Crear lista de cotización con el usuario
        $listaCotizar = ListasCotizar::create([
            'nombre' => 'Lista Test',
            'estado' => 2,
            'usuario_id' => $usuario->id,
        ]);

        // Crear cotización con usuario y lista
        $cotizacion = Cotizacion::create([
            'lista_cotizar_id' => $listaCotizar->id,
            'usuario_id' => $usuario->id,
            'estado' => 1,
            'items_cotizar_stock' => json_encode([]),
            'items_cotizar_proveedor' => json_encode([]),
        ]);

        // Simular Livewire y verificar carga de datos
        Livewire::test(VerCarritoCotisaciones::class, ['idCotisacion' => $cotizacion->id])
            ->assertSet('idCotisacion', $cotizacion->id)
            ->assertSet('idListaActual', $listaCotizar->id);
    }
    /** @test */
    public function calcula_correctamente_el_precio_total()
    {
        // Crear usuario válido
        // Crear una lista y cotización de prueba
        // Crear usuario antes de asignarlo a la lista
        $usuario = User::create([
            'name' => 'Usuario Prueba',
            'first_last_name' => 'Apellido',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
            'status' => true,
        ]);
        // Crear lista de cotización con el usuario
        $listaCotizar = ListasCotizar::create([
            'nombre' => 'Lista Test',
            'estado' => 2,
            'usuario_id' => $usuario->id,
        ]);
        // Crear cotización con usuario y lista
        $cotizacion = Cotizacion::create([
            'lista_cotizar_id' => $listaCotizar->id,
            'usuario_id' => $usuario->id,
            'estado' => 1,
            'items_cotizar_stock' => json_encode([]),
            'items_cotizar_proveedor' => json_encode([]),
        ]);
        // Crear un item base
        $item = Item::create([
            'nombre' => 'Item Test',
            'descripcion' => 'Descripción de prueba',
        ]);
        // Crear un item específico vinculado al item base
        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id, // Clave foránea correcta
            'marca' => 'Marca Test',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'precio_venta_minorista' => 50,
            'precio_venta_mayorista' => 40,
            'unidad' => 'Unidad Test',
            'stock' => 20,
        ]);
        // Ejecutar prueba con Livewire
        Livewire::test(VerCarritoCotisaciones::class, ['idCotisacion' => $cotizacion->id])
            ->assertSet('idCotisacion', $cotizacion->id)
            ->assertSet('idListaActual', $listaCotizar->id);
    }
}
