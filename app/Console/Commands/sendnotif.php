<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrackSampel;
use Carbon\Carbon;

class sendnotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sendnotif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Illuminate\Support\Facades\Log::info('Task executed at: ' . now());

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
        $now = Carbon::now();
        $jumdata = count($data);
        $nohp = '081349807050';

        // Determine the greeting based on the time of day
        if ($now->hour >= 5 && $now->hour < 12) {
            $greeting = "Selamat Pagi";
        } elseif ($now->hour >= 12 && $now->hour < 18) {
            $greeting = "Selamat Siang";
        } else {
            $greeting = "Selamat Malam";
        }
        $dataarr = "$greeting\n"
            . "Pemberitahuan Penting\n"
            . "Kami ingin memberitahukan bahwa Anda memiliki *{$jumdata}* data estimasi yang mendekati batas waktu.\n"
            . "Mohon untuk memeriksanya melalui sistem kami.\n"
            . "Data tenggat dapat dilihat di website https://smartlab.srs-ssms.com/ .\n"
            . "*Perhatian* : _ini adalah pesan otomatis dari sistem_.\n";


        if ($jumdata != 0) {
            sendwhatsapp($dataarr, $nohp);
        } else {
            \Illuminate\Support\Facades\Log::info('No data');
        }
    }
}
