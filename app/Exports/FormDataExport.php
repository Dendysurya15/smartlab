<?php

namespace App\Exports;

use App\Models\TrackSampel;
use App\Models\TrackParameter;
use App\Models\MetodeAnalisis;
use App\Models\JenisSampel;
use App\Models\ParameterAnalisis;
use App\Models\User;
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
use Spatie\Permission\Models\Role;
use App\Models\ExcelManagement;

class FormDataExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
{

    private $id;

    private $countnamaarr; // Class property to store countnamaarr value
    private $semuaRowParameter;
    private $status;
    private $tanggal_penerimaan;

    public function __construct($id)
    {
        $this->id = $id;
        // dd($id);
    }


    public function view(): View
    {
        $idsArray = explode('$', $this->id);
        $queries = TrackSampel::whereIn('id', $idsArray)->with('trackParameters')->with('progressSampel')->with('jenisSampel')->get();
        $petugas = ExcelManagement::where('status', 1)->get();
        $petugas = $petugas->groupBy(['jabatan']);
        $petugas = json_decode($petugas, true);
        // dd($petugas);
        // dd($queries);
        $result = [];
        $result_total = [];
        $inc = 1;
        function transformArray($list_row)
        {
            $result = [];
            $total = 0;

            foreach ($list_row as $valuxe) {
                $result[$total] = $valuxe;
                $total += $valuxe;
            }

            return $result;
        }
        foreach ($queries as $key => $value) {
            // dd($value);
            $tanggal_terima = Carbon::parse($value->tanggal_terima);
            $trackparam = $value->trackParameters;

            // $data = [];
            foreach ($trackparam as $trackParameter) {

                if ($trackParameter->ParameterAnalisis) {
                    if ($trackParameter->ParameterAnalisis->paket_id != null) {
                        $data = explode('$', $trackParameter->ParameterAnalisis->paket_id);
                        $nama_params[] = [
                            'unsur' => ParameterAnalisis::whereIn('id', $data)->pluck('nama_unsur')->toArray(),
                            'metode_analisis' => ParameterAnalisis::whereIn('id', $data)->pluck('metode_analisis')->toArray(),
                            'harga' => $trackParameter->ParameterAnalisis->harga,
                            'jenis' => 'Paket',
                            'row' => count($data),
                            'jumlah_sampel' => $trackParameter->jumlah,
                            'satuan' => ParameterAnalisis::whereIn('id', $data)->pluck('satuan')->toArray(),
                            'sub_total' => $trackParameter->ParameterAnalisis->harga * $trackParameter->jumlah,
                        ];
                    } else {
                        $nama_params[] = [
                            'unsur' => $trackParameter->ParameterAnalisis->nama_unsur,
                            'metode_analisis' => $trackParameter->ParameterAnalisis->metode_analisis,
                            'harga' => $trackParameter->ParameterAnalisis->harga,
                            'jenis' => 'Paket',
                            'row' => 1,
                            'jumlah_sampel' => $trackParameter->jumlah,
                            'satuan' => $trackParameter->ParameterAnalisis->satuan,
                            'sub_total' => $trackParameter->ParameterAnalisis->harga * $trackParameter->jumlah,
                        ];
                    }
                }
            }

            // dd($nama_params);
            $total_row = 0;
            $jum_sampel = 0;
            $harga_total_per_sampel = 0;
            $list_unsur = [];
            $list_analisis = [];
            $list_satuan = [];
            $list_row = [];
            $list_jumlah_sampel = [];
            $list_harga = [];
            $sub_total = [];

            // dd($nama_params);
            foreach ($nama_params as $param) {
                $total_row += $param['row'];
                $jum_sampel += $param['jumlah_sampel'];
                $harga_total_per_sampel += $param['sub_total'];




                $list_jumlah_sampel[] = $param['jumlah_sampel'];
                $list_harga[] = $param['harga'];
                $sub_total[] = $param['sub_total'];
                $list_row[] = $param['row'];



                if (is_array($param['unsur'])) {
                    $list_unsur = array_merge($list_unsur, $param['unsur']);
                    $list_analisis = array_merge($list_analisis, $param['metode_analisis']);
                    $list_satuan = array_merge($list_satuan, $param['satuan']);
                } else {
                    $list_unsur[] = $param['unsur'];
                    $list_analisis[] = $param['metode_analisis'];
                    $list_satuan[] = $param['satuan'];
                }
            }
            $harga_total_dengan_ppn = Money::IDR(hitungPPN($harga_total_per_sampel), true);
            $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($harga_total_per_sampel, true));

            $discountDecimal = $value->discount != 0 ? $value->discount / 100 : 0;
            $discount = $totalppn_harga->multiply($discountDecimal);
            $total_akhir = $totalppn_harga->subtract($discount);

            $nolab = explode('$', $value->nomor_lab);
            // Get the latest lab_label_tahun from database to ensure we have the correct year
            $lab_label_tahun = $value->getRawOriginal('lab_label_tahun') ?? $value->lab_label_tahun;
            $year_from_date = Carbon::parse($value->tanggal_terima)->format('y');
            $year = !empty($lab_label_tahun) ? substr($lab_label_tahun, -2) : $year_from_date;
            $kode_sampel = $value->jenisSampel->kode;


            $colspandata = transformArray($list_row);
            $keys = array_keys($colspandata);
            $jum_samps = [];
            $jum_harga = [];
            $jum_sub_total = [];

            foreach ($keys as $keym1 => $valuem1) {
                if (isset($list_jumlah_sampel[$keym1])) {
                    $jum_samps[$valuem1] = $list_jumlah_sampel[$keym1];
                }
                if (isset($list_harga[$keym1])) {
                    $jum_harga[$valuem1] = $list_harga[$keym1];
                }

                if (isset($sub_total[$keym1])) {
                    $jum_sub_total[$valuem1] = $sub_total[$keym1];
                }
            }

            // dd($jum_sub_total);
            // Format the left lab number
            $labkiri = $year . $kode_sampel . '.' . formatLabNumber($nolab[0]);

            // Check if the right lab number exists
            if (isset($nolab[1])) {
                // Format the right lab number
                $labkanan = $year . $kode_sampel . '.' . formatLabNumber($nolab[1]);
            } else {
                $labkanan = '';
            }
            // dd($total_row);

            // untuk row data 
            for ($i = 0; $i < $total_row; $i++) {

                $result[$i]['no_surat'] = ($i == 0) ? $value->nomor_surat : '';
                $result[$i]['kemasan'] = ($i == 0) ? $value->kemasan_sampel : '';
                $result[$i]['colspan'] = ($i == 0) ? $total_row : 0;
                $result[$i]['jum_sampel'] = ($i == 0) ? $value->jumlah_sampel : '';

                if ($i == 0) {
                    $result[$i]['nolab'] = $labkiri;
                } elseif ($i == 1) {
                    $result[$i]['nolab'] = $labkanan;
                } else {
                    $result[$i]['nolab'] = '';
                }
                $result[$i]['Parameter_Analisis'] = $list_unsur[$i];
                $result[$i]['mark'] = '✓';
                $result[$i]['Metode_Analisis'] = $list_analisis[$i];
                $result[$i]['satuan'] = $list_satuan[$i];
                $result[$i]['Personel'] = ($value->personel == 1) ?   '✓' : '';
                $result[$i]['alat'] = ($value->alat == 1) ?   '✓' : '';
                $result[$i]['bahan'] = ($value->bahan == 1) ?   '✓' : '';
                if (isset($colspandata[$i])) {  // Check if $i is a key in $colspandata
                    $result[$i]['cols'] = $colspandata[$i];
                } else {
                    $result[$i]['cols'] = 0;
                }

                $result[$i]['jum_data'] = $jum_samps[$i] ?? 0;
                $result[$i]['jum_harga'] =  $jum_harga[$i] ?? 0;
                $result[$i]['jum_sub_total'] = $jum_sub_total[$i] ?? 0;
                $result[$i]['Konfirmasi'] = ($value->konfirmasi == 1) ?   '✓' : '';
                $result[$i]['kondisi_sampel'] = $value->kondisi_sampel;

                $result[$i]['estimasi'] = ($i == 0) ? Carbon::parse($value->estimasi)->locale('id')->translatedFormat('d F Y') : '';
            }


            // untuk row totalan dan diskon 

            $titles = ["Total Per Parameter", "PPn 11%", "Diskon", "Total"];
            $values_title = [Money::IDR($harga_total_per_sampel, true), $harga_total_dengan_ppn, $discount, $total_akhir];

            for ($i = 0; $i < 4; $i++) {
                // Initialize the array with empty strings
                $result_total[$i] = array_fill(0, 16, '');

                // Set the specific value at index 5
                $result_total[$i][5] = $titles[$i];
                $result_total[$i][11] = $values_title[$i];
            }
            // dd($result);
            $catatan = $value->catatan;
            $nama_pengirim = $value->nama_pengirim;
            $status = $value->status;
            $memo_created = $value->tanggal_memo;
            $verif = explode(',', $value->status_timestamp);

            $verifikasi_admin_timestamp = $verif[0];
            $verifikasi_head_timestamp = $verif[1] ?? '-';

            $approveby_head = $value->approveby_head;
            $petugas_penerima_sampel = User::where('id', $value->created_by)->pluck('name')->first();
            $jenis_kupa = $value->jenisSampel->nama;
            $tanggal_penerimaan = Carbon::parse($value->tanggal_terima)->locale('id')->translatedFormat('d F Y');
            $no_kupa = $value->nomor_kupa;
            $departemen = $value->departemen;
            $formulir = $value->formulir;
            $doc = $value->no_doc;
        }

