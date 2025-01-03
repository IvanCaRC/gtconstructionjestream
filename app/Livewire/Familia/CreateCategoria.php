<?php
namespace App\Livewire\Familia;

use Livewire\Component;
use App\Models\Familia;

class CreateCategoria extends Component
{
    public $nombre;
    public $descripcion;
    public $familiasFiltradas = []; // Propiedad para almacenar las familias filtradas

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        $familia = new Familia();
        $familia->nombre = $this->nombre;
        $familia->descripcion = $this->descripcion;
        $familia->nivel = 1; // Nivel inicial ya que no hay familia padre

        $familia->save();

        session()->flash('message', 'Familia creada exitosamente!');

        $this->reset(['nombre', 'descripcion']);
    }

    public function calcularSubfamilias($variableBuscada)
    {
        if ($variableBuscada == 0) {
            $this->familiasFiltradas = [];
            return;
        }

        $familiaSeleccionada = Familia::with('subfamiliasRecursivas')->find($variableBuscada);

        if ($familiaSeleccionada) {
            $this->familiasFiltradas = $this->agruparSubfamiliasPorNivel($familiaSeleccionada);
        } else {
            $this->familiasFiltradas = [];
        }
    }

    private function agruparSubfamiliasPorNivel($familia)
    {
        $agrupadas = [];
        $nivelInicial = $familia->nivel;

        $this->agregarSubfamilias($familia, $nivelInicial, $agrupadas);

        return $agrupadas;
    }

    private function agregarSubfamilias($familia, $nivelInicial, &$agrupadas)
    {
        foreach ($familia->subfamiliasRecursivas as $subfamilia) {
            $nivel = $nivelInicial + 1;
            if ($subfamilia->nivel == $nivel) {
                if (!isset($agrupadas[$nivel])) {
                    $agrupadas[$nivel] = [];
                }
                $agrupadas[$nivel][] = $subfamilia;
                $this->agregarSubfamilias($subfamilia, $nivel, $agrupadas);
            }
        }
    }

    public function render()
    {
        $familias = Familia::whereNull('id_familia_padre')->with('subfamiliasRecursivas')->get();

        return view('livewire.familia.create-categoria', [
            'familias' => $familias,
            'familiasFiltradas' => $this->familiasFiltradas
        ]);
    }
}
