<?php

namespace Tests\Feature;

use App\Models\User;
use Livewire\Livewire;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Livewire\ShowUsers as LivewireShowUsers;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }


    public function test_users_can_authenticate_using_the_login_screen(): void {
        // Crear un nuevo usuario utilizando una fábrica de usuarios y estableciendo los datos adicionales
        $user = User::factory()->create([
            'status' => true,
            'estadoEliminacion' => false,
        ]);
        
        // Enviar una solicitud POST a la ruta de inicio de sesión
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    
        // Asegurarse de que el usuario está autenticado después del intento de inicio de sesión
        $this->assertAuthenticated();
        
        // Verificar que se redirige correctamente al usuario
        $response->assertRedirect(); // Ajustar la ruta aquí
    }
    
    // public function test_user_is_not_authenticated_when_status_is_false(): void {
    //     // Crear un nuevo usuario con status = false
    //     $user = User::factory()->create([
    //         'status' => false,
    //         'estadoEliminacion' => false,
    //     ]);
        
    //     // Enviar una solicitud POST a la ruta de inicio de sesión
    //     $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);
        
    //     // Asegurarse de que el usuario no está autenticado
    //     $this->assertGuest();
    // }

    
    // public function test_user_is_not_authenticated_when_estadoEliminacion_is_true(): void {
    //     // Crear un nuevo usuario con estadoEliminacion = true
    //     $user = User::factory()->create([
    //         'status' => true,
    //         'estadoEliminacion' => true,
    //     ]);
        
    //     // Enviar una solicitud POST a la ruta de inicio de sesión
    //     $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);
        
    //     // Asegurarse de que el usuario no está autenticado
    //     $this->assertGuest();
    // }
    

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_invalid_email(): void {
        // Crear un nuevo usuario utilizando una fábrica de usuarios
        $user = User::factory()->create();
        
        // Enviar una solicitud POST a la ruta de inicio de sesión con un correo inválido
        $this->post('/login', [
            'email' => 'invalid_email@example.com',
            'password' => 'correct-password',  // Asumiendo que la contraseña es válida
        ]);
        
        // Asegurarse de que el usuario no está autenticado después del intento de inicio de sesión con correo inválido
        $this->assertGuest();
    }
    

    public function test_users_can_not_authenticate_with_null_data(): void
    {
        // Crear un nuevo usuario utilizando una fábrica de usuarios
        $user = User::factory()->create();

        // Enviar una solicitud POST a la ruta de inicio de sesión con datos nulos
        $response = $this->post('/login', [
            'email' => null,
            'password' => null,
        ]);

        // Asegurarse de que el usuario no está autenticado después del intento de inicio de sesión con datos nulos
        $this->assertGuest();

        // Verificar que se muestran los errores de validación adecuados
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_estadoEliminacion_is_set_to_true(): void
    {
        // Crear un nuevo usuario con estadoEliminacion inicial en false
        $user = User::factory()->create([
            'status' => true,
            'estadoEliminacion' => false,
        ]);
        $this->actingAs($user);
        Livewire::test(LivewireShowUsers::class)
            ->call('eliminar', $user->id);
        $user->refresh();
        $this->assertEquals(1, $user->estadoEliminacion);
    }

    public function test_user_estadoEliminacion_already_true(): void
    {
        $user = User::factory()->create(['status' => true, 'estadoEliminacion' => true,]);
        $this->actingAs($user);
        Livewire::test(LivewireShowUsers::class)->call('eliminar', $user->id);
        $user->refresh();
        $this->assertEquals(1, $user->estadoEliminacion);
    }
}
