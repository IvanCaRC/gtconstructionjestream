<?php

namespace Tests\Feature;

use App\Livewire\Familia\FamiliaComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Familia;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class FamiliaComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renderizaElComponenete()
    {
        Livewire::test(FamiliaComponent::class)
            ->assertStatus(200);
    }

    /** @test */
    public function reseteaLaPaginaCuandoLlamaAlMetodoSearch()
    {
        // Crear una instancia de Familia en la base de datos
        $familia = Familia::create([
            'nombre' => 'Familia de prueba',
            'descripcion' => 'Descripción de prueba',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->set('searchTerm', 'Familia de prueba') // Establecer el término de búsqueda
            ->call('search') // Llamar al método search del componente
            ->assertSet('searchTerm', 'Familia de prueba'); // Verificar que el término de búsqueda está establecido correctamente
    }

    /** @test */
    public function terminoDeBusquedaVacio()
    {
        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->set('searchTerm', '') // Establecer el término de búsqueda vacío
            ->call('search') // Llamar al método search
            ->assertSet('searchTerm', ''); // Verificar que el término de búsqueda está vacío
    }
    /** @test */
    public function terminoDeBusquedaLargo()
    {
        $longSearchTerm = str_repeat('a', 255); // Crear un término de búsqueda muy largo

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->set('searchTerm', $longSearchTerm) // Establecer el término de búsqueda muy largo
            ->call('search') // Llamar al método search
            ->assertSet('searchTerm', $longSearchTerm); // Verificar que el término de búsqueda está establecido correctamente
    }
    /** @test */
    public function terminoDeBusquedaNulo()
    {
        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->set('searchTerm', null) // Establecer el término de búsqueda nulo
            ->call('search') // Llamar al método search
            ->assertSet('searchTerm', null); // Verificar que el término de búsqueda es nulo
    }



    

    /** @test */
    public function llamaRutaDeVistaEspecifica()
    {
        $familia = Familia::create([
            'nombre' => 'Familia a ver',
            'descripcion' => 'Descripción a ver',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        Livewire::test(FamiliaComponent::class)
            ->call('viewFamilia', $familia->id)
            ->assertRedirect(route('compras.familias.viewFamiliaEspecifica', ['idfamilia' => $familia->id]));
    }

    /** @test */
    public function llamaRutaDeVistaEspecificaConIdNoExistente()
    {
        $nonExistentFamilyId = 999;

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('viewFamilia', $nonExistentFamilyId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }
    /** @test */
    public function llamaRutaDeVistaEspecificaConIdNullo()
    {
        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('viewFamilia', null)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }
    /** @test */
    public function llamaRutaDeVistaEspecificaConIdInvalido()
    {
        $invalidFamilyId = 'invalid-id';

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('viewFamilia', $invalidFamilyId)
            ->assertStatus(404); // Verificar que devuelve un código de estado 404
    }


    /** @test */
    public function seObtienenSubfamiliasActivas()
    {
        $familia = Familia::create([
            'nombre' => 'Familia principal',
            'descripcion' => 'Descripción principal',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        $subfamilia = Familia::create([
            'nombre' => 'Subfamilia activa',
            'descripcion' => 'Descripción de subfamilia activa',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familia->id,
        ]);

        Livewire::test(FamiliaComponent::class)
            ->call('obtenerSubfamiliasActivas', $familia->id)
            ->assertSee($subfamilia->nombre);
    }

    /** @test */
    public function seObtienenSubfamiliasActivasConIdNoExistente()
    {
        $nonExistentFamilyId = 999;

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('obtenerSubfamiliasActivas', $nonExistentFamilyId)
            ->assertSet('subfamilias', []); // Verificar que no hay subfamilias
    }

    /** @test */
    public function seObtienenSubfamiliasActivasConIdNulo()
    {
        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('obtenerSubfamiliasActivas', null)
            ->assertSet('subfamilias', []); // Verificar que no hay subfamilias
    }

    /** @test */
    public function seObtienenSubfamiliasActivasConIdInavalido()
    {
        $invalidFamilyId = 'invalid-id';

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('obtenerSubfamiliasActivas', $invalidFamilyId)
            ->assertSet('subfamilias', []); // Verificar que no hay subfamilias
    }

    /** @test */
    public function obtenerFalsoSiNoEstaAsignado()
    {
        // Crear la familia principal
        $familia = Familia::create([
            'nombre' => 'Familia sin asignación',
            'descripcion' => 'Descripción sin asignación',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        // Crear subfamilia
        $subfamilia = Familia::create([
            'nombre' => 'Subfamilia sin asignación',
            'descripcion' => 'Descripción de subfamilia sin asignación',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familia->id,
        ]);

        // Verificar que la función retorna falso
        $component = new FamiliaComponent();
        $result = $component->verificarAsignacion($familia->id);
        $this->assertFalse($result);
    }

    /** @test */
    public function verificarAsignacion($familiaId)
    {
        // Verificar si la familia está asignada en 'proveedor_has_familia' o 'item_especifico_has_familia'
        $familia = Familia::find($familiaId);

        // Verificar en la tabla proveedor_has_familia
        $proveedorAsignado = DB::table('proveedor_has_familia')
            ->where('familia_id', $familiaId)
            ->exists();

        // Verificar en la tabla item_especifico_has_familia
        $itemAsignado = DB::table('item_especifico_has_familia')
            ->where('familia_id', $familiaId)
            ->exists();

        // Verificar si alguna de las subfamilias está asignada
        $subfamiliasAsignadas = $familia->subfamilias->contains(function ($subfamilia) {
            return DB::table('proveedor_has_familia')->where('familia_id', $subfamilia->id)->exists() ||
                DB::table('item_especifico_has_familia')->where('familia_id', $subfamilia->id)->exists();
        });

        // Si la familia o alguna subfamilia está asignada, retornar verdadero
        return $proveedorAsignado || $itemAsignado || $subfamiliasAsignadas;
    }

    /** @test */
    public function verificarAsignacionTrue()
    {
        // Crear familia principal
        $familia = Familia::create([
            'nombre' => 'Familia con asignaciones',
            'descripcion' => 'Descripción con asignaciones',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        // Crear subfamilia
        $subfamilia = Familia::create([
            'nombre' => 'Subfamilia con asignaciones',
            'descripcion' => 'Descripción subfamilia con asignaciones',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familia->id,
        ]);

        // Crear proveedor y asignarlo a la familia
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor de prueba',
            'descripcion' => 'Descripción del proveedor',
            'correo' => 'proveedor@ejemplo.com',
            'rfc' => 'PRV123456789',
            'estado' => true,
            'estado_eliminacion' => false,
        ]);

        DB::table('proveedor_has_familia')->insert([
            'proveedor_id' => $proveedor->id,
            'familia_id' => $familia->id,
        ]);

        // Crear item específico y asignarlo a la subfamilia
        $item = Item::create([
            'nombre' => 'Item de prueba',
            'descripcion' => 'Descripción del item',
        ]);

        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,
            'marca' => 'Marca de prueba',
            'cantidad_piezas_mayoreo' => 100,
            'cantidad_piezas_minorista' => 10,
            'estado' => true,
            'estado_eliminacion' => false,
        ]);

        DB::table('item_especifico_has_familia')->insert([
            'item_especifico_id' => $itemEspecifico->id,
            'familia_id' => $subfamilia->id,
        ]);

        // Verificar que la función retorna verdadero
        $component = new FamiliaComponent();
        $result = $component->verificarAsignacion($familia->id);
        $this->assertTrue($result);
    }


    /** @test */
    public function verificarAsignacionFalse()
    {
        // Crear familia principal
        $familia = Familia::create([
            'nombre' => 'Familia sin asignaciones',
            'descripcion' => 'Descripción sin asignaciones',
            'nivel' => 1,
            'estadoEliminacion' => false,
            'id_familia_padre' => null,
        ]);

        // Crear subfamilia
        $subfamilia = Familia::create([
            'nombre' => 'Subfamilia sin asignaciones',
            'descripcion' => 'Descripción subfamilia sin asignaciones',
            'nivel' => 2,
            'estadoEliminacion' => false,
            'id_familia_padre' => $familia->id,
        ]);

        // Probar el componente Livewire
        Livewire::test(FamiliaComponent::class)
            ->call('verificarAsignacion', $familia->id)
            ->assertSet('proveedorAsignado', false)
            ->assertSet('itemAsignado', false)
            ->assertSet('subfamiliasAsignadas', false);

        // Verificar que la función retorna falso
        $component = new FamiliaComponent();
        $result = $component->verificarAsignacion($familia->id);
        $this->assertFalse($result);
    }
}
