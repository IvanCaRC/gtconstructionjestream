<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use App\Livewire\ShowUsers;
use App\Livewire\CreateUser;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_admin_can_access_the_view()
    {
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        // Asumiendo que tienes un ID de usuario para pasar
        $userId = $adminUser->id; // O algún ID de usuario válido en tu base de datos

        $this->actingAs($adminUser)
            ->get(route('admin.usersView', ['iduser' => $userId]))
            ->assertStatus(200)
            ->assertViewIs('admin.userView');
    }

    /** @test */
    public function admin_can_create_a_user()
    {
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);
        // Crear un rol para asignar al nuevo usuario
        $newUserRole = Role::create(['name' => 'UserRole']);

        $this->actingAs($adminUser);
        Livewire::test(CreateUser::class)
            ->set('name', 'Test User')
            ->set('first_last_name', 'LastName1')
            ->set('second_last_name', 'LastName2')
            ->set('email', 'testuser@example.com')
            ->set('number', '1234567890')
            ->set('status', true)
            ->set('password', 'password')
            ->set('selectedRoles', ['UserRole']) // Asigna el rol creado
            ->call('save')
            ->assertHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /** @test */
    public function can_search_user_by_name()
    {
        $user = User::factory()->create(['name' => 'John']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', 'John')
            ->assertSee('John');
    }

    /** @test */
    public function can_search_user_by_first_last_name()
    {
        $user = User::factory()->create(['first_last_name' => 'Doe']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', 'Doe')
            ->assertSee('Doe');
    }

    /** @test */
    public function can_search_user_by_second_last_name()
    {
        $user = User::factory()->create(['second_last_name' => 'Smith']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', 'Smith')
            ->assertSee('Smith');
    }

    /** @test */
    public function can_search_user_by_full_name_concatenation()
    {
        $user = User::factory()->create([
            'name' => 'John',
            'first_last_name' => 'Doe',
            'second_last_name' => 'Smith'
        ]);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', 'Doe Smith John')
            ->assertSee('John')
            ->assertSee('Doe')
            ->assertSee('Smith');
    }

    /** @test */
    public function can_search_user_by_email()
    {
        $user = User::factory()->create(['email' => 'john@example.com']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', 'john@example.com')
            ->assertSee('john@example.com');
    }

    /** @test */
    public function can_search_user_by_number()
    {
        $user = User::factory()->create(['number' => '1234567890']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', '1234567890')
            ->assertSee('1234567890');
    }

    /** @test */
    public function shows_no_results_when_no_user_found()
    {
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        Livewire::test(ShowUsers::class)
            ->set('searchTerm', 'NonExistingUser')
            ->assertDontSee('John') // Asegura que no aparece un nombre que sí debería encontrar
            ->assertSee('No hay resultados');
    }



    /** @test */
    public function can_filter_active_users()
    {
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        $activeUser = User::factory()->create(['status' => true]);
        $inactiveUser = User::factory()->create(['status' => false]);

        Livewire::test(ShowUsers::class)
            ->set('statusFiltroDeBusqueda', true)
            ->call('filter')
            ->assertSee($activeUser->name)
            ->assertDontSee($inactiveUser->name);
    }

    /** @test */
    public function can_filter_inactive_users()
    {
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        $activeUser = User::factory()->create(['status' => true]);
        $inactiveUser = User::factory()->create(['status' => false]);

        Livewire::test(ShowUsers::class)
            ->set('statusFiltroDeBusqueda', false)
            ->call('filter')
            ->assertSee($inactiveUser->name)
            ->assertDontSee($activeUser->name);
    }



    /** @test */
    public function shows_no_results_when_no_active_users_found()
    {
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);

        // Crear solo usuarios inactivos
        User::factory()->create(['status' => false]);

        $component = Livewire::test(ShowUsers::class)
            ->set('statusFiltroDeBusqueda', true)
            ->call('filter');

        // Dump the rendered HTML to inspect it

        $component->assertSee('No hay resultados'); // Ajusta este mensaje según tu vista
    }
}
