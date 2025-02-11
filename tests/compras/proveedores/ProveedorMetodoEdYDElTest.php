<?php

namespace Tests\Feature;

use App\Livewire\Familia\FamiliaComponent;
use App\Livewire\Proveedor\ProveedorComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Familia;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class ProveedorMetodoEdYDElTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function llamaRutaDeEdicion()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor a editar',
            'correo' => 'editar@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->call('editProveedor', $proveedor->id)
            ->assertRedirect(route('compras.proveedores.editProveedores', ['idproveedor' => $proveedor->id]));
    }

    /** @test */
    public function llamarRutaDeEdicionDeIdNoExistente()
    {
        $nonExistentProveedorId = 999;

        Livewire::test(ProveedorComponent::class)
            ->call('editProveedor', $nonExistentProveedorId)
            ->assertStatus(404); // Ajusta esto según la forma en que manejes errores
    }


    /** @test */
    public function llamarRutaDeEdicionDeIdNulo()
    {
        Livewire::test(ProveedorComponent::class)
            ->call('editProveedor', null)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }



    /** @test */
    public function llamarRutaDeEdicionDeIdInvalido()
    {
        $invalidProveedorId = 'invalid-id';

        Livewire::test(ProveedorComponent::class)
            ->call('editProveedor', $invalidProveedorId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }

    /** @test */
    public function llamaRutaDeEliminacion()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor a eliminar',
            'correo' => 'eliminar@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1
        ]);

        Livewire::test(ProveedorComponent::class)
            ->call('eliminar', $proveedor->id);

        $this->assertDatabaseHas('proveedores', [
            'id' => $proveedor->id,
            'estado_eliminacion' => 0,
        ]);
    }
}
