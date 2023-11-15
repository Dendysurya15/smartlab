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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormDataExport;

class Editprogress extends Component
{

    public $sample;
    public $tanggal;
    public $estimasi;
    public $no_kupa;
    public $jenis_sampel;
    public $nomor_lab_left;
    public $nomor_lab_right;
    public $parameterAnalisisOptions = [];
    public $get_progress = [];
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
    public $hargaparameter;
    public $satuanparameter;
    public $totalsampelval;
    public $foto_sampel;
    public $email;
    public $estimasikupa;
    public $parameterid;
    public $oldform = [];
    public $parameters = [];



    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;
    public $isExporting = false; // Add this property in your Livewire component


    public function render()
    {
        $jenisSampelOptions = JenisSampel::all();

        $metodeAnalisisOptions = metodeAnalisis::all();

        // dd($getTrack, $getAnalisis, $trackform);


        // dd($this->tanggal);
        return view(
            'livewire.editprogress',
            [
                'jenisSampelOptions' => $jenisSampelOptions,
                'metodeAnalisisOptions' => $metodeAnalisisOptions,
                'oldform' => $this->oldform,

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

        $selectedJenisSampel = MetodeAnalisis::find($defaultParameterAnalisis->id);

        // dd($defaultParameterAnalisis);
        if ($selectedJenisSampel) {
            $options = MetodeAnalisis::where('id_parameter', $defaultParameterAnalisis->id)->get();
            $this->hargaparameter = $options->first()->harga;
            $this->satuanparameter = $options->first()->satuan;
            $this->analisisparameter = $options->pluck('nama', 'id')->toArray();
            $sub_total = $this->hargaparameter * 1;
            $ppn = hitungPPN($sub_total);
            $total = $sub_total + $ppn;
            $defaultppn = 11;
        }
        // dd($this->val_parameter);

        $this->parameters[] = [
            'jumlah' => 1,
            'value' => '',
            'parametersanalisis' => $this->analisisparameter,
            'harga' => $this->hargaparameter,
            'sub_total' => $sub_total,
            'total' => $total,
            'ppn' => $ppn,
            'harga_ori' => $this->hargaparameter,
            'judulppn' => $defaultppn . "% PPN",
            'id_parameter' => $this->parameterid = $defaultParameterAnalisis->id
        ];
    }

    public function totalsampel($index)
    {
        $form = $this->parameters[$index];
        $jumlahsample = $form['jumlah'];
        $hargasampel = $form['harga_ori'] * $jumlahsample;
        $ppn = hitungPPN($hargasampel);
        $total = $hargasampel + $ppn;

        // Update the parameters array
        $this->parameters[$index]['sub_total'] = $hargasampel;
        $this->parameters[$index]['ppn'] = $ppn;
        $this->parameters[$index]['total'] = $total;
    }


    public function changeppn($index)
    {
        $form = $this->parameters[$index];
        $sub_total = $form['sub_total'];
        $ppn = $form['ppn'];

        $subtotal = ($sub_total * $ppn) / 100;
        $total = $subtotal + $sub_total;

        $this->parameters[$index]['total'] = $total;
        $this->parameters[$index]['judulppn'] = $ppn . "% PPN";
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


        // dd($options2);
        if ($selectedJenisSampel) {
            $options = ParameterAnalisis::where('id_jenis_sampel', $this->jenis_sampel)->get();
            $options2 = ProgressPengerjaan::whereIn('id', $progressIds)->get();
            $this->parameterAnalisisOptions = $options->pluck('nama', 'id')->toArray();
            $this->prameterproggres = $options2->pluck('nama', 'id')->toArray();
            $this->get_progress = $trackprogres;
        }
    }

    public function mount()
    {
        $id = $this->sample;
        $query = TrackSampel::find($id);

        // dd($query);

        $this->tanggal = Carbon::parse($query->tanggal_penerimaan)->format('Y-m-d');
        $this->estimasi = Carbon::parse($query->estimasi)->format('Y-m-d');
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
        $this->email = $query->email;
        $this->foto_sampel = asset('storage/uploads/' . $query->foto_sampel);


        // dd($this->foto_sampel);
        // dd($this->foto_sampel);


        $getTrack = TrackParameter::where('id_tracksampel', $query->parameter_analisisid)->get()->toArray();
        $getAnalisis = MetodeAnalisis::all()->toArray();
        $getparameters = ParameterAnalisis::all()->toArray();

        $trackform = [];

        // dd($getTrack);

        foreach ($getTrack as $key => $value) {
            $parameters = [];

            foreach ($getAnalisis as $key2 => $value2) {
                if ($value2['id_parameter'] == $value['id_parameter']) {
                    $parameters[] = $value2['nama'];
                    $harga = $value2['harga'];
                }
            }

            $nama = '-';
            foreach ($getparameters as $keyx => $valuex) {
                if ($value['id_parameter'] == $valuex['id']) {
                    $nama = $valuex['nama'];
                }
            }
            $subtotal = $value['jumlah'] * $harga;
            $ppn = hitungPPN($subtotal);

            $trackform[$key] = [
                'id' => $value['id'],
                'harga' => $value['totalakhir'],
                'jenis_analiss' => $parameters,
                'harga' => $harga,
                'nama_parameters' => $nama,
                'jumlah' => $value['jumlah'],
                'harga_total' => $value['totalakhir'],
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'id_parameter' => $value['id_parameter'],
                'judulppn' => "11% PPN",
            ];
        }

        $this->oldform = $trackform;

        $this->ChangeFieldParamAndNomorLab();
    }



    public function hapusItem($index)
    {
        // Remove the item with the specified index from the $oldform array
        unset($this->oldform[$index]);
        // Re-index the array to maintain sequential keys
        $this->oldform = array_values($this->oldform);
    }


    public function totalsampelold($index)
    {
        $form = $this->oldform[$index];


        $jumlahsample = $form['jumlah'];
        $hargasampel = $form['harga'] * $jumlahsample;
        $ppn = hitungPPN($hargasampel);
        $total = $hargasampel + $ppn;

        // Update the parameters array
        $this->oldform[$index]['subtotal'] = $hargasampel;
        $this->oldform[$index]['ppn'] = $ppn;
        $this->oldform[$index]['harga_total'] = $total;

        // dd($form);
    }

    public function ppnold($index)
    {
        $form = $this->oldform[$index];
        $sub_total = $form['subtotal'];
        $ppn = $form['ppn'];

        $subtotal = ($sub_total * $ppn) / 100;
        $total = $subtotal + $sub_total;

        $this->oldform[$index]['harga_total'] = $total;
        $this->oldform[$index]['judulppn'] = $ppn . "% PPN";
    }

    private function processSave()
    {
        $id = $this->sample;
        $query = TrackSampel::find($id);

        // form baru tambahan 
        $newparametersedit = $this->parameters;
        // form lama bawaan query 
        $oldparameteredit = $this->oldform;


        $last_update = $query->last_update;


        $timeupdate = Carbon::now()->format('y-m-d H:i:s');

        $newupdate = $last_update . ',' . $timeupdate;

        $trackid = $query->parameter_analisisid;


        $querytrack = TrackParameter::where('id_tracksampel', $trackid)->get()->toArray();

        $idold = [];

        foreach ($querytrack as $key2 => $value2) {
            $found = false;

            foreach ($oldparameteredit as $key => $value) {
                if ($value['id'] == $value2['id']) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $idold[] = $value2['id'];
            }
        }
        try {
            DB::beginTransaction();

            $id = $this->sample;
            $trackSampel = TrackSampel::find($id);

            // Update the existing TrackSampel model
            $trackSampel->jenis_sampel = $this->jenis_sampel;
            $trackSampel->tanggal_penerimaan = $this->tanggal;
            $trackSampel->progress = $this->get_progress;
            $trackSampel->asal_sampel = $this->asal_sampel;
            $trackSampel->nomor_kupa = $this->no_kupa;
            $trackSampel->nomor_lab = $this->nomor_lab;
            $trackSampel->nama_pengirim = $this->nama_pengirim;
            $trackSampel->departemen = $this->departemen;
            $trackSampel->kode_sampel = $this->kode_sampel;
            $trackSampel->nomor_surat = $this->nomor_surat;
            $trackSampel->estimasi = $this->estimasikupa;
            $trackSampel->tujuan = $this->tujuan;
            $trackSampel->no_hp = $this->no_hp;
            $trackSampel->email = $this->email;
            $trackSampel->last_update = $newupdate;
            $trackSampel->save();



            // Delete records based on the IDs
            TrackParameter::whereIn('id', $idold)->delete();

            foreach ($oldparameteredit as $key => $value) {
                // dd($value);
                TrackParameter::where('id', $value['id'])->update([
                    'jumlah' => $value['jumlah'],
                    'totalakhir' => $value['harga_total'],
                    'id_tracksampel' => $trackid,
                    'id_parameter' => $value['id_parameter'],
                ]);
            }

            // TrackParameter::update($oldParameters);

            if ($newparametersedit != []) {
                $trackParameters = [];

                foreach ($newparametersedit as $key => $value) {
                    $trackParameters[] = [
                        'jumlah' => $value['jumlah'],
                        'totalakhir' => $value['total'],
                        'id_tracksampel' => $trackid,
                        'id_parameter' => $value['id_parameter'],
                    ];
                }

                TrackParameter::insert($trackParameters);
            }



            DB::commit();

            $this->successSubmit = true;
            $this->msgSuccess = $query->kode_track;
        } catch (Exception $e) {
            DB::rollBack();
            $this->msgError = 'An error occurred while saving the data: ' . $e->getMessage();
            $this->errorSubmit = true;
        }

        // You can remove the following dd() statement if everything is working as expected
        // dd($trackSampel);
    }

    public function save()
    {
        if (!$this->isExporting) {
            $this->processSave();
        }
    }

    public function exportExcel()
    {
        $this->isExporting = true; // Set the flag to true when exporting Excel
        $id = $this->sample;
        return Excel::download(new FormDataExport($id), 'Data_Lab.xlsx');
    }
}
