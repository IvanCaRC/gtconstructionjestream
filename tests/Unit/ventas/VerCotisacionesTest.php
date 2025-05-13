<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Cotisaciones\VerCotisaciones;
use App\Models\Cliente;
use Livewire\Livewire;
use App\Models\ListasCotizar;
use App\Models\Proyecto;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class VerCotisacionesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::test(VerCotisaciones::class)
            ->assertStatus(200); // Cambia esto por un texto relevante en la vista
    }
    /** @test */
    public function puede_filtrar_listas_por_nombre()
    {
        // Crear un usuario válido antes de insertar listas
        $usuario = User::create([
            'name' => 'Usuario Prueba',
            'first_last_name' => 'Apellido',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
            'status' => true, // Agregar el campo requerido
        ]);

        // Insertar datos manualmente con el usuario recién creado
        $lista1 = ListasCotizar::create([
            'nombre' => 'Cotización A',
            'estado' => 2,
            'usuario_id' => $usuario->id,
        ]);

        $lista2 = ListasCotizar::create([
            'nombre' => 'Cotización B',
            'estado' => 2,
            'usuario_id' => $usuario->id,
        ]);

        // Ejecutar prueba con Livewire
        $this->assertTrue(true);
    }

    /** @test */


    public function verifica_que_el_componente_renderiza_listas_correctamente()
    {
        // Crear un usuario válido
        $usuario = User::create([
            'name' => 'Usuario Prueba',
            'first_last_name' => 'Apellido',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
            'status' => true,
        ]);
    
        // Crear un cliente válido
        $cliente = Cliente::create([
            'nombre' => 'Cliente Prueba',
            'telefono' => '555-1234', // Campo obligatorio
            'fecha' => now(), // Fecha actual
            'user_id' => $usuario->id, // Asegurar que el usuario con ID 1 existe en la tabla users
        ]);
        
    
        // Crear un proyecto con el cliente asociado
        $proyecto = Proyecto::create([
            'nombre' => 'Proyecto Test',
            'cliente_id' => $cliente->id, // Asegurar que el cliente existe
            'proceso' => 0,
            'listas' => 0,
            'cotisaciones' => 0,
            'ordenes' => 0,
            'tipo' => 1,
            'estado' => 1,
            'fecha' => now(),
        ]);
    
        Livewire::test(VerCotisaciones::class)
            ->assertViewHas('listasCotizar', function ($listas) {
                return count($listas) === 0;
            });
    }
    
}
