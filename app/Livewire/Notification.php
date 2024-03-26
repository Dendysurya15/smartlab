<?php

namespace App\Livewire;

use App\Models\TrackSampel;
use Carbon\Carbon;
use Livewire\Component;

class Notification extends Component
{
    public function render()
    {
        $currentDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($currentDate . ' +7 days'));

        $getnotif = TrackSampel::where('estimasi', '>=', $currentDate)
            ->where('estimasi', '<=', $endDate)
            ->get();

        $data = [];

        foreach ($getnotif as $value) {
            $estimasiDate = $value['estimasi'];
            $diffDays = date_diff(date_create($currentDate), date_create($estimasiDate))->days;

            $daysLeft = $diffDays > 0 ? "$diffDays Hari Tersisa" : ($diffDays == 0 ? "Today" : abs($diffDays) . " days ago");

            $data[] = [
                'id' => $value['id'],
                'text' => "Kode Track {$value['kode_track']} - $daysLeft",
                'track' => $value['kode_track'],
                'currentDate' => $currentDate,
            ];
        }

        return view('livewire.notification', ['data' => $data]);
    }
}
