<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\JenisSampel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class Trackingprogres extends Component
{
    public $progressid;
    public $dataakhir;
    public $resultData;
    public $captchaToken;

    public function render()
    {
        return view('livewire.trackingprogres');
    }

    public function save()
    {
        $kode_input = $this->progressid;
        $token = $this->captchaToken;
        // $token = 0.2;

        $ip = request()->ip();
        $attemptsKey = "captcha_attempts:{$ip}";
        $failedAttempts = Cache::get($attemptsKey, 0);

        // Check if IP is blocked due to multiple failed attempts
        if (Cache::has($ip . '_blocked')) {
            return redirect()->route('blocked');
        }


        // Validate CAPTCHA token
        if ($token > 0.5) {
            // Process the sample data
            $query = TrackSampel::where('kode_track', $kode_input)->first();
            if ($query) {
                $jenisSampel = JenisSampel::find($query->jenis_sampel);
                $queryProgressPengerjaan = ProgressPengerjaan::pluck('nama', 'id')->toArray();
                $record_update = json_decode($query->last_update, true);
                $progres_sampel = explode(',', $jenisSampel->progress);

                $data_update = array_map(function ($id) use ($queryProgressPengerjaan) {
                    return [
                        'id' => $id,
                        'text' => $queryProgressPengerjaan[$id] ?? $id
                    ];
                }, $progres_sampel);

                $progressList = array_column($record_update, 'progress');

                // Initialize the final data array
                $final_data = [];
                $final_step_time = null; // To store the final step time

                foreach ($data_update as $key => $item) {
                    // Default values
                    $final_data[$key] = [
                        'id' => $item['id'],
                        'text' => $item['text'],
                        'time' => null,
                        'status' => 'uncheck'
                    ];

                    // Check if the id exists in the progress list
                    if (in_array($item['id'], $progressList)) {
                        // Find the corresponding record to get the updated_at time
                        foreach ($record_update as $record) {
                            if ($record['progress'] == $item['id']) {
                                $final_data[$key]['time'] = $record['updated_at'];
                                $final_data[$key]['status'] = 'checked';

                                // If this is the final step (id 7), store the time for skipped steps
                                if ($item['id'] == '7') {
                                    $final_step_time = $record['updated_at'];
                                }

                                break; // Exit loop once matching record is found
                            }
                        }
                    }
                }

                // Check if the final step (id 7) is reached and marked as checked
                if ($final_step_time) {
                    // If final step is checked, update all unchecked steps with final step's time
                    foreach ($final_data as &$data) {
                        if ($data['status'] == 'uncheck') {
                            $data['status'] = 'checked';
                            $data['time'] = $final_step_time; // Use the final step's time for skipped steps
                        }
                    }
                }

                // dd($final_data);
                $this->resultData = $final_data;
            } else {
                $this->resultData = 'kosong';
            }

            // Reset failed attempts after successful validation
            Cache::forget($attemptsKey);
        } else {
            // Increment failed attempts
            $failedAttempts++;
            Cache::put($attemptsKey, $failedAttempts, 3600);

            // Block IP after exceeding maximum attempts
            if ($failedAttempts >= 3) {
                Cache::put($ip . '_blocked', true, 3600);
            }

            abort(403, 'Unauthorized action.');
        }
    }
    public function unblockIp(Request $request)
    {
        $ip = $request->ip(); // Or set a specific IP if needed
        Cache::forget($ip . '_failed_attempts');
        Cache::forget($ip . '_blocked');
        return response()->json(['message' => 'IP unblocked']);
    }
}
