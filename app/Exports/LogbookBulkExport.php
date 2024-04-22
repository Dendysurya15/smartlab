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
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LogbookBulkExport implements FromView, ShouldAutoSize, WithEvents, WithDrawings, WithMultipleSheets, WithTitle, WithColumnWidths
{
    private $id;


    public function __construct($id)
    {
        $this->id = $id;
        // dd($id);
    }


    public function view(): View
    {

        // dd($this->id);
        $idsArray = explode('$', $this->id);
        $queries = TrackSampel::whereIn('id', $idsArray)->with('trackParameters')->with('progressSampel')->with('jenisSampel')->get();

        // dd($queries);
        $result = [];
        $inc = 1;
        foreach ($queries as $key => $value) {
            // dd($value);
            $tanggal_terima = Carbon::parse($value->tanggal_terima);
            $trackparam = $value->trackParameters;

            $nama_parameter = [];
            $hargatotal = 0;
            $jumlah_per_parametertotal = 0;
            $hargaasli = [];
            $harga_total_per_sampel = [];
            $jumlah_per_parameter = [];
            foreach ($trackparam as $trackParameter) {

                if ($trackParameter->ParameterAnalisis) {
                    $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                    $hargaasli[] =  Money::IDR($trackParameter->ParameterAnalisis->harga, true);
                    $harga_total_per_sampel[] = Money::IDR($trackParameter->totalakhir, true);
                    $jumlah_per_parameter[] = $trackParameter->jumlah;
                }
                $hargatotal += $trackParameter->totalakhir;
                $jumlah_per_parametertotal += $trackParameter->jumlah;
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

            $timestamp = strtotime($value->tanggal_terima);
            $year = date('Y', $timestamp);
            $lab =  substr($year, 2) . '-' . $value->jenisSampel->kode;
            $Nomorlab = explode('$', $value->nomor_lab);
            $Nomorlab = array_filter($Nomorlab, function ($value) {
                return $value !== "-";
            });
            $countlab = count($Nomorlab);
            $timestamp = strtotime($value->tanggal_terima);
            $tanggal_terima = date('Y-m-d', $timestamp);
            $timestamp2 = strtotime($value->tanggal_penyelesaian);
            $tanggal_penyelesaian = date('Y-m-d', $timestamp);
            if ($countlab > 1) {
                $totalsum_lab = $Nomorlab[1] - $Nomorlab[0];
                for ($i = 0; $i <= $totalsum_lab; $i++) {
                    $newlab_array[$i] = $lab . ($Nomorlab[0] + $i);
                    $no_col[$i] = $i + 1;

                    $result[$i] = [
                        'col' => ' ',
                        'id' => $i + 1,
                        'nomor_lab' => $lab . ($Nomorlab[0] + $i),
                        'mark' => count($newArray),
                        'notmark' => $total_namaparams,
                        'jumlah_sampel' => $value->jumlah_sampel,
                        'tanggal_terima' => $tanggal_terima,
                        'kondisi_sampel' => $value->kondisi_sampel,
                        'tanggal_penyelesaian' => $tanggal_penyelesaian,
                        'no_order' => $value->id,
                        'jenis_sampel' => $value->jenisSampel->nama
                    ];
                }
            } else {
                $totalsum_lab = 1;
                $newlab_array = $lab . $Nomorlab[0];
                $no_col[0] =  1;

                $result[0] = [
                    'col' => ' ',
                    'id' => 1,
                    'nomor_lab' => $lab . $Nomorlab[0],
                    'mark' => count($newArray),
                    'notmark' => $total_namaparams,
                    'jumlah_sampel' => $value->jumlah_sampel,
                    'tanggal_terima' => $tanggal_terima,
                    'kondisi_sampel' => $value->kondisi_sampel,
                    'tanggal_penyelesaian' => $tanggal_penyelesaian,
                    'no_order' => $value->id,
                    'jenis_sampel' => $value->jenisSampel->nama
                ];
            }
            // dd($result);
        }
        // dd($result);


        return view('excelView.logbookbulk', ['data' => $result, 'namaparams' => $newArray, 'total_namaparams' => $total_namaparams]);
    }


    public function sheets(): array
    {
        $idsArray = explode('$', $this->id);
        $sheets = [];

        foreach ($idsArray as $id) {
            $sheets[] = new LogbookBulkExport($id);
        }

        // dd($sheets);

        return $sheets;
    }

    public function title(): string
    {
        return 'ID ' . $this->id;
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
                $styleHeader2 = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ];
                // $event->sheet->getStyle('B15:S15')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('B14:S14')->applyFromArray($styleHeader2);
                $event->sheet->getStyle('D13:P13')->applyFromArray($styleHeader);

                $event->sheet->getStyle('Q12:S12')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('Q12:S12')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('K17')->applyFromArray($styleHeader2);
                // $event->sheet->getStyle('B13:W100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 6,
            'C' => 10,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 15,
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
