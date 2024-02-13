<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisSampel;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackParameter;
use App\Models\SendMsg;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MinotoringExport;


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
    public $jumlah_sampel;
    public $tgl_pengantaran_sampel;
    public $kondisi_sampel;
    public $kemasan_sampel;
    public $personel;
    public $alat;
    public $bahan;
    public $skala_prioritas;
    public $nomor_surat;
    public $tujuan;
    public $no_hp;
    public $val_parameter;
    public $analisisparameter;
    public $hargaparameter;
    public $satuanparameter;
    public $totalsampelval;
    public $foto_sampel;
    public $emailTo;
    public $emailCc;
    public $estimasikupa;
    public $parameterid;
    public $oldform = [];
    public $parameters = [];
    public $nama_jenis_sampel;
    public $selected_status;
    public $badge_color_status;
    public $kode_track;

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;
    public $isExporting = false; // Add this property in your Livewire component


    public function render()
    {
        $jenisSampelOptions = JenisSampel::all();

        $query = TrackSampel::with('trackParameters')->where('id', $this->sample)->first();
        $progressQuery = JenisSampel::with('parameterAnalisis')->find($query->jenis_sampel);

        $relationship = $progressQuery->parameterAnalisis;
        if ($progressQuery->nama != 'Pupuk Anorganik') {
            $list_parameter = $relationship->groupBy('nama_parameter')->flatMap(function ($grouped) {
                return $grouped->count() > 1 ? $grouped->map(function ($item) {
                    return ['id' => $item->id, 'nama_parameter_full' => $item->nama_parameter . ' ' . $item->metode_analisis];
                }) : $grouped->map(function ($item) {
                    return ['id' => $item->id, 'nama_parameter_full' => $item->nama_parameter];
                });
            })->values()->toArray();
        } else {
            $list_parameter = $relationship->groupBy('nama_parameter')->flatMap(function ($grouped) {
                return $grouped->count() > 1 ? $grouped->map(function ($item) {
                    return ['id' => $item->id, 'nama_parameter_full' => $item->bahan_produk . ' ' . $item->nama_parameter . ' ' . $item->metode_analisis];
                }) : $grouped->map(function ($item) {
                    return ['id' => $item->id, 'nama_parameter_full' => $item->nama_parameter];
                });
            })->values()->toArray();
        }
        $arr_progress = explode(',', $progressQuery->progress);

        $progressOptions = [];

        foreach ($arr_progress as $progressId) {
            $queryProgress = ProgressPengerjaan::find($progressId);
            $progressOptions[$queryProgress->id] = $queryProgress->nama;
        }

        return view(
            'livewire.editprogress',
            [
                'status_pengerjaan' => $query->status,
                'jenisSampelOptions' => $jenisSampelOptions,
                'list_parameter' => $list_parameter,
                'listProgress' => $progressOptions,
                'oldform' => $this->oldform,

            ]
        );
    }

    public function addParameter()
    {

        $this->val_parameter = $this->val_parameter ?? $this->parameterAnalisisOptions[0]['id'];
        $defaultParameterAnalisis = ParameterAnalisis::Where('id', $this->val_parameter)->first();

        $this->hargaparameter = $defaultParameterAnalisis->harga;
        $this->satuanparameter = $defaultParameterAnalisis->satuan;
        $this->analisisparameter = $defaultParameterAnalisis->metode_analisis;
        $this->personel = $this->personel == True ? True : False;
        $this->alat = $this->alat == True ? True : False;
        $this->bahan = $this->bahan == True ? True : False;
        $total = $this->hargaparameter * 1;
        $defaultppn = 11;

        $this->oldform[] = [
            'jumlah' => 1,
            'value' => '',
            'nama_parameters' => $defaultParameterAnalisis->nama_parameter,
            'metode_analisis' => $this->analisisparameter,
            'harga' => $this->hargaparameter,
            'total' => $total,
            'personel' =>   $this->personel,
            'alat' =>    $this->alat,
            'bahan' =>    $this->bahan,
            'id_parameter' => $this->parameterid = $defaultParameterAnalisis->id,
            'judulppn' => $defaultppn . "% PPN"
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
            // $this->parameterAnalisisOptions = $options->pluck('nama', 'id')->toArray();
            $this->prameterproggres = $options2->pluck('nama', 'id')->toArray();
            $this->get_progress = $trackprogres;
            $this->val_parameter = $options->pluck('id')->first();
        }
    }

    public function mount()
    {
        $id = $this->sample;
        $query = TrackSampel::find($id);
        $this->selected_status = $query->status;

        if ($this->selected_status === 'Approved') {
            $this->badge_color_status = 'bg-emerald-600';
        } elseif ($this->selected_status === 'Rejected') {
            $this->badge_color_status = 'bg-red-600';
        } elseif ($this->selected_status === 'Pending') {
            $this->badge_color_status = 'bg-yellow-500';
        }



        $this->kode_track = $query->kode_track;
        $this->tanggal = $query->tanggal_penerimaan
            ? Carbon::parse($query->tanggal_penerimaan)->format('Y-m-d')
            : null;
        $this->estimasi = $query->estimasi
            ? Carbon::parse($query->estimasi)->format('Y-m-d')
            : null;
        $this->no_kupa = $query->nomor_kupa;
        $this->jenis_sampel = $query->jenis_sampel;
        $this->asal_sampel = $query->asal_sampel;
        $query = TrackSampel::with('trackParameters')->where('id', $this->sample)->first();
        $this->nama_jenis_sampel = JenisSampel::find($query->jenis_sampel)->nama;

        if ($query->nomor_lab != null) {
            $nomor_lab = $query->nomor_lab;
            $arr_nomor_lab = explode('-', $nomor_lab);
            $this->nomor_lab_left = $arr_nomor_lab[0];
            $this->nomor_lab_right = $arr_nomor_lab[1];
        } else {
            $this->nomor_lab_left = '';
            $this->nomor_lab_right = '';
        }

        $this->tgl_pengantaran_sampel = $query->tanggal_pengantaran
            ? Carbon::parse($query->tanggal_pengantaran)->format('Y-m-d')
            : null;
        $this->jumlah_sampel = $query->jumlah_sampel;
        $this->kondisi_sampel = $query->kondisi_sampel;
        $this->kemasan_sampel = $query->kemasan_sampel;
        $this->skala_prioritas = $query->skala_prioritas;
        // $this->skala_prioritas = $query->skala_prioritas;
        // $this->personel = True;
        // $this->alat = True;
        // $this->bahan = True;

        $this->nama_pengirim = $query->nama_pengirim;
        $this->departemen = $query->departemen;
        $this->kode_sampel = $query->kode_sampel;
        $this->nomor_surat = $query->nomor_surat;
        $this->tujuan = $query->tujuan;
        $this->no_hp = $query->no_hp;
        $this->emailTo = $query->emailTo;
        $this->emailCc = $query->emailCc;
        $this->foto_sampel = asset('storage/uploads/' . $query->foto_sampel);

        $getTrack = TrackParameter::with('ParameterAnalisis')->where('id_tracksampel', $query->parameter_analisisid)->get()->toArray();
        $trackform = [];

        foreach ($getTrack as $key => $value) {
            $harga = $value['parameter_analisis']['harga'];
            $total = $value['jumlah'] * $harga;

            $trackform[$key] = [
                'id' => $value['id'],
                'harga' => $harga,
                'metode_analisis' => $value['parameter_analisis']['metode_analisis'],
                'nama_parameters' => $value['parameter_analisis']['nama_parameter'],
                'jumlah' => $value['jumlah'],
                'total' => $total,
                'personel' => $value['personel'] === 1 ? True : ($value['personel'] === 0 ? False : False),
                'alat' => $value['alat'] === 1 ? True : ($value['alat'] === 0 ? False : False),
                'bahan' => $value['bahan'] === 1 ? True : ($value['bahan'] === 0 ? False : False),
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
        $this->oldform[$index]['total'] = $hargasampel;
    }

    public function updateHargaSampel()
    {

        foreach ($this->oldform as $index => $inputan) {
            $curr_jumlah_sampel = $this->oldform[$index]['jumlah'];
            $curr_harga_sampel = $this->oldform[$index]['harga'];
            $curr_sub_total = $curr_jumlah_sampel * $curr_harga_sampel;
            $this->oldform[$index]['total'] = $curr_sub_total;
        }
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



        $newParams = [];
        foreach ($oldparameteredit as $key => $value) {
            if (!array_key_exists('id', $value)) {
                $newParams[] = $value;
            }
        }

        try {
            DB::beginTransaction();

            $id = $this->sample;
            $trackSampel = TrackSampel::find($id);

            $progress_now =  ProgressPengerjaan::where('id', $this->get_progress)->first()->nama;
            // Update the existing TrackSampel model
            $trackSampel->jenis_sampel = $this->jenis_sampel;
            $trackSampel->tanggal_penerimaan = $this->tanggal;
            $trackSampel->progress = $this->get_progress;

            $trackSampel->status = $this->selected_status;
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
            $trackSampel->emailTo = $this->emailTo;
            $trackSampel->emailCc = $this->emailCc;
            $trackSampel->last_update = $newupdate;
            $trackSampel->save();

            TrackParameter::whereIn('id', $idold)->delete();

            foreach ($oldparameteredit as $key => $value) {
                if (array_key_exists('id', $value)) {
                    TrackParameter::where('id', $value['id'])->update([
                        'jumlah' => $value['jumlah'],
                        'totalakhir' => $value['total'],
                        'id_tracksampel' => $trackid,
                        'personel' => $value['personel'] == True ? 1 : 0,
                        'alat' => $value['alat'] == True ? 1 : 0,
                        'bahan' => $value['bahan'] == True ? 1 : 0,
                        'id_parameter' => $value['id_parameter'],
                    ]);
                }
            }

            if ($newParams != []) {
                $trackParameters = [];

                foreach ($newParams as $key => $value) {
                    $trackParameters[] = [
                        'jumlah' => $value['jumlah'],
                        'totalakhir' => $value['total'],
                        'id_tracksampel' => $trackid,
                        'personel' => $value['personel'] == True ? 1 : 0,
                        'alat' => $value['alat'] == True ? 1 : 0,
                        'bahan' => $value['bahan'] == True ? 1 : 0,
                        'id_parameter' => $value['id_parameter'],
                    ];
                }

                TrackParameter::insert($trackParameters);
            }


            $form_hp = $this->no_hp;

            if (strlen($form_hp) === 10 && strpos($form_hp, '08') === 0) {
                $form_hp = '62' . substr($form_hp, 1);
            }

            // SendMsg::insert([
            //     'pesan' => 'Halo Tracking sample anda sudah di update ke ' . $progress_now .  ', progress anda dapat dilihat di website: https://smartlab.srs-ssms.com dengan kode Tracking sample:',
            //     'kodesample' => $query->kode_track,
            //     'penerima' => $form_hp
            // ]);


            DB::commit();

            $this->successSubmit = true;
            $this->msgSuccess = $query->kode_track;
            $this->selected_status = $this->selected_status;
            $this->badge_color_status = $this->selected_status === 'Approved' ? 'bg-emerald-600' : ($this->selected_status === 'Rejected' ? 'bg-red-600' : ($this->selected_status === 'Pending' ? 'bg-yellow-500' : ''));
        } catch (Exception $e) {
            DB::rollBack();
            $this->msgError = 'An error occurred while saving the data: ' . $e->getMessage();
            $this->errorSubmit = true;
        }
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

        return Excel::download(new MinotoringExport($id), 'Data_Lab.xlsx');
    }

    // public function export()
    // {
    //     $this->isExporting = true; // Set the flag to true when exporting Excel
    //     $id = $this->sample;

    //     // return Excel::download(new FormDataExport($id), 'Data_Lab.xlsx');
    //     return Excel::download(new MinotoringExport, 'invoices.xlsx', true, ['X-Vapor-Base64-Encode' => 'True']);
    // }
}
