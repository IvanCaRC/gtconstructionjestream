<?php

namespace Tests\Feature;

use App\Livewire\Item\CreateItem;
use App\Models\Familia;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;
use App\Models\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function el_componente_se_renderiza_correctamente()
    {
        Livewire::test(CreateItem::class)
            ->assertStatus(200);
    }


    /** @test */
    public function puede_crear_un_item_con_familias_y_proveedores()
    {
        Storage::fake('public');
        $familia = Familia::create(['nombre' => 'Familia Test', 'estadoEliminacion' => 0]);
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'correo' => 'test@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1,
        ]);
        $fichaTecnica = UploadedFile::fake()->create('ficha_tecnica.pdf', 100);
        $imagen = UploadedFile::fake()->image('item.jpg');
        Livewire::test(CreateItem::class)
            ->set('nombre', 'Item Test')
            ->set('descripcion', 'Descripción del item de prueba')
            ->set('marca', 'Marca Test')
            ->set('stock', 10)
            ->set('pz_Mayoreo', 5)
            ->set('pz_Minorista', 1)
            ->set('porcentaje_venta_minorista', 10.00)
            ->set('porcentaje_venta_mayorista', 5.00)
            ->set('precio_venta_minorista', 100.00)
            ->set('precio_venta_mayorista', 90.00)
            ->set('unidad', 'pieza')
            ->set('ficha_Tecnica_pdf', $fichaTecnica)
            ->set('image', [$imagen])
            ->set('familiasSeleccionadas', [$familia])
            ->set('ProvedoresAsignados', [
                [
                    'proveedor_id' => $proveedor->id,
                    'proveedor_nombre' => $proveedor->nombre,
                    'tiempo_minimo_entrega' => 5,
                    'tiempo_maximo_entrega' => 10,
                    'precio_compra' => 80.00,
                    'unidad' => 'caja',
                    'estado' => 1,
                ]
            ])
            ->call('save')
            ->assertHasNoErrors();
        $this->assertDatabaseHas('items', [
            'nombre' => 'Item Test',
            'descripcion' => 'Descripción del item de prueba',
        ]);
        $this->assertDatabaseHas('item_especifico_has_familia', [
            'familia_id' => $familia->id,
        ]);
        $this->assertDatabaseHas('item_especifico_proveedor', [
            'proveedor_id' => $proveedor->id,
            'tiempo_min_entrega' => 5,
            'tiempo_max_entrega' => 10,
            'precio_compra' => '80.00', // Asegúrate de que el valor es una cadena
            'unidad' => 'caja',
        ]);
        Storage::disk('public')->assertExists('archivosFacturacionProveedores/' . $fichaTecnica->hashName());
        Storage::disk('public')->assertExists('imagenesItems/' . $imagen->hashName());
    }

    /** @test */
    public function no_puede_crear_un_item_sin_nombre()
    {
        Livewire::test(CreateItem::class)
            ->set('descripcion', 'Descripción del item de prueba')
            ->set('marca', 'Marca Test')
            ->set('stock', 10)
            ->set('pz_Mayoreo', 5)
            ->set('pz_Minorista', 1)
            ->set('porcentaje_venta_minorista', 10.00)
            ->set('porcentaje_venta_mayorista', 5.00)
            ->set('precio_venta_minorista', 100.00)
            ->set('precio_venta_mayorista', 90.00)
            ->set('unidad', 'pieza')
            ->call('save')
            ->assertHasErrors(['nombre' => 'required']);
    }

    /** @test */
    public function no_puede_crear_un_item_sin_marca()
    {
        Livewire::test(CreateItem::class)
            ->set('nombre', 'Item Test')
            ->set('descripcion', 'Descripción del item de prueba')
            ->set('stock', 10)
            ->set('pz_Mayoreo', 5)
            ->set('pz_Minorista', 1)
            ->set('porcentaje_venta_minorista', 10.00)
            ->set('porcentaje_venta_mayorista', 5.00)
            ->set('precio_venta_minorista', 100.00)
            ->set('precio_venta_mayorista', 90.00)
            ->set('unidad', 'pieza')
            ->call('save')
            ->assertHasErrors(['marca' => 'required']);
    }

    /** @test */
    public function puede_agregar_lineas_tecnicas()
    {
        Livewire::test(CreateItem::class)
            ->call('addLineaTecnica')
            ->assertSee('enunciado');
    }

    /** @test */
    public function puede_asignar_proveedor()
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'correo' => 'test@correo.com',
            'rfc' => 'GARC840215HDF',
            'estado_eliminacion' => 1,
        ]);

        Livewire::test(CreateItem::class)
            ->set('seleccionProvedorModal', $proveedor->id)
            ->set('seleccionProvedorModalNombre', $proveedor->nombre)
            ->set('tiempoMinEntrega', 5)
            ->set('tiempoMaxEntrega', 10)
            ->set('precioCompra', 80.00)
            ->set('unidadSeleccionada', 'caja')
            ->call('asignarProvedorArregloProvedor')
            ->assertSee($proveedor->nombre);
    }
}

