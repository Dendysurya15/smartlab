<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\JenisSampel;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Trackingprogres extends Component
{
    public $progressid;
    public $dataakhir;
    public $resultData;
    public $captchaToken;
    public $sertifikat;
    public $id;
    public $filename;
    public $isDownloading = false;
    public $downloadType;
    public $lastDownloadTime;
    public $v2Token;
    public $v3Token;

    // Add this listener
    protected $listeners = ['setCaptchaToken'];

    public function render()
    {
        return view('livewire.trackingprogres');
    }

    public function save()
    {
        if (!$this->captchaToken) {
            session()->flash('error', 'reCAPTCHA verification failed');
            return;
        }

        try {
            // Verify with Google
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key_v3'),
                'response' => $this->captchaToken,
            ]);

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                session()->flash('error', 'reCAPTCHA verification failed');
                return;
            }

            // Continue with your existing code...
            $kode_input = $this->progressid;
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
                $jenis_sample_final = $query->jenisSampel->nama;
                $carbonDate = Carbon::parse($query->tanggal_memo);
                $dates_final = $carbonDate->format('F');
                $year_final = $carbonDate->format('Y');

                $filename = 'Kupa ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                $this->resultData = $final_data;
                $this->sertifikat = $query->sertifikasi;
                $this->id = $query->id;
                $this->filename = $filename;
            } else {
                $this->resultData = 'kosong';
            }
        } catch (\Exception $e) {
            Log::error('Verification error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred during verification');
            return;
        }
    }

    public function unblockIp(Request $request)
    {
        $ip = $request->ip(); // Or set a specific IP if needed
        Cache::forget($ip . '_failed_attempts');
        Cache::forget($ip . '_blocked');
        return response()->json(['message' => 'IP unblocked']);
    }

    public function downloadSertifikat()
    {
        $files = explode(',', $this->sertifikat);

        // If there's only one file, download it directly
        if (count($files) === 1) {
            $filepath = storage_path('app/private/' . $files[0]);
            if (file_exists($filepath)) {
                return response()->download($filepath);
            }
            abort(404, 'File not found');
        }

        // If there are multiple files, create a zip
        $zip = new \ZipArchive();
        $zipName = 'sertifikat_' . time() . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filepath = storage_path('app/private/' . trim($file));
                if (file_exists($filepath)) {
                    $zip->addFile($filepath, basename($filepath));
                }
            }
            $zip->close();

            // Download zip file and then delete it
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        abort(500, 'Could not create zip file');
    }

    public function downloadPdf()
    {
        // if ($this->isDownloading) {
        //     session()->flash('error', 'Download in progress');
        //     return;
        // }

        // // Check if captcha token exists
        // if (!$this->captchaToken) {
        //     session()->flash('error', 'Please complete the captcha verification');
        //     return;
        // }

        // try {
        //     // Verify captcha
        //     $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //         'secret' => config('services.recaptcha.secret_key_v2'),
        //         'response' => $this->captchaToken
        //     ]);

        //     $result = $response->json();

        //     if (!isset($result['success']) || !$result['success']) {
        //         session()->flash('error', 'Captcha verification failed');
        //         return;
        //     }

        //     $this->isDownloading = true;
        //     $this->downloadType = 'pdf';
        //     $this->captchaToken = null;

        //     return redirect()->route('exporpdfkupa', ['id' => $this->id, 'filename' => $this->filename]);
        // } catch (\Exception $e) {
        //     Log::error('PDF download error:', ['error' => $e->getMessage()]);
        //     session()->flash('error', 'An error occurred during download');
        //     return;
        // }


        $this->isDownloading = true;
        $this->downloadType = 'pdf';
        $this->captchaToken = null;

        return redirect()->route('exporpdfkupa', ['id' => $this->id, 'filename' => $this->filename]);
    }

    public function downloadExcel()
    {
        if ($this->isDownloading || $this->isDownloadTooFrequent()) {
            return;
        }

        // Wait for captcha token to be set by JavaScript
        if (!$this->captchaToken || $this->captchaToken <= 0.5) {
            session()->flash('error', 'Please verify that you are human.');
            return;
        }

        $ip = request()->ip();
        $attemptsKey = "download_attempts:{$ip}";
        $failedAttempts = Cache::get($attemptsKey, 0);

        // Check if IP is blocked
        if (Cache::has($ip . '_download_blocked')) {
            return redirect()->route('blocked');
        }

        $this->isDownloading = true;
        $this->downloadType = 'excel';
        $this->lastDownloadTime = now();

        // Reset captcha token after successful use
        $this->captchaToken = null;

        return redirect()->route('export.excel', ['id' => $this->id]);
    }

    private function isDownloadTooFrequent()
    {
        $now = now();
        $lastDownload = Carbon::parse($this->lastDownloadTime);
        $diffInMinutes = $now->diffInMinutes($lastDownload);

        return $diffInMinutes < 1;
    }

    public function setCaptchaToken($token)
    {
        Log::info('Received token data:', ['token' => $token]);
        $this->captchaToken = $token;

        // Only execute save() if this is a form submission
        if ($this->progressid) {
            $this->save();
        }
    }
}
