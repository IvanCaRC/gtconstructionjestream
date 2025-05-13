<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Cliente\RecepcionLlamada;

class RecepcionLlamadaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_and_remove_telefonos()
    {
        $user = User::create([
            'name' => 'Usuario de Prueba',
            'first_last_name' => 'ApellidoPaterno',
            'status' => true,
            'email' => 'usuario@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        Livewire::test(RecepcionLlamada::class)
            ->call('addTelefono')
            ->assertCount('telefonos', 2)
            ->call('removeTelefono', 0)
            ->assertCount('telefonos', 1);
    }

    /** @test */
    public function it_can_add_and_remove_bancarios()
    {
        $user = User::create([
            'name' => 'Usuario de Prueba',
            'first_last_name' => 'ApellidoPaterno', // ← Obligatorio
            'status' => true,                       // ← También obligatorio
            'email' => 'usuario@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        Livewire::test(RecepcionLlamada::class)
            ->call('addBancarios')
            ->assertCount('bancarios', 2)
            ->call('removeBancarios', 0)
            ->assertCount('bancarios', 1);
    }

    /** @test */
    public function it_validates_required_fields_on_save()
    {
        $user = User::create([
            'name' => 'Usuario de Prueba',
            'first_last_name' => 'ApellidoPaterno',
            'status' => true,
            'email' => 'usuario@example.com',
            'password' => bcrypt('password'),
        ]);


        $this->actingAs($user);

        Livewire::test(RecepcionLlamada::class)
            ->set('nombre', '')
            ->set('correo', '')
            ->set('rfc', '')
            ->call('save')
            ->assertHasErrors(['nombre', 'correo', 'rfc']);
    }

    /** @test */
    public function it_can_save_a_new_cliente()
    {
        $user = User::create([
            'name' => 'Usuario de Prueba',
            'first_last_name' => 'Pérez',
            'email' => 'usuario@example.com',
            'password' => bcrypt('secret'),
            'status' => 1, // 👈 Asegúrate de incluir este campo
        ]);



        $this->actingAs($user);

        Livewire::test(RecepcionLlamada::class)
            ->set('nombre', 'Cliente de Prueba')
            ->set('correo', 'cliente@example.com')
            ->set('rfc', 'UIRN500424NV1') // ✅ RFC genérico válido
            ->set('telefonos', [['nombre' => 'Casa', 'numero' => '1234567890']])
            ->set('bancarios', [[
                'banco' => 'Banco Prueba',
                'titular' => 'Cliente de Prueba',
                'cuenta' => '1234567890',
                'clave' => '032180000118359719' // ✅ CLABE de ejemplo (18 dígitos)
            ]])
            ->call('save')
            ->assertHasNoErrors();


        $this->assertDatabaseHas('clientes', [
            'nombre' => 'Cliente de Prueba',
            'correo' => 'cliente@example.com',
            'rfc' => 'UIRN500424NV1',
        ]);
    }
}
