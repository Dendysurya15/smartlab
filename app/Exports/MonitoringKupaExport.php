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

class MonitoringKupaExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
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
        $tanggal_terima = Carbon::parse($tracksample['tanggal_terima']);
        // dd($tracksample);

        $trackparam = $tracksample->trackParameters;

        $val_track = 0;
        foreach ($trackparam as $key => $value) {
            $val_track += $value['jumlah'];
            $id[] = $value['id'];
        }

        $trackParameters = TrackParameter::with('ParameterAnalisis')->whereIn('id', $id)->get();

        $nama_parameter = [];

        // dd($trackParameters);
        $hargatotal = 0;
        foreach ($trackParameters as $trackParameter) {
            if ($trackParameter->ParameterAnalisis) {
                $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                $hargaasli[] = $trackParameter->ParameterAnalisis->harga;
                $harga_total_per_sampel[] = $trackParameter->totalakhir;
                $jumlah_per_parameter[] = $trackParameter->jumlah;
            }
            $hargatotal += $trackParameter->totalakhir;
        }

        $harga_total_dengan_ppn = Money::IDR(hitungPPN($hargatotal), true);
        $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($hargatotal, true));
        $discountDecimal = $tracksample->discount != 0 ? $tracksample->discount / 100 : 0;
        $discount = $totalppn_harga->multiply($discountDecimal);

        $total_akhir = $totalppn_harga->subtract($discount);
        $row = count($nama_parameter);

        $arr = array();
        for ($i = 0; $i < $row; $i++) {
            $temp = array();
            $temp['no'] =  $i === 0 ? '1' : ' ';
            $temp['tgl_trma'] =  $i === 0 ? $tanggal_terima->format('Y-m-d') : ' ';
            $temp['jenis_sample'] =  $i === 0 ? $tracksample->jenisSampel->nama : ' ';
            $temp['asal_sampel'] = $i === 0 ? $tracksample->asal_sampel : ' ';
            $temp['memo_pengantar'] = $i === 0 ? tanggal_indo($tracksample->tanggal_memo, false, false, true) : ' ';
            $temp['nama_pengirim'] = $i === 0 ? $tracksample->nama_pengirim : ' ';
            $temp['departemen'] = $i === 0 ? $tracksample->departemen : ' ';
            $temp['nomor_kupa'] = $i === 0 ? $tracksample->nomor_kupa : ' ';
            $temp['kode_sampel'] = $i === 0 ? $tracksample->kode_sampel : ' ';
            $temp['jumlah_parameter'] = $i === 0 ?  $val_track : ' ';
            $temp['jumlah_sampel'] =   $jumlah_per_parameter[$i];
            $temp['sub_total_per_parameter'] =   $harga_total_per_sampel[$i];
            $temp['parameter_analisis'] =   $nama_parameter[$i];
            $temp['harga_normal'] =   $hargaasli[$i];
            $temp['estimasi'] = $i === 0 ? tanggal_indo($tracksample->estimasi, false, false, true) : ' ';
            $temp['tanggal_serif'] = $i === 0 ?  '-' : ' ';
            $temp['no_serif'] = $i === 0 ? '-' : ' ';
            $temp['tanggal_kirim_sertif'] = $i === 0 ? '-' : ' ';
            $arr[] = $temp;
        }
        $gethargatotal = $hargatotal;
        $total = [
            'no' =>  ' ',
            'tgl_trma' =>   ' ',
            'jenis_sample' =>  ' ',
            'asal_sampel' =>  ' ',
            'memo_pengantar' => ' ',
            'nama_pengirim' =>   ' ',
            'departemen' =>  ' ',
            'nomor_kupa' =>   ' ',
            'kode_sampel' =>  ' ',
            'jumlah_parameter' =>  ' ',
            'jumlah_sampel' =>   ' ',
            'sub_total_per_parameter' => ' ',
            'parameter_analisis' =>   'Sub Total',
            'harga_normal' => Money::IDR($gethargatotal, true),
            'harga_ppn' =>   ' ',
            'estimasi' =>  ' ',
            'tanggal_serif' =>   ' ',
            'no_serif' =>  ' ',
            'tanggal_kirim_sertif' =>  ' ',
        ];
        $diskon = [
            'no' =>  ' ',
            'tgl_trma' =>   ' ',
            'jenis_sample' =>  ' ',
            'asal_sampel' =>  ' ',
            'memo_pengantar' => ' ',
            'nama_pengirim' =>   ' ',
            'departemen' =>  ' ',
            'nomor_kupa' =>   ' ',
            'kode_sampel' =>  ' ',
            'jumlah_parameter' =>  ' ',
            'jumlah_sampel' =>   ' ',
            'sub_total_per_parameter' => ' ',
            'parameter_analisis' =>   'Diskon' . ' ' . $tracksample->discount . '%',
            'harga_normal' => $discount,
            'harga_ppn' =>   ' ',
            'estimasi' =>  ' ',
            'tanggal_serif' =>   ' ',
            'no_serif' =>  ' ',
            'tanggal_kirim_sertif' =>  ' ',
        ];
        $totalppn = [
            'no' =>  ' ',
            'tgl_trma' =>   ' ',
            'jenis_sample' =>  ' ',
            'asal_sampel' =>  ' ',
            'memo_pengantar' => ' ',
            'nama_pengirim' =>   ' ',
            'departemen' =>  ' ',
            'nomor_kupa' =>   ' ',
            'kode_sampel' =>  ' ',
            'jumlah_parameter' =>  ' ',
            'jumlah_sampel' =>   ' ',
            'sub_total_per_parameter' => ' ',
            'parameter_analisis' =>   'PPN 11%',
            'harga_normal' =>  $harga_total_dengan_ppn,
            'harga_ppn' =>   ' ',
            'estimasi' =>  ' ',
            'tanggal_serif' =>   ' ',
            'no_serif' =>  ' ',
            'tanggal_kirim_sertif' =>  ' ',
        ];
        $totalfinal = [
            'no' =>  ' ',
            'tgl_trma' =>   ' ',
            'jenis_sample' =>  ' ',
            'asal_sampel' =>  ' ',
            'memo_pengantar' => ' ',
            'nama_pengirim' =>   ' ',
            'departemen' =>  ' ',
            'nomor_kupa' =>   ' ',
            'kode_sampel' =>  ' ',
            'jumlah_parameter' =>  ' ',
            'jumlah_sampel' =>   ' ',
            'sub_total_per_parameter' => ' ',
            'parameter_analisis' =>   'Total Harga',
            'harga_normal' =>  $total_akhir,
            'harga_ppn' =>   ' ',
            'estimasi' =>  ' ',
            'tanggal_serif' =>   ' ',
            'no_serif' =>  ' ',
            'tanggal_kirim_sertif' =>  ' ',
        ];

        return view('excelView.monitoringexcel', ['data' => $arr, 'total' => $total, 'totalppn' => $totalppn, 'diskon' => $diskon, 'totalfinal' => $totalfinal]);
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
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 25,
            'G' => 15,
            'H' => 20,
            'I' => 15,
            'J' => 10,
            'K' => 10,
            'L' => 20,
            'M' => 25,
            'N' => 15,
            'O' => 15,
            'P' => 20,
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
