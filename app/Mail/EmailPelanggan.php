<?php

namespace App\Mail;

use App\Models\JenisSampel;
use App\Models\Progress;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailPelanggan extends Mailable
{
    use Queueable, SerializesModels;

    public $nomor_surat;
    public $departement;
    public $jenis_sampel;
    public $jumlah_sampel;
    public $progress;
    public $kode_tracking_sampel;
    public $id;
    public $tanggal_registrasi;
    public $estimasi_kup;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $nomor_surat,
        $departement,
        $jenis_sampel,
        $jumlah_sampel,
        $progress,
        $kode_tracking_sampel,
        $id,
        $tanggal_registrasi,
        $estimasi_kup
    ) {
        $this->nomor_surat = $nomor_surat;
        $this->departement = $departement;
        $this->jenis_sampel = $jenis_sampel;
        $this->jumlah_sampel = $jumlah_sampel;
        $this->progress = $progress;
        $this->kode_tracking_sampel = $kode_tracking_sampel;
        $this->id = $id;
        $this->tanggal_registrasi = $tanggal_registrasi;
        $this->estimasi_kup = $estimasi_kup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get image content
        $imageContent = file_get_contents(public_path('images/logocorp.png'));

        return $this->view('layouts.email')
            ->subject('Hasil Analisa Surat: ' . $this->nomor_surat);
        // ->attachData($imageContent, 'logocorp.png', [
        //     'mime' => 'image/png'
        // ]);
    }
}
