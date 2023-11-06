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

    public $inputanParameter = [];
    public $biayaParameter = [];
    public $parameterAnalisis = [];
    public $metodeAnalisis = [];
    public $jenis_sampel = 5;
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
    public $nomor_lab_left;
    public $nomor_lab_right;
    public $tgl_pengantaran_sampel;
    public $jumlah_sampel_view;
    public $estimasi;
    public $tujuan;
    public $last_update;
    public $admin;
    public $no_hp;
    public $email;
    public $foto_sampel;
    public $skala_prioritas;
    public $hargaparameter;
    public $parameterAnalisisOptions = [];
    public $hargasample = [0];
    public $satuanparameter;
    public $namaparameter;
    public $analisisparameter = [0];
    public $val_parameter;
    public $totalsamples;
    public $subtotal;
    public $list_metode = [];

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;


    public $formData = [];


    protected $rules = [
        'tanggal_penerimaan' => 'required|date',
        'jenis_sampel' => 'required',
        'asal_sampel' => 'required|in:Internal,Eksternal',
        'nomor_kupa' => 'required|numeric',
        // 'nomor_lab' => 'required|string',
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

        if ($this->val_parameter == null) {
            $defaultParameterAnalisis = ParameterAnalisis::Where('id_jenis_sampel', $this->jenis_sampel)->first();
        } else {
            $defaultParameterAnalisis = ParameterAnalisis::Where('id', $this->val_parameter)->first();
        }
        $parameterAnalisisId = $defaultParameterAnalisis ? $defaultParameterAnalisis->id : null;
        $defaultMetodeAnalisis = MetodeAnalisis::first();
        // dd($defaultParameterAnalisis);
        $metodeAnalisisId = $defaultMetodeAnalisis ? $defaultMetodeAnalisis->id : null;
        $this->inputanParameter[] = ['satuan_default' => '', 'parameter_id' => $parameterAnalisisId, 'metode_id' => $metodeAnalisisId, 'jumlah_per_parameter' => 1];
        $selectedJenisSampel = MetodeAnalisis::find($defaultParameterAnalisis->id);

        // dd($selectedJenisSampel);
        if ($selectedJenisSampel) {
            $options = MetodeAnalisis::where('id_parameter', $defaultParameterAnalisis->id)->get();
            $this->hargaparameter = $options->first()->harga;
            $this->satuanparameter = $options->first()->satuan;
            $this->analisisparameter = $options->pluck('nama', 'id')->toArray();
            $sub_total = $this->hargaparameter * 1;
            $ppn = hitungPPN($sub_total);
            $total = $sub_total + $ppn;
        }


        // dd($this->hargaparameter);
        // $this->biayaParameter[] =   [

        //     'nama_parameter' => $defaultParameterAnalisis->nama,
        //     'jumlahsample' => 'Jumlah Sample',
        //     'hargassample' => 'Harga Sample',
        //     'subtotal' => 'Sub Total',
        //     'list_metode' => $this->analisisparameter,
        //     'jumlah_sampel' => 1,
        //     'harga_sampel' =>  $this->hargaparameter,
        //     'satuan' => '',
        //     'sub_total' => $sub_total,
        //     'ppn' => hitungPPN($sub_total),
        //     'total' => $total
        // ];
        // dd($defaultParameterAnalisis->nama);
        $newForm = [
            'nama_parameter' => $defaultParameterAnalisis->nama,
            'jumlahsample' => 'Jumlah Sample',
            'hargassample' => 'Harga Sample',
            'subtotal' => 'Sub Total',
            'list_metode' => $this->analisisparameter,
            'jumlah_sampel' => 1,
            'harga_sampel' =>  $this->hargaparameter,
            'satuan' => '',
            'sub_total' => $sub_total,
            'ppn' => hitungPPN($sub_total),
            'total' => $total
        ];

        $this->formData[] = $newForm;
    }

    public function gethargasample($index)
    {
        $form = $this->formData[$index];

        if ($this->val_parameter == null) {
            $defaultParameterAnalisis = ParameterAnalisis::Where('id_jenis_sampel', $this->jenis_sampel)->first();
        } else {
            $defaultParameterAnalisis = ParameterAnalisis::Where('id', $this->val_parameter)->first();
        }

        $selectedJenisSampel = MetodeAnalisis::find($defaultParameterAnalisis->id);

        if ($selectedJenisSampel) {
            $options = MetodeAnalisis::where('id_parameter', $defaultParameterAnalisis->id)->get();
            $this->hargaparameter = $options->first()->harga;

            $sub_totalx[$index] = $this->hargaparameter * $this->totalsamples[$index];
            $this->formData[$index]['sub_total'] = $sub_totalx[$index];
        }
    }

    public function updatedInputanParameter($value, $index)
    {
        $list = explode('.', $index);

        if ($list[1] == 'parameter_id') {
            $hargaSelected = ParameterAnalisis::find((int)$value)->harga;
            $this->biayaParameter[$list[0]]['harga_sampel'] = $hargaSelected;
        }
    }


    public function removeParameter($index)
    {
        unset($this->inputanParameter[$index]);
        $this->inputanParameter = array_values($this->inputanParameter);

        unset($this->biayaParameter[$index]);
        $this->biayaParameter = array_values($this->biayaParameter);
    }

    public function resetFotoSampel()
    {
        $this->foto_sampel = null;
    }



    public function mount()
    {

        $current = Carbon::now();
        $current = $current->format('y');
        $this->tanggal_penerimaan = Carbon::now()->toDateString();
        $jumlah_sampel_default = 4;
        $this->jumlah_sampel = $jumlah_sampel_default;
        $this->kondisi_sampel = 'Normal';
        $this->asal_sampel = 'Internal';
        $this->personel = True;
        $this->alat = True;
        $this->bahan = True;
        $this->skala_prioritas = 'Normal';
        $this->estimasi = Carbon::now()->toDateString();
        $defaultJenisSampel = JenisSampel::first();
        $this->nomor_lab_left = $current . $defaultJenisSampel->kode . '.';
        $this->nomor_lab_right = $current . $defaultJenisSampel->kode . '.';
        $this->jenis_sampel = $defaultJenisSampel ? $defaultJenisSampel->id : null;
        $defaultParameterAnalisis = ParameterAnalisis::Where('id_jenis_sampel', $defaultJenisSampel->id)->first();
        // dd($defaultParameterAnalisis);
        $parameterAnalisisId = $defaultParameterAnalisis ? $defaultParameterAnalisis->id : [];
        $defaultHarga = '0';
        $defaultMetodeAnalisis = MetodeAnalisis::first();
        $metodeAnalisisId = $defaultMetodeAnalisis ? $defaultMetodeAnalisis->id : null;
        $sub_total = $this->hargaparameter * $jumlah_sampel_default;
        // dd($this->hargaparameter);
        $ppn = hitungPPN($sub_total);

        $total = $sub_total + $ppn;

        $this->inputanParameter = [
            ['satuan_default' => '', 'parameter_id' => $parameterAnalisisId, 'metode_id' => $metodeAnalisisId, 'jumlah_per_parameter' => 1]
        ];

        // dd($this->parameterAnalisisOptions);
        // $this->biayaParameter[] =   [
        //     'nama_parameter' => '',
        //     'jumlahsample' => '',
        //     'hargassample' => '',
        //     'list_metode' => '',
        //     'subtotal' => '',
        //     // 'jumlah_sampel' => '',
        //     'harga_sampel' => $defaultHarga,
        //     'satuan' => '',
        //     'sub_total' => $sub_total,
        //     'ppn' => hitungPPN($sub_total),
        //     'total' => $total
        // ];

        $newForm = [
            'nama_parameter' => '',
            'jumlahsample' => '',
            'hargassample' => '',
            'list_metode' => '',
            'subtotal' => '',
            // 'jumlah_sampel' => '',
            'harga_sampel' => $defaultHarga,
            'satuan' => '',
            'sub_total' => $sub_total,
            'ppn' => hitungPPN($sub_total),
            'total' => $total
        ];

        $this->formData[] = $newForm;
    }

    public function ChangeFieldParamAndNomorLab()
    {
        $selectedJenisSampel = JenisSampel::find($this->jenis_sampel);
        if ($selectedJenisSampel) {
            $current = Carbon::now()->format('y');
            $this->nomor_lab_left = $current . $selectedJenisSampel->kode . '.';
            $this->nomor_lab_right = $current . $selectedJenisSampel->kode . '.';
            $options = ParameterAnalisis::where('id_jenis_sampel', $this->jenis_sampel)->get();

            $this->parameterAnalisisOptions = $options->pluck('nama', 'id')->toArray();

            // dd($this->parameterAnalisisOptions);
        }
    }



    public function updateHargaSampel()
    {

        foreach ($this->inputanParameter as $index => $inputan) {
            if (isset($this->biayaParameter[$index])) {
                $this->biayaParameter[$index]['jumlah_sampel'] = $inputan['jumlah_per_parameter'];
                $this->biayaParameter[$index]['sub_total'] = $this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter'];
                $this->biayaParameter[$index]['ppn'] = hitungPPN($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']);
                $this->biayaParameter[$index]['total'] = ($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']) + hitungPPN($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']);
            }
        }
    }



    public function getJumlahSampel()
    {

        foreach ($this->inputanParameter as $index => $inputan) {
            if (isset($this->biayaParameter[$index])) {
                $this->biayaParameter[$index]['jumlah_sampel'] = $inputan['jumlah_per_parameter'];
                $this->biayaParameter[$index]['sub_total'] = $this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter'];
                $this->biayaParameter[$index]['ppn'] = hitungPPN($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']);
                $this->biayaParameter[$index]['total'] = ($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']) + hitungPPN($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']);
            }
        }
    }

    public function render()
    {
        $jenisSampelOptions = JenisSampel::all();

        $metodeAnalisisOptions = metodeAnalisis::all();
        return view(
            'livewire.input-progress',
            [
                'jenisSampelOptions' => $jenisSampelOptions,
                'metodeAnalisisOptions' => $metodeAnalisisOptions
            ]
        );
    }

    public function updatePPN()
    {



        foreach ($this->inputanParameter as $index => $inputan) {
            if (isset($this->biayaParameter[$index])) {
                $ppn = (($this->hargaparameter * $this->biayaParameter[$index]['jumlah_sampel']) * 11) / 100;
                $subtotal = ($this->hargaparameter * $this->biayaParameter[$index]['jumlah_sampel']);

                $this->biayaParameter[$index]['sub_total'] = $subtotal;
                $this->biayaParameter[$index]['ppn'] = $ppn;
                $this->biayaParameter[$index]['total'] = $ppn + $subtotal;
            }
        }
    }



    public function save()
    {


        dd(
            $this->jenis_sampel,
            $this->parameterAnalisisOptions,
            $this->asal_sampel,
            $this->nomor_kupa,
            $this->nama_pengirim,
            $this->departemen,
        );
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
            // $trackSampel->nomor_lab = $this->nomor_lab;
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
            // 'nomor_lab',
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
