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
use Exception;
use Illuminate\Support\Facades\DB;

class Inputnewparameters extends Component
{

    public $jenis_sampel = 1;
    public $parameters = [];
    public $metode = [];
    public $isDisabled = false;
    public $datatables = [];

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;



    public function render()
    {
        $getparameters = JenisSampel::all()->toArray();

        // dd($getparameters);
        return view('livewire.inputnewparameters', [
            'getparameters' => $getparameters
        ]);
    }

    public function datatabel()
    {
        $this->emit('filterData', $this->jenis_sampel);
    }


    public function deleteItem($id)
    {
        JenisSampel::find($id)->delete();
        // Optional: Dispatch an event or perform any necessary actions after deletion.
        // For example, emit an event to notify other components about the deletion.
        $this->emit('itemDeleted');
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


        $newParameter = [
            'nama' => '',
            'jenis_sampel' => $this->jenis_sampel,
            'hargaparams' => '',
        ];

        $this->parameters[] = $newParameter;

        // Initialize the $isDisabled array for the newly added parameter
        $this->isDisabled = false; // Set the initial state to not disabled
    }





    public function mount()
    {

        $this->datatabel();
    }

    public function addMetode($parameterIndex)
    {

        // kolum validasi 
        $this->validate([
            "parameters.$parameterIndex.nama" => 'required',
        ]);


        $nama = $this->parameters[$parameterIndex]['nama'];
        $harga = $this->parameters[$parameterIndex]['hargaparams'];

        $this->metode[$parameterIndex][] = [
            'nama' => $nama,
            'harga' => $harga,
            'namamethod' => '',
            'satuan' => '',
        ];

        // No need to modify the 'nama' value here
        $this->isDisabled = true;
    }

    public function totalsampel($parameterIndex)
    {
        $hargaparams = $this->parameters[$parameterIndex]['hargaparams'];

        foreach ($this->metode[$parameterIndex] ?? [] as $methodIndex => $method) {
            // Update the harga value for each method in the metode array
            $this->metode[$parameterIndex][$methodIndex]['harga'] = $hargaparams;
        }
    }

    public function resetForm()
    {
        $this->parameters = []; // Reset parameters array
        $this->metode = []; // Reset metode array
        $this->jenis_sampel = null; // Reset jenis_sampel or any other values you need to reset
    }




    public function save()
    {
        $allParameters = $this->parameters;
        // $methods = $this->metode[$parameterIndex] ?? [];
        // dd($allParameters);

        try {
            foreach ($allParameters as $parameterIndex => $parameter) {
                $nama = $parameter['nama'];
                $jenis_sampel = $parameter['jenis_sampel'];
                $methods = $this->metode[$parameterIndex] ?? [];

                // dd($methods;)
                $this->validate([
                    "parameters.$parameterIndex.nama" => 'required',
                ]);

                // Insert into parameter_analisis table
                $parameterModel = ParameterAnalisis::create([
                    'nama' => $nama,
                    'id_jenis_sampel' => $jenis_sampel,
                ]);

                foreach ($methods as $methodIndex => $method) {
                    $this->validate([
                        "metode.$parameterIndex.$methodIndex.harga" => 'numeric|required',
                        "metode.$parameterIndex.$methodIndex.namamethod" => 'required',
                    ]);
                    // dd($method);
                    // Insert into metode_analisis table with the obtained parameter ID
                    MetodeAnalisis::create([
                        'nama' => $method['namamethod'],
                        'harga' => $method['harga'],
                        'satuan' => $method['satuan'],
                        'id_parameter' => $parameterModel->id,
                    ]);
                }
            }
            DB::commit();

            $this->successSubmit = true;
            $this->msgSuccess = "Data saved successfully!";

            $this->resetForm();
        } catch (Exception $e) {
            DB::rollBack();
            $this->msgError = 'An error occurred while saving the data: ' . $e->getMessage();
            $this->errorSubmit = true;
        }
    }
}
