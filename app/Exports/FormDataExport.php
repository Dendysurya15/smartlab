<?php

namespace App\Exports;

use App\Models\TrackSampel;
use App\Models\TrackParameter;
use App\Models\MetodeAnalisis;
use App\Models\JenisSampel;
use App\Models\ParameterAnalisis;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FormDataExport implements FromView, ShouldAutoSize, WithDrawings
{

    private $id;


    public function __construct($id)
    {
        $this->id = $id;
    }


    public function view(): View
    {

        $tracksample = TrackSampel::findOrFail($this->id);

        $getTrack = TrackParameter::where('id_tracksampel', $tracksample->parameter_analisisid)->get()->toArray();
        $getAnalisis = MetodeAnalisis::all()->toArray();
        $getparameters = ParameterAnalisis::all()->toArray();

        $trackform = [];
        foreach ($getTrack as $key => $value) {
            $parameters = [];
            $ppn = 0;
            foreach ($getAnalisis as $key2 => $value2) {
                if ($value2['id_parameter'] == $value['id_parameter']) {
                    $parameters['parameter'][] = $value2['nama'];
                    $parameters['hargaori'] = $value2['harga'];
                    $parameters['jumlah_sampel'] = $value['jumlah'];
                    $parameters['subtotal'] = $value['jumlah'] * $value2['harga'];
                    $ppn = hitungPPN($value['jumlah'] * $value2['harga']);
                    $parameters['ppn'] = $ppn;
                    $parameters['total'] = $ppn + $value['jumlah'] * $value2['harga'];
                }
            }


            $trackform[] = $parameters;
            // $trackform[] = $harga;
        }


        // dd($trackform);

        $getanalis = [];
        foreach ($getparameters as $keyx => $valuex) {
            if ($tracksample->jenis_sampel == $valuex['id_jenis_sampel']) {
                $getanalis[] = $valuex['nama'];
            }
        }



        $exportData = [];

        // dd($trackform, $getanalis);

        $isFirstRow = true;
        foreach ($trackform as $row) {

            $personel = "-";
            if ($tracksample->personel == 1) {
                $personel = "YA";
            } else {
                $personel = "Tidak";
            }
            $alat = "-";
            if ($tracksample->alat == 1) {
                $alat = "YA";
            } else {
                $alat = "Tidak";
            }
            $bahan = "-";
            if ($tracksample->bahan == 1) {
                $bahan = "YA";
            } else {
                $bahan = "Tidak";
            }

            $normal = "-";
            $taknormal = "-";
            if ($tracksample->kondisi_sampel == "Normal") {
                $normal = "YA";
                $taknormal = "";
            } else {
                $normal = "";
                $taknormal = "YA";
            }

            $rowData = [
                'no_surat' => $isFirstRow ? $tracksample->nomor_surat : '',
                'kondisi_sampel' => $isFirstRow ? $tracksample->kondisi_sampel : '',
                'jumlah_sampel' => $isFirstRow ? $tracksample->jumlah_sampel : '',
                'nomor_lab' => $isFirstRow ? $tracksample->nomor_lab : '',
                'parameter_analisis' => $row,
                'metode_analisis' => $isFirstRow ? $getanalis : '',
                'personel' => $isFirstRow ? $personel : '',
                'alat' => $isFirstRow ? $alat : '',
                'bahan' => $isFirstRow ? $bahan : '',
                'email' => $isFirstRow ? $tracksample->email : '',
                'estimasi' => $isFirstRow ? $tracksample->estimasi : '',
                'normal' => $isFirstRow ? $normal : '',
                'taknormal' => $isFirstRow ? $taknormal : '',
            ];

            $exportData[] = $rowData;

            $isFirstRow = false; // Update to false after the first row
        }

        // dd($exportData);
        $no_dokumen = "TLM/87/-B3C";
        $tanggalterima = $tracksample->tanggal_penerimaan;
        $pelanggan = $tracksample->nama_pengirim;
        $jenis_sample = JenisSampel::where('id', $tracksample->jenis_sampel)->pluck('nama')->first();

        // Removing square brackets and double quotes
        $jenis_sample = str_replace(['["', '"]'], '', $jenis_sample);

        // dd($jenis_sample);
        return view('excelView.exportotexcel', [
            'trackdata' => $exportData,
            'tanggal' => $tanggalterima,
            'jenissample' => $jenis_sample,
            'pelanggan' => $pelanggan,
            'no_dokumen' => $no_dokumen,
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('images/suratkop.png'));
        $drawing->setHeight(140);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
