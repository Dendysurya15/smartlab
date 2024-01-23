<?php

namespace App\Http\Controllers;

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

            $id_jns_sampel = $query->jenis_sampel;
            $jnsSampel = JenisSampel::find($id_jns_sampel);


            $progress_id = $query->progress;
            $last_updates = $query->last_update;

            // dd($last_updates);


            if ($jnsSampel && $last_updates) {
                $kumpulan_progress = explode(',', $jnsSampel->progress);
                $update_progress = explode(',', $last_updates);
                $update_progress = array_map('trim', $update_progress);

                $progress_arr = [];
                $jam_progress_arr = [];

                $count = min(count($kumpulan_progress), count($update_progress)); // Get the minimum size

                for ($key = 0; $key < $count; $key++) {
                    $value = $kumpulan_progress[$key];
                    $progress_arr[] = $queryProgressPengerjaan[$value];
                    $jam_progress_arr[] = $update_progress[$key];
                    if ($value == $progress_id) {
                        break;
                    }
                }



                array_multisort($progress_arr, $jam_progress_arr);



                $query->progress = $progress_arr;
                $query->last_update = $jam_progress_arr;
            } else {
                echo 'JenisSample not found.';
            }
        }



        return response()->json($query);
    }
}
