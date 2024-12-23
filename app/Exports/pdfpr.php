<?php

namespace App\Exports;

use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Cknow\Money\Money;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class pdfpr implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
        // dd($id);
    }

    public function view(): View
    {
        $generate = GeneratePR($this->id);

        // dd($generate['result']);
        return view('excelView.prexcel', [
            'data' => $generate['result'],
        ]);
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
                $event->sheet->getStyle('A6:R6')->applyFromArray($styleHeader);
                $event->sheet->getStyle('N7')->applyFromArray($styleHeader);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'Q' => 10,
            'D' => 12,
            'L' => 12,
            'M' => 12,
            'R' => 10,
            'N' => 12,
            'E' => 8
        ];
    }


    public function drawings()
    {


        $drawings = [];


        // First Image
        $drawing1 = new Drawing();
        $drawing1->setName('Logo1');
        $drawing1->setDescription('This is my first logo');
        $drawing1->setPath(public_path('images/Logo_CBI_2.png'));
        $drawing1->setHeight(70);
        $drawing1->setCoordinates('B2');
        $drawings[] = $drawing1;


        return $drawings;
    }
}
