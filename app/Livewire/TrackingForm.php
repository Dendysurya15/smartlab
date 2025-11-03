<?php

namespace App\Livewire;

use App\Models\TrackSampel;
use App\Models\ProgressPengerjaan;
use Livewire\Component;

class TrackingForm extends Component
{
    public $kode = '';
    public $trackSampel = null;
    public $progressData = [];
    public $isSearching = false;
    public $isDownloading = false;
    public $error = '';

    protected $rules = [
        'kode' => 'required|string|max:255',
    ];

    protected $messages = [
        'kode.required' => 'Kode tracking harus diisi',
    ];

    public function search()
    {
        $this->error = '';
        $this->trackSampel = null;
        $this->progressData = [];

        $this->validate();

        $this->isSearching = true;

        try {
            // Cari data berdasarkan kode tracking
            $this->trackSampel = TrackSampel::where('kode_track', $this->kode)->first();

            if (!$this->trackSampel) {
                $this->error = 'Kode tracking tidak ditemukan';
                return;
            }

            // Ambil progress pengerjaan dari sistem lama
            if ($this->trackSampel) {
                $jenisSampel = \App\Models\JenisSampel::find($this->trackSampel->jenis_sampel);
                $queryProgressPengerjaan = \App\Models\ProgressPengerjaan::pluck('nama', 'id')->toArray();
                $record_update = json_decode($this->trackSampel->last_update, true);

                if ($jenisSampel && $record_update) {
                    $progres_sampel = explode(',', $jenisSampel->progress);

                    $data_update = array_map(function ($id) use ($queryProgressPengerjaan) {
                        return [
                            'id' => $id,
                            'text' => $queryProgressPengerjaan[$id] ?? $id
                        ];
                    }, $progres_sampel);

                    $progressList = array_column($record_update, 'progress');

                    $final_data = [];

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

                    // Fill in skipped steps
                    $last_completed_time = null;
                    foreach ($record_update as $record) {
                        $last_completed_time = $record['updated_at'];
                    }

                    if ($last_completed_time) {
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

                    $this->progressData = $final_data;
                }
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat mencari data tracking: ' . $e->getMessage();
        } finally {
            $this->isSearching = false;
        }
    }

    public function clear()
    {
        $this->kode = '';
        $this->trackSampel = null;
        $this->progressData = [];
        $this->error = '';
    }

    public function downloadKupa()
    {
        if (!$this->trackSampel) {
            $this->error = 'Tidak ada data sampel untuk didownload';
            return;
        }

        $this->isDownloading = true;
        $this->error = '';

        try {
            // Generate PDF KUPA using the same function as the old system
            $data = GeneratePdfKupa($this->trackSampel->id, $this->trackSampel->filename ?? 'KUPA');
            $options = new \Dompdf\Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);

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
            $this->error = 'Terjadi kesalahan saat membuat PDF KUPA: ' . $e->getMessage();
        } finally {
            $this->isDownloading = false;
        }
    }

    public function downloadCertificate()
    {
        if (!$this->trackSampel) {
            $this->error = 'Tidak ada data sampel untuk didownload';
            return;
        }

        $this->isDownloading = true;
        $this->error = '';

        try {
            $files = explode(',', $this->trackSampel->sertifikasi ?? '');
            $fotoSampel = [];

            // Handle foto_sampel - split by %\/ if it's a string, otherwise use as array
            $fotoSampelData = $this->trackSampel->foto_sampel;
            if (is_string($fotoSampelData) && !empty($fotoSampelData)) {
                // Split by %\/ and clean up each filename
                $fotos = array_filter(explode('%\/', $fotoSampelData));
                $fotoSampel = array_map(function ($foto) {
                    // Remove quotes and clean up the path
                    return trim($foto, '"\'');
                }, $fotos);
            } elseif (is_array($fotoSampelData)) {
                $fotoSampel = $fotoSampelData;
            }

            // If there's only one certificate file and no sample photos, download it directly
            if (count($files) === 1 && empty($fotoSampel)) {
                $filepath = storage_path('app/private/' . $files[0]);
                if (file_exists($filepath)) {
                    return response()->download($filepath);
                }
                $this->error = 'File sertifikat tidak ditemukan';
                return;
            }

            // If there are multiple files or sample photos, create a zip
            $zip = new \ZipArchive();
            $zipName = 'certificate_' . $this->trackSampel->kode_track . '_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipName);

            // Create temp directory if it doesn't exist
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zipResult = $zip->open($zipPath, \ZipArchive::CREATE);

            if ($zipResult === TRUE) {
                $filesAdded = 0;

                // Add certificate files
                foreach ($files as $file) {
                    $filepath = storage_path('app/private/' . trim($file));
                    if (file_exists($filepath)) {
                        $result = $zip->addFile($filepath, 'Sertifikat/' . basename($filepath));
                        if ($result) $filesAdded++;
                    }
                }

                // Add sample photos if they exist
                if (!empty($fotoSampel)) {
                    foreach ($fotoSampel as $photo) {
                        // Clean the photo path and try both public and private locations
                        $cleanPhoto = ltrim(trim($photo), '/');
                        $photoPathPublic = storage_path('app/public/' . $cleanPhoto);
                        $photoPathPrivate = storage_path('app/private/' . $cleanPhoto);

                        $photoPath = null;
                        if (file_exists($photoPathPublic)) {
                            $photoPath = $photoPathPublic;
                        } elseif (file_exists($photoPathPrivate)) {
                            $photoPath = $photoPathPrivate;
                        }

                        if ($photoPath) {
                            $result = $zip->addFile($photoPath, 'Foto_Sampel/' . basename($photoPath));
                            if ($result) $filesAdded++;
                        }
                    }
                }

                $closeResult = $zip->close();

                // Check if zip file was created successfully
                if (file_exists($zipPath)) {
                    return response()->download($zipPath)->deleteFileAfterSend(true);
                } else {
                    $this->error = 'Gagal membuat file zip';
                }
            } else {
                $this->error = 'Tidak dapat membuat file zip';
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat mendownload: ' . $e->getMessage();
        } finally {
            $this->isDownloading = false;
        }
    }

    public function downloadSamplePhotos()
    {
        if (!$this->trackSampel) {
            $this->error = 'Tidak ada data sampel untuk didownload';
            return;
        }

        $this->isDownloading = true;
        $this->error = '';

        try {
            $fotoSampel = [];

            // Handle foto_sampel - split by %\/ if it's a string, otherwise use as array
            $fotoSampelData = $this->trackSampel->foto_sampel;
            if (is_string($fotoSampelData) && !empty($fotoSampelData)) {
                // Split by %\/ and clean up each filename
                $fotos = array_filter(explode('%\/', $fotoSampelData));
                $fotoSampel = array_map(function ($foto) {
                    // Remove quotes and clean up the path
                    return trim($foto, '"\'');
                }, $fotos);
            } elseif (is_array($fotoSampelData)) {
                $fotoSampel = $fotoSampelData;
            }

            if (empty($fotoSampel)) {
                $this->error = 'Tidak ada foto sampel untuk didownload';
                return;
            }

            // If there's only one photo, download it directly
            if (count($fotoSampel) === 1) {
                $photo = $fotoSampel[0];
                $cleanPhoto = ltrim(trim($photo), '/');
                $photoPathPublic = storage_path('app/public/' . $cleanPhoto);
                $photoPathPrivate = storage_path('app/private/' . $cleanPhoto);

                $photoPath = null;
                if (file_exists($photoPathPublic)) {
                    $photoPath = $photoPathPublic;
                } elseif (file_exists($photoPathPrivate)) {
                    $photoPath = $photoPathPrivate;
                }

                if ($photoPath) {
                    return response()->download($photoPath, 'Foto_Sampel_' . $this->trackSampel->kode_track . '_' . basename($photoPath));
                } else {
                    $this->error = 'File foto sampel tidak ditemukan';
                    return;
                }
            }

            // If there are multiple photos, create a zip
            $zip = new \ZipArchive();
            $zipName = 'foto_sampel_' . $this->trackSampel->kode_track . '_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipName);

            // Create temp directory if it doesn't exist
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zipResult = $zip->open($zipPath, \ZipArchive::CREATE);

            if ($zipResult === TRUE) {
                $filesAdded = 0;

                // Add sample photos
                foreach ($fotoSampel as $photo) {
                    // Clean the photo path and try both public and private locations
                    $cleanPhoto = ltrim(trim($photo), '/');
                    $photoPathPublic = storage_path('app/public/' . $cleanPhoto);
                    $photoPathPrivate = storage_path('app/private/' . $cleanPhoto);

                    $photoPath = null;
                    if (file_exists($photoPathPublic)) {
                        $photoPath = $photoPathPublic;
                    } elseif (file_exists($photoPathPrivate)) {
                        $photoPath = $photoPathPrivate;
                    }

                    if ($photoPath) {
                        $result = $zip->addFile($photoPath, 'Foto_Sampel_' . basename($photoPath));
                        if ($result) $filesAdded++;
                    }
                }

                $closeResult = $zip->close();

                // Check if zip file was created successfully
                if (file_exists($zipPath)) {
                    return response()->download($zipPath)->deleteFileAfterSend(true);
                } else {
                    $this->error = 'Gagal membuat file zip';
                }
            } else {
                $this->error = 'Tidak dapat membuat file zip';
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat mendownload foto sampel: ' . $e->getMessage();
        } finally {
            $this->isDownloading = false;
        }
    }

    public function render()
    {
        return view('livewire.tracking-form');
    }
}
