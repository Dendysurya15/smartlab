<?php

namespace App\Http\Livewire;

use App\Models\JenisSampel;
use App\Models\MetodeAnalisis;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;


class InputProgress extends Component
{
    use WithFileUploads;

    public $inputans = [];
    public $parameterAnalisis = [];
    public $metodeAnalisis = [];
    public $jenis_sampel;
    public $tanggal_penerimaan;
    public $asal_sampel;
    public $nomor_kupa;
    public $nama_pengirim;
    public $departemen;
    public $kode_sampel;
    public $jumlah_sampel;
    public $kondisi_sampel;
    public $kemasan_sampel;
    public $personel;
    public $alat;
    public $bahan;
    public $nomor_surat;
    public $nomor_lab;
    public $estimasi;
    public $tujuan;
    public $last_update;
    public $admin;
    public $no_hp;
    public $email;
    public $foto_sampel;
    public $skala_prioritas;

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;

    protected $rules = [
        'tanggal_penerimaan' => 'required|date',
        'jenis_sampel' => 'required',
        'asal_sampel' => 'required|in:Internal,Eksternal',
        'nomor_kupa' => 'required|numeric',
        'nomor_lab' => 'required|string',
        'nama_pengirim' => 'required|string',
        'departemen' => 'required|string',
        'kode_sampel' => 'required|string',
        'jumlah_sampel' => 'required|numeric',
        'kondisi_sampel' => 'required|string',
        'kemasan_sampel' => 'required|string',
        'nomor_surat' => 'required|string',
        'estimasi' => 'required|date',
        'no_hp' => 'required|string',
        'tujuan' => 'required|string',
        'email' => 'required|email', // Assuming it's an email field
        'foto_sampel' => 'max:5000',
    ];

    protected $messages = [

        // 'email.required' => 'The Email Address cannot be empty.',
        // 'email.email' => 'The Email Address format is not valid.',

    ];
    public function addParameter()
    {
        $defaultParameterAnalisis = JenisSampel::first();
        $parameterAnalisisId = $defaultParameterAnalisis ? $defaultParameterAnalisis->id : null;

        $defaultMetodeAnalisis = JenisSampel::first();
        $metodeAnalisisId = $defaultMetodeAnalisis ? $defaultMetodeAnalisis->id : null;

        $this->inputans[] = ['satuan_default' => '', 'parameter_id' => $parameterAnalisisId, 'metode_id' => $metodeAnalisisId, 'jumlah_per_parameter' => 1];
    }

    public function removeParameter($index)
    {
        unset($this->inputans[$index]);
        $this->inputans = array_values($this->inputans);
    }

    public function resetFotoSampel()
    {
        $this->foto_sampel = null;
    }

    public function mount()
    {
        $this->tanggal_penerimaan = Carbon::now()->toDateString();
        $this->jumlah_sampel = 1;
        $this->kondisi_sampel = 'Normal';
        $this->asal_sampel = 'Internal';
        $this->personel = True;
        $this->alat = True;
        $this->bahan = True;
        $this->skala_prioritas = 'Normal';
        $this->estimasi = Carbon::now()->toDateString();
        $defaultJenisSampel = JenisSampel::first();
        $this->jenis_sampel = $defaultJenisSampel ? $defaultJenisSampel->id : null;
        $defaultParameterAnalisis = JenisSampel::first();
        $parameterAnalisisId = $defaultParameterAnalisis ? $defaultParameterAnalisis->id : null;
        $defaultMetodeAnalisis = JenisSampel::first();
        $metodeAnalisisId = $defaultMetodeAnalisis ? $defaultMetodeAnalisis->id : null;
        $this->inputans = [
            ['satuan_default' => '', 'parameter_id' => $parameterAnalisisId, 'metode_id' => $metodeAnalisisId, 'jumlah_per_parameter' => 1]
        ];
    }

