<?php

namespace App\Mail;

use App\Models\JenisSampel;
use App\Models\Progress;
use Carbon\Carbon;
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

    /**
     * Create a new message instance.
     */
    public function __construct($nomor_surat, $departement, $jenis_sampel, $jumlah_sampel, $progress, $kode_tracking_sampel)
    {
        $this->nomor_surat = $nomor_surat;
        $this->departement = $departement;
        $this->jenis_sampel = $jenis_sampel;
        $this->jumlah_sampel = $jumlah_sampel;
        $this->progress = $progress;
        $this->kode_tracking_sampel = $kode_tracking_sampel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this->view('layouts.email', [
            'nomor_surat' => $this->nomor_surat,
            'departement' => $this->departement,
            'jenis_sampel' => $this->jenis_sampel,
            'jumlah_sampel' => $this->jumlah_sampel,
            'progress' => $this->progress,
            'kode_tracking_sampel' => $this->kode_tracking_sampel,
            'tanggal_surat' => Carbon::now()->format('d-m-Y'),
        ])
            ->subject('Hasil Analisa Surat:' . ' ' . $this->nomor_surat);
    }
}
