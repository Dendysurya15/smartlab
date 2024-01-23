<?php

namespace App\Http\Controllers;

use App\Models\JenisSampel;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InputProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $jns_sampel = JenisSampel::pluck('nama', 'kode');


        return view('pages/inputProgress/index', [
            'jenis_sampel' => $jns_sampel
        ]);
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
    public function store(InputProgressRequest $request)
    {
        $userId = 1;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;
        }

        do {
            $randomCode = generateRandomCode();
            $existingTrackSampel = TrackSampel::where('kode_Track', $randomCode)->first();
        } while ($existingTrackSampel);

        $current = Carbon::now();

        $current = $current->format('Y-m-d H:i:s');

        $jns_sampel = JenisSampel::where('nama', $request->input('jns_sam'))->first();

        try {
            DB::beginTransaction(); // Start a database transaction

            $trackSampel = new TrackSampel();
            $trackSampel->tanggal_penerimaan = $request->input('tanggal_penerimaan');
            $trackSampel->jenis_sample = $jns_sampel->id;
            $trackSampel->asal_sampel = $request->input('asal_sam');
            $trackSampel->nomor_kupa = $request->input('no_kupa');
            $trackSampel->nama_pengirim = $request->input('nama_pelanggan');
            $trackSampel->departemen = $request->input('departemen');
            $trackSampel->kode_sample = $request->input('kode_sampel');
            $trackSampel->nomor_surat = $request->input('no_surat');
            $trackSampel->nomor_lab = $request->input('no_lab');
            $trackSampel->estimasi = $request->input('estimasi');
            $trackSampel->tujuan = $request->input('tujuan');
            $trackSampel->parameter_analisis = $request->input('parameter_analisis');
            $trackSampel->admin = $userId;
            $trackSampel->progress = 4;
            $trackSampel->last_update = $current;
            $trackSampel->no_hp = $request->input('no_hp');
            $trackSampel->email = $request->input('email');
            $trackSampel->kode_track = $randomCode;

            if ($request->hasFile('file-upload')) {
                $file = $request->file('file-upload');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $fileName, 'public');
                $trackSampel->foto_sample = $fileName;
            }
            $trackSampel->save();

            DB::commit(); // Commit the database transaction

            return redirect()->route('input_progress.index')->with('success', $randomCode);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the database transaction in case of an error

            return redirect()->back()->with('error', 'An error occurred while storing the record: ' . $e->getMessage());
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
