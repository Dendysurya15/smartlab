<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use App\Models\JenisSampel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Trackingprogres extends Component
{
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
        try {
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

                                if ($item['id'] == '7') {
                                    $final_step_time = $record['updated_at'];
                                }
                                break;
                            }
                        }
                    }
                }

                if ($final_step_time) {
                    foreach ($final_data as &$data) {
                        if ($data['status'] == 'uncheck') {
                            $data['status'] = 'checked';
                            $data['time'] = $final_step_time;
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
            Log::error('Error in save method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred while processing your request');
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
        // dd('test');
        $this->isDownloading = true;
        $this->downloadType = 'pdf';
        return redirect()->route('exporpdfkupa', ['id' => $this->id, 'filename' => $this->filename]);
    }

    public function downloadExcel()
    {
        if ($this->isDownloading || $this->isDownloadTooFrequent()) {
            return;
        }

        $this->isDownloading = true;
        $this->downloadType = 'excel';
        $this->lastDownloadTime = now();

        return redirect()->route('export.excel', ['id' => $this->id]);
    }

    private function isDownloadTooFrequent()
    {
        $now = now();
        $lastDownload = Carbon::parse($this->lastDownloadTime);
        $diffInMinutes = $now->diffInMinutes($lastDownload);

        return $diffInMinutes < 1;
    }
}
