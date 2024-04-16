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

class FormDataExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithDrawings
{

    private $id;

    private $countnamaarr; // Class property to store countnamaarr value
    private $semuaRowParameter;
    private $status;

    public function __construct($id)
    {
        $this->id = $id;
        // dd($id);
    }


    public function view(): View
    {

        // $test =  Money::USD(500);

        // dd($test);

        $tracksample = TrackSampel::findOrFail($this->id);
        $nama_petugas_penerima_sampel = User::find($tracksample->admin)->name;

        $jenis_sample = JenisSampel::with('parameterAnalisis')->where('id', $tracksample->jenis_sampel)->first();

        $parameter_analisis_excel = $jenis_sample->parameter_analisis;

        $timestampVerifikasiAdmin = '';
        $timestampVerifikasiHeadOfLab = '';
        $isVerifiedByHead = False;
        if ($tracksample->status_timestamp != null) {
            $string = rtrim($tracksample->status_timestamp, ', ');

            $dateTimeArray = explode(' , ', $string);
            $timestampVerifikasiAdmin = $dateTimeArray[0];

            $alurApproved = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name')->toArray();

            if ($tracksample->status_approved_by_role == end($alurApproved)) {
                $timestampVerifikasiHeadOfLab = end($dateTimeArray);
                $isVerifiedByHead = True;
            }
        }


        foreach ($jenis_sample->parameterAnalisis as $key => $value) {

            $getunsur[] = [
                'nama_unsur' => $value['nama_unsur'],
                'metode_analisis' => $value['metode_analisis'],
                'satuan' => $value['satuan'],
            ];
        }


        $array_param_analisis_excel = explode(',', $parameter_analisis_excel);

        $array_param_analisis_excel = array_map('trim', $array_param_analisis_excel);

        $row_metode = $jenis_sample->parameterAnalisis->pluck('metode_analisis');
        $row_satuan = $jenis_sample->parameterAnalisis->pluck('satuan');

        $tgl_penerimaan = tanggal_indo($tracksample->tanggal_terima, false, false, true);
        $tgl_estimasi = tanggal_indo($tracksample->estimasi, false, false, true);

        $jenis_kupa = $jenis_sample->nama;
        $no_kupa = $tracksample->nomor_kupa;
        $catatan = $tracksample->catatan;
        $approval = $tracksample->approveby_admin ? 'Approved' : $tracksample->status;

        $memo_created = $tracksample->tanggal_memo;
        $nama_pengirim = $tracksample->nama_pengirim;
        $this->status = $tracksample->status;

        $arr_per_column = [];

        $arr_per_column[0]['col_no_surat'] = $tracksample->nomor_surat;
        $arr_per_column[0]['col_kemasan'] = $tracksample->kemasan_sampel;
        $arr_per_column[0]['col_jum_sampel'] = $tracksample->jumlah_sampel;
        $arr_per_column[0]['col_no_lab'] = $tracksample->nomor_lab;
        $arr_per_column[0]['col_param'] = $array_param_analisis_excel[0];
        $arr_per_column[0]['col_mark'] = '';
        $arr_per_column[0]['col_metode'] = $row_metode[0];
        $arr_per_column[0]['col_satuan'] = $row_satuan[0];
        $arr_per_column[0]['col_personel'] = '';
        $arr_per_column[0]['col_alat'] = '';
        $arr_per_column[0]['col_bahan'] = '';
        $arr_per_column[0]['col_jum_sampel_2'] = '';
        $arr_per_column[0]['col_harga'] = '';
        $arr_per_column[0]['col_sub_total'] = '';
        $arr_per_column[0]['col_ppn'] = '';
        $arr_per_column[0]['col_total'] = '';
        $arr_per_column[0]['col_langsung'] = '';
        $arr_per_column[0]['col_normal'] = $tracksample->kondisi_sampel == 'Normal' ? 1 : 0;
        $arr_per_column[0]['col_abnormal'] = $tracksample->kondisi_sampel == 'Abnormal' ? 1 : 0;
        $arr_per_column[0]['col_tanggal'] = $tgl_estimasi;

        $sub = 0;
        $final_total = 0;

        if (!is_null($tracksample->parameter_analisisid) && $tracksample->parameter_analisisid !== 0 && $tracksample->parameter_analisisid != '0') {

            $getTrack = TrackParameter::with('ParameterAnalisis')->where('id_tracksampel', $tracksample->parameter_analisisid)->get();
            $inputan_parameter = [];
            $inc = 0;
            // dd($getTrack);

            foreach ($getTrack as $key => $value) {
                $inputan_parameter[$inc]['nama'] = $value->ParameterAnalisis->nama_parameter;
                $inputan_parameter[$inc]['alias'] = $value->ParameterAnalisis->nama_unsur;
                $inputan_parameter[$inc]['satuan'] = $value->ParameterAnalisis->satuan;
                $inputan_parameter[$inc]['metode'] = $value->ParameterAnalisis->metode_analisis;
                $inputan_parameter[$inc]['personel'] = $tracksample->personel;
                $inputan_parameter[$inc]['alat'] = $tracksample->alat;
                $inputan_parameter[$inc]['bahan'] = $tracksample->bahan;
                $inputan_parameter[$inc]['harga_per_satuan'] = $value->ParameterAnalisis->harga;
                $inputan_parameter[$inc]['jumlah'] = $value->jumlah;
                $inputan_parameter[$inc]['total_per_parameter'] = $value->totalakhir;
                $inputan_parameter[$inc]['discount'] = $tracksample->discount;
                $inputan_parameter[$inc]['col_verif'] = $tracksample->konfirmasi;
                $inputan_parameter[$inc]['flagcol'] = 'True';


                $sub += $value->totalakhir;
                $discount = $tracksample->discount;
                $test = $sub + hitungPPN($sub);
                $ppn = Money::IDR($test, true);
                $getdisc = round($test * $discount / 100, 2);

                $sub_total_parameter = Money::IDR($test * (1 - ($discount / 100)), true);

                $inc++;
            }



            $row_count = count($inputan_parameter);


            // dd($inputan_parameter);
            $newInputanParameters = [];

            foreach ($inputan_parameter as $key => $value) {
                $nama = $value['nama'];

                // Check if the nama contains a comma
                if (strpos($nama, ',') !== false) {
                    // Split the nama by comma
                    $namaArray = explode(',', $nama);

                    // dd($namaArray);
                    // Iterate through the split nama and create new arrays
                    foreach ($namaArray as $index => $namaItem) {
                        // dd($namaItem);
                        $lastKey = key(array_slice($namaArray, -1, 1, true));
                        // dd($namaItem);
                        // nama_unsur
                        // metode_analisis
                        $mthod = ' ';
                        $satuan = ' ';
                        foreach ($getunsur as $key => $valuex) {
                            if ($valuex['nama_unsur'] === trim($namaItem)) {
                                $mthod = $valuex['metode_analisis'];
                                $satuan = $valuex['satuan'];
                            }
                        }
                        array_unshift($newInputanParameters, [
                            "nama" => trim($namaItem),
                            "alias" => ($index === $lastKey ?  $value["alias"] : ''),
                            "col_verif" => ($index === $lastKey ?  $value["col_verif"] : ''),
                            "satuan" => $satuan,
                            "metode" => $mthod,
                            "personel" => ($index === $lastKey ? $value["personel"] : ''),
                            "alat" => ($index === $lastKey ? $value["alat"] : ''),
                            "bahan" => ($index === $lastKey ? $value["bahan"] : ''),
                            "harga_per_satuan" => ($index === $lastKey ? $value["harga_per_satuan"] : '-'),
                            "jumlah" => ($index === $lastKey ?  $value["jumlah"] : ''),
                            "total_per_parameter" => ($index === $lastKey ? $value["total_per_parameter"] : '-')
                        ]);
                    }
                } else {
                    // If no comma, simply add the original array
                    $newInputanParameters[] = $value;
                }
            }

            // dd($newInputanParameters, $inputan_parameter);

            // dd($newInputanParameters, $getunsur);
            $row_count = count($newInputanParameters);
            $countnamaarr = count($namaArray ?? []);
            $this->semuaRowParameter = $row_count;

            for ($i = 0; $i < $row_count; $i++) {

                if ($i == 0) {

                    if (array_key_exists($i, $newInputanParameters) && $i === $i) {
                        $arr_per_column[$i]['col_mark'] = 1;
                        $arr_per_column[$i]['col_param'] = $newInputanParameters[$i]['nama'];
                        $arr_per_column[$i]['col_harga'] = ($newInputanParameters[$i]['harga_per_satuan'] === '-') ? '-' : Money::IDR($newInputanParameters[$i]['harga_per_satuan'], true);
                        $arr_per_column[$i]['col_satuan'] = $newInputanParameters[$i]['satuan'];
                        $arr_per_column[$i]['col_metode'] = $newInputanParameters[$i]['metode'];
                        $arr_per_column[$i]['col_personel'] = $newInputanParameters[$i]['personel'];
                        $arr_per_column[$i]['col_alat'] = $newInputanParameters[$i]['alat'];
                        $arr_per_column[$i]['col_bahan'] = $newInputanParameters[$i]['bahan'];
                        $arr_per_column[$i]['col_jum_sampel_2'] = $newInputanParameters[$i]['jumlah'];
                        $arr_per_column[$i]['col_sub_total'] = ($newInputanParameters[$i]['total_per_parameter'] === '-') ? '-' : Money::IDR($newInputanParameters[$i]['total_per_parameter'], true);
                        $arr_per_column[$i]['col_verif'] = $newInputanParameters[$i]['col_verif'];
                        $arr_per_column[$i]['col_total'] = '';
                    }
                } else {
                    if (array_key_exists($i, $newInputanParameters) && $i === $i) {
                        $arr_per_column[$i]['col_mark'] = 1;
                        $arr_per_column[$i]['col_param'] = $newInputanParameters[$i]['nama'];
                        $arr_per_column[$i]['col_harga'] = ($newInputanParameters[$i]['harga_per_satuan'] === '-') ? '-' : Money::IDR($newInputanParameters[$i]['harga_per_satuan'], true);
                        $arr_per_column[$i]['col_satuan'] = $newInputanParameters[$i]['satuan'];
                        $arr_per_column[$i]['col_metode'] = $newInputanParameters[$i]['metode'];
                        $arr_per_column[$i]['col_personel'] = $newInputanParameters[$i]['personel'];
                        $arr_per_column[$i]['col_alat'] = $newInputanParameters[$i]['alat'];
                        $arr_per_column[$i]['col_bahan'] = $newInputanParameters[$i]['bahan'];
                        $arr_per_column[$i]['col_jum_sampel_2'] = $newInputanParameters[$i]['jumlah'];
                        $arr_per_column[$i]['col_sub_total'] = ($newInputanParameters[$i]['total_per_parameter'] === '-') ? '-' : Money::IDR($newInputanParameters[$i]['total_per_parameter'], true);
                        $arr_per_column[$i]['col_verif'] = '';
                        $arr_per_column[$i]['col_total'] = '';
                    }
                    $arr_per_column[$i]['col_no_surat'] = '';
                    $arr_per_column[$i]['col_kemasan'] = '';
                    $arr_per_column[$i]['col_jum_sampel'] = '';
                    $arr_per_column[$i]['col_no_lab'] = '';
                    $arr_per_column[$i]['col_langsung'] = '';
                    $arr_per_column[$i]['col_normal'] = '';
                    $arr_per_column[$i]['col_abnormal'] = '';
                    $arr_per_column[$i]['col_tanggal'] = '';
                }
            }
        }

        $this->countnamaarr = $countnamaarr ?? 0;

        return view('excelView.exportexcel', [
            'petugas_penerima_sampel' => $nama_petugas_penerima_sampel,
            'sub_total' => Money::IDR($sub, true),
            'ppn' => $ppn,
            'final_total' => $sub_total_parameter,
            'nama_pengirim' => $nama_pengirim,
            'no_kupa' => $no_kupa,
            'jenis_kupa' => $jenis_kupa,
            'disclabel' => $discount,
            'discount' => Money::IDR($getdisc, true),
            'tanggal_penerimaan' => $tgl_penerimaan,
            'kupa' => $arr_per_column,
            'catatan' => $catatan,
            'approval' => $approval,

            'memo_created' => Carbon::parse($memo_created)->format('Y-m-d H:i'),
            'verifikasi_admin_timestamp' => $timestampVerifikasiAdmin,
            'verifikasi_head_timestamp' => $timestampVerifikasiHeadOfLab,
            'verified_by_head' => $tracksample->status_approved_by_role,
            'isVerifiedByHead' => $isVerifiedByHead,
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
                    $event->sheet->mergeCells("J14:J$endRow");
                    $event->sheet->getStyle("J14:J$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("K14:K$endRow");
                    $event->sheet->getStyle("K14:K$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("L14:L$endRow");
                    $event->sheet->getStyle("L14:L$endRow")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $event->sheet->mergeCells("M14:M$endRow");
                    $event->sheet->getStyle("M14:M$endRow")->getAlignment()
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
                $kolumcatatan = "J$tinggiDefaultKolom";
                $kolumcatatan1 = "B$tinggiDefaultKolom";
                $kolumcatatan2 = "D$tinggiDefaultKolom";
                // dd($kolumcatatan);

                $event->sheet->getStyle($kolumcatatan)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP)
                    ->setWrapText(true);
                $event->sheet->getStyle($kolumcatatan1)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $event->sheet->getStyle($kolumcatatan2)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // $event->sheet->mergeCells("J25:J30");
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

        $lokasiKolomTtdPenerimaSampel = "B$tinggiDefaultKolom";
        $lokasiKolomTtdHeadOfLab = "D$tinggiDefaultKolom";

        // First Image
        $drawing1 = new Drawing();
        $drawing1->setName('Logo1');
        $drawing1->setDescription('This is my first logo');
        $drawing1->setPath(public_path('images/Logo_CBI_2.png'));
        $drawing1->setHeight(70);
        $drawing1->setCoordinates('B1');
        $drawings[] = $drawing1;


        return $drawings;
    }
}
