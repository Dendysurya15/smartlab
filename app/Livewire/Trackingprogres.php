<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\JenisSampel;

class Trackingprogres extends Component
{
    public $progressid;
    public $dataakhir;
    public $resultData;

    public function render()
    {
        return view('livewire.trackingprogres');
    }

    public function save()
    {
        // dd($this->progressid);
        $kode_input = $this->progressid;

        // dd($kode_input);

        $query = TrackSampel::where('kode_track', $kode_input)->first();
        // dd($query);
        if ($query) {

            $queryProgressPengerjaan = ProgressPengerjaan::pluck('nama', 'id')->toArray();
            $progress_id = $query->progress;
            $last_updates = explode('$', $query->last_update);

            // dd($last_updates);
            // Assuming $progres is your array of progress stages
            $result = array_filter($queryProgressPengerjaan, function ($key) use ($progress_id) {
                return $key <= $progress_id;
            }, ARRAY_FILTER_USE_KEY);

            $reformattedArray = array_values($result);

            // dd($reformattedArray);
            $data = [];

            $count = min(count($reformattedArray), count($last_updates)); // Get the minimum count to avoid undefined index errors

            for ($i = 0; $i < $count; $i++) {
                $data[] = [
                    'text' => $reformattedArray[$i] ?? null, // Use null if the index is undefined in $result
                    'date' => $last_updates[$i] ?? null, // Use null if the index is undefined in $last_updates
                ];
            }

            $data = array_values($data);

            $this->resultData = $data;
            // dd($this->resultData);
        } else {
            $data = 'kosong';
            $this->resultData = $data;
        }
    }
}
