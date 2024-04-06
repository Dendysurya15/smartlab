<?php

namespace App\Livewire;

use App\Models\TrackSampel;
use Carbon\Carbon;
use Livewire\Component;
use Filament\Notifications\Notification as Notif;

class Notification extends Component
{
    public $idform = [];
    public function render()
    {
        $currentDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($currentDate . ' +7 days'));

        $getnotif = TrackSampel::where('estimasi', '>=', $currentDate)
            ->where('estimasi', '<=', $endDate)
            ->where('notif', '!=', 1)
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

        return view('livewire.notification', [
            'data' => $data,
        ]);
    }

    public function save()
    {
        // Access the selected IDs from the $idform variable
        $selectedIds = array_keys(array_filter($this->idform));

        try {
            // Update the 'notif' column to 1 for the selected IDs
            TrackSampel::whereIn('id', $selectedIds)->update(['notif' => 1]);

            // Send a success notification
            Notif::make()
                ->title('Notifikasi diupdate')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            // Send an error notification with the exception message
            Notif::make()
                ->title('Error')
                ->error()
                ->body($th->getMessage())
                ->send();
        }
    }
}
