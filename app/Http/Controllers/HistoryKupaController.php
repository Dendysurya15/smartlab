<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputProgressRequest;
use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Illuminate\Http\Request;

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


        ($query);

        $jns_sampel = JenisSampel::all();
        // dd($query);

        $progressStr = JenisSampel::find($query->jenis_sampel)->progress;

        $arr_progress = explode(',', $progressStr);

        $progressOptions = [];

        foreach ($arr_progress as $progressId) {
            $queryProgress = ProgressPengerjaan::find($progressId);
            $progressOptions[$queryProgress->id] = $queryProgress->nama;
        }


        // dd($arr_progress, $progressOptions);




        $getparams = $query->trackParameters;


        $newquery = TrackSampel::with('trackParameters')->where('id', $id)->first();

        // $newquery = DB::connection('mysql')
        //     ->table('track_parameter')
        //     ->select('track_parameter.*', 'parameter_analisis.nama')
        //     ->join('track_sampel', 'track_sampel.parameter_analisisid', '=', 'track_parameter.id_tracksampel')
        //     ->join('parameter_analisis', 'parameter_analisis.id', '=', 'track_parameter.id_parameter')
        //     ->where('track_sampel.id', $id)
        //     ->get();
        // dd($newquery, $id);
        // $analisis = DB::connection('mysql')
        //     ->table('metode_analisis')
        //     ->select('metode_analisis.*')
        //     ->get();


        $newquery = json_decode($newquery, true);
        // $analisis = $analisis->groupBy(['id_parameter']);
        // $analisis = json_decode($analisis, true);
        // dd($newquery, $analisis);

        $list_metode = [];
        // foreach ($newquery as $key => $value) {
        //     foreach ($analisis as $key2 => $value2) {
        //         if ($value['id_parameter'] == $key2) {

        //             $list_metode[$key]['id'] =  $value['id'];
        //             $list_metode[$key]['jumlah'] =  $value['jumlah'];
        //             $list_metode[$key]['totalakhir'] =  $value['totalakhir'];
        //             $list_metode[$key]['id_tracksampel'] =  $value['id_tracksampel'];
        //             $list_metode[$key]['id_parameter'] =  $value['id_parameter'];
        //             $list_metode[$key]['nama'] =  $value['nama'];
        //             $list_metode[$key]['metodeanalisis'] =  $value2;
        //         }
        //     }
        // }
        // dd($list_metode);

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
}
