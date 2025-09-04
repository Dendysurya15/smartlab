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
    public $fotoSampel; // Add new property for sample photos

    // Property untuk menyimpan tracking code sebelumnya
    public $previousProgressId = null;

    public function render()
    {
        // Pastikan data ter-reset jika progressid kosong
        if (empty(trim($this->progressid))) {
            $this->resetData();
        }

        return view('livewire.trackingprogres');
    }

    // Method untuk reset semua data
    public function resetData()
    {
        $this->resultData = null;
        $this->sertifikat = null;
        $this->id = null;
        $this->filename = null;
        $this->dataakhir = null;
        $this->captchaResponse = null;
        $this->fotoSampel = null;
    }

    // Method untuk memantau perubahan progressid
    public function updatedProgressid()
    {
        // Reset data setiap kali progressid berubah
        $this->resetData();
        $this->previousProgressId = $this->progressid;
    }

    // Method untuk reset data ketika form di-submit
    public function resetForm()
    {
        $this->resetData();
        $this->previousProgressId = null;
    }

    // Method untuk handle input kosong
    public function clearInput()
    {
        $this->progressid = '';
        $this->resetData();
        $this->previousProgressId = null;
    }

    public function save()
    {
        // Validasi input tidak boleh kosong
        if (empty(trim($this->progressid))) {
            session()->flash('error', 'Kode tracking tidak boleh kosong');
            return;
        }

        // PENTING: Hapus dd() dan tambahkan logging untuk debug
        Log::info('Captcha response in save method:', [
            'captchaResponse' => $this->captchaResponse,
            'progressid' => $this->progressid
        ]);

        // Jika captcha masih null, coba refresh dulu
        if (!$this->captchaResponse) {
            // Skip captcha in development environment
            if (config('app.env') === 'local' || config('app.debug')) {
                Log::info('Skipping captcha verification in development environment');
            } else {
                $this->dispatch('refresh-captcha'); // Emit event ke JavaScript
                session()->flash('error', 'Please wait for captcha verification to complete. If this persists, please refresh the page.');
                return;
            }
        }
        // Skip reCAPTCHA verification in development environment
        if (config('app.env') === 'local' || config('app.debug')) {
            Log::info('Skipping reCAPTCHA verification in development environment');
        } else {
            // Log the secret key being used (for debugging)
            $secretKey = config('services.recaptcha.secret_key_v3');
            Log::info('Using secret key:', ['secret' => $secretKey ? 'set' : 'not set']);

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $this->captchaResponse
            ]);

            $responseData = $response->json();
            Log::info('reCAPTCHA verification response:', $responseData);

            if (!$responseData['success']) {
                Log::error('reCAPTCHA verification failed:', $responseData);
                session()->flash('error', 'Invalid captcha verification');
                return;
            }

            // Check score for v3 (optional but recommended)
            if (isset($responseData['score']) && $responseData['score'] < 0.5) {
                Log::warning('reCAPTCHA score too low:', ['score' => $responseData['score']]);
                session()->flash('error', 'Captcha verification failed. Please try again.');
                return;
            }
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
            // Handle foto_sampel - split by % if it's a string, otherwise use as array
            $fotoSampelData = $query->foto_sampel;
            if (is_string($fotoSampelData) && !empty($fotoSampelData)) {
                // Split by % and clean up each filename
                $fotos = array_filter(explode('%', $fotoSampelData));
                $this->fotoSampel = array_map(function ($foto) {
                    // Remove quotes and clean up the path
                    return trim($foto, '"\'');
                }, $fotos);
            } elseif (is_array($fotoSampelData)) {
                $this->fotoSampel = $fotoSampelData;
            } else {
                $this->fotoSampel = [];
            }

            // Update previous progress ID
            $this->previousProgressId = $this->progressid;

            // $this->resetCaptcha();
        } else {
            $this->resultData = 'kosong';
            // Update previous progress ID even when no data found
            $this->previousProgressId = $this->progressid;
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

    public function refreshCaptcha()
    {
        // This method will be called from JavaScript to refresh captcha
        $this->captchaResponse = null;
        Log::info('Captcha refreshed');
    }
}
