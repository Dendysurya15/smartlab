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
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FormDataExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
{

    private $id;


    public function __construct($id)
    {
        $this->id = $id;
        // dd($id);
    }


    public function view(): View
    {

        $tracksample = TrackSampel::findOrFail($this->id);

        $jenis_sample = JenisSampel::with('parameterAnalisis')->where('id', $tracksample->jenis_sampel)->first();

        $parameter_analisis_excel = $jenis_sample->parameter_analisis;

        $array_param_analisis_excel = explode(',', $parameter_analisis_excel);

        $array_param_analisis_excel = array_map('trim', $array_param_analisis_excel);

        $row_metode = $jenis_sample->parameterAnalisis->pluck('metode_analisis');
        $row_satuan = $jenis_sample->parameterAnalisis->pluck('satuan');

        $tgl_penerimaan = tanggal_indo($tracksample->tanggal_penerimaan, false, false, true);
        $tgl_penyelesaian = tanggal_indo($tracksample->tanggal_pengantaran, false, false, true);
        $jenis_kupa = $jenis_sample->nama;
        $no_kupa = $tracksample->nomor_kupa;
        $nama_pengirim = $tracksample->nama_pengirim;

        $arr_per_column = [];

        $arr_per_column[0]['col_no_surat'] = $tracksample->nomor_surat;
        $arr_per_column[0]['col_kemasan'] = $tracksample->kemasan_sampel;
        $arr_per_column[0]['col_jum_sampel'] = $tracksample->jumlah_sampel;
        $arr_per_column[0]['col_no_lab'] = $tracksample->nomor_lab;
        $arr_per_column[0]['col_param'] = $array_param_analisis_excel[0];
        $arr_per_column[0]['col_mark'] = '';
        $arr_per_column[0]['col_metode'] = $row_metode[0];
        $arr_per_column[0]['col_satuan'] = $row_satuan[0];
        $arr_per_column[0]['col_personel'] = $tracksample->personel;
        $arr_per_column[0]['col_alat'] = $tracksample->alat;
        $arr_per_column[0]['col_bahan'] = $tracksample->bahan;
        $arr_per_column[0]['col_jum_sampel_2'] = '';
        $arr_per_column[0]['col_harga'] = '';
        $arr_per_column[0]['col_sub_total'] = '';
        $arr_per_column[0]['col_ppn'] = '';
        $arr_per_column[0]['col_total'] = '';
        $arr_per_column[0]['col_langsung'] = '';
        $arr_per_column[0]['col_normal'] = '';
        $arr_per_column[0]['col_abnormal'] = '';
        $arr_per_column[0]['col_tanggal'] = $tgl_penyelesaian;

        $final_total = 0;

        if (!is_null($tracksample->parameter_analisisid) && $tracksample->parameter_analisisid !== 0 && $tracksample->parameter_analisisid != '0') {

            $getTrack = TrackParameter::with('ParameterAnalisis')->where('id_tracksampel', $tracksample->parameter_analisisid)->get();



            $row = 0;
            $temp_param = [];
            $temp_metode = [];
            $temp_harga = [];
            $temp_subtotal = [];
            $temp_jumlah = [];
            $temp_satuan = [];
            $temp_ppn  = [];

            $inputan_parameter = [];
            $inc = 0;
            foreach ($getTrack as $key => $value) {
                $inputan_parameter[$inc]['nama'] = $value->ParameterAnalisis->nama_parameter;
                $inputan_parameter[$inc]['alias'] = $value->ParameterAnalisis->nama_unsur;
                $inputan_parameter[$inc]['satuan'] = $value->ParameterAnalisis->satuan;
                $inputan_parameter[$inc]['metode'] = $value->ParameterAnalisis->metode_analisis;
                $inputan_parameter[$inc]['harga'] = $value->ParameterAnalisis->harga;
                $inputan_parameter[$inc]['jumlah'] = $value->jumlah;

                $inc++;
            }

            $match_parameter = [];

            foreach ($array_param_analisis_excel as $key => $item) {
                // Check if the value exists in the "nama" or "nama_unsur" key of the second array
                $foundItem = array_filter($inputan_parameter, function ($secondItem) use ($item) {
                    return $secondItem['nama'] === $item || $secondItem['alias'] === $item;
                });

                if (!empty($foundItem)) {
                    $match_parameter[$key] = reset($foundItem);
                }
            }


            $row_count = count($array_param_analisis_excel);


            $subtotal  = 0;
            $total = 0;
            foreach ($match_parameter as $key => $value) {
                $subtotal  = $value['jumlah'] * $value['harga'];
                $ppn = hitungPPN($subtotal);
                $total = $subtotal + $ppn;
                $final_total += $total;
                $match_parameter[$key]['ppn'] = $ppn;
                $match_parameter[$key]['subtotal'] = $subtotal;
                $match_parameter[$key]['total'] = $total;
            }

            for ($i = 0; $i < $row_count; $i++) {

                if ($i == 0) {

                    if (array_key_exists($i, $match_parameter) && $i === $i) {
                        $arr_per_column[$i]['col_mark'] = 1;
                        $arr_per_column[$i]['col_harga'] = $match_parameter[$i]['harga'];
                        $arr_per_column[$i]['col_satuan'] = $match_parameter[$i]['satuan'];
                        $arr_per_column[$i]['col_metode'] = $match_parameter[$i]['metode'];
                        $arr_per_column[$i]['col_jum_sampel_2'] = $match_parameter[$i]['jumlah'];
                        $arr_per_column[$i]['col_sub_total'] = $match_parameter[$i]['subtotal'];
                        $arr_per_column[$i]['col_ppn'] = $match_parameter[$i]['ppn'];
                        $arr_per_column[$i]['col_total'] = $match_parameter[$i]['total'];
                    } else {
                        $arr_per_column[$i]['col_mark'] = '';
                        $arr_per_column[$i]['col_harga'] = '';
                        $arr_per_column[$i]['col_satuan'] = '';
                        $arr_per_column[$i]['col_metode'] = '';
                        $arr_per_column[$i]['col_jum_sampel_2'] = '';
                        $arr_per_column[$i]['col_sub_total'] = '';
                        $arr_per_column[$i]['col_ppn'] = '';
                        $arr_per_column[$i]['col_total'] = '';
                    }
                    $arr_per_column[$i]['col_param'] = $array_param_analisis_excel[$i];
                    $arr_per_column[$i]['col_personel'] = '';
                    $arr_per_column[$i]['col_alat'] = '';
                    $arr_per_column[$i]['col_bahan'] = '';
                } else {
                    if (array_key_exists($i, $match_parameter) && $i === $i) {
                        $arr_per_column[$i]['col_mark'] = 1;
                        $arr_per_column[$i]['col_harga'] = $match_parameter[$i]['harga'];
                        $arr_per_column[$i]['col_satuan'] = $match_parameter[$i]['satuan'];
                        $arr_per_column[$i]['col_metode'] = $match_parameter[$i]['metode'];
                        $arr_per_column[$i]['col_jum_sampel_2'] = $match_parameter[$i]['jumlah'];
                        $arr_per_column[$i]['col_sub_total'] = $match_parameter[$i]['subtotal'];
                        $arr_per_column[$i]['col_ppn'] = $match_parameter[$i]['ppn'];
                        $arr_per_column[$i]['col_total'] = $match_parameter[$i]['total'];
                    } else {
                        $arr_per_column[$i]['col_mark'] = '';
                        $arr_per_column[$i]['col_harga'] = '';
                        $arr_per_column[$i]['col_satuan'] = '';
                        $arr_per_column[$i]['col_metode'] = '';
                        $arr_per_column[$i]['col_jum_sampel_2'] = '';
                        $arr_per_column[$i]['col_sub_total'] = '';
                        $arr_per_column[$i]['col_ppn'] = '';
                        $arr_per_column[$i]['col_total'] = '';
                    }
                    $arr_per_column[$i]['col_no_surat'] = '';
                    $arr_per_column[$i]['col_kemasan'] = '';
                    $arr_per_column[$i]['col_jum_sampel'] = '';
                    $arr_per_column[$i]['col_no_lab'] = '';
                    $arr_per_column[$i]['col_param'] = $array_param_analisis_excel[$i];
                    $arr_per_column[$i]['col_personel'] = '';
                    $arr_per_column[$i]['col_alat'] = '';
                    $arr_per_column[$i]['col_bahan'] = '';
                    $arr_per_column[$i]['col_langsung'] = '';
                    $arr_per_column[$i]['col_normal'] = '';
                    $arr_per_column[$i]['col_abnormal'] = '';
                    $arr_per_column[$i]['col_tanggal'] = '';
                }
            }
        }




        // dd($arr_per_column);
        // $getAnalisis = MetodeAnalisis::all()->toArray();
        // $getparameters = ParameterAnalisis::all()->toArray();


        // $trackform = [];
        // foreach ($getTrack as $key => $value) {
        //     $parameters = [];
        //     $ppn = 0;
        //     foreach ($getAnalisis as $key2 => $value2) {
        //         if ($value2['id_parameter'] == $value['id_parameter']) {
        //             $parameters['parameter'][] = $value2['nama'];
        //             $parameters['hargaori'] = $value2['harga'];
        //             $parameters['jumlah_sampel'] = $value['jumlah'];
        //             $parameters['subtotal'] = $value['jumlah'] * $value2['harga'];
        //             $ppn = hitungPPN($value['jumlah'] * $value2['harga']);
        //             $parameters['ppn'] = $ppn;
        //             $parameters['total'] = $ppn + $value['jumlah'] * $value2['harga'];
        //         }
        //     }


        //     $trackform[] = $parameters;
        //     // $trackform[] = $harga;
        // }


        // dd($trackform);

        // $getanalis = [];
        // foreach ($getparameters as $keyx => $valuex) {
        //     if ($tracksample->jenis_sampel == $valuex['id_jenis_sampel']) {
        //         $getanalis[] = $valuex['nama'];
        //     }
        // }



        // $exportData = [];

        // // dd($trackform, $getanalis);

        // $isFirstRow = true;
        // foreach ($trackform as $row) {

        //     $personel = "-";
        //     if ($tracksample->personel == 1) {
        //         $personel = "YA";
        //     } else {
        //         $personel = "Tidak";
        //     }
        //     $alat = "-";
        //     if ($tracksample->alat == 1) {
        //         $alat = "YA";
        //     } else {
        //         $alat = "Tidak";
        //     }
        //     $bahan = "-";
        //     if ($tracksample->bahan == 1) {
        //         $bahan = "YA";
        //     } else {
        //         $bahan = "Tidak";
        //     }

        //     $normal = "-";
        //     $taknormal = "-";
        //     if ($tracksample->kondisi_sampel == "Normal") {
        //         $normal = "YA";
        //         $taknormal = "";
        //     } else {
        //         $normal = "";
        //         $taknormal = "YA";
        //     }

        //     $rowData = [
        //         'no_surat' => $isFirstRow ? $tracksample->nomor_surat : '',
        //         'kondisi_sampel' => $isFirstRow ? $tracksample->kondisi_sampel : '',
        //         'jumlah_sampel' => $isFirstRow ? $tracksample->jumlah_sampel : '',
        //         'nomor_lab' => $isFirstRow ? $tracksample->nomor_lab : '',
        //         'parameter_analisis' => $row,
        //         'metode_analisis' => $isFirstRow ? $getanalis : '',
        //         'personel' => $isFirstRow ? $personel : '',
        //         'alat' => $isFirstRow ? $alat : '',
        //         'bahan' => $isFirstRow ? $bahan : '',
        //         'email' => $isFirstRow ? $tracksample->email : '',
        //         'estimasi' => $isFirstRow ? $tracksample->estimasi : '',
        //         'normal' => $isFirstRow ? $normal : '',
        //         'taknormal' => $isFirstRow ? $taknormal : '',
        //     ];

        //     $exportData[] = $rowData;

        //     $isFirstRow = false; // Update to false after the first row
        // }

        // $no_dokumen = "TLM/87/-B3C";
        // $tanggalterima = $tracksample->tanggal_penerimaan;
        // $pelanggan = $tracksample->nama_pengirim;




        // Removing square brackets and double quotes
        // $jenis_sample = str_replace(['["', '"]'], '', $jenis_sample);




        // dd($tracksample);

        // dd($arr_per_column);
        return view('excelView.exportexcel', [
            // 'trackdata' => $exportData,
            // 'tanggal' => $tanggalterima,
            // 'jenissample' => $jenis_sample,
            // 'pelanggan' => $pelanggan,
            'final_total' => $final_total,
            'nama_pengirim' => $nama_pengirim,
            'no_kupa' => $no_kupa,
            'jenis_kupa' => $jenis_kupa,
            'tanggal_penerimaan' => $tgl_penerimaan,
            'kupa' => $arr_per_column
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $columnsToStyle = ['B12', 'C12', 'D12', 'E12', 'B5', 'B6', 'D1', 'D2', 'D3', 'D4', 'F12',  'H12', 'I12', 'J13', 'K13', 'L13', 'M13', 'N13', 'O13', 'P13', 'Q13', 'R13', 'S13', 'T13', 'U12'];
                foreach ($columnsToStyle as $column) {
                    $event->sheet->getStyle($column)->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getStyle($column)->getAlignment()->setWrapText(true);
                }

                $event->sheet->getStyle('C12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('D12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('G')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('E12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('I13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('J13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('K13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('L13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('M13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('Q13')->getAlignment()->setWrapText(true);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 15,
            'D' => 10,
            'E' => 15,
            'F' => 35,
            'G' => 5,
            'H' => 35,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 10,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 30

        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('images/Logo_CBI_2.png'));
        $drawing->setHeight(70);
        $drawing->setCoordinates('B1');

        return $drawing;
    }
}
