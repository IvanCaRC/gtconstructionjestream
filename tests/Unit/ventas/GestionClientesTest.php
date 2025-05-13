<?php

namespace Tests\Unit\Livewire\Cliente;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Livewire\Cliente\GestionClientes;

class GestionClientesTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithRole(string $roleName, string $email = null): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
    
        $user = User::create([
            'name' => 'Test',
            'first_last_name' => 'User',
            'email' => $email ?? fake()->unique()->safeEmail(), // genera un email único si no se proporciona
            'password' => Hash::make('password'),
            'status' => 1,
        ]);
    
        $user->assignRole($role);
    
        return $user;
    }
    

    /** @test */
    public function admin_can_see_all_clientes()
    {
        $admin = $this->createUserWithRole('Administrador');

        Cliente::create([
            'nombre' => 'Cliente A',
            'correo' => 'a@example.com',
            'rfc' => 'AAA000111AA0',
            'telefono' => '1234567890',
            'fecha' => now()->toDateString(),
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(GestionClientes::class)
            ->assertSee('Cliente A');
    }

    /** @test */
    public function regular_user_only_sees_own_clientes()
    {
        $user = $this->createUserWithRole('Vendedor');
        $otherUser = $this->createUserWithRole('Vendedor');

        Cliente::create([
            'nombre' => 'Cliente Propio',
            'correo' => 'propio@example.com',
            'rfc' => 'BBB000222BB1',
            'telefono' => '1234567890',
            'fecha' => now()->toDateString(),
            'user_id' => $user->id,
        ]);

        Cliente::create([
            'nombre' => 'Cliente Ajeno',
            'correo' => 'ajeno@example.com',
            'rfc' => 'CCC000333CC2',
            'telefono' => '1234567890',
            'fecha' => now()->toDateString(),
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user);

        Livewire::test(GestionClientes::class)
            ->assertSee('Cliente Propio')
            ->assertDontSee('Cliente Ajeno');
    }

    /** @test */
    public function search_filters_results()
    {
        $admin = $this->createUserWithRole('Administrador');

        Cliente::create([
            'nombre' => 'Cliente Uno',
            'correo' => 'uno@example.com',
            'rfc' => 'UNO000111UU1',
            'telefono' => '1234567890',
            'fecha' => now()->toDateString(),
            'user_id' => $admin->id,
        ]);

        Cliente::create([
            'nombre' => 'Cliente Dos',
            'correo' => 'dos@example.com',
            'rfc' => 'DOS000222DD2',
            'telefono' => '1234567890',
            'fecha' => now()->toDateString(),
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin);

        Livewire::test(GestionClientes::class)
            ->set('searchTerm', 'Uno')
            ->assertSee('Cliente Uno')
            ->assertDontSee('Cliente Dos');
    }

    /** @test */
    public function test_status_filter_works_correctly()
    {
        // Crear usuario con rol
        $admin = $this->createUserWithRole('Administrador', 'admin2@example.com');
    
        // Crear clientes asociados
        Cliente::create([
            'nombre' => 'Activo',
            'correo' => 'activo@example.com',
            'rfc' => 'ACT000111AA1',
            'telefono' => '555-1234',
            'fecha' => now(),
            'proyectos' => 2,
            'proyectos_activos' => 2, // Activo
            'user_id' => $admin->id,
        ]);
    
        Cliente::create([
            'nombre' => 'Inactivo',
            'correo' => 'inactivo@example.com',
            'rfc' => 'INA000222II2',
            'telefono' => '555-5678',
            'fecha' => now(),
            'proyectos' => 1,
            'proyectos_activos' => 0, // Inactivo
            'user_id' => $admin->id,
        ]);
    
        // Actuar como admin
        $this->actingAs($admin);
    
        Livewire::test(GestionClientes::class)
            ->set('statusFiltroDeBusqueda', 1) // Solo activos
            ->call('filter') // Requiere para resetear paginación
            ->assertSee('Activo')
            ->assertDontSee('Inactivo');
    }
    
}
