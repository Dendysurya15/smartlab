<?php

namespace App\Livewire;

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

class Addparameters extends Component
{

    public $jenis_sampel = 1;
    public $parameters = [];
    public $metode = [];
    public $isDisabled = true; // Initially, the button is disabled

    public $datatables = [];

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;


    public function render()
    {

        $getparameters = JenisSampel::all()->toArray();
        return view('livewire.addparameters', [
            'getparameters' => $getparameters
        ]);
    }
    public function datatabel()
    {
        $this->dispatch('filterData', $this->jenis_sampel);
    }


    public function deleteItem($id)
    {
        JenisSampel::find($id)->delete();
        // Optional: Dispatch an event or perform any necessary actions after deletion.
        // For example, emit an event to notify other components about the deletion.
        $this->dispatch('itemDeleted');
    }

    public function deleteParameter($parameterIndex)
    {
        // Remove the parameter at the specified index
        unset($this->parameters[$parameterIndex]);

        // Re-index the arrays to avoid missing indices
        $this->parameters = array_values($this->parameters);

        if (empty($this->parameters)) {
            // Disable the button if there are no parameters
            $this->isDisabled = true;
        }
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
        $this->isDisabled = false;
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
        $method = $this->parameters[$parameterIndex]['namathod'];
        $satuatod = $this->parameters[$parameterIndex]['satuatod'];

        $this->metode[$parameterIndex][] = [
            'nama' => $nama,
            'harga' => $harga,
            'namamethod' => $method,
            'satuan' => $satuatod
        ];

        // No need to modify the 'nama' value here
        $this->isDisabled = true;
    }


    public function resetForm()
    {
        $this->parameters = []; // Reset parameters array
        $this->metode = []; // Reset metode array
        $this->jenis_sampel = null; // Reset jenis_sampel or any other values you need to reset
    }



    public function save()
    {
        // $this->validate([
        //     "parameters.$this->parameterIndex.hargaparams" => 'required', // Validate the 'harga' parameter
        // ]);

        $allParameters = $this->parameters;

        // dd($allParameters);



        try {
            foreach ($allParameters as $parameterIndex => $parameter) {
                $nama = $parameter['nama'];
                $jenis_sampel = $parameter['jenis_sampel'];
                $hargaparams = $parameter['hargaparams'];
                $namathod = $parameter['namathod'];
                $satuatod = $parameter['satuatod'];

                // dd($nama);
                ParameterAnalisis::create([
                    'nama_parameter' => $nama,
                    'nama_unsur' => '-',
                    'bahan_produk' => '-',
                    'metode_analisis' => $namathod,
                    'harga' => $hargaparams,
                    'satuan' => $satuatod,
                    'id_jenis_sampel' => $jenis_sampel,

                ]);
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
