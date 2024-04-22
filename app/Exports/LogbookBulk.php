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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use OpenSpout\Common\Entity\Style\Border;
use Cknow\Money\Money;

class LogbookBulk implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
{

    private $id;


    public function __construct($id)
    {
        $this->id = $id;
        // dd($id);
    }

    public function view(): View
    {
        $queries = TrackSampel::where('id', $this->id)->with('trackParameters')->with('progressSampel')->with('jenisSampel')->first();
        $inc = 1;
        $tanggal_terima = Carbon::parse($queries->tanggal_terima);
        $tanggal_memo = Carbon::parse($queries->tanggal_memo);
        $estimasi = Carbon::parse($queries->estimasi);
        $trackparam = $queries->trackParameters;

        foreach ($trackparam as $trackParameter) {

            if ($trackParameter->ParameterAnalisis) {
                $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                $hargaasli[] =  Money::IDR($trackParameter->ParameterAnalisis->harga, true);
                $harga_total_per_sampel[] = Money::IDR($trackParameter->totalakhir, true);
                $jumlah_per_parameter[] = $trackParameter->jumlah;
            }
        }

        // dd($queries);
        $result[] = [
            'col' => ' ',
            'id' => $inc++,
            'tanggalterima' => $tanggal_terima->format('Y-m-d'),
            'jenis_sample' => $queries->jenisSampel->nama,
            'asal_sampel' => $queries->asal_sampel,
            'pelanggan' => $queries->nama_pengirim,
            'nomor_kupa' => $queries->nomor_kupa,
            'Parameter' => $nama_parameter,
            'nomor_lab' => $queries->nomor_lab,
        ];

        // dd($result);
        return view('excelView.logbookbulk', ['data' => $result]);
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
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];
                $styletotal = [
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => 'thick', // Corrected
                            'color' => [
                                'rgb' => '808080'
                            ]
                        ],
                        'top' => [
                            'borderStyle' => 'thick', // Corrected
                            'color' => [
                                'rgb' => '808080'
                            ]
                        ]
                    ],
                ];
                $arrtotal = [
                    'L19',
                    'L20',
                    'L21',
                    'M19',
                    'M20',
                    'M21',
                ];
                $arrcells = [
                    'B11', 'C11', 'D11', 'E11', 'F11', 'G11', 'H12', 'I11',
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
                    'S11',
                    'D1',
                    'D2',
                    'D3',
                    'D4'
                ];

                foreach ($arrcells as $key => $value) {
                    $event->sheet->getStyle($value)->applyFromArray($styleHeader);
                }
                // foreach ($arrtotal as $key => $value) {
                //     $event->sheet->getStyle($value)->applyFromArray($styleHeader);
                // }
            },
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 6,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10,
            'Q' => 10,
            'R' => 10,
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
        $drawing->setCoordinates('B1');

        return $drawing;
    }
}
