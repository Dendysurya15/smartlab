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
                            $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
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

                $kode_sampel = explode('$', $kdsmpel);

                // dd($kode_sampel, $sampel_data);
                $nomor_lab = explode('$', $nolab);
                $new_sampel = [];
                $incc = 0;
                foreach ($sampel_data as $keyx => $valuex) {
                    $new_sampel[$incc++] = implode(',', $valuex);
                }

                // dd($kode_sampel, $new_sampel);
                for ($i = 0; $i < $jumlahsample; $i++) {

                    $result[$key][$key1][$i]['jenis_sample'] = $jenissample;
                    $result[$key][$key1][$i]['jumlah_sampel'] = ($i == 0) ? $jumlahsample : 'null';
                    $result[$key][$key1][$i]['kode_sampel'] = $new_sampel[$i];
                    $result[$key][$key1][$i]['nomor_lab'] = $nomor_lab[0] + $i;
                    $result[$key][$key1][$i]['nama_pengirim'] = $value2['nama_pengirim'];
                    $result[$key][$key1][$i]['asal_sampel'] = $value2['asal_sampel'];
                    $result[$key][$key1][$i]['departemen'] = $value2['departemen'];
                    $result[$key][$key1][$i]['nomor_surat'] = $value2['nomor_surat'];
                    $result[$key][$key1][$i]['nomor_kupa'] = $value2['nomor_kupa'];
                    $result[$key][$key1][$i]['tanggal_terima'] = $carbonDate->format('Y-m-d');
                    $result[$key][$key1][$i]['tanggal_memo'] = $value2['tanggal_memo'];
                    $result[$key][$key1][$i]['Jumlah_Parameter'] = count($newnamaparameter);
                    $result[$key][$key1][$i]['Parameter_Analisa'] = implode(',', $nama_parameter);
                    $result[$key][$key1][$i]['tujuan'] = $value2['tujuan'];
                    $result[$key][$key1][$i]['estimasi'] = $carbonDate2->format('Y-m-d');
                    $result[$key][$key1][$i]['Tanggal_Selesai_Analisa'] = '-';
                    $result[$key][$key1][$i]['Tanggal_Rilis_Sertifikat'] = '-';
                    $result[$key][$key1][$i]['No_sertifikat'] = '-';
                    $result[$key][$key1][$i]['total'] = ($i == 0) ? $total_akhir : 'null';
                }
            }
            $result[$key]['jenis'] = $jenissample;
        }

        // dd($result);
        $data = [
            'data' => $result,
        ];
        $filename = 'PDF Kupa.pdf';
        $pdf = Pdf::setPaper('letter', 'portrait');
        $pdf->setOptions([
            'dpi' => 100,
            'defaultFont' => 'Nunito, sans-serif', 'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true, 'isJavascriptEnabled' => true
        ]);

        // $pdf->setOptions(['dpi' => 150, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif']);
        $pdf->loadView('pdfview.vrdata', $data);
        $pdf->set_paper('A2', 'landscape');

        // return $pdf->stream($filename);
        return $pdf->download($filename);
    }
}
