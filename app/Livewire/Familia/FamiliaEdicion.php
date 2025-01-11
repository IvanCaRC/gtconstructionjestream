<?php

namespace App\Livewire\Familia;

use App\Models\Familia;
use Livewire\Component;

class FamiliaEdicion extends Component
{
    public $familia;
    public $familiaEdit = [
        'id' => '',
        'id_familia_padre' => '',
        'nombre' => '',
        'descripcion' => '',
        'nivel' => '',
    ];
    public $niveles = [];
    public $seleccionadas = [];
    public $familiasPadre = [];
    public $familiaActual;

    public function render()
    {
        return view('livewire.familia.familia-edicion', [
            'familiasPadre' => $this->familiasPadre,
            'familiaActual' => $this->familiaActual

        ]);
    }


    public function mount($idfamilia)
    {
        $this->familia = Familia::findOrFail($idfamilia);
        $this->familiaActual = Familia::findOrFail($idfamilia);

        $this->familiaEdit['id'] = $this->familia->id;
        $this->familiaEdit['id_familia_padre'] = $this->familia->id_familia_padre;
        $this->familiaEdit['nombre'] = $this->familia->nombre;
        $this->familiaEdit['descripcion'] = $this->familia->descripcion;
        $this->familiaEdit['nivel'] = $this->familia->nivel;

        // Construir jerarquía de familias padre
        $this->crearArregloDeFamiliasPadre($this->familia);

        if (!isset($this->niveles[1])) {
            $this->niveles[1] = Familia::whereNull('id_familia_padre')
                ->where('estadoEliminacion', 0)
                ->get();
        }

        // Inicializar niveles y seleccionadas según familiasPadre
        $this->niveles = [];
        $this->seleccionadas = [];
        $nivel = 1;

        foreach ($this->familiasPadre as $familiaPadre) {
            if ($familiaPadre) {
                $this->niveles[$nivel] = Familia::where('id_familia_padre', $familiaPadre->id_familia_padre)
                    ->where('estadoEliminacion', 0)
                    ->get();
                $this->seleccionadas[$nivel] = $familiaPadre->id;
                $nivel++;
            }
        }
        
        // Cargar el nivel inicial si no hay padres
        
    }

    private function crearArregloDeFamiliasPadre($familiaActual)
    {
        $this->familiasPadre = [];
        $contador = 0;

        $this->familiasPadre[$contador] = $familiaActual;
        $contador++;
        while ($familiaActual) {
            $this->familiasPadre[$contador] = $familiaActual->padre;
            $contador++;
            $familiaActual = $familiaActual->padre; // Asegúrate de tener la relación padre definida en el modelo
        }
        

        $this->familiasPadre = array_reverse($this->familiasPadre, true);
    }



    public function calcularSubfamilias($idFamiliaSeleccionada, $nivel)
    {
        $resultado = Familia::calcularSubfamilias($idFamiliaSeleccionada, $nivel, $this->niveles, $this->seleccionadas);

        $this->niveles = $resultado['niveles'];
        $this->seleccionadas = $resultado['seleccionadas'];
    }
}
