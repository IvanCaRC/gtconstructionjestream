<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\Proveedor;
use App\Models\ItemEspecificoHasFamilia;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class EditItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function se_renderiza_el_componente_edit_item()
    {
        // Crear instancias de Item e ItemEspecifico
        $item = Item::create([
            'nombre' => 'Nombre de prueba',
            'descripcion' => 'Descripción de prueba'
        ]);
        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,
            'image' => 'image1.jpg,image2.jpg',
            'marca' => 'Marca de prueba',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'porcentaje_venta_minorista' => 10.00,
            'porcentaje_venta_mayorista' => 20.00,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 200.00,
            'unidad' => 'unidad',
            'moc' => 'MOC de prueba',
            'stock' => 50,
            'especificaciones' => json_encode([['enunciado' => 'Especificación 1', 'concepto' => 'Concepto 1']]),
            'ficha_tecnica_pdf' => 'ruta_a_ficha_tecnica.pdf',
            'estado' => true,
            'estado_eliminacion' => false
        ]);

        // Montar el componente y pasar el ID del itemEspecifico
        Livewire::test('item.edit-item', ['idItem' => $itemEspecifico->id])
            ->assertSee($item->nombre)
            ->assertSet('itemEdit.nombre', $item->nombre)
            ->assertSet('itemEspecificoEdit.id', $itemEspecifico->id);
    }

    /** @test */
    public function guarda_el_item_y_itemEspecifico()
    {
        // Crear instancias de Item e ItemEspecifico
        $item = Item::create([
            'nombre' => 'Nombre de prueba',
            'descripcion' => 'Descripción de prueba'
        ]);
        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,
            'image' => 'image1.jpg,image2.jpg',
            'marca' => 'Marca de prueba',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'porcentaje_venta_minorista' => 10.00,
            'porcentaje_venta_mayorista' => 20.00,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 200.00,
            'unidad' => 'unidad',
            'moc' => 'MOC de prueba',
            'stock' => 50,
            'especificaciones' => json_encode([['enunciado' => 'Especificación 1', 'concepto' => 'Concepto 1']]),
            'ficha_tecnica_pdf' => 'ruta_a_ficha_tecnica.pdf',
            'estado' => true,
            'estado_eliminacion' => false
        ]);

        // Subir archivo ficticio para ficha técnica
        $file = UploadedFile::fake()->create('ficha_tecnica.pdf', 100);

        // Montar el componente y pasar los datos necesarios
        Livewire::test('item.edit-item', ['idItem' => $itemEspecifico->id])
            ->set('itemEdit.nombre', 'Nombre Editado')
            ->set('itemEspecificoEdit.descripcion', 'Descripción Editada')
            ->set('ficha_Tecnica_pdf', $file)
            ->call('save')
            ->assertHasNoErrors();

        // Verificar que los datos han sido actualizados en la base de datos
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'nombre' => 'Nombre Editado',
            'descripcion' => 'Descripción Editada',
        ]);

    }

    
    /** @test */
    public function elimina_una_imagen_del_componente()
    {
        // Crear instancias de Item e ItemEspecifico con imágenes
        $item = Item::create([
            'nombre' => 'Nombre de prueba',
            'descripcion' => 'Descripción de prueba'
        ]);
        $itemEspecifico = ItemEspecifico::create([
            'item_id' => $item->id,
            'image' => 'image1.jpg,image2.jpg',
            'marca' => 'Marca de prueba',
            'cantidad_piezas_mayoreo' => 10,
            'cantidad_piezas_minorista' => 5,
            'porcentaje_venta_minorista' => 10.00,
            'porcentaje_venta_mayorista' => 20.00,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 200.00,
            'unidad' => 'unidad',
            'moc' => 'MOC de prueba',
            'stock' => 50,
            'especificaciones' => json_encode([['enunciado' => 'Especificación 1', 'concepto' => 'Concepto 1']]),
            'ficha_tecnica_pdf' => 'ruta_a_ficha_tecnica.pdf',
            'estado' => true,
            'estado_eliminacion' => false
        ]);

        // Montar el componente y eliminar una imagen
        Livewire::test('item.edit-item', ['idItem' => $itemEspecifico->id])
            ->call('eliminarImagenActual', 0)
            ->assertSet('imagenesCargadas', ['image2.jpg']);

        // Verificar que la imagen se ha eliminado correctamente
    }

    /** @test */
/** @test */
public function no_se_guarda_el_item_sin_nombre_en_edicion()
{
    // Crear instancias de Item e ItemEspecifico con todos los datos
    $item = Item::create([
        'nombre' => 'Nombre de prueba',
        'descripcion' => 'Descripción de prueba'
    ]);
    $itemEspecifico = ItemEspecifico::create([
        'item_id' => $item->id,
        'image' => 'image1.jpg,image2.jpg',
        'marca' => 'Marca de prueba',
        'cantidad_piezas_mayoreo' => 10,
        'cantidad_piezas_minorista' => 5,
        'porcentaje_venta_minorista' => 10.00,
        'porcentaje_venta_mayorista' => 20.00,
        'precio_venta_minorista' => 100.00,
        'precio_venta_mayorista' => 200.00,
        'unidad' => 'unidad',
        'moc' => 'MOC de prueba',
        'stock' => 50,
        'especificaciones' => json_encode([['enunciado' => 'Especificación 1', 'concepto' => 'Concepto 1']]),
        'ficha_tecnica_pdf' => 'ruta_a_ficha_tecnica.pdf',
        'estado' => true,
        'estado_eliminacion' => false
    ]);

    // Montar el componente y quitar el nombre en la edición
    Livewire::test('item.edit-item', ['idItem' => $itemEspecifico->id])
        ->set('itemEdit.nombre', '')
        ->call('save')
        ->assertHasErrors(['itemEdit.nombre' => 'required']);
}

/** @test */
public function no_se_guarda_el_item_sin_descripcion_en_edicion()
{
    // Crear instancias de Item e ItemEspecifico con todos los datos
    $item = Item::create([
        'nombre' => 'Nombre de prueba',
        'descripcion' => 'Descripción de prueba'
    ]);
    $itemEspecifico = ItemEspecifico::create([
        'item_id' => $item->id,
        'image' => 'image1.jpg,image2.jpg',
        'marca' => 'Marca de prueba',
        'cantidad_piezas_mayoreo' => 10,
        'cantidad_piezas_minorista' => 5,
        'porcentaje_venta_minorista' => 10.00,
        'porcentaje_venta_mayorista' => 20.00,
        'precio_venta_minorista' => 100.00,
        'precio_venta_mayorista' => 200.00,
        'unidad' => 'unidad',
        'moc' => 'MOC de prueba',
        'stock' => 50,
        'especificaciones' => json_encode([['enunciado' => 'Especificación 1', 'concepto' => 'Concepto 1']]),
        'ficha_tecnica_pdf' => 'ruta_a_ficha_tecnica.pdf',
        'estado' => true,
        'estado_eliminacion' => false
    ]);

    // Montar el componente y quitar la descripción en la edición
    Livewire::test('item.edit-item', ['idItem' => $itemEspecifico->id])
        ->set('itemEspecificoEdit.descripcion', '')
        ->call('save')
        ->assertHasErrors(['itemEspecificoEdit.descripcion' => 'required']);
}
}
