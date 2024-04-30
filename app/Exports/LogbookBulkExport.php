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
            $namakode_sampel = explode('$', $value->kode_sampel);

            $nama_parameter = [];
            $hargatotal = 0;
            $jumlah_per_parametertotal = 0;
            $hargaasli = [];
            $harga_total_per_sampel = [];
            $jumlah_per_parameter = [];
            $namakode_sampelparams = [];
            foreach ($trackparam as $trackParameter) {

                if ($trackParameter->ParameterAnalisis) {
                    $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                    $hargaasli[] =  Money::IDR($trackParameter->ParameterAnalisis->harga, true);
                    $harga_total_per_sampel[] = Money::IDR($trackParameter->totalakhir, true);
                    $jumlah_per_parameter[] = $trackParameter->jumlah;
                    $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
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
            $sampel_data = [];
            // dd($namakode_sampelparams);
            $inc = 0;
            foreach ($namakode_sampelparams as $attribute => $items) {
                foreach ($items as $item) {
                    if (!isset($sampel_data[$item])) {
                        $sampel_data[$item] = [];
                    }

                    $explodedAttributes = strpos($attribute, ',') !== false ? explode(',', $attribute) : [$attribute];

                    // Merge the exploded attributes only if they are not already present in the array
                    foreach ($explodedAttributes as $attr) {
                        $trimmedAttr = trim($attr); // Trim the attribute to remove any leading or trailing spaces
                        if (!in_array($trimmedAttr, $sampel_data[$item])) {
                            $sampel_data[$item][] = $trimmedAttr;
                        }
                    }
                }
            }

            // dd($sampel_data);
            $total_namaparams = 13 - count($newArray);
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
            $tanggal_penyelesaian = date('Y-m-d', $timestamp);
            $inc = 0;
            $startingValue = $Nomorlab[0]; // Assuming $Nomorlab[0] is 22
            $data = count($namakode_sampel);
            // dd($namakode_sampel, $sampel_data);
            foreach ($namakode_sampel as $keyx => $valuex) {
                foreach ($sampel_data as $keyx2 => $valuex2) {
                    if ($valuex === $keyx2) {
                        $result[$valuex]['id'] = $inc++;
                        $result[$valuex]['nomor_lab'] = $lab .  ($startingValue + $inc - 1);

                        $result[$valuex]['jumlah_sampel'] = $value->jumlah_sampel;
                        $result[$valuex]['tanggal_terima'] = $tanggal_terima;
                        $result[$valuex]['kondisi_sampel'] = $value->kondisi_sampel;
                        $result[$valuex]['tanggal_penyelesaian'] = $tanggal_penyelesaian;
                        $result[$valuex]['no_order'] = $value->id;
                        $result[$valuex]['jenis_sampel'] = $value->jenisSampel->nama;
                        $result[$valuex]['parameter_sampel'] = $valuex2;
                    }
                }
            }

            // dd($result);
        }
        // dd($newArray, $result);


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