        // dd($petugas_penerima_sampel);
        // dd($total_row);
        if ($total_row == 1) {
            $test = 2;
        } else {
            $test = $total_row;
        }
        $this->tanggal_penerimaan = $tanggal_penerimaan;
        return view('excelView.exportexcel', [
            'data' => $result,
            'total_row' => $total_row,
            'result_total' => $result_total,
            'catatan' => $catatan,
            'nama_pengirim' => $nama_pengirim,
            'petugas_penerima_sampel' => $petugas_penerima_sampel,
            'approval' => $status,
            'memo_created' => $memo_created,
            'verifikasi_admin_timestamp' => $verifikasi_admin_timestamp,
            'isVerifiedByHead' => $approveby_head,
            'verifikasi_head_timestamp' => $verifikasi_head_timestamp,
            'jenis_kupa' => $jenis_kupa,
            'tanggal_penerimaan' => $tanggal_penerimaan,
            'no_kupa' => $no_kupa,
            'departemen' => $departemen,
            'formulir' => $formulir,
            'doc' => $doc,
            'labkiri' => $labkiri,
            'labkanan' => $labkanan,
        ]);
    }

    public function registerEvents(): array
    {
        return [


            AfterSheet::class => function (AfterSheet $event) {

                // $countnamaarr = 5;
                if ($this->countnamaarr != 0) {
                    $endRow = 13 + $this->countnamaarr; // Access countnamaarr from the class property

                    // dd($endRow);
                    // Merge cells
                    $event->sheet->mergeCells("O14:O$endRow");
                    $event->sheet->getStyle("O14:O$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                    $event->sheet->mergeCells("N14:N$endRow");
                    $event->sheet->getStyle("N14:N$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("P14:P$endRow");
                    $event->sheet->getStyle("P14:P$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                }

                $columnsToStyle = ['B12', 'C12', 'D12', 'E12', 'B5', 'B6', 'D1', 'D2', 'D3', 'D4', 'F12',  'H12', 'I12', 'J13', 'K13', 'L13', 'M13', 'N13', 'O13', 'P13', 'Q13', 'R13', 'S12', 'T13', 'U12'];
                foreach ($columnsToStyle as $column) {
                    $event->sheet->getStyle($column)->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getStyle($column)->getAlignment()->setWrapText(true);
                }

                $event->sheet->getStyle('C12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('D12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('G')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('H')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('E12')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('I13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('J13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('K13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('L13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('M13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('Q13')->getAlignment()->setWrapText(true);
                $tinggiDefaultKolom = 13 +  7 + $this->semuaRowParameter;
            },


        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 15,
            'D' => 10,
            'E' => 15,
            'F' => 35,
            'G' => 5,
            'H' => 35,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 10,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 30,
            // 'T' => 30

        ];
    }


    public function drawings()
    {

        // dd($this->semuaRowParameter);

        $tinggiDefaultKolom = 13 +  4 + 1 + 1 + 1 +  $this->semuaRowParameter;

        $drawings = [];

        // First Image
        $drawing1 = new Drawing();
        $drawing1->setName('Logo1');
        $drawing1->setDescription('This is my first logo');

        if (defaultIconPT($this->tanggal_penerimaan)) {
            $drawing1->setPath(public_path('images/Logo_CBI_2.png'));
        } else {
            $drawing1->setPath(public_path('images/logocorp.png'));
        }
        $drawing1->setHeight(60);
        $drawing1->setCoordinates('B2');
        $drawings[] = $drawing1;


        return $drawings;
    }
}
