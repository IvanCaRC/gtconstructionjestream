<?php

namespace App\Livewire\Familia;

use App\Models\Familia;
use Livewire\Component;

class FamiliaVistaEspecifica extends Component
{
    public $familia;
    public $nivel;
    public $familiaPadre = null;

    public function render()
    {
        if ($this->familia->estadoEliminacion) {
            abort(404); // Lanza un error 404 si la familia está eliminada
        }

        $query = Familia::where('id_familia_padre', $this->familia->id) ->where('estadoEliminacion', 0);

        $subfamilias = $query->paginate(10);

        return view('livewire.familia.familia-vista-especifica', [
            'familia' => $this->familia,
            'familiaPadre' => $this->familiaPadre,
            'subfamilias' => $subfamilias,
            'nivel' => $this->nivel
        ]);
    }

    


    public function mount($idfamilia)
    {
        $this->familia = Familia::findOrFail($idfamilia);
        $this->buscarFamiliaPadreDirecta($idfamilia);
        $this->nivel = $this->familia->nivel;
    }

    public function buscarFamiliaPadreDirecta($idfamilia)
    {
        // Buscar la familia con el ID especificado
        $familia = Familia::find($idfamilia);

        // Verificar si la familia existe
        if ($familia) {
            // Buscar la familia padre directa utilizando el campo 'id_familia_padre'
            $familiaPadre = Familia::find($familia->id_familia_padre);

            // Retornar la familia padre directa si existe
            if ($familiaPadre) {
                $this->familiaPadre = $familiaPadre;
            } else {
                $this->familiaPadre = null;
            }
        }
    }
}


