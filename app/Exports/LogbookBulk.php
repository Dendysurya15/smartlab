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
use Maatwebsite\Excel\Concerns\WithTitle;

class LogbookBulk implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings, WithTitle
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
        $newArray = [];
        foreach ($nama_parameter as $item) {
            if (strpos($item, ',') !== false) {
                $explodedItems = array_map('trim', explode(',', $item));
                $newArray = array_merge($newArray, $explodedItems);
            } else {
                $newArray[] = $item;
            }
        }
        $total_namaparams = 13 - count($newArray);
        // dd($newArray);

        $timestamp = strtotime($queries->tanggal_terima);
        $year = date('Y', $timestamp);
        $lab =  substr($year, 2) . '-' . $queries->jenisSampel->kode;
        $Nomorlab = explode('$', $queries->nomor_lab);
        $Nomorlab = array_filter($Nomorlab, function ($value) {
            return $value !== "-";
        });
        $countlab = count($Nomorlab);
        $timestamp = strtotime($queries->tanggal_terima);
        $tanggal_terima = date('Y-m-d', $timestamp);
        $timestamp2 = strtotime($queries->tanggal_penyelesaian);
        $tanggal_penyelesaian = date('Y-m-d', $timestamp);
        // dd($queries);
        $result[0] = [
            'col' => ' ',
            'id' => 1,
            'nomor_lab' => $lab . $Nomorlab[0],
            'mark' => count($newArray),
            'notmark' => $total_namaparams,
            'jumlah_sampel' => $queries->jumlah_sampel,
            'tanggal_terima' => $tanggal_terima,
            'kondisi_sampel' => $queries->kondisi_sampel,
            'tanggal_penyelesaian' => $tanggal_penyelesaian,
            'no_order' => $queries->id,
            'jenis_sampel' => $queries->jenisSampel->nama
        ];

        // dd($result);
        return view('excelView.logbookbulk', ['data' => $result, 'namaparams' => $newArray, 'total_namaparams' => $total_namaparams]);
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


    public function title(): string
    {
        return 'ID ' . $this->id;
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
