<?php

namespace App\Livewire\Cotisaciones\OrdeneCompra;

use App\Http\Controllers\Cotisaciones;
use App\Models\Cotizacion;
use App\Models\ordenCompra;
use Livewire\Component;

class VistaEspecificaDeOrden extends Component
{

    public $cotisacion;
    public $searchTerm = '';
    public $statusFiltro = 0; // Filtro de estado

    public function mount($idCotisaciones)
    {
        $this->cotisacion = Cotizacion::findOrFail($idCotisaciones);
    }
    public function render()
    {

        $listas = ordenCompra::where('id_cotizacion', $this->cotisacion->id)
        ->when($this->searchTerm, function ($query) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('estado', 'like', '%' . $this->searchTerm . '%');
            });
        })
        ->when($this->statusFiltro != 0, function ($query) {
            $query->where('estado', $this->statusFiltro);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Usar paginate() con el trait WithPagination

        return view('livewire.cotisaciones.ordene-compra.vista-especifica-de-orden',['listas' => $listas]);
    }



}
