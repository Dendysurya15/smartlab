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
    private $tanggal_penerimaan;
    private $generateData;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        // Generate data once and cache it
        $this->generateData = GeneratePR($this->id);

        // Fix: Use correct key from GeneratePR function
        $this->tanggal_penerimaan = $this->generateData['tanggal_terima'] ?? null;

        return view('excelView.prexcel', [
            'data' => $this->generateData['result'] ?? [],
            'tanggal_terima' => $this->tanggal_penerimaan
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Define common border style
                $borderStyle = [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['rgb' => '808080']
                    ],
                ];

                // Header style with center alignment
                $styleHeader = [
                    'borders' => $borderStyle,
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];

                // Apply styles efficiently
                $event->sheet->getStyle('A6:R6')->applyFromArray($styleHeader);
                $event->sheet->getStyle('N7')->applyFromArray($styleHeader);

                // Auto-fit columns for better readability
                foreach (range('A', 'R') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    public function columnWidths(): array
    {
        // Optimized column widths for better readability
        return [
            'A' => 8,   // NO
            'B' => 15,  // tanggal sample
            'C' => 10,  // Sampel
            'D' => 15,  // Jenis Sampel
            'E' => 12,  // Asal Sampel
            'F' => 10,  // No Kupa
            'G' => 12,  // No Lab
            'H' => 20,  // Nama Pengirim
            'I' => 15,  // Departemen
            'J' => 15,  // No Surat
            'K' => 20,  // Parameter Analisa
            'L' => 10,  // Tujuan
            'M' => 25,  // Kode Sampel
            'N' => 15,  // Estimasi KUPA
            'O' => 18,  // Tanggal Selesai Analisa
            'P' => 12,  // Kode Tracking
            'Q' => 18,  // Tanggal Rilis Sertifikat
            'R' => 15,  // No. Sertifikat
        ];
    }

    public function drawings()
    {
        $drawings = [];

        // Only create drawing if we have tanggal_penerimaan
        if ($this->tanggal_penerimaan) {
            $drawing1 = new Drawing();
            $drawing1->setName('Company Logo');
            $drawing1->setDescription('Company logo for export');

            // Use optimized logo selection
            $logoPath = defaultIconPT($this->tanggal_penerimaan)
                ? public_path('images/Logo_CBI_2.png')
                : public_path('images/logocorp.png');

            // Check if logo file exists before setting
            if (file_exists($logoPath)) {
                $drawing1->setPath($logoPath);
                $drawing1->setHeight(30);
                $drawing1->setCoordinates('B3');
                $drawings[] = $drawing1;
            }
        }

        return $drawings;
    }
}
