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
        // $data = $ids;

        $id = explode('$', $this->id);

        // dd($id);
        $queries = TrackSampel::whereIn('id', $id)
            ->with('trackParameters')
            ->with('progressSampel')
            ->with('jenisSampel')
            ->get();

        $queries = $queries->groupBy(['jenis_sampel', 'nomor_kupa']);
        // dd($queries);

        $result = [];
        foreach ($queries as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $kode_sampel = [];
                $nomor_lab = [];
                $nama_parameter = [];
                foreach ($value1 as $key2 => $value2) {
                    $jenissample = $value2->jenisSampel->nama;
                    $jenissample_komuditas = $value2->jenis_pupuk;
                    $jumlahsample = $value2['jumlah_sampel'];
                    $kdsmpel = $value2['kode_sampel'];
                    $nolab = $value2['nomor_lab'];
                    $trackparam = $value2->trackParameters;
                    $carbonDate = Carbon::parse($value2['tanggal_terima'])->locale('id')->translatedFormat('d F Y');
                    $carbonDate2 = Carbon::parse($value2['estimasi'])->locale('id')->translatedFormat('d F Y');
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

                            $statuspaket = $trackParameter->ParameterAnalisis->paket_id;

                            if ($statuspaket != null) {
                                $paket = explode('$', $statuspaket);
                                $params = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                                // $nama_parameter[] = $nama_params;
                                // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                                $namakode_sampelparams[implode(',', $params)] =  explode('$', $trackParameter->namakode_sampel);
                            } else {
                                // $nama_parameter[] = $namaunsur;
                                $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_unsur] = explode('$', $trackParameter->namakode_sampel);
                            }

                            // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
                        }
                        $hargatotal += $trackParameter->totalakhir;
                        $jumlah_per_parametertotal += $trackParameter->jumlah;
                    }
                    $harga_total_dengan_ppn = Money::IDR(hitungPPN($hargatotal), true);
                    $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($hargatotal, true));

                    $discountDecimal = $value2->discount != 0 ? $value2->discount / 100 : 0;
                    $discount = $totalppn_harga->multiply($discountDecimal);

                    $total_akhir = $totalppn_harga->subtract($discount);
                    $newnamaparameter = [];

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
                    // dd($newArray, $sampel_data);

                    foreach ($nama_parameter as $item) {
                        if (strpos($item, ',') !== false) {
                            $explodedItems = array_map('trim', explode(',', $item));
                            $newnamaparameter = array_merge($newnamaparameter, $explodedItems);
                        } else {
                            $newnamaparameter[] = $item;
                        }
                    }
                }
                // dd($sampel_data);
                // dd($newnamaparameter);
                $kode_sampel = explode('$', $kdsmpel);

                // dd($kode_sampel, $sampel_data);
                $nomor_lab = explode('$', $nolab);
                $new_sampel = [];
                $incc = 0;
                foreach ($sampel_data as $keyx => $valuex) {
                    $new_sampel[$incc++] = implode(',', $valuex);
                }
                $timestamp = strtotime($value2['tanggal_terima']);
                $year = date('Y', $timestamp);
                $lab =  substr($year, 2) . $value2->jenisSampel->kode . '.';
                $kode_sampel = array_map('rtrim', $kode_sampel);

                foreach ($sampel_data as $keysx => $valuems) {
                    foreach ($kode_sampel as $index => $kode) {
                        if ((string)$keysx === $kode) {
                            $result[$key][$key1][$keysx]['jenis_sample'] = $jenissample;
                            $result[$key][$key1][$keysx]['jenissample_komuditas'] = $jenissample_komuditas;
                            $result[$key][$key1][$keysx]['jumlah_sampel'] = ($index == 0) ? $jumlahsample : 'null';
                            $result[$key][$key1][$keysx]['kode_sampel'] = $kode_sampel[$index];
                            $result[$key][$key1][$keysx]['nomor_lab'] = $lab . $nomor_lab[0] + $index;
                            $result[$key][$key1][$keysx]['nama_pengirim'] = $value2['nama_pengirim'];
                            $result[$key][$key1][$keysx]['asal_sampel'] = $value2['asal_sampel'];
                            $result[$key][$key1][$keysx]['departemen'] = $value2['departemen'];
                            $result[$key][$key1][$keysx]['nomor_surat'] = $value2['nomor_surat'];
                            $result[$key][$key1][$keysx]['nomor_kupa'] = $value2['nomor_kupa'];
                            $result[$key][$key1][$keysx]['tanggal_terima'] = $carbonDate;
                            $result[$key][$key1][$keysx]['tanggal_memo'] = $value2['tanggal_memo'];
                            $result[$key][$key1][$keysx]['Jumlah_Parameter'] = count($valuems);
                            $result[$key][$key1][$keysx]['Parameter_Analisa'] = implode(',', $valuems);
                            $result[$key][$key1][$keysx]['tujuan'] = $value2['tujuan'];
                            $result[$key][$key1][$keysx]['estimasi'] = $carbonDate2;
                            $result[$key][$key1][$keysx]['Tanggal_Selesai_Analisa'] = '-';
                            $result[$key][$key1][$keysx]['Tanggal_Rilis_Sertifikat'] = '-';
                            $result[$key][$key1][$keysx]['No_sertifikat'] = '-';
                            $result[$key][$key1][$keysx]['total'] = ($index == 0) ? $total_akhir : 'null';
                        }
                    }
                }
            }
            $result[$key]['jenis'] = $jenissample;
        }
        $jenissamplel = [];
        foreach ($result as $key => $value) {
            $jenissamplel[] = $value['jenis'];
        }
        // dd($result);


        return view('excelView.prexcel', ['data' => $result]);
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
