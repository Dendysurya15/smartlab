<?php

namespace App\Http\Controllers;

use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrackSampelController extends Controller
{

    public function index()
    {
        //

        return view('pages/trackingSampel/index');
    }

    public function search(Request $request)
    {

        $kode_input = $request->get('kode');

        $query = TrackSampel::where('kode_track', $kode_input)->first();


        if ($query) {
            $queryProgressPengerjaan = ProgressPengerjaan::pluck('nama', 'id')->toArray();
            $id_jns_sample = $query->jenis_sample;
            $jnsSample = JenisSampel::find($id_jns_sample);
            $progress_id = $query->progress;
            if ($jnsSample) {
                $kumpulan_progress = explode(',', $jnsSample->progress);

                $progress_arr = [];
                foreach ($kumpulan_progress as $value) {
                    $progress_arr[] = $queryProgressPengerjaan[$value];
                    if ($value == $progress_id) {
                        break;
                    }
                }
                $query->progress = $progress_arr;
            } else {
                echo 'JenisSample not found.';
            }
            $date = Carbon::parse($query->last_update);
            $formattedDate = $date->format('Y-m-d H:i');
            $query->last_update = tanggal_indo($formattedDate);
        }

        return response()->json($query);
    }
}
