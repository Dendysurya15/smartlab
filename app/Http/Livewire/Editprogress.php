<?php

namespace App\Http\Livewire;


use App\Models\JenisSampel;
use App\Models\MetodeAnalisis;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackParameter;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Editprogress extends Component
{

    public $sample;
    public $tanggal;
    public $no_kupa;
    public $jenis_sampel;
    public $nomor_lab_left;
    public $nomor_lab_right;
    public $parameterAnalisisOptions = [];
    public $get_progress;
    public $changeautoprogress;
    public $prameterproggres = [];
    public $asal_sampel;
    public $nomor_lab;
    public $departemen;
    public $nama_pengirim;
    public $kode_sampel;
    public $nomor_surat;
    public $tujuan;
    public $no_hp;
    public $val_parameter;
    public $analisisparameter;
    public $parameters = [];



    public function render()
    {
        $jenisSampelOptions = JenisSampel::all();

        $metodeAnalisisOptions = metodeAnalisis::all();



        // dd($jenisSampelOptions);
        return view(
            'livewire.editprogress',
            [
                'jenisSampelOptions' => $jenisSampelOptions,
                'metodeAnalisisOptions' => $metodeAnalisisOptions
            ]
        );
    }

    public function addParameter()
    {

        if ($this->val_parameter == null) {
            $defaultParameterAnalisis = ParameterAnalisis::Where('id_jenis_sampel', $this->jenis_sampel)->first();
        } else {
            $defaultParameterAnalisis = ParameterAnalisis::Where('id', $this->val_parameter)->first();
        }

        // dd($defaultParameterAnalisis);
        // Retrieve records where id_parameter is 1
        $getanalisis = MetodeAnalisis::where('id_parameter', $defaultParameterAnalisis->id)->get();

        $this->analisisparameter = $getanalisis->pluck('nama', 'id')->toArray();


        // dd($this->analisisparameter);

        $this->parameters[] = [
            'name' => '',
            'value' => '',
            'parametersanalisis' => $this->analisisparameter
        ];
    }

    public function removeParameter($index)
    {
        unset($this->parameters[$index]);
        $this->parameters = array_values($this->parameters); // Re-index the array
    }

    public function ChangeFieldParamAndNomorLab()
    {
        $selectedJenisSampel = JenisSampel::find($this->jenis_sampel);
        $progres = $selectedJenisSampel->progress;
        // dd($this->jenis_sampel, $progres);

        $progressIds = explode(',', $progres);

        // dd($progressIds);
        $id = $this->sample;

        $query = TrackSampel::find($id);

        // dd($query);

        $trackprogres = $query->progress;

        // dd($trackprogres);
        // Retrieve progress records based on IDs

        // dd($options2);
        if ($selectedJenisSampel) {
            $current = Carbon::now()->format('y');
            $options = ParameterAnalisis::where('id_jenis_sampel', $this->jenis_sampel)->get();
            $options2 = ProgressPengerjaan::whereIn('id', $progressIds)->get();


            $this->parameterAnalisisOptions = $options->pluck('nama', 'id')->toArray();
            $this->prameterproggres = $options2->pluck('nama', 'id')->toArray();


            $this->get_progress = $trackprogres;
            // dd($this->get_progress);
            // dd($this->prameterproggres);
        }
    }

    public function mount()
    {
        $id = $this->sample;

        $query = TrackSampel::find($id);

        // dd($query);

        // $this->tanggal = Carbon::parse($query->tanggal_penerimaan)->format('d/m/Y');
        $this->tanggal = Carbon::parse($query->tanggal_penerimaan)->format('d/m/Y');

        $this->no_kupa = $query->nomor_kupa;
        $this->jenis_sampel = $query->jenis_sampel;
        $this->asal_sampel = $query->asal_sampel;
        $this->nomor_lab = $query->nomor_lab;
        $this->nama_pengirim = $query->nama_pengirim;
        $this->departemen = $query->departemen;
        $this->kode_sampel = $query->kode_sampel;
        $this->nomor_surat = $query->nomor_surat;
        $this->tujuan = $query->tujuan;
        $this->no_hp = $query->no_hp;
        $this->get_progress = 4;
        // dd($this->asal_sampel);
    }

    public function changeautoprogress()
    {

        $progres = ProgressPengerjaan::all();

        // dd($progres);
    }


    public function save()
    {
    }
}
