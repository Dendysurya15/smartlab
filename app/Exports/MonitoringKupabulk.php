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

class MonitoringKupabulk implements FromView, ShouldAutoSize, WithEvents, WithDrawings
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
            $tanggal_terima = Carbon::parse($value->tanggal_terima);
            $tanggal_memo = Carbon::parse($value->tanggal_memo);
            $estimasi = Carbon::parse($value->estimasi);
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
            $harga_total_dengan_ppn = Money::IDR(hitungPPN($hargatotal), true);
            $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($hargatotal, true));

            $discountDecimal = $value->discount != 0 ? $value->discount / 100 : 0;
            $discount = $totalppn_harga->multiply($discountDecimal);

            $total_akhir = $totalppn_harga->subtract($discount);
            // dd($totalppn_harga, $discountDecimal, $discount, $total_akhir);
            $result[] = [
                'col' => ' ',
                'id' => $inc++,
                'tanggalterima' => $tanggal_terima->format('Y-m-d'),
                'jenis_sample' => $value->jenisSampel->nama,
                'asal_sampel' => $value->asal_sampel,
                'memo_pengantar' => $tanggal_memo->format('Y-m-d'),
                'nama_pengirim' => $value->nama_pengirim,
                'departemen' => $value->departemen,
                'nomor_kupa' => $value->nomor_kupa,
                'kode_sampel' => $value->kode_sampel,
                'jumlah_parameter' => $jumlah_per_parametertotal,
                'jumlah_sampel' => $jumlah_per_parameter,
                'parameter_analisis' => $nama_parameter,
                'biaya_analisa' => $hargaasli,
                'sub_total_per_parameter' => $harga_total_per_sampel,
                'estimasi' => $estimasi->format('Y-m-d'),
                'tanggal_serif' => '-',
                'no_serif' => '-',
                'tanggal_kirim_sertif' => '-',
                'sub_total_akhir' => Money::IDR($hargatotal, true),
                'harga_total_dengan_ppn' => $harga_total_dengan_ppn,
                'diskon' => $discount,
                'total' => $total_akhir,
                'text_disc' => $value->discount,
                'formulir' => $value->formulir,
                'nodoc' => $value->no_doc,
            ];
        }
        $jenis_samples_string = implode(',', array_column($result, 'jenis_sample'));
        $tanggalterima = implode(',', array_column($result, 'tanggalterima'));
        $nomor_kupa = implode(',', array_column($result, 'nomor_kupa'));
        $nama_pengirim = implode(',', array_column($result, 'nama_pengirim'));
        $formulir = implode(',', array_column($result, 'formulir'));
        $nodoc = implode(',', array_column($result, 'nodoc'));

        // dd($jenis_samples_string);


        return view('excelView.monitoringexcelbulk', [
            'data' => $result,
            'tanggalterima' => $tanggalterima,
            'jenis_sample' => $jenis_samples_string,
            'nomor_kupa' => $nomor_kupa,
            'nama_pengirim' => $nama_pengirim,
            'formulir' => $formulir,
            'nodoc' => $nodoc,
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

                // $event->sheet->getStyle('B13:W100')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                // $event->sheet->getStyle('B13:W100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 6,
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 25,
            'G' => 15,
            'H' => 20,
            'I' => 15,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 25,
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
        $drawing->setCoordinates('B1');

        return $drawing;
    }
}
