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

        $queries = TrackSampel::whereIn('id', $id)->with('trackParameters')->with('progressSampel')->with('jenisSampel')->get();

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
            ];
        }
        // dd($result);
        $filename = 'testing.pdf';
        $pdf = PDF::loadView('pdfview.vrdata');
        $pdf->set_paper('A2', 'landscape');

        return $pdf->stream($filename);
        // return $pdf->download($filename);
    }
}
