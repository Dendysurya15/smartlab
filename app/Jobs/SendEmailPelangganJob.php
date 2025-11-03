<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailPelanggan;
use Illuminate\Support\Facades\Log;

class SendEmailPelangganJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailAddresses;
    protected $emailcc;
    protected $emailData;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 5;

    /**
     * The maximum number of seconds the job can run before timing out.
     */
    public $timeout = 120;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [60, 120, 300, 600]; // Retry setelah 1 menit, 2 menit, 5 menit, 10 menit
    }

    /**
     * Create a new job instance.
     */
    public function __construct($emailAddresses, $emailcc, $emailData)
    {
        $this->emailAddresses = $emailAddresses;
        $this->emailcc = $emailcc;
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Delay 1 menit sebelum kirim email
        sleep(60);

        try {
            Mail::to($this->emailAddresses)
                ->cc($this->emailcc)
                ->send(new EmailPelanggan(
                    $this->emailData['nomor_surat'],
                    $this->emailData['departement'],
                    $this->emailData['jenis_sampel'],
                    $this->emailData['jumlah_sampel'],
                    $this->emailData['progress'],
                    $this->emailData['kode_tracking_sampel'],
                    $this->emailData['id'],
                    $this->emailData['tanggal_registrasi'],
                    $this->emailData['estimasi_kup']
                ));

            Log::info('Email berhasil dikirim ke: ' . implode(', ', (array)$this->emailAddresses) . ' (Attempt: ' . $this->attempts() . ')');
        } catch (\Swift_TransportException $e) {
            // Handle rate limit atau SMTP errors
            if (
                str_contains($e->getMessage(), 'rate limit') ||
                str_contains($e->getMessage(), 'throttle') ||
                str_contains($e->getMessage(), 'too many')
            ) {

                Log::warning('Rate limit tercapai, akan retry: ' . $e->getMessage() . ' (Attempt: ' . $this->attempts() . ')');

                // Release job kembali ke queue untuk retry
                $this->release($this->backoff()[$this->attempts() - 1] ?? 600);
                return;
            }

            Log::error('Gagal mengirim email: ' . $e->getMessage() . ' (Attempt: ' . $this->attempts() . ')');
            throw $e;
        } catch (\Exception $e) {
            // Handle general exceptions
            if (
                str_contains($e->getMessage(), 'rate limit') ||
                str_contains($e->getMessage(), 'throttle') ||
                str_contains($e->getMessage(), 'too many')
            ) {

                Log::warning('Rate limit tercapai, akan retry: ' . $e->getMessage() . ' (Attempt: ' . $this->attempts() . ')');

                // Release job kembali ke queue untuk retry dengan backoff
                $this->release($this->backoff()[$this->attempts() - 1] ?? 600);
                return;
            }

            Log::error('Gagal mengirim email: ' . $e->getMessage() . ' (Attempt: ' . $this->attempts() . ')');
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job SendEmailPelangganJob gagal setelah ' . $this->tries . ' percobaan: ' . $exception->getMessage());
        Log::error('Email gagal dikirim ke: ' . implode(', ', (array)$this->emailAddresses));
    }
}
