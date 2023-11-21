<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JenisSampel;
use App\Models\MetodeAnalisis;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackParameter;
use Termwind\Components\Dd;

class Inputnewparameters extends Component
{

    public $jenis_sampel = 1;
    public $parameters = [];
    public $metode = [];
    public $isDisabled = false;


    protected $rules = [
        'parameters.*.nama' => 'required' // Validate 'nama' field in all parameters
    ];

    public function render()
    {
        $getparameters = JenisSampel::all()->toArray();

        // dd($getparameters);
        return view('livewire.inputnewparameters', [
            'getparameters' => $getparameters
        ]);
    }

    public function deleteParameter($parameterIndex)
    {
        // Remove the parameter at the specified index
        unset($this->parameters[$parameterIndex]);
        // Remove the associated methods
        unset($this->metode[$parameterIndex]);

        // Re-index the arrays to avoid missing indices
        $this->parameters = array_values($this->parameters);
        $this->metode = array_values($this->metode);
    }

    public function deleteMetode($parameterIndex, $methodIndex)
    {
        // Remove the method at the specified index for the given parameter
        unset($this->metode[$parameterIndex][$methodIndex]);

        // Re-index the array to avoid missing indices
        $this->metode[$parameterIndex] = array_values($this->metode[$parameterIndex]);
    }


    public function addParameter()
    {
        $this->validate();

        // dd($this->jenis_sampel);

        $newParameter = [
            'nama' => '',
            'jenis_sampel' => $this->jenis_sampel,
            'methods' => [],
        ];

        $this->parameters[] = $newParameter;

        // Initialize the $isDisabled array for the newly added parameter
        $this->isDisabled = false; // Set the initial state to not disabled
    }

    public function addMetode($parameterIndex)
    {

        $this->validate([
            "parameters.$parameterIndex.nama" => 'required'
        ]);

        $nama = $this->parameters[$parameterIndex]['nama']; // Store the name

        $this->metode[$parameterIndex][] = [
            'nama' => $nama,
            'harga' => 0, // Default value for harga
            'namamethod' => '', // Default value for namamethod
        ];

        // No need to modify the 'nama' value here
        $this->isDisabled = true;
    }




    public function mount()
    {

        // dd($getparameters);


    }

    public function save()
    {
        $allParameters = $this->parameters;

        // dd($allParameters);

        // Loop through parameters to gather the data
        foreach ($allParameters as $parameterIndex => $parameter) {
            // Access parameter nama and methods
            // $nama = $parameter;
            $nama = $parameter['nama'];
            $jenis_sampel = $parameter['jenis_sampel'];
            $methods = $this->metode[$parameterIndex] ?? [];

            // dd($parameter);
            // Do something with $nama and $methods (e.g., save to database, perform operations, etc.)
            // For demonstration purposes, let's just output them
            // dd("Parameter Nama: $nama");

            $new_arr[] = [
                'nama_parameter' => $nama,
                'jenis_sampel' => $jenis_sampel,
                'methods' => $methods
            ];
            // dd($nama, $methods);
        }
        dd($new_arr);

        // Perform any additional actions after handling the form data
    }
}
