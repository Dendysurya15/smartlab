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
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class MinotoringExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
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
        $tanggal_penerimaan = Carbon::parse($tracksample['tanggal_penerimaan']);


        $trackparam = $tracksample->trackParameters;
        // dd($trackparam);
        $val_track = 0;
        foreach ($trackparam as $key => $value) {
            $val_track += $value['jumlah'];
            $id[] = $value['id'];
        }
        // dd($id);
        $trackParameters = TrackParameter::with('ParameterAnalisis')->whereIn('id', $id)->get();

        $nama_parameter = [];

        foreach ($trackParameters as $trackParameter) {
            if ($trackParameter->ParameterAnalisis) {
                $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                $hargaasli[] = $trackParameter->ParameterAnalisis->harga;
                $hargappn[] = intval(round($trackParameter->ParameterAnalisis->harga * 1.11, 0)); // Convert to integer
            }
        }

        // dd($tracksample);

        $row = count($nama_parameter);

        $arr = array();
        for ($i = 0; $i < $row; $i++) {
            $temp = array(); // Create a temporary array for each row
            $temp['no'] =  $i === 0 ? '1' : ' ';
            $temp['tgl_trma'] =  $i === 0 ? $tanggal_penerimaan->format('Y-m-d') : ' ';
            $temp['jenis_sample'] =  $i === 0 ? $tracksample->jenisSampel->nama : ' ';
            $temp['asal_sampel'] = $i === 0 ? $tracksample->asal_sampel : ' ';
            $temp['memo_pengantar'] = $i === 0 ? '-' : ' ';
            $temp['nama_pengirim'] = $i === 0 ? $tracksample->nama_pengirim : ' ';
            $temp['departemen'] = $i === 0 ? $tracksample->departemen : ' ';
            $temp['nomor_kupa'] = $i === 0 ? $tracksample->nomor_kupa : ' ';
            $temp['kode_sampel'] = $i === 0 ? $tracksample->kode_sampel : ' ';
            $temp['jumlah_parameter'] = $i === 0 ?  $val_track : ' ';
            $temp['jumlah_sampel'] = $i === 0 ?  $tracksample->jumlah_sampel : ' ';
            $temp['parameter_anal'] =   $nama_parameter[$i]; // Access specific element based on index
            $temp['harga_normal'] =   $hargaasli[$i]; // Access specific element based on index
            $temp['harga_ppn'] =   $hargappn[$i]; // Access specific element based on index
            $temp['estimasi'] = $i === 0 ? $tracksample->estimasi : ' ';
            $temp['tanggal_serif'] = $i === 0 ?  '-' : ' ';
            $temp['no_serif'] = $i === 0 ? '-' : ' ';
            $temp['tanggal_kirimserif'] = $i === 0 ? '-x' : ' ';

            $arr[] = $temp; // Add the row to the main array
        }



        // dd($arr, $tracksample);

        return view('excelView.monotoringexcel', ['data' => $arr]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $styleHeader = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ],
                    'alignment' => [
                        'wrapText' => true,
                    ],
                ];
                $arrcells = [
                    'B11', 'C11', 'D11', 'E11', 'F11', 'G11', 'H11', 'I11',
                    'J11',
                    'K11',
                    'L11',
                    'M11',
                    'N11',
                    'O11',
                    'P11',
                    'Q11',
                    'E12',
                    'F12',
                    'G12',
                    'R11',
                ];

                // dd($arrcells);
                foreach ($arrcells as $key => $value) {
                    $event->sheet->getStyle($value)->applyFromArray($styleHeader);
                }
            },
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 10,
            'C' => 8,
            'D' => 15,
            'E' => 15,
            'F' => 10,
            'G' => 10,
            'H' => 8,
            'I' => 15,
            'J' => 10,
            'K' => 10,
            'L' => 20,
            'M' => 10,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('images/Logo_CBI_2.png'));
        $drawing->setHeight(70);
        $drawing->setWidth(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
