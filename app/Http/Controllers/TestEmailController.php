<?php

// app/Http/Controllers/TestEmailController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailPelanggan;
use Swift_SmtpTransport;
use Swift_Mailer;

class TestEmailController extends Controller
{
    public function sendTestEmail()
    {
        $recipient = 'valentinojaja@gmail.com';
        $cc = 'jajavalentino23@gmail.com';
        $tanggal = '2023-01-31';
        $nomorsurat = 'ACNK_KPS';
        $nomorlab = 'LA-DA_CA';
        // Mail::to($recipients)
        // ->cc($cc)
        // ->send(new EmailPelanggan($this->tanggal_penerimaan, $this->nomor_surat, $nomorlab));

        try {
            Mail::to($recipient)->cc($cc)->send(new EmailPelanggan($tanggal, $nomorsurat, $nomorlab));
            // Mail::to($recipient)
            //     ->cc($cc)
            //     ->send(new EmailPelanggan('test'));

            return "Test email sent successfully!";
        } catch (\Exception $e) {
            return "Error sending test email: " . $e->getMessage();
        }
    }
}
