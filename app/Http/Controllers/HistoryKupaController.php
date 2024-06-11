<?php

namespace App\Http\Controllers;

use App\Exports\FormDataExport;
use App\Exports\MonitoringKupaExport;
use App\Exports\MonitoringKupabulk;
use App\Http\Requests\InputProgressRequest;
use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Cknow\Money\Money;
use App\Models\User;
use App\Models\ExcelManagement;
use App\Models\ParameterAnalisis;
use Dompdf\Dompdf;
use Dompdf\Options;

class HistoryKupaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        return view('pages/historySampel/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $query = TrackSampel::find($id);

        $jns_sampel = JenisSampel::all();
        // dd($query);

        $progressStr = JenisSampel::find($query->jenis_sampel)->progress;

        $arr_progress = explode(',', $progressStr);

        $progressOptions = [];

        foreach ($arr_progress as $progressId) {
            $queryProgress = ProgressPengerjaan::find($progressId);
            $progressOptions[$queryProgress->id] = $queryProgress->nama;
        }


        $newquery = TrackSampel::with('trackParameters')->where('id', $id)->first();


        $newquery = json_decode($newquery, true);


        return view('pages/historySampel/edit', ['sampel' => $query]);
    }

    public function getProgressOptions(Request $request)
    {
        $selectedValue = $request->input('jenis_sampel');

        $progressStr = JenisSampel::find($selectedValue)->progress;

        $arr_progress = explode(',', $progressStr);

        $progressOptions = [];

        foreach ($arr_progress as $progressId) {
            $nama = ProgressPengerjaan::find($progressId)->nama;
            $progressOptions[] = $nama;
        }


        return response()->json($progressOptions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InputProgressRequest $request, $id)
    {
        $trackSampel = TrackSampel::findOrFail($id);
        $current = Carbon::now();
        $current = $current->format('Y-m-d H:i:s');
        // Update the model with the request data
        $trackSampel->update($request->all());

        // Optionally, you can also update the parameter_analisis attribute
        // separately, as it might need additional processing

        $trackSampel->last_update = $trackSampel->last_update . ', ' .  $current;
        $trackSampel->nomor_lab = $request->input('no_lab');
        $trackSampel->save();

        return redirect()->back()->with('success', 'Record has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function exportExcel($id)
    {
        $query = TrackSampel::find($id);

        // dd($query);

        $filename = 'Kupa ' . $query->JenisSampel->nama . '-' .  $query->nomor_kupa . ' ' . tanggal_indo($query->tanggal_terima, false, false, true) . '.xlsx';

        return Excel::download(new FormDataExport($id), $filename);
    }

    public function exportFormMonitoringKupabulk($id)
    {
        // $idsArray = explode('$', $id);
        // $queries = TrackSampel::whereIn('id', $idsArray)->get();

        // // dd($queries);
        $filename = 'Form Monitoring Sampel bulanan.xlsx';
        return Excel::download(new MonitoringKupabulk($id), $filename);
    }

    public function exportvr($ids)
    {
        $data = $ids;

        $id = explode('$', $data);

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
                    $jumlahsample = $value2['jumlah_sampel'];
                    $kdsmpel = $value2['kode_sampel'];
                    $nolab = $value2['nomor_lab'];
                    $trackparam = $value2->trackParameters;
                    $carbonDate = Carbon::parse($value2['tanggal_terima']);
                    $carbonDate2 = Carbon::parse($value2['estimasi']);
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



                    $sampel_data = [];

                    foreach ($namakode_sampelparams as $attribute => $items) {
                        foreach ($items as $item) {
                            if (!isset($sampel_data[$item])) {
                                $sampel_data[$item] = [];
                            }

                            $explodedAttributes = strpos($attribute, ',') !== false ? explode(',', $attribute) : [$attribute];

                            foreach ($explodedAttributes as $attr) {
                                $trimmedAttr = trim($attr); // Ensure no leading/trailing spaces
                                if (!in_array($trimmedAttr, $sampel_data[$item])) {
                                    $sampel_data[$item][] = $trimmedAttr;
                                }
                            }
                        }
                    }
                }
                // dd($sampel_data, $namakode_sampelparams);

                $kode_sampel = explode('$', $kdsmpel);


                $nomor_lab = explode('$', $nolab);
                $new_sampel = [];
                $incc = 0;
                foreach ($sampel_data as $keyx => $valuex) {
                    $new_sampel[$incc++] = implode(',', $valuex);
                }


                foreach ($sampel_data as $keysx => $valuems) {
                    foreach ($kode_sampel as $index => $kode) {
                        if ((string)$keysx === $kode) {
                            $result[$key][$key1][$keysx]['jenis_sample'] = $jenissample;
                            $result[$key][$key1][$keysx]['jumlah_sampel'] = ($index == 0) ? $jumlahsample : 'null';
                            $result[$key][$key1][$keysx]['kode_sampel'] = $kode_sampel[$index];
                            $result[$key][$key1][$keysx]['nomor_lab'] = $nomor_lab[0] + $index;
                            $result[$key][$key1][$keysx]['nama_pengirim'] = $value2['nama_pengirim'];
                            $result[$key][$key1][$keysx]['asal_sampel'] = $value2['asal_sampel'];
                            $result[$key][$key1][$keysx]['departemen'] = $value2['departemen'];
                            $result[$key][$key1][$keysx]['nomor_surat'] = $value2['nomor_surat'];
                            $result[$key][$key1][$keysx]['nomor_kupa'] = $value2['nomor_kupa'];
                            $result[$key][$key1][$keysx]['tanggal_terima'] = $carbonDate->format('Y-m-d');
                            $result[$key][$key1][$keysx]['tanggal_memo'] = $value2['tanggal_memo'];
                            $result[$key][$key1][$keysx]['Jumlah_Parameter'] = count($valuems);
                            $result[$key][$key1][$keysx]['Parameter_Analisa'] = implode(',', $valuems);
                            $result[$key][$key1][$keysx]['tujuan'] = $value2['tujuan'];
                            $result[$key][$key1][$keysx]['estimasi'] = $carbonDate2->format('Y-m-d');
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
        // dd($result);
        $jenissamplel = [];
        foreach ($result as $key => $value) {
            $jenissamplel[] = $value['jenis'];
        }
        // dd($result);
        $jenissamplefix = implode(',', $jenissamplel);

        $data = [
            'data' => $result,
        ];
        $filename = 'PDF Kupa,' . $jenissamplefix . '.pdf';
        $pdf = Pdf::setPaper('letter', 'portrait');
        $pdf->setOptions([
            'dpi' => 100,
            'defaultFont' => 'Nunito, sans-serif', 'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true, 'isJavascriptEnabled' => true
        ]);

        // $pdf->setOptions(['dpi' => 150, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif']);
        $pdf->loadView('pdfview.vrdata', $data);
        $pdf->set_paper('A2', 'landscape');

        return $pdf->stream($filename);
        // return $pdf->download($filename);
    }

    public function export_form_pdf($id, $filename)
    {
        // You can now use $id and $filename directly
        $idsArray = explode('$', $id);
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
        $data = [
            'data' => $result
        ];

        // dd($filename);
        $pdf = Pdf::setPaper('letter', 'landscape');
        $pdf->setOptions([
            'dpi' => 100,
            'defaultFont' => 'Nunito, sans-serif', 'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true, 'isJavascriptEnabled' => true
        ]);

        // $pdf->setOptions(['dpi' => 150, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif']);
        $pdf->loadView('pdfview.export_monitoring', $data);
        $pdf->set_paper('A2', 'landscape');

        // return $pdf->stream($filename);
        return $pdf->download($filename . '.pdf');
    }

    public function export_kupa_pdf($id, $filename)
    {

        $idsArray = explode('$', $id);
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
            $year = Carbon::parse($value->tanggal_terima)->format('y');
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
                $result[$i]['Personel'] = ($value->personel == 1) ?   '✔' : '';
                $result[$i]['alat'] = ($value->alat == 1) ?   '✔' : '';
                $result[$i]['bahan'] = ($value->bahan == 1) ?   '✔' : '';
                if (isset($colspandata[$i])) {  // Check if $i is a key in $colspandata
                    $result[$i]['cols'] = $colspandata[$i];
                } else {
                    $result[$i]['cols'] = 0;
                }

                $result[$i]['jum_data'] = $jum_samps[$i] ?? 0;
                $result[$i]['jum_harga'] =  $jum_harga[$i] ?? 0;
                $result[$i]['jum_sub_total'] = $jum_sub_total[$i] ?? 0;
                $result[$i]['Konfirmasi'] = ($value->konfirmasi == 1) ?   '✔' : '';
                $result[$i]['kondisi_sampel'] = $value->kondisi_sampel;
                $result[$i]['estimasi'] = ($i == 0) ? Carbon::parse($value->estimasi)->format('Y-m-d') : '';
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
            $petugas_penerima_sampel = User::where('id', $value->status_changed_by_id)->pluck('name')->first();
            $jenis_kupa = $value->jenisSampel->nama;
            $tanggal_penerimaan = Carbon::parse($value->tanggal_terima)->format('Y-m-d');
            $no_kupa = $value->nomor_kupa;
            $departemen = $value->departemen;
            $formulir = $value->formulir;
            $doc = $value->no_doc;
        }

        $data = [
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
            'img' => asset('images/Logo_CBI_2.png'), // Correctly generate the image URL
        ];

        // dd($data);
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true); // Enable loading of remote resources
        $dompdf = new Dompdf($options);

        $view = view('pdfview.export_kupa', $data)->render();
        $dompdf->loadHtml($view);

        // Set paper size and orientation
        $dompdf->setPaper('A2', 'landscape');

        // Render the PDF
        $dompdf->render();

        // return $dompdf->stream($filename, ["Attachment" => false]);
        $dompdf->stream($filename, ["Attachment" => false]);
    }

    public function export_pr_pdf($id, $filename)
    {

        $idsArray = explode('$', $id);
        $queries = TrackSampel::whereIn('id', $idsArray)->with('trackParameters')->with('progressSampel')->with('jenisSampel')->get();
        $petugas = ExcelManagement::where('status', 1)->get();
        $petugas = $petugas->groupBy(['jabatan']);
        $petugas = json_decode($petugas, true);
        // dd($petugas);
        // dd($queries);
        $result = [];
        $inc = 1;
        foreach ($queries as $key => $value) {
            // dd($value);
            $tanggal_terima = Carbon::parse($value->tanggal_terima);
            $trackparam = $value->trackParameters;
            $namakode_sampel = explode('$', $value->kode_sampel);
            $namakode_sampel = array_map('trim', $namakode_sampel);

            // dd($namakode_sampel);
            $nama_parameter = [];
            $hargatotal = 0;
            $jumlah_per_parametertotal = 0;
            $hargaasli = [];
            $harga_total_per_sampel = [];
            $jumlah_per_parameter = [];
            $namakode_sampelparams = [];
            foreach ($trackparam as $trackParameter) {

                if ($trackParameter->ParameterAnalisis) {
                    $nama_params = $trackParameter->ParameterAnalisis->nama_parameter;
                    $namaunsur = $trackParameter->ParameterAnalisis->nama_unsur;
                    $statuspaket = $trackParameter->ParameterAnalisis->paket_id;

                    if ($statuspaket != null) {
                        $paket = explode('$', $statuspaket);
                        $params = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                        $nama_parameter[] = $nama_params;
                        // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                        $namakode_sampelparams[implode(',', $params)] =  explode('$', $trackParameter->namakode_sampel);
                    } else {
                        $nama_parameter[] = $namaunsur;
                        $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_unsur] = explode('$', $trackParameter->namakode_sampel);
                    }
                    // if (strpos($nama_params, ',') !== false) {
                    //     $nama_parameter[] = $nama_params;
                    //     $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
                    // } else if ($namaunsur === '-' || $namaunsur === '' || $namaunsur === null) {
                    //     $nama_parameter[] = $nama_params;
                    //     $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
                    // } else {
                    //     $nama_parameter[] = $namaunsur;
                    //     $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_unsur] = explode('$', $trackParameter->namakode_sampel);
                    // }


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
            // dd($nama_parameter, $namakode_sampelparams);

            $sampel_data = [];
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

            // dd($sampel_data, $namakode_sampelparams);
            $total_namaparams = 20 - count($newArray);
            $timestamp = strtotime($value->tanggal_terima);
            $year = date('Y', $timestamp);
            $lab =  substr($year, 2) . $value->jenisSampel->kode . '.';
            $Nomorlab = explode('$', $value->nomor_lab);
            $Nomorlab = array_filter($Nomorlab, function ($value) {
                return $value !== "-";
            });
            $timestamp2 = strtotime($value->estimasi);
            $tanggal_terima = date('Y-m-d', $timestamp);
            $tanggal_penyelesaian = date('Y-m-d', $timestamp2);
            $inc = 0;
            $inc2 = 1;
            $startingValue = $Nomorlab[0];
            // dd($startingValue);
            $data = count($namakode_sampel);

            $petugas_prep[$key] = $value->petugas_preparasi;
            $penyelia_prep[$key] = $value->penyelia;
            $PenerimaSampel = $petugas['Petugas Penerima Sampel'][0]['nama'];
            if (!is_null($petugas_prep)) {
                // dd($petugas_prep);
                $Preparasi = $petugas_prep;
            } else {

                $Preparasi = $petugas['Petugas Preparasi'][0]['nama'];
            }

            $Staff = $petugas['Staff Kimia & Lingkungan'][0]['nama'];
            $Penyelia = (!is_null($penyelia_prep) ? $penyelia_prep : $petugas['Penyelia'][0]['nama']);
            foreach ($namakode_sampel as $keyx => $valuex) {
                foreach ($sampel_data as $keyx2 => $valuex2) {
                    if ($valuex == $keyx2) { // Change === to ==
                        $nolabdata = $startingValue + $inc;
                        $nolabdata = formatLabNumber($nolabdata);
                        $result[$key]['data'][$valuex]['id'] = $inc2++;
                        $result[$key]['data'][$valuex]['id_data'] = $inc++;
                        $result[$key]['data'][$valuex]['nomor_lab'] = $lab .  $nolabdata;
                        $result[$key]['data'][$valuex]['jumlah_sampel'] = $value->jumlah_sampel;
                        $result[$key]['data'][$valuex]['tanggal_terima'] = $tanggal_terima;
                        $result[$key]['data'][$valuex]['kondisi_sampel'] = $value->kondisi_sampel;
                        $result[$key]['data'][$valuex]['tanggal_penyelesaian'] = $tanggal_penyelesaian;
                        $result[$key]['data'][$valuex]['parameter_sampel'] = $valuex2;
                        $result[$key]['namaparams'] = array_unique($newArray);
                        $result[$key]['jenis_sampel'] = $value->jenisSampel->nama;
                        $result[$key]['jumlah_sampel'] = $value->jumlah_sampel;
                        $result[$key]['tanggal_terima'] = $tanggal_terima;
                        $result[$key]['kondisi_sampel'] = $value->kondisi_sampel;
                        $result[$key]['tanggal_penyelesaian'] = $tanggal_penyelesaian;
                        $result[$key]['no_order'] = $value->nomor_kupa;
                        $result[$key]['total_namaparams'] = $total_namaparams;
                        $result[$key]['PenerimaSampel'] = $PenerimaSampel;
                        $result[$key]['Preparasi'] = $Preparasi[0];
                        $result[$key]['Staff'] = $Staff;
                        $result[$key]['Penyelia'] = $Penyelia[0];
                        $result[$key]['doc'] = $value->no_doc;
                        $result[$key]['formulir'] = $value->formulir;
                    }
                }
            }
        }
        $data = [
            'data' => $result,
        ];
        // dd($data);

        // dd($data);
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true); // Enable loading of remote resources
        $dompdf = new Dompdf($options);

        $view = view('pdfview.pr', $data)->render();
        $dompdf->loadHtml($view);

        // Set paper size and orientation
        $dompdf->setPaper('A2', 'potrait');
        // $dompdf->setPaper('A2', 'landscape');

        // Render the PDF
        $dompdf->render();


        // $dompdf->stream($filename, ["Attachment" => true]);
        return $dompdf->stream($filename, ["Attachment" => false]);
    }
}
