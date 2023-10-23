<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputProgressRequest;
use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistorySampel extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        $progressStr = JenisSampel::find($query->jenis_sample)->progress;

        $arr_progress = explode(',', $progressStr);

        $progressOptions = [];

        foreach ($arr_progress as $progressId) {
            $queryProgress = ProgressPengerjaan::find($progressId);
            $progressOptions[$queryProgress->id] = $queryProgress->nama;
        }

        return view('pages/historySampel/edit', ['sampel' => $query, 'jenis_sampel' => $jns_sampel, 'progress_sampel' => $progressOptions]);
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
