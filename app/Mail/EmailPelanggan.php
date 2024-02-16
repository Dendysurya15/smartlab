<?php

namespace App\Mail;

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

    public $tgl;
    public $nomor_surat;
    public $nomorlab;
    public $randomCode;
    public $nomorserif;

    /**
     * Create a new message instance.
     */
    public function __construct($tgl, $nomor_surat, $nomorlab, $randomCode, $nomorserif)
    {
        $this->tgl = $tgl;
        $this->nomor_surat = $nomor_surat;
        $this->nomorlab = $nomorlab;
        $this->randomCode = $randomCode;
        $this->nomorserif = $nomorserif;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $datenow = Carbon::now();

        // Format the date as "dd-mm-yy"
        $formattedDate = $datenow->format('d-m-y');


        return $this->view('layouts.email', [
            'tanggal' => $this->tgl,
            'nomorsurat' => $this->nomor_surat,
            'nomorlab' => $this->nomorlab,
            'track' => $this->randomCode,
            'nomorserif' => $this->nomorserif,
            'tanggalkirim' => $formattedDate,
        ])
            ->subject('Hasil Analisa Surat:' . ' ' . $this->nomor_surat);
    }
}