    public function render()
    {
        $jenisSampelOptions = JenisSampel::all();
        $parameterAnalisisOptions = ParameterAnalisis::all();
        $metodeAnalisisOptions = metodeAnalisis::all();
        return view(
            'livewire.input-progress',
            [
                'jenisSampelOptions' => $jenisSampelOptions,
                'parameterAnalisisOptions' => $parameterAnalisisOptions,
                'metodeAnalisisOptions' => $metodeAnalisisOptions
            ]
        );
    }

    public function save()
    {


        $this->validate();

        $userId = 1;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;
        }

        do {
            $randomCode = generateRandomCode();
            $existingTrackSampel = TrackSampel::where('kode_Track', $randomCode)->first();
        } while ($existingTrackSampel);

        $current = Carbon::now();
        $current = $current->format('Y-m-d H:i:s');

        try {
            DB::beginTransaction();

            $trackSampel = new TrackSampel();
            $trackSampel->jenis_sampel = $this->jenis_sampel;
            $trackSampel->tanggal_penerimaan = $this->tanggal_penerimaan;
            $trackSampel->asal_sampel = $this->asal_sampel;
            $trackSampel->nomor_kupa = $this->nomor_kupa;
            $trackSampel->nama_pengirim = $this->nama_pengirim;
            $trackSampel->departemen = $this->departemen;
            $trackSampel->kode_sampel = $this->kode_sampel;
            $trackSampel->jumlah_sampel = $this->jumlah_sampel;
            $trackSampel->kondisi_sampel = $this->kondisi_sampel;
            $trackSampel->kemasan_sampel = $this->kemasan_sampel;
            $trackSampel->personel = $this->personel;
            $trackSampel->alat = $this->alat;
            $trackSampel->bahan = $this->bahan;
            $trackSampel->nomor_surat = $this->nomor_surat;
            $trackSampel->nomor_lab = $this->nomor_lab;
            $trackSampel->estimasi = $this->estimasi;
            $trackSampel->tujuan = $this->tujuan;
            $trackSampel->progress = 4;
            $trackSampel->last_update = $current;
            $trackSampel->admin = $userId;
            $trackSampel->no_hp = $this->no_hp;
            $trackSampel->email = $this->email;
            $trackSampel->kode_track = $randomCode;
            $trackSampel->skala_prioritas = $this->skala_prioritas;

            if ($this->foto_sampel) {
                $fileName = time() . '_' . $this->foto_sampel->getClientOriginalName();
                $this->foto_sampel->storeAs('uploads', $fileName, 'public');
                $trackSampel->foto_sampel = $fileName;
            }

            $trackSampel->save();
            DB::commit();
            // session()->flash('successSubmit', $randomCode);
            // $this->redirect('input_progress');
            $this->successSubmit = true;
            $this->msgSuccess = $randomCode;
        } catch (Exception $e) {
            DB::rollBack();
            // session()->flash('errorSubmit', 'An error occurred while saving the data. ' .  $e->getMessage());
            $this->msgError = 'An error occurred while saving the data: ' . $e->getMessage();
            // Set the error flag
            $this->errorSubmit = true;
        }

        $this->reset([
            'jenis_sampel',
            'tanggal_penerimaan',
            'asal_sampel',
            'nomor_kupa',
            'nama_pengirim',
            'departemen',
            'kode_sampel',
            'jumlah_sampel',
            'kondisi_sampel',
            'kemasan_sampel',
            'personel',
            'alat',
            'bahan',
            'nomor_surat',
            'nomor_lab',
            'estimasi',
            'tujuan',
            'last_update',
            'admin',
            'no_hp',
            'email',
            'foto_sampel',
            'skala_prioritas'
        ]);

        // dd($this->tanggal_penerimaan);
        // dd($this->inputans);

        // Your other logic here (validation, data processing, etc.)

        // Optionally, you can reset the form or perform any other actions
        // $this->inputans = [];
        // session()->forget(['success', 'errorMessages']);
        // Provide user feedback (e.g., flash messages)
        // session()->flash('success', 'Data saved successfully');
    }
}
