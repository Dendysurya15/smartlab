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

        $val_track = 0;
        foreach ($trackparam as $key => $value) {
            $val_track += $value['jumlah'];
        }
        $arr = array();

        $arr['tgl_trma'] = $tanggal_penerimaan->format('Y-m-d');
        $arr['jenis_sample'] = $tracksample->jenisSampel->nama;
        $arr['asal_sampel'] = $tracksample->asal_sampel;
        $arr['memo_pengantar'] = '-';
        $arr['nama_pengirim'] =  $tracksample->nama_pengirim;
        $arr['departemen'] =  $tracksample->departemen;
        $arr['nomor_kupa'] =  $tracksample->nomor_kupa;
        $arr['kode_sampel'] =  $tracksample->kode_sampel;
        $arr['jumlah_parameter'] =   $val_track;
        $arr['jumlah_sampel'] =   $tracksample->jumlah_sampel;

        dd($arr, $tracksample);

        return view('excelView.monotoringexcel', []);
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
                    'Q11'
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
            'C' => 15,
            'D' => 10,
            'E' => 15,
            'F' => 10,
            'G' => 10,
            'H' => 5,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 10,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
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
