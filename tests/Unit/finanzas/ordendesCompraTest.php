<?php

namespace Tests\Unit\finanzas;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Cotisaciones\OrdeneCompra\VistaEspecificaDeOrden;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\ListasCotizar;
use App\Models\User;
use App\Models\ordenCompra;
use App\Models\Proyecto;

class ordendesCompraTest extends TestCase
{
    use RefreshDatabase;

    /** @test */



    /** @test */
    public function inicializa_correctamente_el_componente()
    {
        // Crear usuario válido
        $usuario = User::create([
            'name' => 'Usuario Prueba',
            'first_last_name' => 'Apellido',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
            'status' => true,
        ]);
        // Crear cliente válido
        $cliente = Cliente::create([
            'nombre' => 'Cliente Test',
            'telefono' => '555-1234',
            'fecha' => now(),
            'user_id' => $usuario->id,
        ]);
        // Crear proyecto con el cliente válido
        $proyecto = Proyecto::create([
            'nombre' => 'Proyecto Test',
            'cliente_id' => $cliente->id,
            'proceso' => 0,
            'listas' => 0,
            'cotisaciones' => 0,
            'ordenes' => 0,
            'tipo' => 1,
            'estado' => 1,
            'fecha' => now(),
        ]);
        // Crear lista de cotización con el usuario y el proyecto
        $listaCotizar = ListasCotizar::create([
            'nombre' => 'Lista Test',
            'estado' => 2,
            'usuario_id' => $usuario->id,
            'proyecto_id' => $proyecto->id,
        ]);
        // Crear cotización vinculada a la lista y al usuario
        $cotizacion = Cotizacion::create([
            'lista_cotizar_id' => $listaCotizar->id,
            'usuario_id' => $usuario->id,
            'estado' => 1,
            'items_cotizar_stock' => json_encode([]),
            'items_cotizar_proveedor' => json_encode([]),
        ]);
        // Simular Livewire y verificar carga de datos
        Livewire::test(VistaEspecificaDeOrden::class, ['idCotisaciones' => null])
        ->assertDontSee('cotisacion.id');
    }
}
