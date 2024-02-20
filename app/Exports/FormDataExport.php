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

    private $countnamaarr; // Class property to store countnamaarr value

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

        $tgl_penerimaan = tanggal_indo($tracksample->tanggal_terima, false, false, true);
        $tgl_penyelesaian = tanggal_indo($tracksample->tanggal_pengantaran, false, false, true);
        $jenis_kupa = $jenis_sample->nama;
        $no_kupa = $tracksample->nomor_kupa;
        $nama_pengirim = $tracksample->nama_pengirim;

        $track = $jenis_sample->parameterAnalisis->toArray();


        // dd($track);

        $arr_per_column = [];

        $arr_per_column[0]['col_no_surat'] = $tracksample->nomor_surat;
        $arr_per_column[0]['col_kemasan'] = $tracksample->kemasan_sampel;
        $arr_per_column[0]['col_jum_sampel'] = $tracksample->jumlah_sampel;
        $arr_per_column[0]['col_no_lab'] = $tracksample->nomor_lab;
        $arr_per_column[0]['col_param'] = $array_param_analisis_excel[0];
        $arr_per_column[0]['col_mark'] = '';
        $arr_per_column[0]['col_metode'] = $row_metode[0];
        $arr_per_column[0]['col_satuan'] = $row_satuan[0];
        $arr_per_column[0]['col_personel'] = '';
        $arr_per_column[0]['col_alat'] = '';
        $arr_per_column[0]['col_bahan'] = '';
        $arr_per_column[0]['col_jum_sampel_2'] = '';
        $arr_per_column[0]['col_harga'] = '';
        $arr_per_column[0]['col_sub_total'] = '';
        $arr_per_column[0]['col_ppn'] = '';
        $arr_per_column[0]['col_total'] = '';
        $arr_per_column[0]['col_langsung'] = '';
        $arr_per_column[0]['col_normal'] = '';
        $arr_per_column[0]['col_abnormal'] = '';
        $arr_per_column[0]['col_tanggal'] = '-';

        $sub_total_parameter = 0;
        $final_total = 0;

        if (!is_null($tracksample->parameter_analisisid) && $tracksample->parameter_analisisid !== 0 && $tracksample->parameter_analisisid != '0') {

            $getTrack = TrackParameter::with('ParameterAnalisis')->where('id_tracksampel', $tracksample->parameter_analisisid)->get();
            $inputan_parameter = [];
            $inc = 0;


            foreach ($getTrack as $key => $value) {
                $inputan_parameter[$inc]['nama'] = $value->ParameterAnalisis->nama_parameter;
                $inputan_parameter[$inc]['alias'] = $value->ParameterAnalisis->nama_unsur;
                $inputan_parameter[$inc]['satuan'] = $value->ParameterAnalisis->satuan;
                $inputan_parameter[$inc]['metode'] = $value->ParameterAnalisis->metode_analisis;
                $inputan_parameter[$inc]['personel'] = $value->personel;
                $inputan_parameter[$inc]['alat'] = $value->alat;
                $inputan_parameter[$inc]['bahan'] = $value->bahan;
                $inputan_parameter[$inc]['harga_per_satuan'] = $value->ParameterAnalisis->harga;
                $inputan_parameter[$inc]['jumlah'] = $value->jumlah;
                $inputan_parameter[$inc]['total_per_parameter'] = $value->totalakhir;


                $sub_total_parameter += $value->totalakhir;
                $inc++;
            }

            // $match_parameter = [];

            // foreach ($array_param_analisis_excel as $key => $item) {
            //     // Check if the value exists in the "nama" or "nama_unsur" key of the second array
            //     $foundItem = array_filter($inputan_parameter, function ($secondItem) use ($item) {
            //         return $secondItem['nama'] === $item || $secondItem['alias'] === $item;
            //     });

            //     if (!empty($foundItem)) {
            //         $match_parameter[$key] = reset($foundItem);
            //     }
            // }


            $row_count = count($inputan_parameter);


            // $subtotal  = 0;
            // $total = 0;
            // foreach ($match_parameter as $key => $value) {
            //     $subtotal  = $value['jumlah'] * $value['harga'];
            //     $ppn = hitungPPN($subtotal);
            //     $total = $subtotal + $ppn;
            //     $final_total += $total;
            //     $match_parameter[$key]['ppn'] = $ppn;
            //     $match_parameter[$key]['subtotal'] = $subtotal;
            //     $match_parameter[$key]['total'] = $total;
            // }
            $newInputanParameters = [];

            foreach ($inputan_parameter as $key => $value) {
                $nama = $value['nama'];

                // Check if the nama contains a comma
                if (strpos($nama, ',') !== false) {
                    // Split the nama by comma
                    $namaArray = explode(',', $nama);

                    // dd($namaArray, $track);

                    $countnamaarr = count($namaArray);
                    // Iterate through the split nama and create new arrays
                    foreach ($namaArray as $index => $namaItem) {
                        foreach ($track as $key => $value1) {
                            // Compare $namaItem with "nama_unsur" instead of comparing with index
                            if ($value1['nama_unsur'] === trim($namaItem)) {
                                $newInputanParameters[] = [
                                    "nama" => trim($namaItem),
                                    "alias" => ($index === 0 ?  $value["alias"] : ''),
                                    "satuan" => $value1["satuan"],
                                    "metode" => $value1["metode_analisis"],
                                    "personel" => ($index === 0 ? $value["personel"] : ''),
                                    "alat" => ($index === 0 ? $value["alat"] : ''),
                                    "bahan" => ($index === 0 ? $value["bahan"] : ''),
                                    "harga_per_satuan" => ($index === 0 ? $value["harga_per_satuan"] : ''),
                                    "jumlah" => ($index === 0 ?  $value["jumlah"] : ''),
                                    "total_per_parameter" => ($index === 0 ? $value["total_per_parameter"] : '')
                                ];
                            }
                        }
                    }
                } else {
                    // If no comma, simply add the original array
                    $newInputanParameters[] = $value;
                }
            }
            // dd($newInputanParameters);
            $row_count = count($newInputanParameters);




            for ($i = 0; $i < $row_count; $i++) {

                if ($i == 0) {

                    if (array_key_exists($i, $newInputanParameters) && $i === $i) {
                        $arr_per_column[$i]['col_mark'] = 1;
                        $arr_per_column[$i]['col_param'] = $newInputanParameters[$i]['nama'];
                        $arr_per_column[$i]['col_harga'] = $newInputanParameters[$i]['harga_per_satuan'];
                        $arr_per_column[$i]['col_satuan'] = $newInputanParameters[$i]['satuan'];
                        $arr_per_column[$i]['col_metode'] = $newInputanParameters[$i]['metode'];
                        $arr_per_column[$i]['col_personel'] = $newInputanParameters[$i]['personel'];
                        $arr_per_column[$i]['col_alat'] = $newInputanParameters[$i]['alat'];
                        $arr_per_column[$i]['col_bahan'] = $newInputanParameters[$i]['bahan'];
                        $arr_per_column[$i]['col_jum_sampel_2'] = $newInputanParameters[$i]['jumlah'];
                        $arr_per_column[$i]['col_sub_total'] = $newInputanParameters[$i]['total_per_parameter'];
                        $arr_per_column[$i]['col_ppn'] = '';
                        $arr_per_column[$i]['col_total'] = '';
                    }
                } else {
                    if (array_key_exists($i, $newInputanParameters) && $i === $i) {
                        $arr_per_column[$i]['col_mark'] = 1;
                        $arr_per_column[$i]['col_param'] = $newInputanParameters[$i]['nama'];
                        $arr_per_column[$i]['col_harga'] = $newInputanParameters[$i]['harga_per_satuan'];
                        $arr_per_column[$i]['col_satuan'] = $newInputanParameters[$i]['satuan'];
                        $arr_per_column[$i]['col_metode'] = $newInputanParameters[$i]['metode'];
                        $arr_per_column[$i]['col_personel'] = $newInputanParameters[$i]['personel'];
                        $arr_per_column[$i]['col_alat'] = $newInputanParameters[$i]['alat'];
                        $arr_per_column[$i]['col_bahan'] = $newInputanParameters[$i]['bahan'];
                        $arr_per_column[$i]['col_jum_sampel_2'] = $newInputanParameters[$i]['jumlah'];
                        $arr_per_column[$i]['col_sub_total'] = $newInputanParameters[$i]['total_per_parameter'];
                        $arr_per_column[$i]['col_ppn'] = '';
                        $arr_per_column[$i]['col_total'] = '';
                    }
                    $arr_per_column[$i]['col_no_surat'] = '';
                    $arr_per_column[$i]['col_kemasan'] = '';
                    $arr_per_column[$i]['col_jum_sampel'] = '';
                    $arr_per_column[$i]['col_no_lab'] = '';
                    $arr_per_column[$i]['col_langsung'] = '';
                    $arr_per_column[$i]['col_normal'] = '';
                    $arr_per_column[$i]['col_abnormal'] = '';
                    $arr_per_column[$i]['col_tanggal'] = '';
                }
            }
        }

        $this->countnamaarr = $countnamaarr ?? 0;

        // dd($arr_per_column);
        return view('excelView.exportexcel', [
            // 'trackdata' => $exportData,
            // 'tanggal' => $tanggalterima,
            // 'jenissample' => $jenis_sample,
            // 'pelanggan' => $pelanggan,
            'sub_total' => $sub_total_parameter,
            'ppn' => hitungPPN($sub_total_parameter),
            'final_total' => $sub_total_parameter + hitungPPN($sub_total_parameter),
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

                // $countnamaarr = 5;
                if ($this->countnamaarr != 0) {
                    $endRow = 13 + $this->countnamaarr; // Access countnamaarr from the class property

                    // dd($endRow);
                    // Merge cells
                    $event->sheet->mergeCells("O14:O$endRow");
                    $event->sheet->getStyle("O14:O$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                    $event->sheet->mergeCells("N14:N$endRow");
                    $event->sheet->getStyle("N14:N$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->mergeCells("J14:J$endRow");
                    $event->sheet->getStyle("J14:J$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("K14:K$endRow");
                    $event->sheet->getStyle("K14:K$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("L14:L$endRow");
                    $event->sheet->getStyle("L14:L$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("M14:M$endRow");
                    $event->sheet->getStyle("M14:M$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                }

                $columnsToStyle = ['B12', 'C12', 'D12', 'E12', 'B5', 'B6', 'D1', 'D2', 'D3', 'D4', 'F12',  'H12', 'I12', 'J13', 'K13', 'L13', 'M13', 'N13', 'O13', 'P13', 'Q13', 'R13', 'S12', 'T13', 'U12'];
                foreach ($columnsToStyle as $column) {
                    $event->sheet->getStyle($column)->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getStyle($column)->getAlignment()->setWrapText(true);
                }

                $event->sheet->getStyle('C12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('D12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('G')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('H')->getAlignment()->setWrapText(true);
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
            'S' => 30,
            // 'T' => 30

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
