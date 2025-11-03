<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TrackSampel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Cknow\Money\Money;
use App\Models\ParameterAnalisis;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Fpdi;

class Generatebulkpdfpr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $filename;
    public $user_id;
    public $totalItems;
    public $random_task_id;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $filename, $user_id, $totalItems, $random_task_id)
    {
        $this->data = $data;
        $this->filename = $filename;
        $this->user_id = $user_id;
        $this->totalItems = $totalItems;
        $this->random_task_id = $random_task_id;
        $this->onQueue('pdf_generation_task');
    }

    /**
     * Execute the job.
     */
    public function handle(): array
    {
        // Add error handling wrapper around the entire process
        try {
            $id = explode('$', $this->data);
            // Set a minimum chunk size
            $minChunkSize = 5;
            $idealChunkSize = 10;
            $totalItems = count($id);

            // Determine appropriate chunk size based on total items
            $chunkSize = $totalItems <= $minChunkSize ? $totalItems : ($totalItems <= $idealChunkSize ? floor($totalItems / 2) : $idealChunkSize);

            $totalChunks = ceil(count($id) / $chunkSize);
            Log::info('Processing IDs count: ' . count($id) . ' with chunk size: ' . $chunkSize);

            // Initial progress broadcast
            // event(new PdfExportProgress(0, $this->user_id, null, 0, $totalChunks));

            $tempPdfs = [];
            $allJenisSamples = [];
            $tanggal_terima = [];
            // If total items is very small, process without chunking
            if ($totalItems <= $minChunkSize) {
                Log::info("Processing small dataset without chunking");

                $queries = TrackSampel::whereIn('id', $id)
                    ->with(['trackParameters', 'progressSampel', 'jenisSampel'])
                    ->get()
                    ->groupBy(['jenis_sampel', 'nomor_kupa']);

                $result = $this->processChunk($queries);

                foreach ($result as $key => $value) {
                    if (isset($value['jenis'])) {
                        $allJenisSamples[] = $value['jenis'];
                    }
                }

                $finalFileName = 'PDF Kupa,' . implode(',', array_unique($allJenisSamples)) . $this->random_task_id . '.pdf';
                $finalFilePath = storage_path('app/public/temp/' . $finalFileName);

                // Generate single PDF directly
                $pdf = Pdf::setPaper('A2', 'landscape');
                $pdf->setOptions([
                    'dpi' => 100,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'chroot' => public_path(),
                    'debugPng' => false,
                    'debugKeepTemp' => false,
                ]);

                $pdf->loadView('pdfview.vrdata', $result);
                $pdf->save($finalFilePath);
                return [
                    'filepath' => $finalFilePath,
                    'filename' => $finalFileName
                ];
            }

            // Original chunking logic for larger datasets
            collect($id)->chunk($chunkSize)->each(function ($chunk, $index) use (&$tempPdfs, &$allJenisSamples, $totalChunks) {
                Log::info("Processing chunk {$index} with " . count($chunk) . " items");
                $queries = TrackSampel::whereIn('id', $chunk->toArray())
                    ->with(['trackParameters', 'progressSampel', 'jenisSampel'])
                    ->get()
                    ->groupBy(['jenis_sampel', 'nomor_kupa']);

                $result = $this->processChunk($queries);

                // Collect jenis samples
                foreach ($result as $key => $value) {
                    if (isset($value['jenis'])) {
                        $allJenisSamples[] = $value['jenis'];
                    }
                }

                // $data = $result;

                // Generate temporary PDF for this chunk
                $tempFileName = "chunk_{$index}_" . uniqid() . '.pdf';
                $tempFilePath = storage_path("app/public/temp/{$tempFileName}");

                $pdf = Pdf::setPaper('A2', 'landscape');
                $pdf->setOptions([
                    'dpi' => 100,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'chroot' => public_path(),
                    'debugPng' => false,
                    'debugKeepTemp' => false,
                ]);

                $pdf->loadView('pdfview.vrdata', $result);
                $pdf->save($tempFilePath);

                $tempPdfs[] = $tempFilePath;

                Log::info("Chunk {$index} PDF generated: {$tempFileName}");
            });

            // Broadcast merging status
            // event(new PdfExportProgress(90, $this->user_id, null, $totalChunks, $totalChunks, 'Merging PDFs...'));

            // Merge PDFs
            $jenissamplefix = implode(',', array_unique($allJenisSamples));
            $finalFileName = 'PDF Kupa,' . $jenissamplefix . $this->random_task_id . '.pdf';
            $finalFilePath = storage_path('app/public/temp/' . $finalFileName);

            $this->mergePDFs($tempPdfs, $finalFilePath);

            // Cleanup temporary files
            foreach ($tempPdfs as $tempPdf) {
                if (File::exists($tempPdf)) {
                    File::delete($tempPdf);
                }
            }

            Log::info('Final PDF generated: ' . $finalFileName);
            // event(new PdfExportProgress(
            //     100,
            //     $this->user_id,
            //     $finalFileName,
            //     $totalChunks,
            //     $totalChunks,
            //     'Finished'
            // ));

            return [
                'filepath' => $finalFilePath,
                'filename' => $finalFileName
            ];
        } catch (\Exception $e) {
            Log::error('PDF Generation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function mergePDFs($pdfFiles, $outputPath)
    {
        try {
            $pdf = new Fpdi();

            // Add memory limit check
            $memoryLimit = ini_get('memory_limit');
            Log::info("Current memory limit: " . $memoryLimit);

            foreach ($pdfFiles as $file) {
                if (!file_exists($file)) {
                    Log::error("PDF file not found: " . $file);
                    continue;
                }

                try {
                    // Add file size check
                    $fileSize = filesize($file);
                    Log::info("Processing file: " . $file . " (size: " . ($fileSize / 1024 / 1024) . " MB)");

                    // Check if file is valid PDF
                    if (!$this->isValidPDF($file)) {
                        Log::error("Invalid PDF file: " . $file);
                        continue;
                    }

                    $pageCount = $pdf->setSourceFile($file);

                    for ($i = 1; $i <= $pageCount; $i++) {
                        // Add page-level error handling
                        try {
                            $template = $pdf->importPage($i);
                            $size = $pdf->getTemplateSize($template);
                            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                            $pdf->useTemplate($template);
                        } catch (\Exception $e) {
                            Log::error("Error processing page {$i} of file {$file}: " . $e->getMessage());
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing PDF file: " . $file . " - " . $e->getMessage());
                    // Continue with next file instead of failing completely
                    continue;
                }
            }

            // Use buffer to prevent partial writes
            $tempOutput = tempnam(sys_get_temp_dir(), 'pdf_');
            $pdf->Output('F', $tempOutput);

            if (file_exists($tempOutput)) {
                rename($tempOutput, $outputPath);
            } else {
                throw new \Exception("Failed to create temporary output file");
            }
        } catch (\Exception $e) {
            Log::error("PDF merge failed: " . $e->getMessage());
            throw $e;
        }
    }

    // Add new helper method to validate PDF files
    private function isValidPDF($file)
    {
        try {
            $handle = fopen($file, "rb");
            if ($handle === false) {
                return false;
            }

            // Read first 4 bytes to check PDF signature
            $signature = fread($handle, 4);
            fclose($handle);

            return $signature === "%PDF";
        } catch (\Exception $e) {
            Log::error("PDF validation failed for file {$file}: " . $e->getMessage());
            return false;
        }
    }

    private function processChunk($queries)
    {
        $result = [];
        foreach ($queries as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $kode_sampel = [];
                $nomor_lab = [];
                $nama_parameter = [];
                foreach ($value1 as $key2 => $value2) {
                    $jenissample = $value2->jenisSampel->nama;
                    $jenissample_komuditas = $value2->jenis_pupuk ?? 'Tidak tersedia';
                    $jumlahsample = $value2['jumlah_sampel'];
                    $catatan = $value2['catatan'];
                    $kdsmpel = $value2['kode_sampel'];
                    $nolab = $value2['nomor_lab'];
                    $trackparam = $value2->trackParameters;
                    $carbonDate = Carbon::parse($value2['tanggal_terima'])->locale('id')->translatedFormat('d F Y');
                    $carbonDate2 = Carbon::parse($value2['estimasi'])->locale('id')->translatedFormat('d F Y');
                    $nama_parameter = [];
                    $hargatotal = 0;
                    $jumlah_per_parametertotal = 0;
                    $hargaasli = [];
                    $harga_total_per_sampel = [];
                    $jumlah_per_parameter = [];
                    $namakode_sampelparams = [];
                    foreach ($trackparam as $trackParameter) {

                        if ($trackParameter->ParameterAnalisis) {
                            $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                            $hargaasli[] =  Money::IDR($trackParameter->ParameterAnalisis->harga, true);
                            $harga_total_per_sampel[] = Money::IDR($trackParameter->totalakhir, true);
                            $jumlah_per_parameter[] = $trackParameter->jumlah;

                            $statuspaket = $trackParameter->ParameterAnalisis->paket_id;

                            if ($statuspaket != null) {
                                $paket = explode('$', $statuspaket);
                                $params = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                                // $nama_parameter[] = $nama_params;
                                // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                                $namakode_sampelparams[implode(',', $params)] =  explode('$', $trackParameter->namakode_sampel);
                            } else {
                                // $nama_parameter[] = $namaunsur;
                                $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_unsur] = explode('$', $trackParameter->namakode_sampel);
                            }

                            // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
                        }
                        $hargatotal += $trackParameter->totalakhir;
                        $jumlah_per_parametertotal += $trackParameter->jumlah;
                    }
                    $harga_total_dengan_ppn = Money::IDR(hitungPPN($hargatotal), true);
                    $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($hargatotal, true));

                    $discountDecimal = $value2->discount != 0 ? $value2->discount / 100 : 0;
                    $discount = $totalppn_harga->multiply($discountDecimal);

                    $total_akhir = $totalppn_harga->subtract($discount);
                    $newnamaparameter = [];

                    // dd($namakode_sampelparams);

                    $sampel_data = [];

                    foreach ($namakode_sampelparams as $attribute => $items) {
                        foreach ($items as $item) {
                            if (!isset($sampel_data[$item])) {
                                $sampel_data[$item] = [];
                            }

                            $explodedAttributes = strpos($attribute, ',') !== false ? explode(',', $attribute) : [$attribute];

                            foreach ($explodedAttributes as $attr) {
                                $trimmedAttr = trim($attr); // Ensure no leading/trailing spaces
                                if (!in_array($trimmedAttr, $sampel_data[$item])) {
                                    $sampel_data[$item][] = $trimmedAttr;
                                }
                            }
                        }
                    }
                }
                // dd($total_akhir);
                // dd($sampel_data, $namakode_sampelparams);

                $kode_sampel = explode('$', $kdsmpel);


                $nomor_lab = explode('$', $nolab);
                $new_sampel = [];
                $incc = 0;
                foreach ($sampel_data as $keyx => $valuex) {
                    $new_sampel[$incc++] = implode(',', $valuex);
                }
                // dd($value2);
                $timestamp = strtotime($value2['tanggal_terima']);
                $year = date('Y', $timestamp);
                $lab =  substr($year, 2) . $value2->jenisSampel->kode . '.';
                // Remove leading and trailing spaces from each element
                $kode_sampel = array_map(function ($value) {
                    return trim($value); // Removes spaces from both start and end
                }, $kode_sampel);
                $new_nomor_lab = $nomor_lab[0] - 1;
                $lab_counter = 1;
                $progress = $value2->progressSampel->nama;
                $progresHistory = json_decode($value2->last_update, true);

                $dateSertifikat = '-';
                $dateAnalisa = '-';

                if ($value2->progressSampel->id == 7) {
                    $dateSertifikat = Carbon::now()->locale('id')->translatedFormat('d F Y');
                    $foundProgress6 = false;

                    foreach ($progresHistory as $progress) {
                        if ($progress['progress'] == '7') {
                            $dateSertifikat = Carbon::parse($progress['updated_at'])
                                ->locale('id')
                                ->translatedFormat('d F Y');
                        }

                        if ($progress['progress'] == '6') {
                            $dateAnalisa = Carbon::parse($progress['updated_at'])
                                ->locale('id')
                                ->translatedFormat('d F Y');
                            $foundProgress6 = true;
                        }
                    }

                    if (!$foundProgress6) {
                        $dateAnalisa = $dateSertifikat;
                    }
                }
                foreach ($sampel_data as $keysx => $valuems) {
                    // $inc = 1;
                    foreach ($kode_sampel as $index => $kode) {
                        if ((string)$keysx === $kode) {
                            $result[$key][$key1][$keysx]['jenis_sample'] = $jenissample;
                            $result[$key][$key1][$keysx]['nama_unsur'] = $keysx;
                            $result[$key][$key1][$keysx]['jenis_sample_komoditas'] = $jenissample_komuditas;
                            $result[$key][$key1][$keysx]['jumlah_sampel'] = ($index == 0) ? $jumlahsample : 'null';
                            $result[$key][$key1][$keysx]['catatan'] = ($index == 0) ? $catatan : 'null';
                            $result[$key][$key1][$keysx]['kode_sampel'] = $kode_sampel[$index];
                            $current_lab_number = $new_nomor_lab + $lab_counter;
                            $result[$key][$key1][$keysx]['nomor_lab'] = $lab . formatLabNumber($current_lab_number);
                            $lab_counter++; // Increment the counter for next iteration
                            $result[$key][$key1][$keysx]['nama_pengirim'] = $value2['nama_pengirim'];
                            $result[$key][$key1][$keysx]['asal_sampel'] = $value2['asal_sampel'];
                            $result[$key][$key1][$keysx]['departemen'] = $value2['departemen'];
                            $result[$key][$key1][$keysx]['nomor_surat'] = $value2['nomor_surat'];
                            $result[$key][$key1][$keysx]['nomor_kupa'] = $value2['nomor_kupa'];
                            $result[$key][$key1][$keysx]['tanggal_terima'] = $carbonDate;
                            $result[$key][$key1][$keysx]['tanggal_memo'] = $value2['tanggal_memo'];
                            $result[$key][$key1][$keysx]['kode_track'] = $value2['kode_track'];
                            $result[$key][$key1][$keysx]['tujuan'] = $value2['tujuan'];
                            $result[$key][$key1][$keysx]['Jumlah_Parameter'] = count($valuems);
                            $result[$key][$key1][$keysx]['Parameter_Analisa'] = implode(',', $valuems);
                            $result[$key][$key1][$keysx]['tujuan'] = $value2['tujuan'];
                            $result[$key][$key1][$keysx]['estimasi'] = $carbonDate2;
                            $result[$key][$key1][$keysx]['Tanggal_Selesai_Analisa'] = $dateAnalisa;
                            $result[$key][$key1][$keysx]['No_sertifikat'] = '-';
                            $result[$key][$key1][$keysx]['total'] = ($index == 0) ? $total_akhir : 'null';
                            $result[$key][$key1][$keysx]['total_string'] = ($index == 0) ? NumberToWords::transformNumber('id', $hargatotal) : 'null';
                            $result[$key][$key1][$keysx]['progress'] = $progress;
                            $result[$key][$key1][$keysx]['Tanggal_Rilis_Sertifikat'] = $dateSertifikat;
                        }
                    }
                }
                // dd($result);
            }
            $result[$key]['jenis'] = $jenissample;
            $result[$key]['tanggal_terima'] = $value2['tanggal_terima'];
        }
        // dd($result);
        $jenissamplel = [];
        foreach ($result as $key => $value) {
            $jenissamplel[] = $value['jenis'];
        }
        // $jenissamplefix = implode(',', $jenissamplel);

        // $filename = 'PDF Kupa,' . $jenissamplefix . '.pdf';
        $nomor_labs = $nomor_lab[0] - 1;
        // return [
        //     'result' => $result,
        //     'filename' => $filename,
        //     'nomor_lab' => $nomor_labs
        // ];
        $data = [
            'data' => $result,
            'lab' => $nomor_labs
        ];
        return $data;
    }
}
