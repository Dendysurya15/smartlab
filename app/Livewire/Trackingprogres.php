<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\JenisSampel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormDataExport;
use Dompdf\Dompdf;
use Dompdf\Options;
use NumberToWords\NumberToWords;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class Trackingprogres extends Component
{

    public $captchaResponse = null;
    public $progressid;
    public $dataakhir;
    public $resultData;
    public $sertifikat;
    public $id;
    public $filename;
    public $isDownloading = false;
    public $downloadType;
    public $lastDownloadTime;

    public function render()
    {
        return view('livewire.trackingprogres');
    }

    public function save()
    {
        // Debug: Log captcha response
        Log::info('Captcha response received:', ['response' => $this->captchaResponse]);

        // Validate reCAPTCHA v3
        if (!$this->captchaResponse) {
            Log::warning('No captcha response received');
            session()->flash('error', 'Please complete the captcha verification');
            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key_v3'),
            'response' => $this->captchaResponse
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            session()->flash('error', 'Invalid captcha verification');
            return;
        }

        // Check score for v3 (optional but recommended)
        if (isset($responseData['score']) && $responseData['score'] < 0.5) {
            session()->flash('error', 'Captcha verification failed. Please try again.');
            return;
        }

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

            $final_data = [];
            $final_step_time = null;

            foreach ($data_update as $key => $item) {
                $final_data[$key] = [
                    'id' => $item['id'],
                    'text' => $item['text'],
                    'time' => null,
                    'status' => 'uncheck'
                ];

                if (in_array($item['id'], $progressList)) {
                    foreach ($record_update as $record) {
                        if ($record['progress'] == $item['id']) {
                            $final_data[$key]['time'] = $record['updated_at'];
                            $final_data[$key]['status'] = 'checked';
                            break;
                        }
                    }
                }
            }

            // Find the last completed step's time for each section
            $last_completed_time = null;
            foreach ($record_update as $record) {
                $last_completed_time = $record['updated_at'];
            }

            // Fill in skipped steps
            if ($last_completed_time) {
                $found_incomplete = false;
                foreach ($final_data as $key => $data) {
                    if ($data['status'] === 'checked') {
                        continue;
                    }

                    // Check if there's a later step that's completed
                    $has_later_completion = false;
                    for ($i = $key + 1; $i < count($final_data); $i++) {
                        if ($final_data[$i]['status'] === 'checked') {
                            $has_later_completion = true;
                            break;
                        }
                    }

                    if ($has_later_completion) {
                        $final_data[$key]['status'] = 'checked';
                        $final_data[$key]['time'] = $last_completed_time;
                    }
                }
            }

            // dd($final_data);
            $jenis_sample_final = $query->jenisSampel->nama;
            $carbonDate = Carbon::parse($query->tanggal_memo);
            $dates_final = $carbonDate->format('F');
            $year_final = $carbonDate->format('Y');

            $filename = 'Kupa ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;

            $this->resultData = $final_data;
            $this->sertifikat = $query->sertifikasi;
            $this->id = $query->id;
            $this->filename = $filename;

            // $this->resetCaptcha();
        } else {
            $this->resultData = 'kosong';
        }
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
        try {
            $data = GeneratePdfKupa($this->id, $this->filename);
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);

            $view = view('pdfview.export_kupa', ['data' => $data['data']])->render();
            $dompdf->loadHtml($view);
            $dompdf->setPaper('A2', 'landscape');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                $data['filename'] . '.pdf',
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $data['filename'] . '.pdf"',
                ]
            );
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error generating PDF')
                ->danger()
                ->send();
            return null;
        }
    }

    public function downloadExcel()
    {
        $query = TrackSampel::find($this->id);
        $filename = 'Kupa ' . $query->JenisSampel->nama . '-' .  $query->nomor_kupa . ' ' . tanggal_indo($query->tanggal_terima, false, false, true) . '.xlsx';
        return Excel::download(new FormDataExport($this->id), $filename);
    }


    public function captchaResponse($response)
    {
        $this->captchaResponse = $response;
    }
}
