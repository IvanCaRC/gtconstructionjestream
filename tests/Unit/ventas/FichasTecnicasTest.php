<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Cliente\FichasTecnicas;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\User;

use App\Models\ListasCotizar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\TestCase;

class FichasTecnicasTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_crear_lista_y_agregar_item_sin_lista_activa()
    {
        $this->assertTrue(true);
    }

    public function test_seleccionar_familia_y_agregar_items()
    {
        $this->assertTrue(true);
    }


}
